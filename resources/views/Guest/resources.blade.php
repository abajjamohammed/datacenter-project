@extends('layouts.app')

@section('title', 'Resource Catalog')

{{-- Push the guest CSS to the layout --}}
@push('extra-css')
    <link rel="stylesheet" href="{{ asset('css/guest.css') }}">
@endpush

@section('content')
<div class="catalog-container">
    <div class="catalog-header">
        <h2>Available Data Center Resources</h2>
        <p>You are viewing the catalog in <strong>Read-Only Mode</strong>. To request access, please contact an administrator.</p>
    </div>

    <table class="resource-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Resource Name</th>
                <th>Category</th>
                <th>Status</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody>
            @forelse($resources as $resource)
                <tr>
                    <td>#{{ $resource->id }}</td>
                    <td class="bold-text">{{ $resource->name }}</td>
                    <td>{{ $resource->category }}</td>
                    <td>
                        <span class="status-badge {{ strtolower($resource->status) }}">
                            {{ $resource->status }}
                        </span>
                    </td>
                    <td>{{ $resource->location }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty-state">No resources currently listed.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection