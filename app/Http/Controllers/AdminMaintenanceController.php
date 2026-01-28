<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminMaintenanceController extends Controller
{
    // 1. LIST MAINTENANCES
    public function index()
    {
        // Get maintenances ordered by start date (newest first)
        $maintenances = Maintenance::with(['resource', 'creator'])
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        return view('admin.maintenances.index', compact('maintenances'));
    }

    // 2. SHOW CREATE FORM
    public function create()
    {
        // Only show resources that are not currently 'hors_service'
        $resources = Resource::where('resource_status', '!=', 'hors_service')->get();
        return view('admin.maintenances.create', compact('resources'));
    }

    // 3. STORE MAINTENANCE
    public function store(Request $request)
    {
        $request->validate([
            'resource_id' => 'required|exists:resources,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'required|string|max:500',
        ]);

        // 1. Create the Maintenance
        Maintenance::create([
            'resource_id' => $request->resource_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'created_by' => Auth::id(),
        ]);

        // 2. Logic to update Resource Status immediately
    // Use Carbon to handle the "Midnight" problem here too
    $start = Carbon::parse($request->start_date)->startOfDay();
    $end   = Carbon::parse($request->end_date)->endOfDay(); // Set to 23:59:59
    $now   = now();

    if ($start <= $now && $end >= $now) {
        $resource = Resource::find($request->resource_id);
        if($resource->resource_status !== 'hors_service') {
            $resource->update(['resource_status' => 'maintenance']);
        }
    }

        return redirect()->route('admin.maintenances.index')->with('success', 'Maintenance scheduled successfully.');
    }

    // 4. DELETE (CANCEL) MAINTENANCE
    public function destroy($id)
    {
        $maintenance = Maintenance::findOrFail($id);
        $resource = $maintenance->resource;

        // If this maintenance was currently active, set resource back to available
        if (
            $resource->resource_status === 'maintenance' &&
            now() >= $maintenance->start_date &&
            now() <= $maintenance->end_date
        ) {

            $resource->update(['resource_status' => 'disponible']);
        }

        $maintenance->delete();

        return back()->with('success', 'Maintenance record deleted.');
    }
}
