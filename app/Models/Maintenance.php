<?php

namespace App\Models;
//created by mohammed 05/01

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Maintenance extends Model
{
    protected $fillable = [
        'resource_id',
        'start_date',
        'end_date',
        'description',
        'created_by',
    ];

    protected function casts()
    {
        return [
            'start_date' => 'datetime',
            'end_date'=> 'datetime',
        ] ;
    }

    public function resource(){
        return $this->belongsTo(Resource::class);
    }

    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }

}
