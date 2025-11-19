<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function material()
    {
        return $this->hasMany(Material::class, 'type_id', 'id');
    }

    public function capacity()
    {
        return $this->hasMany(Capacity::class, 'type_id', 'id');
    }
    public function cart()
    {
        return $this->hasMany(Cart::class, 'type_id', 'id');
    }
      public function ProjectProduct()
    {
        return $this->hasMany(ProjectProduct::class);
    }

}
