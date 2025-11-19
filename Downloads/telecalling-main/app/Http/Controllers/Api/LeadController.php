<?php

namespace App\Http\Controllers\Api;

use App\Models\Lead;
use App\Models\Sheat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    public function fetchLeadList(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'sheat_id' => 'required|exists:sheats,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                ], 400);
            }

            $company = $request->user();

            $sheat = Sheat::where('id', $request->sheat_id)->where('company_id', $company->id)->first();
            if (!$sheat) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access.',
                ], 401);
            }

            $leads = Lead::where('sheat_id', $request->sheat_id)->get();
            if ($leads) {
                return response()->json([
                    'status' => true,
                    'message' => 'Successfully fetched lead list.',
                    'data' => $leads
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function agentLeadList(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'sheat_id' => 'required|exists:sheats,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                ], 400);
            }

            $agent = $request->user();

            $sheat = Sheat::where('id', $request->sheat_id)->where('agent_id', $agent->id)->first();
            if (!$sheat) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access.',
                ], 401);
            }

            $leads = Lead::where('sheat_id', $request->sheat_id)->get();
            if ($leads) {
                return response()->json([
                    'status' => true,
                    'message' => 'Successfully fetched lead list.',
                    'data' => $leads
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function updateLead(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'lead_id' => 'required|exists:leads,id',
                'name' => 'required',
                'email' => 'required|email',
                'phone' => 'required|numeric|digits:10',
                'lead_source' => 'required',
                'follow_up' => 'nullable',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                ], 400);
            }

            $company = $request->user();

            $lead = Lead::find($request->lead_id)->where('company_id', $company->id)->first();
            if (!$lead) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized access.',
                ], 401);
            }

            $lead = Lead::find($request->lead_id);
            if ($lead) {
                $lead->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'lead_source' => $request->lead_source,
                    'follow_up' => $request->follow_up,
                    'status' => $request->status,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Successfully updated lead.',
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function deleteLead(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'lead_id' => 'required|exists:leads,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                ], 400);
            }

            $lead = Lead::find($request->lead_id);
            if ($lead) {
                $lead->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Successfully deleted lead.',
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function agentUpdateLead(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'lead_id' => 'required|exists:leads,id',
                'follow_up' => 'nullable',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                ], 400);
            }

            $lead = Lead::find($request->lead_id);
            if ($lead) {
                $lead->update([
                    'follow_up' => $request->follow_up,
                    'status' => $request->status,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Successfully updated lead.',
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
