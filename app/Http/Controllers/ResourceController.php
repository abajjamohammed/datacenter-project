<?php
namespace App\Http\Controllers;

use App\Models\Resource; // Ensure you have a Resource model
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    /*Display the resource catalog with search functionality. */
    public function index(Request $request)
    {
        // Get the search query from the header search bar
        $search = $request->input('search');

        // Query resources based on name or technical specs
        $resources = Resource::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                       //  ->orWhere('type', 'like', "%{$search}%") we dont have type in db :mohammed 13/01
                         ->orWhere('location', 'like', "%{$search}%")
                         ->orWhere('description', 'like', "%{$search}%");
        })  ->where('is_active', true) // Only show active resources
            ->with('category') // Eager load category to prevent N+1 queries
            ->get();

        // Return the view (we will create this next)
        return view('catalog', compact('resources', 'search'));
    }
}

?>