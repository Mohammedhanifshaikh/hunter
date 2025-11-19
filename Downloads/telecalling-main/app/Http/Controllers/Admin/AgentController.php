<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lead;
use App\Models\Agent;
use App\Models\SubscriptionOrder;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AgentController extends Controller
{

    public function agentList()
    {
        $agents = Agent::all();
        return view('admin.menu-pages.agent-list', ['agents' => $agents]);
    }

    public function fetchAgentList(Request $request)
    {
        try {
            $draw = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $search = $request->get('search')['value'] ?? null;

            $query = Agent::with('company');

            if ($search) {
                $query->where('agent_name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%")
                      ->orWhere('phone', 'like', "%$search%");
            }

            $totalRecords = $query->count();
            $agents = $query->offset($start)->limit($length)->get();

            $data = [];
            foreach ($agents as $agent) {
                $statusBtn = $agent->status == 'approved' 
                    ? '<span class="badge bg-label-success me-1">Approved</span>'
                    : '<span class="badge bg-label-warning me-1">Pending</span>';
                
                $data[] = [
                    'id' => $agent->id,
                    'company' => $agent->company->company_name ?? 'N/A',
                    'agent_name' => $agent->agent_name,
                    'phone' => $agent->phone,
                    'email' => $agent->email,
                    'status' => $statusBtn,
                    'action' => '<button class="btn btn-sm btn-warning change-status" data-id="' . $agent->id . '">Change Status</button> 
                                 <a href="' . route('update.agent') . '?id=' . $agent->id . '" class="btn btn-sm btn-info">Edit</a> 
                                 <button class="btn btn-sm btn-danger agent_delete_btn" data-id="' . $agent->id . '">Delete</button>'
                ];
            }

            return response()->json([
                'draw' => intval($draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $data
            ]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function updateAgent(Request $request)
    {
        // GET request - show the edit form
        if ($request->isMethod('get')) {
            $agent = Agent::find($request->id);
            if (!$agent) {
                return redirect()->route('agent.list')->with('error', 'Agent not found');
            }
            return view('admin.menu-pages.edit-agent', ['agent' => $agent]);
        }

        // POST request - save the data
        if ($request->isMethod('post')) {
            try {
                $agent = Agent::find($request->agent_id);
                if (!$agent) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Agent not found'
                    ], 404);
                }

                $validator = Validator::make($request->all(), [
                    'agent_name' => 'required|string',
                    'phone' => 'required|numeric|digits:10|unique:agents,phone,' . $agent->id,
                    'email' => 'required|email|unique:agents,email,' . $agent->id,
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'errors' => $validator->errors()
                    ], 422);
                }

                $agent->update([
                    'agent_name' => $request->agent_name,
                    'phone' => $request->phone,
                    'email' => $request->email,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Agent updated successfully',
                    'data' => $agent
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        }
    }

    public function deleteAgent(Request $request)
    {
        try {
            $agent = Agent::find($request->agentId);
            if ($agent) {
                $agent->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Agent deleted successfully'
                ]);
            }
            return response()->json(['status' => false, 'message' => 'Agent not found'], 404);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function changeAgentStatus(Request $request)
    {
        try {
            $agent = Agent::find($request->agentId);
            if ($agent) {
                $newStatus = $agent->status == 'approved' ? 'pending' : 'approved';

                // If approving, enforce quota
                if ($newStatus === 'approved') {
                    $now = now();
                    $activeOrder = SubscriptionOrder::where('company_id', $agent->company_id)
                        ->where('status', 'active')
                        ->whereNotNull('start_date')
                        ->whereNotNull('end_date')
                        ->where('start_date', '<=', $now)
                        ->where('end_date', '>=', $now)
                        ->orderByDesc('end_date')
                        ->first();

                    if (!$activeOrder || !$activeOrder->subscription) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Cannot approve: No active subscription for company.'
                        ], 422);
                    }

                    $allowed = (int) ($activeOrder->subscription->agents ?? 0);
                    $approvedCount = Agent::where('company_id', $agent->company_id)
                        ->where('status', 'approved')
                        ->count();
                    // If current approved already at limit (excluding this agent if currently approved)
                    if ($allowed > 0 && $approvedCount >= $allowed) {
                        return response()->json([
                            'status' => false,
                            'message' => 'Agent approval exceeds plan limit.'
                        ], 422);
                    }
                }

                $agent->status = $newStatus;
                $agent->save();
                return response()->json([
                    'status' => true,
                    'message' => 'Status updated successfully'
                ]);
            }
            return response()->json(['status' => false, 'message' => 'Agent not found'], 404);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function addAgent(Request $request)
    {
        try {
            $company = $request->user();
            // Enforce agent quota before validation & creation
            // Find active subscription order (latest end_date within range)
            $now = now();
            $activeOrder = SubscriptionOrder::where('company_id', $company->id)
                ->where('status', 'active')
                ->whereNotNull('start_date')
                ->whereNotNull('end_date')
                ->where('start_date', '<=', $now)
                ->where('end_date', '>=', $now)
                ->orderByDesc('end_date')
                ->first();

            if (!$activeOrder || !$activeOrder->subscription) {
                return response()->json([
                    'status' => false,
                    'message' => 'No active subscription found. Please renew or attach a plan.'
                ], 403);
            }

            $agentsAllowed = (int) ($activeOrder->subscription->agents ?? 0);
            $currentAgents = Agent::where('company_id', $company->id)->count();
            if ($agentsAllowed > 0 && $currentAgents >= $agentsAllowed) {
                return response()->json([
                    'status' => false,
                    'message' => 'Agent quota exceeded for the current plan.'
                ], 422);
            }
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