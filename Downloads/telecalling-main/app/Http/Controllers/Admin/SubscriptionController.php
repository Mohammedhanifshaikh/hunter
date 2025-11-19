<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class SubscriptionController extends Controller
{
    public function addSubscription()
    {
        $mode = 'add';
        return view('admin.menu-pages.add-subscription', compact('mode'));
    }

    public function storeSubscription(Request $request)
    {
        try {
            if ($request->ajax() && $request->isMethod('post')) {
                $rules = [
                    'name' => 'required',
                    'duration' => 'required',
                    'agents' => 'required',
                    'price' => 'required',
                    'description' => 'required',
                    'features' => 'required',
                    'status' => 'required',
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'errors' => $validator->errors(),
                    ]);
                }

                $Subscription = Subscription::create([
                    'name' => $request->name,
                    'duration' => $request->duration,
                    'price' => $request->price,
                    'description' => $request->description,
                    'agents' => $request->agents,
                    'features' => $request->features,
                    'status' => $request->status,
                ]);

                if ($Subscription) {
                    return response()->json([
                        'status' => true,
                        'message' => 'Subscription added successfully.',
                        'redirect_url' => route('subscription.list'),
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Failed to add subscription.',
                    ]);
                }
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $th->getMessage(),
            ]);
        }
    }

    public function subscriptionList()
    {
        return view('admin.menu-pages.subscription-list');
    }

    public function fetchSubscriptionList(Request $request)
    {
        try {
            if ($request->ajax()) {
                $Subscriptions = Subscription::orderBy('created_at', 'desc')
                    ->select(['id', 'name', 'duration', 'price', 'description', 'status', 'created_at']);

                return DataTables::of($Subscriptions)
                    ->addColumn('created_at', function ($subscription) {
                        return Carbon::parse($subscription->created_at)->format('j-M-Y');
                    })

                    ->addColumn('status', function ($subscription) {
                        $statusClass = $subscription->status == 1 ? 'bg-label-success' : 'bg-label-secondary';
                        $statusText = $subscription->status == 1 ? 'Active' : 'Inactive';
                        return '<div href="javascript:void(0);" data-id="' . $subscription->id . '" class="change-status"><span class="badge ' . $statusClass . '">' . $statusText . '</span></div>';
                    })
                    ->addColumn('action', function ($subscription) {
                        $editRoute = route("update.subscription", ["id" => $subscription->id]);
                        return '
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item subscription_edit_btn" href="' . $editRoute . '">
                                    <i class="bx bx-edit-alt me-1"></i> Edit
                                </a></li>
                                <li><a class="dropdown-item subscription_delete_btn" href="javascript:void(0);" data-id="' . $subscription->id . '">
                                    <i class="bx bx-trash me-1"></i> Delete
                                </a></li>
                            </ul>
                        </div>';
                    })
                    ->rawColumns(['status', 'action'])
                    ->make(true);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()]);
        }
    }

    public function updateSubscription(Request $request)
    {
        try {
            if ($request->ajax()) {
                $subscription = Subscription::findOrFail($request->subscription_id);

                $rules = [
                    'name' => 'required',
                    'duration' => 'required',
                    'price' => 'required',
                    'description' => 'required',
                    'agents' => 'required',
                    'features' => 'required',
                    'status' => 'required',
                ];

                $validator = Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'errors' => $validator->errors(),
                    ], 422);
                }


                $subscription->update([
                    'name' => $request->name,
                    'duration' => $request->duration,
                    'price' => $request->price,
                    'description' => $request->description,
                    'agents' => $request->agents,
                    'features' => $request->features,
                    'status' => $request->status,
                ]);

                return response()->json([
                    'status' => true,
                    'message' => ' Subscription updated successfully.',
                    'redirect_url' => route('subscription.list'),
                ]);
            } else {
                $mode = 'edit';
                $subscription = Subscription::findOrFail($request->id);
                return view('admin.menu-pages.add-subscription', compact('mode', 'subscription'));
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function deleteSubscription()
    {
        try {
            if (request()->ajax()) {
                $subscription = Subscription::find(request()->subscriptionId);
                $subscription->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Subscription deleted successfully',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function changeSubscriptionStatus(Request $request)
    {
        try {
            if ($request->ajax()) {
                $subscription = Subscription::find(request()->subscriptionId);
                $subscription->status = !$subscription->status;
                $subscription->save();
                return response()->json([
                    'status' => true,
                    'message' => 'Status updated successfully',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
