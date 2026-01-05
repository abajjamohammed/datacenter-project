<?php

namespace App\Models;

//created by mohammed 05/01
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Incident extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'resource_id',
        'reservation_id',
        'title',
        'description',
        'incident_status',
        'priority',
        'resolved_by',
        'resolved_at',
    ];

    protected function casts()
    {
        return [
            'resolved_at' =>'datetime',
        ];
    }

    public function reporter(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function resource(){
        return $this->belongsTo(Resource::class);
    }

    public function reservation(){
        return $this->belongsTo(Reservation::class);
    }

    public function resolver(){
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
