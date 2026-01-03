<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReservationController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request)
    {
        $this->authorize('create-reservation');

        return response()->json([
            'message' => 'Reservation created successfully'
        ]);
    }

    public function approve($id)
    {
        $this->authorize('approve-reservation');

        return response()->json([
            'message' => "Reservation {$id} approved successfully"
        ]);
    }
}
