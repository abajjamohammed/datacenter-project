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
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminResourceController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminMaintenanceController;
use App\Http\Controllers\NotificationController; // <--- NEW IMPORT

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
    return view('auth.login'); 
})->name('home');

// Common Tools: Search & Catalog 
Route::get('/catalog', [ResourceController::class, 'index'])->name('catalog.index');

// Authentication Forms
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

// Guest Account Request
Route::get('/register-request', [GuestController::class, 'showRegisterForm'])->name('guest.register.show');
Route::post('/register-request', [GuestController::class, 'submitRegisterRequest'])->name('guest.register.submit');

// Authentication Logic
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// 2. PROTECTED ROUTES (Require Login)
Route::middleware(['auth'])->group(function () {

    // --- GLOBAL ROUTES (Accessible by ANY logged-in user) ---
    // Notification Logic
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');


    // --- A. ROLE: INVITE ---
    Route::prefix('guest')->middleware(['role:invite'])->group(function () {
        Route::get('/dashboard', function () {
            return view('Guest.dashboard');
        })->name('guest.dashboard');
        Route::get('/resources', [GuestController::class, 'index'])->name('guest.resources');
        Route::get('/policies', [GuestController::class, 'policies'])->name('guest.policies');
    });


    // --- B. ROLE: UTILISATEUR INTERNE ---
    Route::prefix('my')->middleware(['role:utilisateur_interne'])->group(function () { 
        Route::get('/dashboard', function () {
            return view('user.dashboard');
        })->name('user.dashboard');
        Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    });


    // --- C. ROLE: RESPONSABLE TECHNIQUE ---
    Route::prefix('manager')->middleware(['role:responsable_technique'])->group(function () { 
        Route::get('/dashboard', function () {
            return view('manager.dashboard');
        })->name('manager.dashboard');
        Route::post('/reservations/approve', [ReservationController::class, 'approve'])->name('manager.reservations.approve');
    });


    // --- D. ROLE: ADMIN ---
    Route::prefix('admin')->middleware(['role:admin'])->group(function () {
        
        // 1. Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // 2. User Management
        Route::post('/users/{id}/toggle', [AdminUserController::class, 'toggleStatus'])->name('admin.users.toggle');
        Route::resource('users', AdminUserController::class)->names('admin.users');

        // 3. Reservation Management
        Route::post('/reservations/{id}/approve', [ReservationController::class, 'approve'])->name('reservations.approve');

        // 4. Resource Management (CRUD)
        Route::resource('resources', AdminResourceController::class)->names('admin.resources');
        Route::post('/resources/{id}/toggle', [AdminResourceController::class, 'toggleStatus'])->name('admin.resources.toggle');

        // 5. Maintenance Management
        Route::resource('maintenances', AdminMaintenanceController::class)
             ->only(['index', 'create', 'store', 'destroy'])
             ->names('admin.maintenances');
    });

});
 

// 3. TESTING UTILITIES
Route::get('/test-all-roles', function () {
    $roles = Role::all();
    $output = "<h2>Reservation Permissions by Role</h2>";
    $output .= "<table border='1' cellpadding='8' style='border-collapse: collapse; font-family: Arial, sans-serif;'>";
    $output .= "<tr style='background-color:#f0f0f0;'><th>Role</th><th>Can Create Reservation?</th><th>Can Approve Reservation?</th></tr>";

    foreach ($roles as $role) {
        $tempUser = new User();
        $tempUser->role_id = $role->id;
        $tempUser->setRelation('role', $role);

        $canCreate = Gate::forUser($tempUser)->allows('create-reservation');
        $canApprove = Gate::forUser($tempUser)->allows('approve-reservation');

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