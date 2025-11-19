<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sheat extends Model
{
    use HasFactory;

    protected $fillable = [
        'sheat_name',
        'company_id',
        'agent_id',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'sheat_id');
    }

}
