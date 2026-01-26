<?php

namespace App\Http\Controllers;

use App\Models\AccountRequest;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class AdminAccountRequestController extends Controller
{
    // 1. APPROVE: Convert Request to User
    public function approve($id)
    {
        $request = AccountRequest::findOrFail($id);

        // A. Find the Role (Default to 'utilisateur_interne' for standard requests)
        // If the request specifically asked for 'invité', logic can be adjusted here.
        $role = Role::where('name', 'utilisateur_interne')->first();

        // B. Generate a temporary password
        $tempPassword = Str::random(10);

        // C. Create the real User
        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($tempPassword), // Hash the temp password
            'role_id' => $role->id,
            'department' => $request->department,
            'profile' => $request->profile, // e.g. Ingénieur
            'phone' => $request->phone,
            'is_active' => true,
        ]);

        // 👉 NOTIFY THE ADMIN (Confirming the action)
        Notification::create([
            'user_id' => Auth::id(), // Send to the Admin who just clicked approve
            'type' => 'account',
            'title' => 'Account Created',
            'message' => "You have successfully approved the account for {$newUser->name}.",
            'is_read' => false
        ]);

        // D. Delete the request
        $request->delete();

        // E. Return with the password so Admin can share it
        return back()->with('success', "User approved! Temporary Password: " . $tempPassword);
    }

    // 2. REJECT: Delete the request
    public function reject($id)
    {
        $request = AccountRequest::findOrFail($id);
        $request->delete();

        return back()->with('success', 'Account request rejected.');
    }
}
