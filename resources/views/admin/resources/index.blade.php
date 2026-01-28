@extends('layouts.app')

@section('title', 'Manage Resources')


@section('styles')
    <link rel="stylesheet" href="{{ asset('admin.css') }}">
@endsection


@section('content')
<div class="header-controls">
    <div>
        <h2 style="color: #2c3e50; margin: 0;"><i class="fas fa-server"></i> Resource Catalog</h2>
        <p style="color: #95a5a6; margin: 5px 0 0; font-size: 0.9rem;">Hardware & Virtual Inventory</p>
    </div>
    <a href="{{ route('admin.resources.create') }}" class="btn-primary-small" style="text-decoration: none; padding: 12px 25px; border-radius: 30px;">
        <i class="fas fa-plus"></i> Add Resource
    </a>
</div>

<div class="table-container">
    {{-- Search Filter --}}
    <form action="{{ route('admin.resources.index') }}" method="GET" style="margin-bottom: 25px; position: relative;">
        <i class="fas fa-search" style="position: absolute; left: 15px; top: 12px; color: #ccc;"></i>
        <input type="text" name="search" value="{{ $search }}" placeholder="Search by name, category or location..." style="padding: 10px 10px 10px 40px; border: 1px solid #eee; border-radius: 30px; width: 100%; max-width: 400px; outline: none;">
    </form>

    <table cellspacing="0">
        <thead>
            <tr>
                <th>Resource Details</th>
                <th>Category</th>
                <th>Location</th>
                <th>Status</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($resources as $resource)
            <tr>
                <td>
                    <div style="font-weight: 600; color: #2c3e50;">{{ $resource->name }}</div>
                    <span style="font-size: 0.75rem; color: #95a5a6;">
                        @if(is_array($resource->specifications))
                            {{ $resource->specifications['CPU'] ?? '' }} 
                            {{ isset($resource->specifications['RAM']) ? '• ' . $resource->specifications['RAM'] : '' }}
                        @endif
                    </span>
                </td>
                <td>
                    @php
                        $catName = $resource->category->name ?? 'Unknown';
                        $catClass = match($catName) {
                            'Serveurs' => 'cat-server',
                            'Machines Virtuelles' => 'cat-vm',
                            'Stockage' => 'cat-storage',
                            'Réseau' => 'cat-network',
                            default => 'cat-server'
                        };
                        $icon = $resource->category->icon ?? 'server';
                    @endphp
                    <span class="cat-badge {{ $catClass }}">
                        <i class="fas fa-{{ $icon }}"></i> {{ $catName }}
                    </span>
                </td>
                <td style="color: #555; font-size: 0.9rem;">
                    <i class="fas fa-map-marker-alt" style="color: #ccc;"></i> {{ $resource->location }}
                </td>
           <td>
                    {{-- 1. Check if resource is completely disabled --}}
                    @if(!$resource->is_active)
                        <span style="color: #e74c3c; font-size: 0.85rem; font-weight: bold; background: #fdedec; padding: 4px 8px; border-radius: 4px;">
                            Disabled
                        </span>
                    
                    {{-- 2. Check specific status (Maintenance, Reserved, etc.) --}}
                    @elseif($resource->resource_status === 'maintenance')
                        <span style="color: #d35400; font-size: 0.85rem; font-weight: bold; background: #fdebd0; padding: 4px 8px; border-radius: 4px;">
                            <i class="fas fa-tools"></i> Maintenance
                        </span>

                    @elseif($resource->resource_status === 'réservée')
                        <span style="color: #2980b9; font-size: 0.85rem; font-weight: bold; background: #ebf5fb; padding: 4px 8px; border-radius: 4px;">
                            Reserved
                        </span>

                    @elseif($resource->resource_status === 'hors_service')
                        <span style="color: #c0392b; font-size: 0.85rem; font-weight: bold; background: #fadbd8; padding: 4px 8px; border-radius: 4px;">
                            Broken
                        </span>

                    @else
                        <span style="color: #27ae60; font-size: 0.85rem; font-weight: bold; background: #e8f8f5; padding: 4px 8px; border-radius: 4px;">
                            Available
                        </span>
                    @endif
                </td>
                <td style="text-align: right;">
                    <a href="{{ route('admin.resources.edit', $resource->id) }}" class="btn-icon btn-edit" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    
                    {{-- Toggle Status Form --}}
                    <form action="{{ route('admin.resources.toggle', $resource->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-icon {{ $resource->is_active ? 'btn-power-on' : 'btn-power-off' }}" 
                                title="{{ $resource->is_active ? 'Turn Off / Deactivate' : 'Turn On / Activate' }}">
                            <i class="fas fa-power-off"></i>
                        </button>
                    </form>

                    <form action="{{ route('admin.resources.destroy', $resource->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this resource?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon btn-delete" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #999; padding: 40px;">
                    <i class="fas fa-box-open" style="font-size: 2rem; margin-bottom: 10px; opacity: 0.5;"></i><br>
                    No resources found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $resources->links() }}
    </div>
</div>
@endsection