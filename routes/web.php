<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;

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

// Define the home page route again
Route::get('/', function () {
    return view('test'); 
});