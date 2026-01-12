@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div style="max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <h2 style="margin-bottom: 20px; color: #2c3e50;">Edit User: {{ $user->name }}</h2>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Row 1 --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Full Name</label>
                <input type="text" name="name" value="{{ $user->name }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Email Address</label>
                <input type="email" name="email" value="{{ $user->email }}" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
        </div>

        {{-- Row 2 --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">System Role</label>
                <select name="role_id" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: white;">
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Department</label>
                <input type="text" name="department" value="{{ $user->department }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
        </div>

        {{-- Row 3 --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">New Password (Optional)</label>
                <input type="password" name="password" placeholder="Leave blank to keep current" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">Confirm New Password</label>
                <input type="password" name="password_confirmation" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
        </div>

        <div style="margin-top: 20px; text-align: right;">
            <a href="{{ route('admin.users.index') }}" style="color: #666; text-decoration: none; margin-right: 15px;">Cancel</a>
            <button type="submit" class="btn-primary-small" style="padding: 12px 25px; border: none; cursor: pointer;">Update User</button>
        </div>
    </form>
</div>
@endsection