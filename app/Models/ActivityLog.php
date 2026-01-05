<?php

namespace App\Models;
//created by mohammed 05/01

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'description',
        'model_type',
        'model_id',
        'ip_address',
        'user_agent',
        'created_at',
    ];

      protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }



    public function user(){
        return $this->belongsTo(User::class);
    }
    

    //how we can use it, for ex: $log->subject
    public function subject()
    {
        return $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
    }
}



