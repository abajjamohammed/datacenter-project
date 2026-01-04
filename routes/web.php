<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;

// !!!!!!!!!
//NB: when we split the users, every1 should write his user routes in his specific prefix
// !!!!!!!!!!!!!!!!!!!


// Route for the home page, it was here the first idk why and who delete it 
Route::get('/', function () {
    return view('homepage');
});


// TODO: add authentication routes after the creation of AuthController
//dont forgt to use App\Http\Controllers\AuthController;


// Routes that require authentication
Route::middleware(['auth'])->group(function () {

    Route::post('/reservations', [ReservationController::class, 'store'])
        ->name('reservations.store');

    Route::post('/reservations/{id}/approve', [ReservationController::class, 'approve'])
        ->name('reservations.approve');

});

// Route to test all roles and show their permissions
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





// here we have to put the guest routes (inside the prefixe)
//Route::prefix('guest')->middleware(['auth', 'role:invité'])->group(function () {
Route::prefix('guest')->group(function () {
    // 1. View available resources (Read-only)
    Route::get('/resources', [App\Http\Controllers\GuestController::class, 'index'])
        ->name('guest.resources');

    // 2. View usage policies
    Route::get('/policies', [App\Http\Controllers\GuestController::class, 'policies'])
        ->name('guest.policies');

    // 3. Submit account registration request (Form and Post)
    Route::get('/register-request', [App\Http\Controllers\GuestController::class, 'showRegisterForm'])
        ->name('guest.register.show');

    Route::post('/register-request', [App\Http\Controllers\GuestController::class, 'submitRegisterRequest'])
        ->name('guest.register.submit');
    // u gonna write the guest routes here
});


// Routes for the intern user
Route::prefix('user')->middleware(['auth', 'role:utilisateur_interne'])->group(function () {
      // it has to be completed
    // u gonna write the user routes here
});



// Routes for the technical responsable
Route::prefix('responsable')->middleware(['auth', 'role:responsable_technique'])->group(function () {
     // it has to be completed
    // u gonna write the responsable routes here
});



// Routes for admin
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    // it has to be completed
    // u gonna write the admin routes here
});
// Define the home page route again
Route::get('/', function () {
    return view('test'); 
});