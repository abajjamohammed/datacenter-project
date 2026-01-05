<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReservationController extends Controller
{
    /**
     * List reservations
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        if (Gate::allows('approve-reservation', $user)) {
            $reservations = Reservation::with(['user', 'resource'])->get();
        } else {
            $reservations = Reservation::with(['resource'])
                ->where('user_id', $user->id)
                ->get();
        }

        return response()->json($reservations);
    }

    /**
     * Create reservation
     */
    public function store(Request $request)
    {
        Gate::authorize('create-reservation', $request->user());

        $validated = $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'start_time'  => 'required|date',
            'end_time'    => 'required|date|after:start_time',
        ]);

        $reservation = Reservation::create([
            'resource_id' => $validated['resource_id'],
            'start_time'  => $validated['start_time'],
            'end_time'    => $validated['end_time'],
            'status'      => 'pending',
            'user_id'     => $request->user()->id,
        ]);

        return response()->json($reservation, 201);
    }

    /**
     * Approve reservation
     */
    public function approve(Request $request, $id)
    {
        Gate::authorize('approve-reservation', $request->user());

        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => 'approved']);

        return response()->json($reservation);
    }
}
