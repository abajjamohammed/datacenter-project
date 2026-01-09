<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    use AuthorizesRequests;

    public function approve($id)
    {
        $this->authorize('approve-reservation');

        return response()->json([
            'message' => "Reservation {$id} approved successfully"
        ]);
    }

    // this func is for the reservations history  :mohammed 08/01
    public function index(Request $request)
    {
        // Start the query strictly for the logged-in user
        $query = Reservation::where('user_id', Auth::id());

        // Filter by Resource Name 
        if ($request->filled('resource')) {
            $query->whereHas('resource', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->resource . '%');
            });
        }
        // 2. Filter by Status
        if ($request->filled('status')) {
            $query->where('reservation_status', $request->status);
        }
        // 3. Filter by Date (Exact match on Start Date)
        if ($request->filled('date')) {
            $query->whereDate('start_date', $request->date);
        }

        // Get results sorted by newest
        $reservations = $query->latest()->get();

        return view('user.reservations.index', compact('reservations'));
    }



    //showing the booking form
    public function create(\App\Models\Resource $resource)
    {
        // Check if resource is actually available/active
        if (!$resource->is_active) {
            return back()->with('error', 'This resource is currently inactive.');
        }

        return view('user.reservations.create', compact('resource'));
    }



    //this is the store fun to store the reservation from an internal user    :mohammed:09/01
    public function store(Request $request)
    {
        //  Validate inputs
        $validated = $request->validate([
            'resource_id'   => 'required|exists:resources,id', 
            'start_date'    => 'required|date|after_or_equal:now',
            'end_date'      => 'required|date|after:start_date',
            'justification' => 'required|string|min:10', 
        ], [
            'start_date.after_or_equal' => 'Start date must be today or later.',
            'end_date.after' => 'End date must be after the start date.',
        ]);

        // CHECK AVAILABILITY 
        // Check if there is an overlapping APPROVED reservation
        $conflict = Reservation::where('resource_id', $validated['resource_id'])
            ->where('reservation_status', 'approved') // We check against approved bookings
            ->where(function ($query) use ($validated) {
                // Overlap logic: Start A < End B  AND  End A > Start B
                $query->where('start_date', '<', $validated['end_date'])
                    ->where('end_date', '>', $validated['start_date']);
            })
            ->exists();

        if ($conflict) {
            return back()
                ->withInput()
                ->withErrors(['start_date' => 'This resource is already reserved during this time slot.']);
        }

        // and now we can Create Reservation
        Reservation::create([
            'user_id'            => Auth::id(),
            'resource_id'        => $validated['resource_id'], // Don't forget to save this!
            'start_date'         => $validated['start_date'],
            'end_date'           => $validated['end_date'],
            'justification'      => $validated['justification'],
            'reservation_status' => 'en_attente', 
        ]);

        return redirect()->route('reservations.index')->with('success', 'Reservation created successfully!');
    }

   //this fun is to delete a reservation
    public function destroy(Reservation $reservation)
    {
        // 1. Security Check: Ensure the logged-in user owns this reservation
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // 2. Status Check: You can only cancel "Pending" requests
        if ($reservation->reservation_status !== 'en_attente') {
            return back()->with('error', 'You cannot cancel a reservation that has already been processed.');
        }

        // 3. Delete it
        $reservation->delete();

        return back()->with('success', 'Reservation request cancelled successfully.');
    }
}
