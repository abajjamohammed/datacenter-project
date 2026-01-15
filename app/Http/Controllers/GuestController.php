<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use App\Models\AccountRequest;
use App\Models\User;
use App\Models\Notification;

class GuestController extends Controller
{
    // 1. Show Public Resources (Existing)
    public function index() {
        $resources = Resource::where('is_active', true)->get(); 
        return view('Guest.resources', compact('resources'));
    }

    // 2. Show the "Request Access" Form (NEW)
    public function showRegisterForm()
    {
        return view('auth.register-request');
    }

    // 3. Handle the Form Submission (NEW)
    public function submitRegisterRequest(Request $request)
    {
        // Validate Input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:account_requests,email', // Check if email is already taken
            'phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'profile' => 'required|in:ingénieur,enseignant,doctorant',
            'justification' => 'required|string|max:500',
        ]);

        // Create the Request in Database
        $accountRequest = AccountRequest::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'department' => $validated['department'],
            'profile' => $validated['profile'],
            'justification' => $validated['justification'],
            'status' => 'en_attente'
        ]);

        // Notify Admins
        $admins = User::whereHas('role', function($q) {
            $q->where('name', 'admin');
        })->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'account',
                'title' => 'New Account Request',
                'message' => "{$validated['name']} ({$validated['profile']}) has requested an account.",
                'is_read' => false
            ]);
        }

        // Redirect back to login with success message
        return redirect()->route('login')->with('success', 'Your request has been sent successfully! Please wait for Admin approval.');
    }
}