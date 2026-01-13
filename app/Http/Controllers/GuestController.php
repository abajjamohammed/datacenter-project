<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index() {
    $resources = \App\Models\Resource::all(); 
    return view('Guest.resources', compact('resources')); // This matches the folder/file we just made
}

public function showRegisterForm()
    {
        return view('auth.register'); 
    }
    public function submitRegisterRequest(Request $request)
{
    // For now, just a placeholder so it doesn't crash
    return "Request submitted successfully!";
}
}

