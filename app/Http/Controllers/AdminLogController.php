<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;


class AdminLogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $logs = ActivityLog::with('user')
            ->when($search, function ($query, $search) {
                return $query->where('action', 'like', "%{$search}%")
                             ->orWhere('description', 'like', "%{$search}%")
                             ->orWhereHas('user', function($q) use ($search) {
                                 $q->where('name', 'like', "%{$search}%");
                             });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.logs.index', compact('logs', 'search'));
    }
}