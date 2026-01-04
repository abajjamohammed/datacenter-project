<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index() {
    $resources = \App\Models\Resource::all(); 
    return view('Guest.resources', compact('resources')); // This matches the folder/file we just made
}
}
