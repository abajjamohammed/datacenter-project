<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    use HasFactory;

    // 🔴 FIX: Tell Laravel NOT to look for an 'updated_at' column
    // This solves the SQL "Column not found" error.
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'action',       
        'description',  
        'model_type',   
        'model_id',     
        'ip_address',
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

    // STATIC HELPER TO CREATE LOGS EASILY
    public static function record($action, $description, $model = null)
    {
        self::create([
            'user_id'     => Auth::id(),
            'action'      => $action,
            'description' => $description,
            'model_type'  => $model ? get_class($model) : null,
            'model_id'    => $model ? $model->id : null,
            'ip_address'  => request()->ip(),
        ]);
    }
}