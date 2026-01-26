<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        
        // Mark as read
        $notification->update(['is_read' => true]);

        // Redirect based on notification type
        // For now, we redirect to the specific reservation or back to dashboard
        if ($notification->reservation_id) {
            // Determine user role to know which view to send them to
            if (Auth::user()->role->name === 'admin') {
                return redirect()->route('admin.dashboard'); 
            } elseif (Auth::user()->role->name === 'utilisateur_interne') {
                return redirect()->route('user.dashboard');
            }
        }

        return back();
    }
    
    public function markAllRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'All notifications marked as read.');
    }
}