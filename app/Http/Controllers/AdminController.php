<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Resource;
use App\Models\Reservation;
use App\Models\AccountRequest;
use App\Models\ResourceCategory; // Added Import

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Base Counts
        $total_resources = Resource::count();
        $total_users = User::count();

        $stats = [
            'total_users' => $total_users,
            'total_resources' => $total_resources,
            'pending_reservations' => Reservation::where('reservation_status', 'en_attente')->count(),
            'pending_accounts' => AccountRequest::where('status', 'en_attente')->count(),
        ];

        // 2. Global Occupancy Rate (Active Reservations vs Total Resources)
        // A resource is "occupied" if it has an ACTIVE reservation happening RIGHT NOW.
        $occupied_count = Reservation::where('reservation_status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->count();
            
        // Calculate percentage (avoid division by zero)
        $occupancy_rate = $total_resources > 0 ? round(($occupied_count / $total_resources) * 100) : 0;

        // 3. DATA CENTER BREAKDOWN (Resources per Category)
        // We need the category name and the count of resources in it for the bar charts
        $categories_breakdown = ResourceCategory::withCount('resources')->get();

        // 4. INFRASTRUCTURE HEALTH (Status Breakdown)
        // Groups resources by their current status (Available vs Maintenance vs Out of Order)
        $status_breakdown = [
            'disponible' => Resource::where('resource_status', 'disponible')->count(),
            'maintenance' => Resource::where('resource_status', 'maintenance')->count(),
            'hors_service' => Resource::where('resource_status', 'hors_service')->count(),
            // Optional: Count 'Reserved' specifically if you treat it as a status distinct from 'Active Reservation'
            'réservée'     => Resource::where('resource_status', 'réservée')->count(), 
        ];

        // 5. TOP ACTIVE USERS (Who has the most approved reservations?)
        // Useful for seeing who consumes the most resources
        $top_active_users = User::withCount(['reservations' => function($q) {
            $q->where('reservation_status', 'approuvée')
              ->orWhere('reservation_status', 'active');
        }])
        ->orderBy('reservations_count', 'desc')
        ->take(4) // Get Top 4
        ->get();

        // 6. Recent Account Requests (Keep existing logic)
        $account_requests = AccountRequest::where('status', 'en_attente')->get();
        
        // 7. Recent Users (Keep existing logic)
        $recent_users = User::with('role')->latest()->take(5)->get();

        // Pass ALL data to the view
        return view('admin.dashboard', compact(
            'stats', 
            'occupancy_rate', 
            'categories_breakdown', 
            'status_breakdown', 
            'top_active_users',
            'account_requests', 
            'recent_users'
        ));
    }

    // Method to toggle user status (Ban/Unban)
    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent admin from banning themselves
        if($user->hasRole('admin')) {
            return back()->with('error', 'Cannot ban an admin.');
        }
        
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User {$user->name} has been {$status}.");
    }
}