<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lead;
use App\Models\Agent;
use App\Models\Sheat;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{

    public function companyList()
    {
        $companies = Company::all();
        return view('admin.menu-pages.company-list', ['companies' => $companies]);
    }

    public function fetchCompanyList(Request $request)
    {
        try {
            $draw = $request->get('draw');
            $start = $request->get('start');
            $length = $request->get('length');
            $search = $request->get('search')['value'] ?? null;

            $query = Company::query();

            if ($search) {
                $query->where('company_name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%")
                      ->orWhere('mobile_no', 'like', "%$search%");
            }

            $totalRecords = $query->count();
            $companies = $query->offset($start)->limit($length)->get();

            $data = [];
            foreach ($companies as $company) {
                // Active subscription order (latest end_date in range)
                $now = now();
                $activeOrder = $company->subscriptionOrders()
                    ->where('status', 'active')
                    ->whereNotNull('start_date')
                    ->whereNotNull('end_date')
                    ->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now)
                    ->orderByDesc('end_date')
                    ->first();

                $planName = '—';
                $daysLeft = '—';
                $agentsLeft = '—';
                if ($activeOrder) {
                    $plan = $activeOrder->subscription; // plan catalog
                    if ($plan) {
                        $planName = $plan->name;
                        // Approved agents count for company
                        $approvedAgents = Agent::where('company_id', $company->id)->where('status', 'approved')->count();
                        $agentsAllowed = (int) ($plan->agents ?? 0);
                        $agentsLeft = max(0, $agentsAllowed - $approvedAgents);
                    }
                    $daysLeft = \Carbon\Carbon::now()->startOfDay()
                        ->diffInDays(\Carbon\Carbon::parse($activeOrder->end_date)->endOfDay(), false);
                    $daysLeft = $daysLeft >= 0 ? (int) $daysLeft : 0;
                }

                $statusBtn = $company->status == 'active' 
                    ? '<span class="badge bg-label-success me-1">Active</span>'
                    : '<span class="badge bg-label-danger me-1">Inactive</span>';
                
                $data[] = [
                    'id' => $company->id,
                    'company_name' => $company->company_name,
                    'pan_no' => $company->pan_no,
                    'mobile_no' => $company->mobile_no,
                    'email' => $company->email,
                    'subscription' => '<div>
                        <div><strong>Plan:</strong> ' . e($planName) . '</div>
                        <div><strong>Days left:</strong> ' . e($daysLeft) . '</div>
                        <div><strong>Agents left:</strong> ' . e($agentsLeft) . '</div>
                    </div>',
                    'status' => $statusBtn,
                    'action' => '<a href="' . route('attach.plan') . '?company_id=' . $company->id . '" class="btn btn-sm btn-primary">Attach Plan</a> 
                                 <button class="btn btn-sm btn-warning change-status" data-id="' . $company->id . '">Change Status</button> 
                                 <a href="' . route('update.company') . '?id=' . $company->id . '" class="btn btn-sm btn-info">Edit</a> 
                                 <button class="btn btn-sm btn-danger company_delete_btn" data-id="' . $company->id . '">Delete</button>'
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

    public function updateCompany(Request $request)
    {
        // GET request - show the edit form
        if ($request->isMethod('get')) {
            $company = Company::find($request->id);
            if (!$company) {
                return redirect()->route('company.list')->with('error', 'Company not found');
            }
            return view('admin.menu-pages.edit-company', ['company' => $company]);
        }

        // POST request - save the data
        if ($request->isMethod('post')) {
            try {
                $company = Company::find($request->company_id);
                if (!$company) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Company not found'
                    ], 404);
                }

                $validator = Validator::make($request->all(), [
                    'company_name' => 'required|string',
                    'company_address' => 'required|string',
                    'pan_no' => 'required|string',
                    'adhaar_no' => 'required|numeric',
                    'mobile_no' => 'required|numeric|digits:10|unique:companies,mobile_no,' . $company->id,
                    'email' => 'required|email|unique:companies,email,' . $company->id,
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'errors' => $validator->errors()
                    ], 422);
                }

                $company->update([
                    'company_name' => $request->company_name,
                    'company_address' => $request->company_address,
                    'pan_no' => $request->pan_no,
                    'adhaar_no' => $request->adhaar_no,
                    'mobile_no' => $request->mobile_no,
                    'email' => $request->email,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => 'Company updated successfully',
                    'data' => $company
                ], 200);

            } catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        }
    }

    public function deleteCompany(Request $request)
    {
        try {
            $company = Company::find($request->companyId);
            if ($company) {
                $company->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Company deleted successfully'
                ]);
            }
            return response()->json(['status' => false, 'message' => 'Company not found'], 404);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function changeCompanyStatus(Request $request)
    {
        try {
            $company = Company::find($request->companyId);
            if ($company) {
                $company->status = $company->status == 'active' ? 'inactive' : 'active';
                $company->save();
                return response()->json([
                    'status' => true,
                    'message' => 'Status updated successfully'
                ]);
            }
            return response()->json(['status' => false, 'message' => 'Company not found'], 404);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function companyDashboard(Request $request)
    {
        try {
            $company = auth()->user();

            $totalAgents = Agent::where('company_id', $company->id)->where('status', 'approved')->count();
            $totalSheats = Sheat::where('company_id', $company->id)->count();
            $totalLeads = Lead::where('company_id', $company->id)->count();

            return response()->json([
                'status' => true,
                'message' => 'Dashboard data fetched successfully.',
                'data' => [
                    'company_name' => $company->name,
                    'total_agents' => $totalAgents,
                    'total_sheats' => $totalSheats,
                    'total_leads' => $totalLeads,
                ]
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