<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lead;
use App\Models\Sheat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SheatController extends Controller
{
    public function sheatList()
    {
        $sheats = Sheat::all();
        return view('admin.menu-pages.sheat-list', ['sheats' => $sheats]);
    }

    public function fetchSheatList(Request $request)
    {
        try {
            $draw = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $search = $request->get('search')['value'] ?? null;

            $query = Sheat::with('company');

            if ($search) {
                $query->where('sheat_name', 'like', "%$search%");
            }

            $totalRecords = $query->count();
            $sheats = $query->offset($start)->limit($length)->get();

            $data = [];
            foreach ($sheats as $sheat) {
                $leadCount = Lead::where('sheat_id', $sheat->id)->count();
                $data[] = [
                    'id' => $sheat->id,
                    'sheat_name' => $sheat->sheat_name,
                    'company' => $sheat->company->company_name ?? 'N/A',
                    'agent' => count(json_decode($sheat->agents ?? '[]')) . ' agents',
                    'lead' => $leadCount . ' leads',
                    'status' => $sheat->status,
                    'action' => '<a href="' . route('update.sheat') . '?id=' . $sheat->id . '" class="btn btn-sm btn-info">Edit</a> 
                                 <button class="btn btn-sm btn-danger sheat_delete_btn" data-id="' . $sheat->id . '">Delete</button>'
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

    public function updateSheat(Request $request)
    {
        // GET request - show the edit form
        if ($request->isMethod('get')) {
            $sheet = Sheat::find($request->id);
            if (!$sheet) {
                return redirect()->route('sheat.list')->with('error', 'Sheat not found');
            }
            return view('admin.menu-pages.edit-sheat', ['sheet' => $sheet]);
        }

        // POST request - save the data
        if ($request->isMethod('post')) {
            try {
                $validator = Validator::make($request->all(), [
                    'sheat_id' => 'required|exists:sheats,id',
                    'sheat_name' => 'required',
                    'csv_file' => 'nullable|mimes:csv,txt',
                    'agents' => 'required|array|min:1',
                    'agents.*' => 'exists:agents,id',
                    'status' => 'required',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'errors' => $validator->errors()
                    ], 422);
                }

                $sheat = Sheat::find($request->sheat_id);

                if ($sheat) {
                    $sheat->update([
                        'sheat_name' => $request->sheat_name,
                        'agents' => json_encode($request->agents),
                        'status' => $request->status,
                    ]);

                    if ($request->hasFile('csv_file')) {
                        $this->importCsvLeads($request->file('csv_file'), $sheat, $request->agents);
                    }

                    return response()->json([
                        'status' => true,
                        'message' => 'Sheat and leads updated successfully',
                        'data' => $sheat->load('leads'),
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

    public function deleteSheat(Request $request)
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

            $sheat = Sheat::where('id', $request->sheat_id)->first();

            $leads = $sheat->leads()->get();
            foreach ($leads as $lead) {
                $lead->delete();
            }

            $sheat->delete();

            return response()->json([
                'status' => true,
                'message' => 'Sheat and leads deleted successfully',
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function addSheat(Request $request)
    {
        try {
            $company = $request->user();

            $validator = Validator::make($request->all(), [
                'sheat_name' => 'required',
                'csv_file' => 'required|mimes:csv,txt',
                'agents' => 'required|array|min:1',
                'agents.*' => 'exists:agents,id',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                ], 400);
            }

            $sheat = Sheat::create([
                'sheat_name' => $request->sheat_name,
                'company_id' => $company->id,
                'agents' => json_encode($request->agents),
                'status' => $request->status,
            ]);

            if ($sheat && $request->hasFile('csv_file')) {
                $this->importCsvLeads($request->file('csv_file'), $sheat, $request->agents);
            }

            return response()->json([
                'status' => true,
                'message' => 'Sheat and leads added successfully',
                'data' => $sheat->load('leads'),
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    private function importCsvLeads($file, $sheat, $agentIds)
    {
        if (!is_array($agentIds) || count($agentIds) === 0) {
            throw new \Exception("Agent IDs must be a non-empty array.");
        }

        $path = $file->getRealPath();
        $rows = array_map('str_getcsv', file($path));

        $header = array_map('trim', $rows[0]);
        unset($rows[0]);

        $totalLeads = count($rows);
        $totalAgents = count($agentIds);

        $chunks = array_chunk($rows, ceil($totalLeads / $totalAgents));

        foreach ($agentIds as $index => $agentId) {
            $leadsForAgent = $chunks[$index] ?? [];

            foreach ($leadsForAgent as $row) {
                $data = array_combine($header, $row);

                if (!$data)
                    continue;

                Lead::updateOrCreate(
                    [
                        'sheat_id' => $sheat->id,
                        'phone' => $data['phone'] ?? null,
                    ],
                    [
                        'company_id' => $sheat->company_id,
                        'agent_id' => $agentId,
                        'name' => $data['name'] ?? null,
                        'email' => $data['email'] ?? null,
                        'lead_source' => $data['lead_source'] ?? null,
                        'status' => $data['status'] ?? null,
                        'follow_up' => $data['follow_up'] ?? null,
                    ]
                );
            }
        }
    }

    public function agentSheatList(Request $request)
    {
        try {
            $agent = $request->user();
            $sheat = Sheat::whereJsonContains('agents', (string) $agent->id)->get();
            if ($sheat) {
                return response()->json([
                    'status' => true,
                    'message' => 'Successfully fetched sheat list.',
                    'data' => $sheat
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