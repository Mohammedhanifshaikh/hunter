<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'sheat_id',
        'agent_id',
        'company_id',
        'name',
        'email',
        'phone',
        'lead_source',
        'status',
        'follow_up'
    ];

    public function sheat()
    {
        return $this->belongsTo(Sheat::class, 'sheat_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
