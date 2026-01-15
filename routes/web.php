<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Role;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminResourceController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminMaintenanceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminLogController;
use App\Http\Controllers\AdminAccountRequestController;

// ==========================================
// 1. PUBLIC ROUTES (No Login Required)
// ==========================================

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

// Common Tools
Route::get('/catalog', [ResourceController::class, 'index'])->name('catalog.index');

// Auth Views & Logic
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Standard Registration (Direct Account Creation)
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store'); // <--- FIXED: ADDED THIS

// Guest Account Request (For Admin Approval Workflow)
Route::get('/register-request', [GuestController::class, 'showRegisterForm'])->name('guest.register.show');
Route::post('/register-request', [GuestController::class, 'submitRegisterRequest'])->name('guest.register.submit');


// ==========================================
// 2. PROTECTED ROUTES (Login Required)
// ==========================================

Route::middleware(['auth'])->group(function () {

    // --- GLOBAL: NOTIFICATIONS ---
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');

    // --- A. ROLE: INVITE ---
    Route::prefix('guest')->middleware(['role:invite'])->group(function () {
        Route::get('/dashboard', function () { return view('Guest.dashboard'); })->name('guest.dashboard');
        Route::get('/resources', [GuestController::class, 'index'])->name('guest.resources');
        Route::get('/policies', [GuestController::class, 'policies'])->name('guest.policies');
    });

    // --- B. ROLE: INTERNAL USER ---
    Route::prefix('my')->middleware(['role:utilisateur_interne'])->group(function () { 
        Route::get('/dashboard', function () { return view('user.dashboard'); })->name('user.dashboard');
        Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    });

    // --- C. ROLE: MANAGER ---
    Route::prefix('manager')->middleware(['role:responsable_technique'])->group(function () { 
        Route::get('/dashboard', function () { return view('manager.dashboard'); })->name('manager.dashboard');
        Route::post('/reservations/approve', [ReservationController::class, 'approve'])->name('manager.reservations.approve');
    });

    // --- D. ROLE: ADMIN ---
    Route::prefix('admin')->middleware(['role:admin'])->group(function () {
        
        // 1. Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // 2. User Management
        Route::post('/users/{id}/toggle', [AdminUserController::class, 'toggleStatus'])->name('admin.users.toggle');
        Route::resource('users', AdminUserController::class)->names('admin.users');

        // 3. Resource Management
        Route::resource('resources', AdminResourceController::class)->names('admin.resources');
        Route::post('/resources/{id}/toggle', [AdminResourceController::class, 'toggleStatus'])->name('admin.resources.toggle');

        // 4. Maintenance Management
        Route::resource('maintenances', AdminMaintenanceController::class)
             ->only(['index', 'create', 'store', 'destroy'])
             ->names('admin.maintenances');

        // 5. Global Logs
        Route::get('/logs', [AdminLogController::class, 'index'])->name('admin.logs.index');

        // 6. Reservation Actions
        Route::post('/reservations/{id}/approve', [ReservationController::class, 'approve'])->name('reservations.approve');

        // 7. Account Request Actions
        Route::post('/account-requests/{id}/approve', [AdminAccountRequestController::class, 'approve'])->name('admin.accounts.approve');
        Route::post('/account-requests/{id}/reject', [AdminAccountRequestController::class, 'reject'])->name('admin.accounts.reject');
    });

}); 

// ==========================================
// 3. TESTING UTILITIES
// ==========================================
Route::get('/test-all-roles', function () {
    $roles = Role::all();
    $output = "<h2>Reservation Permissions</h2><table border='1'><tr><th>Role</th><th>Create</th><th>Approve</th></tr>";
    foreach ($roles as $role) {
        $u = new User(); $u->role_id = $role->id; $u->setRelation('role', $role);
        $c = Gate::forUser($u)->allows('create-reservation') ? 'YES' : 'NO';
        $a = Gate::forUser($u)->allows('approve-reservation') ? 'YES' : 'NO';
        $output .= "<tr><td>{$role->name}</td><td>{$c}</td><td>{$a}</td></tr>";
    }
    return $output . "</table>";
});