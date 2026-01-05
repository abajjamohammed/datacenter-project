<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'resource_id',
        'start_date',      // changes time by date    :mohammed 05/01
        'end_date',    // changes time by date  :mohammed 05/01
        'reservation_status',   // changed from status to reservation status  :mohammed 05/01
        'justification',
        // added those :mohammed
        'approved_by',  
        'approval_comment',
        'approved_at',
    ];

    

// mohammed: added this to tell laravel which type are those columns
     protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    //added those next ones :mohammed 05/01
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by'); //Each reservation is approved/rejected by at most one responsable
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class); //One reservation can generate multiple notifications
    }


    public function incidents()
    {
        return $this->hasMany(Incident::class); 
    }
}
