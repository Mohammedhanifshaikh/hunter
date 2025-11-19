<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'duration',
        'price',
        'agents',
        'description',
        'features',
        'status',
    ];

    public function orders()
    {
        return $this->hasMany(SubscriptionOrder::class);
    }
}
