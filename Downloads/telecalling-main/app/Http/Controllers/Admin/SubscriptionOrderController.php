<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\Subscription; // using as plan catalog
use App\Models\SubscriptionOrder;

class SubscriptionOrderController extends Controller
{
    // Show form to attach a plan to a company
    public function attachForm()
    {
        $companies = Company::orderBy('company_name')->get(['id','company_name','email']);
        // Only active plans
        $plans = Subscription::where('status', 1)->orderBy('name')->get(['id','name','duration','price']);
        return view('admin.menu-pages.attach-plan', compact('companies','plans'));
    }

    // Store the subscription order
    public function store(Request $request)
    {
        $rules = [
            'company_id' => 'required|exists:companies,id',
            'subscription_id' => 'required|exists:subscriptions,id',
            'start_date' => 'nullable|date',
        ];
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $company = Company::findOrFail($request->company_id);
        $plan = Subscription::findOrFail($request->subscription_id);

        DB::beginTransaction();
        try {
            $order = SubscriptionOrder::create([
                'company_id' => $company->id,
                'subscription_id' => $plan->id,
                'price' => $plan->price,
                'start_date' => $request->start_date ? Carbon::parse($request->start_date) : null,
                // end_date will auto-calc in model creating() hook based on plan duration
                'status' => 'active',
            ]);

            DB::commit();

            return redirect()->route('company.list')
                ->with('success', 'Plan attached and company activated successfully.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage())->withInput();
        }
    }
}
