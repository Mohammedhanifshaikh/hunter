<?php

namespace App\Http\Controllers\Api;

use App\Models\Agent;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Start Send OTP & verify OTP
    public function login(Request $request)
    {
        // Wrapper to maintain backward compatibility for POST /api/login
        // Use ?type=agent to login as agent, otherwise defaults to company login
        $type = strtolower($request->input('type', 'company'));
        if ($type === 'agent') {
            return $this->agentLogin($request);
        }
        return $this->companyLogin($request);
    }

    public function companyRegister(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'company_name' => 'required|string',
                'company_address' => 'required|string',
                'pan_no' => 'required|string',
                'adhaar_no' => 'required|numeric',
                'mobile_no' => 'required|numeric|digits:10|unique:companies,mobile_no',
                'email' => 'required|email|unique:companies,email',
                'password' => 'required|string',
                'device_token' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                ], 400);
            }

            $company = Company::create([
                'company_name' => $request->company_name,
                'company_address' => $request->company_address,
                'pan_no' => $request->pan_no,
                'adhaar_no' => $request->adhaar_no,
                'mobile_no' => $request->mobile_no,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'device_token' => $request->device_token,
                'status' => 'pending',
            ]);

            if ($company) {
                return response()->json([
                    'status' => true,
                    'message' => 'Successfully registered company.',
                    'data' => $company
                ], 200);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }


    public function companyLogin(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                ], 400);
            }

            $company = Company::where('email', $request->email)->first();

            if (!$company) {
                return response()->json([
                    'status' => false,
                    'message' => 'Company not found.',
                ], 404);
            }

            if (!password_verify($request->password, $company->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid credentials.',
                ], 401);
            }

            // Optional: Check for approval status
            if (!in_array($company->status, ['approved', 'active'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'Company account is not approved.',
                ], 403);
            }

            // Block login if no active subscription
            if (!$company->hasActiveSubscription()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Subscription expired or not found.',
                ], 403);
            }

            // Create Passport token
           $token = $company->createToken('CompanyToken')->accessToken;

            return response()->json([
                'status' => true,
                'message' => 'Successfully logged in.',
                'data' => [
                    'company' => $company,
                    'token' => $token,
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function agentLogin(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                ], 400);
            }

            $agent = Agent::where('email', $request->email)->first();

            if (!$agent) {
                return response()->json([
                    'status' => false,
                    'message' => 'Agent not found.',
                ], 404);
            }

            if (!password_verify($request->password, $agent->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid credentials.',
                ], 401);
            }

            // Optional: Check for approval status
            if ($agent->status !== 'approved') {
                return response()->json([
                    'status' => false,
                    'message' => 'Agent account is not approved.',
                ], 403);
            }

            // Create Passport token
           $token = $agent->createToken('AgentToken')->accessToken;

            return response()->json([
                'status' => true,
                'message' => 'Successfully logged in.',
                'data' => [
                    'agent' => $agent,
                    'token' => $token,
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    // Logout
    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            if ($user) {
                $user->device_token = null;
                $user->save();
                $user->token()->revoke();
                return response()->json([
                    'status' => true,
                    'message' => 'Successfully logged out'
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
