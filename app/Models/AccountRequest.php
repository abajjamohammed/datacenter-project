<?php

namespace App\Models;
//created by mohammed 05/01

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountRequest extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'email',
        'phone',
        'department',
        'profile',
        'justification',
        'status',
        'processed_by',
        'processed_at',
        'rejection_reason',
    ];

    protected function casts()
    {
        return [
            'processed_at' => 'datetime',

        ];
    }

    public function processor(){
        return $this->belongsTo(User::class, 'processed_by');
    }
}
