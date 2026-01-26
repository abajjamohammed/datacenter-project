<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resource;
use App\Models\AccountRequest; // We need this model to save requests

class GuestController extends Controller
{
    // 1. Existing function for the guest dashboard/resources
    public function index() {
        $resources = Resource::all(); 
        return view('Guest.resources', compact('resources'));
    }

    // 2. MISSING FUNCTION 1: Show the form
    // This fixes the error in your screenshot
    public function showRegisterForm() {
        return view('auth.register'); // We will create this view next
    }

    // 3. MISSING FUNCTION 2: Handle the form submission
    public function submitRegisterRequest(Request $request) {
        // Validate the input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'department' => 'required|string',
            'justification' => 'required|string|min:10',
        ]);

        // Save to Database
        AccountRequest::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'department' => $validated['department'],
            'justification' => $validated['justification'],
            'status' => 'En attente', // Pending
        ]);

        return back()->with('success', 'Your request has been sent to the administrator.');
    }
}
