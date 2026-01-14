<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\ResourceCategory;
use App\Models\User;
use App\Models\ActivityLog; // <--- IMPERATIVE: Import the ActivityLog model
use Illuminate\Http\Request;

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
        $managers = User::whereHas('role', function($q) {
            $q->where('name', 'responsable_technique');
        })->get();

        return view('admin.resources.create', compact('categories', 'managers'));
    }

    // 3. STORE NEW RESOURCE (With Logging)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:resource_categories,id',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'responsable_id' => 'nullable|exists:users,id',
            'cpu' => 'nullable|string',
            'ram' => 'nullable|string',
            'storage' => 'nullable|string',
            'os' => 'nullable|string',
        ]);

        $specs = [
            'CPU' => $request->cpu,
            'RAM' => $request->ram,
            'Storage' => $request->storage,
            'OS' => $request->os,
            'Other' => $request->other_specs
        ];

        // Create the resource and assign it to a variable
        $resource = Resource::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'location' => $request->location,
            'description' => $request->description,
            'responsable_id' => $request->responsable_id,
            'specifications' => $specs,
            'resource_status' => 'disponible',
            'is_active' => true,
        ]);

        // LOG THE ACTION
        ActivityLog::record('Created Resource', "Added new resource: {$resource->name}", $resource);

        return redirect()->route('admin.resources.index')->with('success', 'Resource created successfully.');
    }

    // 4. SHOW EDIT FORM
    public function edit($id)
    {
        $resource = Resource::findOrFail($id);
        $categories = ResourceCategory::all();
        $managers = User::whereHas('role', function($q) {
            $q->where('name', 'responsable_technique');
        })->get();

        return view('admin.resources.edit', compact('resource', 'categories', 'managers'));
    }

    // 5. UPDATE RESOURCE (With Logging)
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

        // LOG THE ACTION
        ActivityLog::record('Updated Resource', "Updated details for resource: {$resource->name}", $resource);

        return redirect()->route('admin.resources.index')->with('success', 'Resource updated successfully.');
    }

    // 6. TOGGLE STATUS (With Logging)
    public function toggleStatus($id)
    {
        $resource = Resource::findOrFail($id);
        $resource->is_active = !$resource->is_active;
        $resource->resource_status = $resource->is_active ? 'disponible' : 'hors_service';
        $resource->save();

        // LOG THE ACTION
        $status = $resource->is_active ? 'Activated' : 'Deactivated';
        ActivityLog::record('Resource Status', "Resource {$resource->name} was {$status}", $resource);

        return back()->with('success', 'Resource status updated.');
    }

    // 7. DELETE RESOURCE (With Logging)
    public function destroy($id)
    {
        $resource = Resource::findOrFail($id);
        $name = $resource->name; // Capture name before deletion
        
        $resource->delete();

        // LOG THE ACTION
        ActivityLog::record('Deleted Resource', "Permanently removed resource: {$name}");

        return back()->with('success', 'Resource deleted successfully.');
    }
}