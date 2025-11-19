<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    AuthController,
    CompanyController,
    AgentController,
    SheatController,
    LeadController,
    SubscriptionController,
    SubscriptionOrderController
};

Route::controller(AuthController::class)->group(function () {
    Route::get('/my-admin', 'loadLogin')->name('load.login');
    Route::post('/login', 'postLogin')->name('post.login');
    Route::get('/login', function () {
        return redirect()->route('load.login');
    })->name('login');
    Route::get('/admin/dashboard', function () {
        return redirect()->route('load.login');
    });
});

// Auth routes
Route::group(['prefix' => 'admin/', 'middleware' => ['web', 'auth', 'is_admin']], function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('dashboard', 'dashboard')->name('dashboard');
        Route::get('profile', 'profile')->name('profile');
        Route::post('update-profile', 'updateProfile')->name('update.profile');
        Route::get('logout', 'logout')->name('logout');
    });

    // Hospitals
    Route::controller(CompanyController::class)->group(function () {
        Route::match(['get', 'post'], 'update-company', 'updateCompany')->name('update.company');
        Route::get('fetch-company-list', 'fetchCompanyList')->name('fetch.company-list');
        Route::get('company-list', 'companyList')->name('company.list');
        Route::post('delete-company', 'deleteCompany')->name('delete.company');
        Route::post('change-company-status', 'changeCompanyStatus')->name('change.company.status');
    });

    // Agents
    Route::controller(AgentController::class)->group(function () {
        Route::match(['get', 'post'], 'update-agent', 'updateAgent')->name('update.agent');
        Route::get('fetch-agent-list', 'fetchAgentList')->name('fetch.agent-list');
        Route::get('agent-list', 'agentList')->name('agent.list');
        Route::post('delete-agent', 'deleteAgent')->name('delete.agent');
        Route::post('change-agent-status', 'changeAgentStatus')->name('change.agent.status');
    });

    // Sheats
    Route::controller(SheatController::class)->group(function () {
        Route::match(['get', 'post'], 'update-sheat', 'updateSheat')->name('update.sheat');
        Route::get('fetch-sheat-list', 'fetchSheatList')->name('fetch.sheat-list');
        Route::get('sheat-list', 'sheatList')->name('sheat.list');
        Route::post('delete-sheat', 'deleteSheat')->name('delete.sheat');
    });

    Route::controller(LeadController::class)->group(function () {
        Route::get('lead-list/{sheat_id?}', 'leadList')->name('lead.list');
        Route::get('fetch-lead-list/{sheat_id?}', 'fetchLeadList')->name('fetch.lead-list');

        Route::match(['get', 'post'], 'update-lead', 'updateLead')->name('update.lead');
        Route::post('delete-lead', 'deleteLead')->name('delete.lead');
        Route::post('change-lead-status', 'changeLeadStatus')->name('change.lead.status');
    });

    // Attach Plan to Company (Subscription Orders)
    Route::controller(SubscriptionOrderController::class)->group(function () {
        Route::get('attach-plan', 'attachForm')->name('attach.plan');
        Route::post('attach-plan', 'store')->name('attach.plan.store');
    });

      Route::controller(SubscriptionController::class)->group(function () {
        Route::match(['get', 'post'], 'add-subscription', 'addSubscription')->name('add.subscription');
        Route::post('store-subscription', 'storeSubscription')->name('store.subscription');
        Route::get('subscription-list', 'subscriptionList')->name('subscription.list');
        Route::get('fetch-subscription-list', 'fetchSubscriptionList')->name('fetch.subscription-list');
        Route::match(['get', 'post'], 'update-subscription', 'updateSubscription')->name('update.subscription');
        Route::post('delete-subscription', 'deleteSubscription')->name('delete.subscription');
        Route::post('change-subscription-status', 'changeSubscriptionStatus')->name('change.subscription.status');
    });






});
