<?php
//created by mohammed 12/01
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Resource;
use App\Models\Incident;
use App\Models\Notification;
use App\Models\ActivityLog;

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

        $incident = Incident::create([
            'user_id'         => Auth::id(),
            'resource_id'     => $validated['resource_id'],
            'reservation_id'  => $validated['reservation_id'] ?? null,
            'title'           => $validated['title'],
            'priority'        => $validated['priority'],
            'description'     => $validated['description'],
            'incident_status' => 'ouvert', // Default status
        ]);

        Notification::create([
            'user_id' => $incident->resource->responsable_id,
            'type' => 'incident',
            'title' => 'Technical Incident Reported',
            'message' => "An incident was reported on '{$incident->resource->name}' by " . Auth::user()->name,
            'is_read' => false
        ]);

        // 🔥 LOG ACTIVITY
        ActivityLog::record(
            'Reported Incident',
            "User reported {$validated['priority']} priority incident for {$incident->resource->name}: {$validated['title']}",
            $incident
        );

        return redirect()->route('user.dashboard')->with('success', 'Incident reported successfully. The management team has been notified.');
    }
}
