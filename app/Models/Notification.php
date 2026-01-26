<?php

namespace App\Models;

//created by mohammed 05/01

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'reservation_id',
        'is_read',
    ];

    protected function casts()
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function reservation(){
        return $this->belongsTo(Reservation::class);
    }
}