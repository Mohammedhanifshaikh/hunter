<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Models\Agent;

class SubscriptionOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'subscription_id',
        'price',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'price' => 'decimal:2',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    protected static function booted(): void
    {
        static::creating(function (SubscriptionOrder $order) {
            // Default status
            if (empty($order->status)) {
                $order->status = 'active';
            }

            // Compute start/end dates from plan duration if not provided
            $now = now();
            if (empty($order->start_date)) {
                $order->start_date = $now;
            }

            if (empty($order->end_date)) {
                $subscription = Subscription::find($order->subscription_id);
                if ($subscription) {
                    // Assume duration is in months
                    $months = (int) $subscription->duration;
                    $order->end_date = Carbon::parse($order->start_date)->addMonthsNoOverflow(max(1, $months));
                }
            }
        });

        static::created(function (SubscriptionOrder $order) {
            // Immediately activate company and its agents upon new order creation
            if ($order->company) {
                $order->company->status = 'active';
                $order->company->save();

                // Cascade to agents
                Agent::where('company_id', $order->company_id)->update(['status' => 'approved']);
            }
        });
    }
}
