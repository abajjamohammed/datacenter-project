<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\AccountRequest;

class AuthController extends Controller
{
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
            $role = Auth::user()->role->name;

            switch ($role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'responsable_technique':
                    return redirect()->route('manager.dashboard');
                case 'utilisateur_interne':
                    return redirect()->route('user.dashboard');
                case 'invite':
                    return redirect()->route('guest.dashboard');
                default:
                    return redirect()->route('home');
            }
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // 1. Validate the incoming request
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:account_requests,email',
            'password'      => 'required|string|min:8',
            'justification' => 'required|string|max:2000',
            'department'    => 'nullable|string',
            'phone'         => 'nullable|string',
        ]);

        /* --- COMMENTED OUT OLD LOGIC (Creates user immediately) ---
        $roleInvite = Role::where('name', 'invite')->first();
        if (!$roleInvite) {
            return back()->withErrors(['email' => 'Erreur système : Rôle invité introuvable.']);
        }
        $user = User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role_id'    => $roleInvite->id,
            'department' => $request->department,
            'phone'      => $request->phone,
            'is_active'  => true
        ]);
        Auth::login($user);
        return redirect()->route('guest.dashboard')->with('success', 'Account created successfully!');
        ------------------------------------------------------------- */

        // 2. NEW LOGIC: Save to account_requests table
        $newRequest = AccountRequest::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'department'    => $request->department,
            'phone'         => $request->phone,
            'justification' => $request->justification,
            'status'        => 'en_attente',
        ]);

        // 2. NOW notify the Admin that someone new applied!
        $admins = \App\Models\User::whereHas('role', function ($q) {
            $q->where('name', 'admin');
        })->get();

        foreach ($admins as $admin) {
            \App\Models\Notification::create([
                'user_id' => $admin->id,
                'type'    => 'account', // This matches your ENUM exactly
                'title'   => 'New Access Request',
                'message' => "{$newRequest->name} just submitted a new account request.",
                'is_read' => false
            ]);
        }

        // 3. Redirect back to login with success message
        return redirect()->route('guest.dashboard')->with('success', 'Request submitted successfully! An admin will review it soon.');
    }

    // Added Logout for completeness
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
    public function showPolicies()
    {
        return view('auth.policies'); // Move the view file to the 'auth' folder for better organization
    }
}
