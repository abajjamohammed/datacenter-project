@extends('layouts.app')

@section('title', 'Manage Users')

@section('styles')
<style>
    .header-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .table-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    
    table { width: 100%; border-collapse: separate; border-spacing: 0 5px; } /* Adds spacing between rows */
    th { padding: 15px; text-align: left; color: #7f8c8d; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px; border-bottom: 2px solid #eee; }
    td { padding: 15px; background: white; border-top: 1px solid #f1f1f1; border-bottom: 1px solid #f1f1f1; }
    
    /* Role Badges */
    .role-badge { padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; display: inline-block; }
    .role-admin { background: #fadbd8; color: #c0392b; }
    .role-manager { background: #fdebd0; color: #d35400; }
    .role-user { background: #d6eaf8; color: #2980b9; }
    .role-guest { background: #f2f3f4; color: #7f8c8d; }

    /* Status Indicators */
    .status-dot { height: 10px; width: 10px; border-radius: 50%; display: inline-block; margin-right: 6px; }
    .active-dot { background-color: #2ecc71; box-shadow: 0 0 5px #2ecc71; }
    .inactive-dot { background-color: #e74c3c; }

    /* Action Buttons */
    .btn-icon { width: 32px; height: 32px; border-radius: 6px; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; transition: 0.2s; margin-right: 5px; }
    .btn-edit { background: #ebf5fb; color: #3498db; }
    .btn-edit:hover { background: #3498db; color: white; }
    
    .btn-toggle-on { background: #e8f8f5; color: #27ae60; } /* Green background for active items */
    .btn-toggle-on:hover { background: #fdedec; color: #e74c3c; } /* Turns red on hover to indicate deactivation */
    
    .btn-toggle-off { background: #fdedec; color: #e74c3c; } /* Red background for inactive items */
    .btn-toggle-off:hover { background: #e8f8f5; color: #27ae60; } /* Turns green on hover to indicate activation */

    .btn-delete { background: white; color: #e74c3c; border: 1px solid #fdedec; }
    .btn-delete:hover { background: #e74c3c; color: white; }

</style>
@endsection

@section('content')
<div class="header-controls">
    <div>
        <h2 style="color: #2c3e50; margin: 0;"><i class="fas fa-users-cog"></i> User Management</h2>
        <p style="color: #95a5a6; margin: 5px 0 0; font-size: 0.9rem;">Manage system access and roles</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn-primary-small" style="text-decoration: none; padding: 12px 25px; border-radius: 30px;">
        <i class="fas fa-plus"></i> New User
    </a>
</div>

<div class="table-container">
    {{-- Search Filter --}}
    <form action="{{ route('admin.users.index') }}" method="GET" style="margin-bottom: 25px; position: relative;">
        <i class="fas fa-search" style="position: absolute; left: 15px; top: 12px; color: #ccc;"></i>
        <input type="text" name="search" value="{{ $search }}" placeholder="Search by name, email or role..." style="padding: 10px 10px 10px 40px; border: 1px solid #eee; border-radius: 30px; width: 100%; max-width: 400px; outline: none;">
    </form>

    <table cellspacing="0">
        <thead>
            <tr>
                <th>User Identity</th>
                <th>Role</th>
                <th>Department</th>
                <th>Status</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>
                    <div style="display: flex; align-items: center;">
                        <div style="width: 40px; height: 40px; background: #ecf0f1; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 15px; font-weight: bold; color: #7f8c8d;">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <div style="font-weight: 600; color: #2c3e50;">{{ $user->name }}</div>
                            <div style="font-size: 0.85rem; color: #95a5a6;">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="role-badge 
                        {{ $user->role->name == 'admin' ? 'role-admin' : '' }}
                        {{ $user->role->name == 'responsable_technique' ? 'role-manager' : '' }}
                        {{ $user->role->name == 'utilisateur_interne' ? 'role-user' : '' }}
                        {{ $user->role->name == 'invite' ? 'role-guest' : '' }}
                    ">
                        {{ ucfirst(str_replace('_', ' ', $user->role->name)) }}
                    </span>
                </td>
                <td style="color: #555;">{{ $user->department ?? '--' }}</td>
                <td>
                    @if($user->is_active)
                        <span style="color: #27ae60; font-size: 0.9rem; font-weight: 600;">
                            <span class="status-dot active-dot"></span> Active
                        </span>
                    @else
                        <span style="color: #e74c3c; font-size: 0.9rem; font-weight: 600;">
                            <span class="status-dot inactive-dot"></span> Inactive
                        </span>
                    @endif
                </td>
                <td style="text-align: right;">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-icon btn-edit" title="Edit Details">
                        <i class="fas fa-pen"></i>
                    </a>
                    
                    @if(auth()->id() !== $user->id)
                        {{-- Toggle Status Button (Uses new AdminUserController method) --}}
                        <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-icon {{ $user->is_active ? 'btn-toggle-on' : 'btn-toggle-off' }}" 
                                    title="{{ $user->is_active ? 'Click to Deactivate' : 'Click to Activate' }}">
                                <i class="fas {{ $user->is_active ? 'fa-unlock' : 'fa-lock' }}"></i>
                            </button>
                        </form>

                        {{-- Delete Button --}}
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Permanently delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon btn-delete" title="Delete User">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $users->links() }} 
    </div>
</div>
@endsection