@extends('layouts.app')

@section('title', 'Resource Catalog')

@section('content')
<div class="catalog-container">
    <div class="catalog-header">
        <h1>Resource <span>Catalog</span></h1>
        <p>Browse and request data center resources for your projects.</p>
    </div>

    {{-- The Grid Container --}}
    <div class="resource-grid">
        @forelse($resources as $resource)
            <div class="resource-card">
                {{-- Status Badge --}}
                <div class="status-pill {{ $resource->status === 'available' ? 'status-available' : 'status-unavailable' }}">
                    {{ ucfirst($resource->status) }}
                </div>
                
                <span class="type-label">{{ $resource->type }}</span>
                <h2 class="resource-name">{{ $resource->name }}</h2>

                <div class="specs-container">
                    <p class="specs-text">
                        <strong>Configuration:</strong><br>
                        {{ $resource->specs }}
                    </p>
                </div>

                <div class="card-actions">
                    {{-- Role-based buttons --}}
                    
                    @if(Auth::check() && Auth::user()->role->name === 'utilisateur_interne') 
                        <button class="btn-action btn-primary">Request Reservation</button>
                    @else {{--i added Auth::check() &&(line33) to verify the connection and the mode lecture badge for the other cases (line38) --}}
                        {{-- Guest view --}}
                        <span class="badge">Mode Lecture</span>
                        <a href="{{ route('guest.register.show') }}" class="btn-action btn-outline">Apply for Access</a>
                    @endif
                </div>
            </div>
        @empty
            <div style="padding: 40px; text-align: center; color: #666; grid-column: 1 / -1;">
                <p>No resources found matching your search.</p>
                <a href="{{ route('catalog.index') }}" style="color: #0096FF;">View all resources</a>
            </div>
        @endforelse
    </div>
</div>
@endsection