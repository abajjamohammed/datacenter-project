<?php

namespace App\Http\Controllers;

use App\Models\AccountRequest;
use App\Models\User;
use App\Models\Role;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminAccountRequestController extends Controller
{
    // 1. APPROVE REQUEST
    public function approve($id)
    {
        $request = AccountRequest::findOrFail($id);

        // Check if email already exists in Users table
        if (User::where('email', $request->email)->exists()) {
            return back()->with('error', 'A user with this email already exists.');
        }

        // Find the 'Internal User' role (assuming standard users)
        $role = Role::where('name', 'utilisateur_interne')->first();
        
        // Fallback
        if (!$role) { $role = Role::first(); }

        // Create the new User
        $user = User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make('password123'), // Default password
            'role_id'    => $role->id,
            'department' => $request->department,
            'phone'      => $request->phone,
            'profile'    => $request->profile,
            'is_active'  => true,
        ]);

        // Update Request Status
        $request->update([
            'status' => 'approuvée',
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        // Log it
        if (class_exists(ActivityLog::class)) {
            ActivityLog::record('Approved Account', "Created user {$user->name}", $user);
        }

        return back()->with('success', "Account approved! User created.");
    }

    // 2. REJECT REQUEST
    public function reject($id)
    {
        $request = AccountRequest::findOrFail($id);

        $request->update([
            'status' => 'refusée',
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        // Log it
        if (class_exists(ActivityLog::class)) {
            ActivityLog::record('Rejected Account', "Rejected request for {$request->email}");
        }

        return back()->with('success', 'Account request rejected.');
    }
}