<?php
//created by mohammed 12/01
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Resource;
use App\Models\Incident;

class UserIncidentController extends Controller
{
    // Show the Report Form
    public function create(Request $request)
    {
        //the list of resources  :mohammed 12/01
        $resources = Resource::where('is_active', true)->get();

        // Check if we are coming from a specific reservation
        $selectedReservation = null;
        if ($request->has('reservation_id')) {
            $selectedReservation = \App\Models\Reservation::find($request->reservation_id);
        }

        return view('user.incidents.create', compact('resources', 'selectedReservation'));
    }

    // Save the Incident
    public function store(Request $request)
    {
        $validated = $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'title'       => 'required|string|max:255',
            'priority'    => 'required|in:basse,moyenne,haute',
            'description' => 'required|string|min:10',
            'reservation_id' => 'nullable|exists:reservations,id', 
        ]);

        Incident::create([
            'user_id'         => Auth::id(),
            'resource_id'     => $validated['resource_id'],
            'reservation_id'  => $validated['reservation_id'] ?? null,
            'title'           => $validated['title'],
            'priority'        => $validated['priority'],
            'description'     => $validated['description'],
            'incident_status' => 'ouvert', // Default status
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Incident reported successfully. The management team has been notified.');
    }
}
