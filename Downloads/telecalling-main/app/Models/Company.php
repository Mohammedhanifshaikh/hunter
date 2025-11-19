<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Company extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $fillable = [
        'company_name',
        'company_address',
        'pan_no',
        'adhaar_no',
        'mobile_no',
        'email',
        'password',
        'device_token',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function agent()
    {
        return $this->hasMany(Agent::class, 'company_id');
    }

    public function sheat()
    {
        return $this->hasMany(Sheat::class, 'company_id');
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'company_id');
    }

    public function subscriptionOrders(): HasMany
    {
        return $this->hasMany(SubscriptionOrder::class, 'company_id');
    }

    public function hasActiveSubscription(): bool
    {
        $now = now();
        $activeOrder = $this->subscriptionOrders()
            ->where('status', 'active')
            ->whereNotNull('start_date')
            ->whereNotNull('end_date')
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->orderByDesc('end_date') // overlapping orders -> pick latest end_date
            ->first();

        return (bool) $activeOrder;
    }
}
