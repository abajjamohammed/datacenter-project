<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; //REQUIRED 
use App\Models\User; 
use Illuminate\Support\Facades\Hash; //Encrypts passwords

class AuthController extends Controller
{
    // Display the Login Form
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request) 
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }

    // Display the Registration Form
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id'  => 'required|integer|exists:roles,id', 
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), 
            'role_id'  => $request->role_id, 
        ]);

        Auth::login($user);
        return redirect('/')->with('success', 'Account created successfully!');
    }

    // Added Logout for completeness
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}