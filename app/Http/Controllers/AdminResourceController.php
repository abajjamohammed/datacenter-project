<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\ResourceCategory;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class AdminResourceController extends Controller
{
    // 1. LIST RESOURCES
    public function index(Request $request)
    {
        $search = $request->input('search');

        $resources = Resource::with(['category', 'responsable'])
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.resources.index', compact('resources', 'search'));
    }

    // 2. SHOW CREATE FORM
    public function create()
    {
        $categories = ResourceCategory::all();
        // Get users who are 'responsable_technique' to assign as managers
        $managers = User::whereHas('role', function ($q) {
            $q->where('name', 'responsable_technique');
        })->get();

        return view('admin.resources.create', compact('categories', 'managers'));
    }

    // 3. STORE NEW RESOURCE
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:resource_categories,id',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'responsable_id' => 'nullable|exists:users,id',
            // Technical Specs Validation
            'cpu' => 'nullable|string',
            'ram' => 'nullable|string',
            'storage' => 'nullable|string',
            'os' => 'nullable|string',
        ]);

        // Merge individual inputs into the JSON 'specifications' array
        $specs = [
            'CPU' => $request->cpu,
            'RAM' => $request->ram,
            'Storage' => $request->storage,
            'OS' => $request->os,
            'Other' => $request->other_specs
        ];

        $resource = Resource::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'location' => $request->location,
            'description' => $request->description,
            'responsable_id' => $request->responsable_id,
            'specifications' => $specs, // Eloquent casts this to JSON automatically
            'resource_status' => 'disponible',
            'is_active' => true,
        ]);
        ActivityLog::record(
            'Created Resource',
            "Created new resource: {$resource->name} ({$resource->category->name})",
            $resource
        );

        return redirect()->route('admin.resources.index')->with('success', 'Resource created successfully.');
    }
    // 4. SHOW EDIT FORM
    public function edit($id)
    {
        $resource = Resource::findOrFail($id);
        $categories = ResourceCategory::all();
        $managers = User::whereHas('role', function ($q) {
            $q->where('name', 'responsable_technique');
        })->get();

        return view('admin.resources.edit', compact('resource', 'categories', 'managers'));
    }

    // 5. UPDATE RESOURCE
    public function update(Request $request, $id)
    {
        $resource = Resource::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:resource_categories,id',
            'location' => 'required|string',
            'description' => 'required|string',
            'responsable_id' => 'nullable|exists:users,id',
        ]);

        // Update specs array
        $specs = [
            'CPU' => $request->cpu,
            'RAM' => $request->ram,
            'Storage' => $request->storage,
            'OS' => $request->os,
            'Other' => $request->other_specs
        ];

        $resource->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'location' => $request->location,
            'description' => $request->description,
            'responsable_id' => $request->responsable_id,
            'specifications' => $specs,
        ]);

        $oldName = $resource->name;

        ActivityLog::record(
            'Updated Resource',
            "Updated resource: {$oldName} → {$resource->name}",
            $resource
        );

        return redirect()->route('admin.resources.index')->with('success', 'Resource updated successfully.');
    }

    // 6. TOGGLE STATUS
    public function toggleStatus($id)
    {
        $resource = Resource::findOrFail($id);
        $resource->is_active = !$resource->is_active;
        // If deactivated, force status to 'hors_service'
        $resource->resource_status = $resource->is_active ? 'disponible' : 'hors_service';
        $resource->save();
        $action = $resource->is_active ? 'Activated Resource' : 'Deactivated Resource';
        $status = $resource->is_active ? 'Activated' : 'Deactivated';

        ActivityLog::record(
            $action,
            "{$status} resource: {$resource->name}",
            $resource
        );

        return back()->with('success', 'Resource status updated.');
    }

    // 7. DELETE RESOURCE
    public function destroy($id)
    {
        $resource = Resource::findOrFail($id);
        $resource->delete();

        // 🔥 CAPTURE NAME BEFORE DELETION:
        $resourceName = $resource->name;

        // 🔥 LOG BEFORE DELETING:
        ActivityLog::record(
            'Deleted Resource',
            "Deleted resource: {$resourceName}",
            null // Don't pass model since it will be deleted
        );

        $resource->delete();

        return back()->with('success', 'Resource deleted successfully.');
    }
}
