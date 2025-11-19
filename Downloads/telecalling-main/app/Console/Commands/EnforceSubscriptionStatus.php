<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use App\Models\Agent;

class EnforceSubscriptionStatus extends Command
{
    protected $signature = 'subscriptions:enforce-status';

    protected $description = 'Ensure companies and agents are active only if there is an active subscription';

    public function handle(): int
    {
        $this->info('Enforcing subscription statuses...');

        $updatedCompanies = 0;
        Company::chunk(100, function ($companies) use (&$updatedCompanies) {
            foreach ($companies as $company) {
                $hasActive = $company->hasActiveSubscription();
                $newStatus = $hasActive ? 'active' : 'inactive';

                if ($company->status !== $newStatus) {
                    $company->status = $newStatus;
                    $company->save();
                    $updatedCompanies++;
                }

                // Cascade to agents
                if ($hasActive) {
                    Agent::where('company_id', $company->id)
                        ->where('status', '!=', 'approved')
                        ->update(['status' => 'approved']);
                } else {
                    Agent::where('company_id', $company->id)
                        ->where('status', '!=', 'inactive')
                        ->update(['status' => 'inactive']);
                }
            }
        });

        $this->info("Updated companies: {$updatedCompanies}");
        return Command::SUCCESS;
    }
}
