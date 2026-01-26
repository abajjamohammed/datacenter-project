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
// Admin Controllers (Your Work)
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminResourceController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminMaintenanceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AdminLogController;
use App\Http\Controllers\AdminAccountRequestController;
// Manager & User Controllers (Remote Work)
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\UserDashboardController; // Ensure this exists from pull
use App\Http\Controllers\UserIncidentController; // Ensure this exists from pull

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
Route::get('/usage-policies', [AuthController::class, 'showPolicies'])->name('policies.show');

// Auth Views & Logic
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Registration
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

// Guest Account Request
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

    // --- B. ROLE: INTERNAL USER (Merged Remote Work) ---
    Route::prefix('my')->middleware(['role:utilisateur_interne'])->group(function () { 
        // Dashboard
        Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

        // Reservations
        Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
        Route::get('/reservations/create/{resource}', [ReservationController::class, 'create'])->name('reservations.create');
        Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
        Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');

        // Incidents
        Route::get('/incidents/report', [UserIncidentController::class, 'create'])->name('incidents.create');
        Route::post('/incidents', [UserIncidentController::class, 'store'])->name('incidents.store');
    });

    // --- C. ROLE: MANAGER (Merged Remote Work) ---
    Route::prefix('manager')->middleware(['role:responsable_technique'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [ManagerController::class, 'dashboard'])->name('manager.dashboard');

        // Resources (Add, Edit, Disable, Maintenance)
        Route::get('/resources', [ManagerController::class, 'myResources'])->name('manager.resources.index');
        Route::post('/resources', [ManagerController::class, 'storeResource'])->name('manager.resources.store');
        Route::put('/resources/{id}', [ManagerController::class, 'updateResource'])->name('manager.resources.update');
        Route::delete('/resources/{id}', [ManagerController::class, 'destroyResource'])->name('manager.resources.destroy');
        Route::post('/resources/{id}/maintenance', [ManagerController::class, 'toggleMaintenance'])->name('manager.resources.maintenance');

        // Reservations (Approve, Reject)
        Route::get('/reservations', [ManagerController::class, 'reservations'])->name('manager.reservations.index');
        Route::post('/reservations/{id}/approve', [ManagerController::class, 'approveReservation'])->name('manager.reservations.approve');
        Route::post('/reservations/{id}/reject', [ManagerController::class, 'rejectReservation'])->name('manager.reservations.reject');

        // Incidents (Resolve)
        Route::get('/incidents', [ManagerController::class, 'incidents'])->name('manager.incidents.index');
        Route::post('/incidents/{id}/resolve', [ManagerController::class, 'resolveIncident'])->name('manager.incidents.resolve');

        // Moderation
        Route::get('/moderation', [ManagerController::class, 'moderation'])->name('manager.moderation.index');
        Route::delete('/moderation/incident/{id}', [ManagerController::class, 'destroyIncident'])->name('manager.moderation.delete');
    });

    // --- D. ROLE: ADMIN (Your Work) ---
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

// Activity Logs Placeholder (From Remote)
Route::get('/activity-logs', function() {
    return "Activity Logs Page - Coming Soon";
})->name('activity.logs');

// Testing
Route::get('/test-all-roles', function () {
    // ... your existing testing code ...
});