<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog; 

class UserDashboardController extends Controller
{
    //
    public function index()
    {
        $userId = Auth::id();

        //  Calculate Stats
        $stats = [
            'pending'  => Reservation::where('user_id', $userId)->where('reservation_status', 'en_attente')->count(),
            'approved' => Reservation::where('user_id', $userId)->where('reservation_status', 'approuvée')->count(),
            'rejected' => Reservation::where('user_id', $userId)->where('reservation_status', 'refusée')->count(),
            'total'    => Reservation::where('user_id', $userId)->count(),
        ];

        // Get recent activity (last 5 items)
        $recentActivity = Reservation::where('user_id', $userId)
            ->with('resource') // Eager load resource name
            ->latest()
            ->take(5)
            ->get();

        //  Get the Next Upcoming Reservation (to show a shortcut widget)
        $upcoming = Reservation::where('user_id', $userId)
            ->where('reservation_status', 'approved')
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->first();

        return view('user.dashboard', compact('stats', 'upcoming', 'recentActivity'));
    }
}
