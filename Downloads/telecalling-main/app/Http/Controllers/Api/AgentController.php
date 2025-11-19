<?php

namespace App\Http\Controllers\Api;

use App\Models\Lead;
use App\Models\Agent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AgentController extends Controller
{

    public function addAgent(Request $request)
    {
        try {
            $company = $request->user();
            $validator = Validator::make($request->all(), [
                'agent_name' => 'required|string',
                'phone' => 'required|numeric|digits:10|unique:agents,phone',
                'email' => 'required|email|unique:agents,email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                ], 400);
            }

            $agent = Agent::create([
                'agent_name' => $request->agent_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'company_id' => $company->id
            ]);

            if ($agent) {
                return response()->json([
                    'status' => true,
                    'message' => 'Successfully added agent.',
                    'data' => $agent
                ], 200);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function fetchAgentList(Request $request)
    {
        try {
            $company = $request->user();
            $agent = Agent::where('company_id', $company->id)->get();
            if ($agent) {
                return response()->json([
                    'status' => true,
                    'message' => 'Successfully fetched agent list.',
                    'data' => $agent
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function updateAgent(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'agent_id' => 'required|exists:agents,id',
                'agent_name' => 'required|string',
                'phone' => 'required|numeric|digits:10',
                'email' => 'required|email',
                'password' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                ], 400);
            }

            $agent = Agent::where('id', $request->agent_id)->first();
            if ($agent) {
                $agent->update([
                    'agent_name' => $request->agent_name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                ]);
                return response()->json([
                    'status' => true,
                    'message' => 'Successfully updated agent.',
                    'data' => $agent
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function deleteAgent(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'agent_id' => 'required|exists:agents,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                ], 400);
            }

            $agent = Agent::where('id', $request->agent_id)->first();
            if ($agent) {
                $agent->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Successfully deleted agent.',
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function agentProfile(Request $request)
    {
        try {
            $agent = $request->user();
            return response()->json([
                'status' => true,
                'message' => 'Successfully fetched agent profile.',
                'data' => $agent
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }


    public function agentDashboard(Request $request)
    {
        try {
            $agent = auth()->user();
            $today = now()->toDateString();

            $totalLeads = Lead::where('agent_id', $agent->id)->count();

            $todayCalls = Lead::where('agent_id', $agent->id)
                ->whereDate('follow_up', $today)
                ->count();

            // Interested leads
            $interestedLeads = Lead::where('agent_id', $agent->id)
                ->where('status', 'interested')
                ->count();

            $followUpLeads = Lead::where('agent_id', $agent->id)
                ->where('status', 'follow-up')
                ->count();

            return response()->json([
                'status' => true,
                'message' => 'Dashboard data fetched successfully.',
                'data' => [
                    'total_leads' => $totalLeads,
                    'today_calls' => $todayCalls,
                    'interested' => $interestedLeads,
                    'follow_up' => $followUpLeads,
                ]
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }


}