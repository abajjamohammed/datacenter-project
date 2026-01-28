<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth; // Added this import
use App\Models\ActivityLog;

class AdminUserController extends Controller
{
    // 1. LIST USERS
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::with('role')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.users.index', compact('users', 'search'));
    }

    // 2. SHOW CREATE FORM
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    // 3. STORE NEW USER
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'department' => 'nullable|string',
            'profile' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = true;

        $user = User::create($validated);
        // 🔥 LOG ACTIVITY
        ActivityLog::record(
            'Created User',
            "Admin created new user: {$user->name} ({$user->email}) with role: {$user->role->name}",
            $user
        );

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    // 4. SHOW EDIT FORM
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    // 5. UPDATE USER
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        // 🔥 CAPTURE OLD VALUES
        $oldEmail = $user->email;
        $oldRole = $user->role->name;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role_id' => 'required|exists:roles,id',
            'department' => 'nullable|string',
            'phone' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed', // Optional on update
        ]);

        // Only hash password if a new one is provided
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        // Reload to get new role name
        $user->refresh();

        // 🔥 LOG ACTIVITY WITH CHANGES
        $changes = [];
        if ($oldEmail !== $user->email) {
            $changes[] = "Email: {$oldEmail} → {$user->email}";
        }
        if ($oldRole !== $user->role->name) {
            $changes[] = "Role: {$oldRole} → {$user->role->name}";
        }
        if ($request->filled('password')) {
            $changes[] = "Password updated";
        }

        $changeDescription = !empty($changes) ? " (" . implode(', ', $changes) . ")" : "";

        ActivityLog::record(
            'Updated User',
            "Admin updated user: {$user->name}{$changeDescription}",
            $user
        );

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    // 6. DELETE USER
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Check if user is trying to delete themselves
        if ($user->id == Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // 🔥 CAPTURE DATA BEFORE DELETION
        $userName = $user->name;
        $userEmail = $user->email;
        $userRole = $user->role->name;

        // 🔥 LOG BEFORE DELETION
        ActivityLog::record(
            'Deleted User',
            "Admin deleted user: {$userName} ({$userEmail}, Role: {$userRole})",
            null
        );

        $user->delete();

        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    // 7. TOGGLE USER STATUS (Active/Inactive) - NEW ADDITION
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        // Security: Prevent Admin from deactivating themselves
        if ($user->hasRole('admin') && $user->id == Auth::id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        // 🔥 LOG ACTIVITY
        $action = $user->is_active ? 'Activated User' : 'Deactivated User';
        $status = $user->is_active ? 'Activated' : 'Deactivated';

        ActivityLog::record(
            $action,
            "Admin {$status} user: {$user->name} ({$user->email})",
            $user
        );

        $status = $user->is_active ? 'Activated' : 'Deactivated';
        return back()->with('success', "User account has been {$status}.");
    }
}
