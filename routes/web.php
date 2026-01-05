<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\GuestController;

// !!!!!!!!!
//NB: when we split the users, every1 should write his user routes in his specific prefix
// !!!!!!!!!!!!!!!!!!!

// 1. PUBLIC ROUTES
Route::get('/', function () {
    return view('layouts.app'); // This matches your main dashboard
})->name('home');

// Authentication Forms
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

// Authentication Logic
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 2. PROTECTED ROUTES (Require Login)
Route::middleware(['auth'])->group(function () {

    // Common Tools: Search & Catalog
    Route::get('/catalog', [ResourceController::class, 'index'])->name('catalog.index');

    // Guest Specific Routes
    Route::prefix('guest')->middleware(['role:invité'])->group(function () {
        Route::get('/resources', [GuestController::class, 'index'])->name('guest.resources');
        Route::get('/policies', [GuestController::class, 'policies'])->name('guest.policies');
        Route::get('/register-request', [GuestController::class, 'showRegisterForm'])->name('guest.register.show');
        Route::post('/register-request', [GuestController::class, 'submitRegisterRequest'])->name('guest.register.submit');
    });

    // Internal User Specific Routes
    Route::prefix('user')->middleware(['role:utilisateur_interne'])->group(function () {
        Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    });

    Route::prefix('user')->middleware(['role:responsable_technique'])->group(function () {
        Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    });
    // Admin Specific Routes
    Route::prefix('admin')->middleware(['role:admin'])->group(function () {
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

