<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

//edited by mohammed 04/01
class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',    // i added this :mohammed 04/01
    ];

    /**
     * 🔗 Relationship: Role has many users
     */
    
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
