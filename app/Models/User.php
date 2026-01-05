<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id', // 👈 added to allow assigning roles
        // i added the following line bcs u forgot them :mohammed
        'profile',
        'phone',
        'department',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean', // (to have true/false instead of 0 1) i added this it was missing :mohammed 
        ];
    }

    /**
     * 🔗 Relationship: User belongs to a Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    //u forget to add the other relations :mohammed
    public function reservations()
    {
        return $this->hasMany(Reservation::class);  // the reservations sitted by a user
    }


    public function approvedReservations()
    {
        return $this->hasMany(Reservation::class, 'approved_by');  // reservations approved by a rsponsable  :mohammed 05/01
    }

    public function managedResources()
    {
        return $this->hasMany(Resource::class, 'responsable_id');  // the resources managed by a user
    }


    public function notifications()
    {
        return $this->hasMany(Notification::class);  // user notifs
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class, 'created_by');  // Maintenances created by user
    }


    public function incidents()
    {
        return $this->hasMany(Incident::class);  // the incident which has been signaled by a user
    }

    public function resolvedIncidents()
    {
        return $this->hasMany(Incident::class, 'resolved_by');  // a responsable can resolve mltpl incidents 
    }


    public function processedAccountRequests()
    {
        return $this->hasMany(AccountRequest::class, 'processed_by');
    }  // an admin can process mltpl acc requests



    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }



    
    // helpers-------
    
    public function hasRole(string $roleName): bool
    {
        return $this->role->name === $roleName;
    }

    // added 05/01 by mohammed
    // verify if l'the user is actif
    public function isActive(): bool
    {
        return $this->is_active;
    }

    // verify if l'the user is admin
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    // verify if l'the user is a responsable 
    public function isResponsable(): bool
    {
        return $this->hasRole('responsable_technique');
    }

    // count the notifications that hasnt been read
    public function unreadNotifications()
    {
        return $this->notifications()->where('is_read', false);
    }
}
