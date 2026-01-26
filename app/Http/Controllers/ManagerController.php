<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Resource;
use App\Models\ResourceCategory;
use App\Models\Reservation;
use App\Models\Maintenance;
use App\Models\Incident;
use App\Models\Notification;

class ManagerController extends Controller
{
    // --- DASHBOARD ---
    public function dashboard()
    {
        $user = Auth::user();
        $myResourceIds = $user->managedResources->pluck('id');

        // Statistics
        $totalResources = $user->managedResources->count();

        // FIX 1: Changed 'en_attente' to 'EN_ATTENTE' (Uppercase) to match database
        $pendingReservations = Reservation::whereIn('resource_id', $myResourceIds)
            ->where('reservation_status', 'EN_ATTENTE')
            ->count();

        $activeIncidents = Incident::whereIn('resource_id', $myResourceIds)
            ->where('incident_status', '!=', 'Resolved')
            ->count();

        // Recent Reservations waiting for approval
        // FIX 2: Changed 'en_attente' to 'EN_ATTENTE' (Uppercase)
        $recentRequests = Reservation::with(['user', 'resource'])
            ->whereIn('resource_id', $myResourceIds)
            ->where('reservation_status', 'EN_ATTENTE')
            ->latest()
            ->take(5)
            ->get();

        return view('manager.dashboard', compact('totalResources', 'pendingReservations', 'activeIncidents', 'recentRequests'));
    }

    // --- RESOURCE MANAGEMENT (CRUD) ---
    public function myResources()
    {
        $resources = Resource::where('responsable_id', Auth::id())->with('category')->get();
        $categories = ResourceCategory::all(); // For the dropdown in Add/Edit forms
        return view('manager.resources.index', compact('resources', 'categories'));
    }

    public function storeResource(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'category_id' => 'required|exists:resource_categories,id',
            'location' => 'required|string',
        ]);

        Resource::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'location' => $request->location,
            'specifications' => [], // Default empty array
            'resource_status' => 'disponible',
            'responsable_id' => Auth::id(), // Assigned to YOU
            'is_active' => true,
        ]);

        return back()->with('success', 'Resource created successfully.');
    }

    public function updateResource(Request $request, $id)
    {
        $resource = Resource::where('id', $id)->where('responsable_id', Auth::id())->firstOrFail();

        $request->validate(['name' => 'required|string', 'location' => 'required|string']);

        $resource->update([
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
        ]);

        return back()->with('success', 'Resource updated successfully.');
    }

    public function destroyResource($id)
    {
        $resource = Resource::where('id', $id)->where('responsable_id', Auth::id())->firstOrFail();
        // Soft disable
        $resource->update(['is_active' => false]);
        return back()->with('success', 'Resource deactivated.');
    }

    public function toggleMaintenance(Request $request, $id)
    {
        $resource = Resource::where('id', $id)->where('responsable_id', Auth::id())->firstOrFail();

        if ($resource->resource_status === 'maintenance') {
            $resource->update(['resource_status' => 'disponible']);
        } else {
            $resource->update(['resource_status' => 'maintenance']);
            Maintenance::create([
                'resource_id' => $resource->id,
                'description' => 'Maintenance initiated by manager',
                'start_date' => now(),
                'created_by' => Auth::id(),
            ]);
        }
        return back()->with('success', 'Resource status updated.');
    }

    // --- RESERVATION MANAGEMENT ---
    public function reservations()
    {
        $myResourceIds = Auth::user()->managedResources->pluck('id');
        $reservations = Reservation::with(['user', 'resource'])
            ->whereIn('resource_id', $myResourceIds)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('manager.reservations.index', compact('reservations'));
    }

    public function approveReservation(Request $request, $id)
    {
        // Validate the approval justification
        $request->validate([
            'approval_comment' => 'required|string|min:5'
        ], [
            'approval_comment.required' => 'Please provide a justification for approval',
            'approval_comment.min' => 'Justification must be at least 5 characters'
        ]);

        // Find the reservation belonging to your managed resources
        $reservation = Reservation::where('id', $id)
            ->whereHas('resource', function ($q) {
                $q->where('responsable_id', Auth::id());
            })->firstOrFail();

        // FIX 3: Changed status to 'APPROUVÉE' (Uppercase)
        $reservation->update([
            'reservation_status' => 'APPROUVÉE',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'approval_comment' => $request->approval_comment,
        ]);

        // Optional: Update the resource status to 'réservée'
        $reservation->resource->update([
            'resource_status' => 'réservée'
        ]);

        // 👉 3. CREATE NOTIFICATION (This was missing!)
        Notification::create([
            'user_id' => $reservation->user_id, // Send to the student/engineer
            'type' => 'reservation_response',
            'title' => 'Reservation Approved',
            'message' => "Your request for '{$reservation->resource->name}' has been APPROVED.",
            'reservation_id' => $reservation->id,
            'is_read' => false
        ]);

        return back()->with('success', 'Reservation approved successfully with justification.');
    }

    public function rejectReservation(Request $request, $id)
    {
        // Validate the rejection justification
        $request->validate([
            'rejection_reason' => 'required|string|min:5'
        ], [
            'rejection_reason.required' => 'Please provide a reason for rejection',
            'rejection_reason.min' => 'Rejection reason must be at least 5 characters'
        ]);

        // Find the reservation belonging to your managed resources
        $reservation = Reservation::where('id', $id)
            ->whereHas('resource', function ($q) {
                $q->where('responsable_id', Auth::id());
            })->firstOrFail();

        // FIX 4: Changed status to 'REFUSÉE' (Uppercase)
        $reservation->update([
            'reservation_status' => 'REFUSÉE',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'approval_comment' => $request->rejection_reason,
        ]);

        // 👉 2. CREATE NOTIFICATION (This was missing!)
        Notification::create([
            'user_id' => $reservation->user_id,
            'type' => 'reservation_response',
            'title' => 'Reservation Rejected',
            'message' => "Your request was REJECTED. Reason: {$request->rejection_reason}",
            'reservation_id' => $reservation->id,
            'is_read' => false
        ]);


        return back()->with('success', 'Reservation rejected with justification.');
    }

    // --- INCIDENT & MODERATION MANAGEMENT ---
    public function incidents()
    {
        $myResourceIds = Auth::user()->managedResources->pluck('id');
        $incidents = Incident::with(['reporter', 'resource'])
            ->whereIn('resource_id', $myResourceIds)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('manager.incidents.index', compact('incidents'));
    }

    public function resolveIncident($id)
    {
        $incident = Incident::where('id', $id)
            ->whereHas('resource', function ($q) {
                $q->where('responsable_id', Auth::id());
            })->firstOrFail();

        $incident->update([
            'incident_status' => 'Resolved',
            'resolved_by' => Auth::id(),
            'resolved_at' => now(),
        ]);

        // 👉 NOTIFY THE USER WHO REPORTED IT
        Notification::create([
            'user_id' => $incident->user_id,
            'type' => 'incident',
            'title' => 'Incident Resolved',
            'message' => "Your report regarding '{$incident->resource->name}' has been marked as Resolved.",
            'is_read' => false
        ]);
        return back()->with('success', 'Incident marked as resolved.');
    }

    public function moderation()
    {
        $myResourceIds = Auth::user()->managedResources->pluck('id');
        $alerts = Incident::with(['reporter', 'resource'])
            ->whereIn('resource_id', $myResourceIds)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('manager.moderation.index', compact('alerts'));
    }

    public function destroyIncident($id)
    {
        $incident = Incident::where('id', $id)
            ->whereHas('resource', function ($q) {
                $q->where('responsable_id', Auth::id());
            })->firstOrFail();

        $incident->delete(); // Delete inappropriate content
        return back()->with('success', 'Inappropriate alert deleted.');
    }
}
