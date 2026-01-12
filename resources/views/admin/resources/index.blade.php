@extends('layouts.app')

@section('title', 'Manage Resources')

@section('styles')
<style>
    .header-controls { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .table-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    
    table { width: 100%; border-collapse: separate; border-spacing: 0 5px; }
    th { padding: 15px; text-align: left; color: #7f8c8d; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.5px; border-bottom: 2px solid #eee; }
    td { padding: 15px; background: white; border-top: 1px solid #f1f1f1; border-bottom: 1px solid #f1f1f1; }
    
    /* Category Badges */
    .cat-badge { padding: 5px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; }
    .cat-server { background: #e8f6f3; color: #1abc9c; }
    .cat-vm { background: #eaf2f8; color: #3498db; }
    .cat-storage { background: #fef9e7; color: #f1c40f; }
    .cat-network { background: #fdedec; color: #e74c3c; }

    /* Action Buttons */
    .btn-icon { width: 32px; height: 32px; border-radius: 6px; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; transition: 0.2s; margin-right: 5px; }
    .btn-edit { background: #ebf5fb; color: #3498db; }
    .btn-edit:hover { background: #3498db; color: white; }
    
    /* Toggle Switch Logic */
    .btn-power-on { background: #e8f8f5; color: #27ae60; }
    .btn-power-on:hover { background: #fdedec; color: #e74c3c; } /* Hover turns red to show 'turn off' */
    
    .btn-power-off { background: #fdedec; color: #e74c3c; }
    .btn-power-off:hover { background: #e8f8f5; color: #27ae60; } /* Hover turns green to show 'turn on' */

    .btn-delete { background: white; color: #bdc3c7; }
    .btn-delete:hover { color: #e74c3c; }
</style>
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
                    @if($resource->is_active)
                        <span style="color: #27ae60; font-size: 0.85rem; font-weight: bold; background: #e8f8f5; padding: 4px 8px; border-radius: 4px;">
                            Active
                        </span>
                    @else
                        <span style="color: #e74c3c; font-size: 0.85rem; font-weight: bold; background: #fdedec; padding: 4px 8px; border-radius: 4px;">
                            Disabled
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