@extends('layouts.app')
@section('title', 'My Resources')

@section('content')
<div class="manager-resources">
    <div class="page-header-flex">
        <h1>My Infrastructure</h1>
        <button onclick="document.getElementById('add-modal').style.display='block'" class="btn-primary-small">+ Add Resource</button>
    </div>

    @if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
    @endif

    @section('styles')
    <link rel="stylesheet" href="{{ asset('css/manager.css') }}">
    @endsection

    <table class="resource-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Location</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resources as $resource)
            <tr class="{{ !$resource->is_active ? 'row-inactive' : '' }}">
                <td><strong>{{ $resource->name }}</strong><br><small>{{ $resource->description }}</small></td>
                <td>{{ $resource->category->name }}</td>
                <td>{{ $resource->location }}</td>
                <td><span class="status-badge {{ $resource->resource_status == 'disponible' ? 'available' : 'maintenance' }}">{{ $resource->resource_status }}</span></td>
                <td>
                    <div class="flex-gap-5">
                        <form action="{{ route('manager.resources.maintenance', $resource->id) }}" method="POST">
                            @csrf <button class="btn-primary-small btn-amber btn-tiny">🔧 Maint.</button>
                        </form>
                        <button onclick="document.getElementById('edit-modal-{{$resource->id}}').style.display='block'" class="btn-primary-small btn-blue btn-tiny">✏️ Edit</button>
                        @if($resource->is_active)
                        <form action="{{ route('manager.resources.destroy', $resource->id) }}" method="POST" onsubmit="return confirm('Disable?');">
                            @csrf @method('DELETE') <button class="btn-primary-small btn-red btn-tiny">❌</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            <!-- Edit Modal inside Loop -->
            <div id="edit-modal-{{$resource->id}}" class="modal-overlay">
                <div class="modal-box">
                    <h3>Edit {{ $resource->name }}</h3>
                    <form action="{{ route('manager.resources.update', $resource->id) }}" method="POST">
                        @csrf @method('PUT')
                        <label>Name:</label><input type="text" name="name" value="{{ $resource->name }}" required class="form-input-full">
                        <label>Location:</label><input type="text" name="location" value="{{ $resource->location }}" required class="form-input-full">
                        <label>Desc:</label><textarea name="description" class="form-textarea-full">{{ $resource->description }}</textarea>
                        <button type="submit" class="btn-primary-small btn-margin-top">Save</button>
                        <button type="button" onclick="document.getElementById('edit-modal-{{$resource->id}}').style.display='none'">Cancel</button>
                    </form>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>

    <!-- Add Modal -->
    <div id="add-modal" class="modal-overlay">
        <div class="modal-box">
            <h3>Add Resource</h3>
            <form action="{{ route('manager.resources.store') }}" method="POST">
                @csrf
                <label>Name:</label><input type="text" name="name" required class="form-input-full">
                <label>Category:</label>
                <select name="category_id" required class="form-input-full">
                    @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->name }}</option> @endforeach
                </select>
                <label>Location:</label><input type="text" name="location" required class="form-input-full">
                <label>Desc:</label><textarea name="description" class="form-textarea-full"></textarea>
                <button type="submit" class="btn-primary-small btn-margin-top">Create</button>
                <button type="button" onclick="document.getElementById('add-modal').style.display='none'">Cancel</button>
            </form>
        </div>
    </div>
</div>
@endsection