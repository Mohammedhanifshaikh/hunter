<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable; // yeh import zaroori hai
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agent extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $fillable = [
        'company_id',
        'agent_name',
        'phone',
        'email',
        'password',
        'device_token',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relationships
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function sheat()
    {
        return $this->hasMany(Lead::class, 'agent_id');
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'agent_id');
    }
}
