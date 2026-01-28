<?php

namespace App\Models;
//created by mohammed 05/01

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLog extends Model
{
    public $timestamps = false;

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



    public function user()
    {
        return $this->belongsTo(User::class);
    }


    //how we can use it, for ex: $log->subject
    public function subject()
    {
        return $this->morphTo(__FUNCTION__, 'model_type', 'model_id');
    }
    /**
     * Record an activity log
     * 
     * @param string $action - Action type (e.g., 'Created Resource', 'Updated User')
     * @param string $description - Detailed description
     * @param Model|null $model - The related model (optional)
     * @return ActivityLog
     */
    public static function record(string $action, string $description, $model = null)
    {
        return self::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'created_at' => now(),
        ]);
    }
}
