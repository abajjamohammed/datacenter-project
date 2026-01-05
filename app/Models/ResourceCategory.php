<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class ResourceCategory extends Model
{
    protected $fillable = [
        'name',
        'description',
        'icon',
    ];

     public function resources()
    {
        return $this->hasMany(Resource::class, 'category_id');
    }
}
