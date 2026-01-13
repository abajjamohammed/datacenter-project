<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\GuestController;


// 1. PUBLIC ROUTES
Route::get('/', function () {
    if (Auth::check()) {
        $role = Auth::user()->role->name;

        return match($role) {
            'admin'                 => redirect()->route('admin.dashboard'),
            'responsable_technique' => redirect()->route('manager.dashboard'),
            'utilisateur_interne'   => redirect()->route('user.dashboard'),
            'invite'                => redirect()->route('guest.dashboard'),
            default                 => redirect()->route('catalog.index'),
        };
    }
    return view('auth.login'); // If not logged in, show login
})->name('home');

//--Guest section--//
// --- A. ROLE: INVITE (Logged in user with limited rights) ---
Route::prefix('guest')->group(function () {
        // Dashboard (Required for Login Redirection)
        Route::get('/dashboard', function () {
            return view('Guest.dashboard');
        })->name('guest.dashboard');

        // Other logged-in guest features 
        Route::get('/resources', [GuestController::class, 'index'])->name('guest.resources');
    });

// Common Tools: Search & Catalog 
// mohamed: moved this from the middleware to outside here, because everyone can access it we dont have to securise it by the middleware
Route::get('/catalog', [ResourceController::class, 'index'])->name('catalog.index');


// Authentication Forms
Route::get('/login', function () {
    return view('auth.login');
})->name('login');


Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

//moved the acc request here bcs Must be PUBLIC so they can ask for an account
Route::get('/register-request', [GuestController::class, 'showRegisterForm'])->name('guest.register.show');
Route::post('/register-request', [AuthController::class, 'register'])->name('guest.register.submit');

// Authentication Logic
//Route::post('/login', [AuthController::class, 'login']);
//Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//USage policies available for every role to see.
Route::get('/usage-policies', [AuthController::class, 'showPolicies'])->name('policies.show');


// 2. PROTECTED ROUTES (Require Login)
Route::middleware(['auth'])->group(function () {

   Route::get('/activity-logs', function() {
        return "Activity Logs Page - Coming Soon";
    })->name('activity.logs');

    // --- B. ROLE: UTILISATEUR INTERNE ---
    Route::prefix('my')->middleware(['role:utilisateur_interne'])->group(function () {  // changed from prefix user to prefix my  :mohammed 06/01
        // Dashboard (Required for Login Redirection)
        Route::get('/dashboard', function () {
            return view('user.dashboard');
        })->name('user.dashboard');

        Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    });


    // --- C. ROLE: RESPONSABLE TECHNIQUE ---
    Route::prefix('manager')->middleware(['role:responsable_technique'])->group(function () {   // changed from user prefix to manager prefix  :mohammed 06/01
        // Dashboard
        Route::get('/dashboard', function () {
            return view('manager.dashboard');
        })->name('manager.dashboard');

        Route::post('/reservations/approve', [ReservationController::class, 'approve'])->name('manager.reservations.approve');
     // Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');  replaced it wth the previous line :mohammed
    });


    // --- D. ROLE: ADMIN ---
    Route::prefix('admin')->middleware(['role:admin'])->group(function () {
        // Dashboard
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        Route::post('/reservations/{id}/approve', [ReservationController::class, 'approve'])->name('reservations.approve');
    });


});
 




// 3. TESTING UTILITIES
Route::get('/test-all-roles', function () {

    $roles = Role::all();

    $output = "<h2>Reservation Permissions by Role</h2>";
    $output .= "<table border='1' cellpadding='8' style='border-collapse: collapse; font-family: Arial, sans-serif;'>";
    $output .= "<tr style='background-color:#f0f0f0;'><th>Role</th><th>Can Create Reservation?</th><th>Can Approve Reservation?</th></tr>";

    foreach ($roles as $role) {
        // Create a temporary user in memory
        $tempUser = new User();
        $tempUser->role_id = $role->id;

        // Attach the actual Role model so the Gate can see it
        $tempUser->setRelation('role', $role);

        // Check permissions using the Gates
        $canCreate = Gate::forUser($tempUser)->allows('create-reservation');
        $canApprove = Gate::forUser($tempUser)->allows('approve-reservation');

        // Color formatting
        $createColor = $canCreate ? 'green' : 'red';
        $approveColor = $canApprove ? 'green' : 'red';

        $output .= "<tr>
                        <td>{$role->name}</td>
                        <td style='color: {$createColor}; font-weight:bold;'>" . ($canCreate ? 'YES' : 'NO') . "</td>
                        <td style='color: {$approveColor}; font-weight:bold;'>" . ($canApprove ? 'YES' : 'NO') . "</td>
                    </tr>";
    }

    $output .= "</table>";

    return $output;
});
