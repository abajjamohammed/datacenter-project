<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; //REQUIRED 
use App\Models\User;
use App\Models\Role;
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
            // return redirect()->intended('/');   mohammed: re,oved this bcs its redirect every1 to the home page, but we have to redirect every user to his specific dashboard
            // On récupère le rôle pour savoir où l'envoyer
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
        //  'role_id'  => 'required|integer|exists:roles,id',  mohammed: removed for sercurity
            // added those, u forgot them!  :mohammed  06/01
            'department' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        // On cherche l'ID du rôle 'invite' dans la base de données
        $roleInvite = Role::where('name', 'invite')->first();

        // Si le rôle n'existe pas, on gère l'erreur
        if (!$roleInvite) {
            return back()->withErrors(['email' => 'Erreur système : Rôle invité introuvable.']);
        }


        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $roleInvite->id,   //we force the role id, so no 1 can wirte for ex an admin id and access to it    
            'department' => $request->department,
            'phone' => $request->phone,
            'is_active' => true
        ]);
     

        Auth::login($user);
       // return redirect('/')->with('success', 'Account created successfully!');
        return redirect()->route('guest.dashboard')->with('success', 'Account created successfully!'); // mohammed: we redirect the guest to his dashboard
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
