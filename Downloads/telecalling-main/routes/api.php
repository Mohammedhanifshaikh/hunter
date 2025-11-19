<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
    CompanyController,
    AgentController,
    SheatController,
    LeadController
};

Route::controller(AuthController::class)->group(function () {
    Route::post('company-register', 'companyRegister');
    Route::post('company-login', 'companyLogin');
    Route::post('agent-login', 'agentLogin');
    Route::post('login', 'login');
});


Route::middleware('auth:company-api')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout');
    });

    Route::controller(CompanyController::class)->group(function () {
        Route::get('company-dashboard', 'companyDashboard');
        Route::get('company-profile', 'companyProfile');
        Route::post('company-update', 'companyUpdate');
    });

    Route::controller(AgentController::class)->group(function () {
        Route::post('add-agent', 'addAgent');
        Route::get('fetch-agent-list', 'fetchAgentList');
        Route::post('update-agent', 'updateAgent');
        Route::delete('delete-agent', 'deleteAgent');
    });

    Route::controller(SheatController::class)->group(function () {
        Route::post('add-sheat', 'addSheat');
        Route::get('fetch-sheat-list', 'fetchSheatList');
        Route::post('update-sheat', 'updateSheat');
        Route::delete('delete-sheat', 'deleteSheat');
    });

    Route::controller(LeadController::class)->group(function () {
        Route::get('fetch-lead-list', 'fetchLeadList');
        Route::post('update-lead', 'updateLead');
        Route::delete('delete-lead', 'deleteLead');
    });

});

Route::middleware('auth:agent-api')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout');
    });

    Route::controller(AgentController::class)->group(function () {
        Route::get('agent-dashboard', 'agentDashboard');
        Route::get('agent-profile', 'agentProfile');
    });

    Route::controller(SheatController::class)->group(function () {
        Route::get('agent-sheat-list', 'agentSheatList');
    });

    Route::controller(LeadController::class)->group(function () {
        Route::get('fetch-agent-lead-list', 'fetchAgentLeadList');
        Route::get('agent-lead-list', 'agentLeadList');
        Route::post('agent-update-lead', 'agentUpdateLead');
        Route::get('follow-up-lead', 'followUpLead');
    });


});
