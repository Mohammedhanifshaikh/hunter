<?php

namespace App\Http\Controllers\Api;

use App\Models\Agent;
use App\Models\Lead;
use App\Models\Sheat;
use App\Models\SubscriptionOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function companyDashboard(Request $request)
    {
        try {
            $company = $request->user();
            if (!$company) {
                return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
            }

            // Agents
            $totalAgents = Agent::where('company_id', $company->id)->count();
            $approvedAgents = Agent::where('company_id', $company->id)->where('status', 'approved')->count();

            // Sheets (if owned by company)
            $sheetsCount = method_exists(Sheat::class, 'where') ? Sheat::where('company_id', $company->id)->count() : 0;

            // Leads across company's agents
            $leadsCount = method_exists(Lead::class, 'where') ? Lead::whereIn('agent_id', Agent::where('company_id', $company->id)->pluck('id'))
                ->count() : 0;

            // Subscription info
            $now = Carbon::now();
            $activeOrder = SubscriptionOrder::where('company_id', $company->id)
                ->where('status', 'active')
                ->whereNotNull('start_date')
                ->whereNotNull('end_date')
                ->where('start_date', '<=', $now)
                ->where('end_date', '>=', $now)
                ->orderByDesc('end_date')
                ->first();

            $planName = null;
            $daysLeft = 0;
            $agentsAllowed = null;
            if ($activeOrder && $activeOrder->subscription) {
                $planName = $activeOrder->subscription->name;
                $agentsAllowed = (int) ($activeOrder->subscription->agents ?? 0);
                $daysLeft = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($activeOrder->end_date)->endOfDay(), false);
                $daysLeft = $daysLeft >= 0 ? (int) $daysLeft : 0;
            }

            return response()->json([
                'status' => true,
                'message' => 'Dashboard data fetched successfully.',
                'data' => [
                    'company' => $company,
                    'agents' => [
                        'total' => $totalAgents,
                        'approved' => $approvedAgents,
                    ],
                    'sheets' => $sheetsCount,
                    'leads' => $leadsCount,
                    'subscription' => [
                        'active' => (bool) $activeOrder,
                        'plan' => $planName,
                        'days_left' => $daysLeft,
                        'agents_allowed' => $agentsAllowed,
                    ],
                ],
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
    public function companyProfile(Request $request)
    {
        try {
            $company = $request->user();
            if ($company) {
                return response()->json([
                    'status' => true,
                    'message' => 'Successfully fetched company profile.',
                    'data' => $company
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

    public function companyUpdate(Request $request)
    {
        try {
            $company = $request->user();

            $validator = Validator::make($request->all(), [
                'company_name' => 'required|string',
                'company_address' => 'required|string',
                'pan_no' => 'required|string',
                'adhaar_no' => 'required|numeric',
                'mobile_no' => 'required|numeric|digits:10|unique:companies,mobile_no,' . $company->id,
                'email' => 'required|email|unique:companies,email,' . $company->id,
                'password' => 'required|string',
                'device_token' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                ], 400);
            }

            if ($company) {
                $company->update([
                    'company_name' => $request->company_name,
                    'company_address' => $request->company_address,
                    'pan_no' => $request->pan_no,
                    'adhaar_no' => $request->adhaar_no,
                    'mobile_no' => $request->mobile_no,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'device_token' => $request->device_token
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Successfully updated company profile.',
                    'data' => $company
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
