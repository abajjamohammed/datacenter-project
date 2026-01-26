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
                    <div
                        class="status-pill {{ $resource->status === 'available' ? 'status-available' : 'status-unavailable' }}">
                        {{ ucfirst($resource->status) }}
                    </div>

                    <span class="type-label">{{ $resource->type }}</span>
                    <h2 class="resource-name">{{ $resource->name }}</h2>

                    <div class="specs-container">
                        <p>
                            <strong>Configuration:</strong><br>

                            @if (is_array($resource->specifications) || is_object($resource->specifications))
                                <ul style="list-style-type: none; padding-left: 0;">
                                    @foreach ($resource->specifications as $key => $value)
                                        <li>
                                            {{-- Met la première lettre en majuscule pour faire propre (ex: "Ram" au lieu de "ram") --}}
                                            <strong>{{ ucfirst($key) }}:</strong> {{ $value }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                {{-- Au cas où le champ est vide ou mal formaté --}}
                                <span>Aucune spécification détaillée.</span>
                            @endif
                            </div>

                        <div class="card-actions">
                            {{-- Role-based buttons --}}

                            @if (Auth::check() && Auth::user()->role->name === 'utilisateur_interne')
                                <a href="{{ route('reservations.create', $resource->id) }}"
                                    class="btn-action btn-primary">Request Reservation</a>
                            @else
                                {{-- i added Auth::check() &&(line33) to verify the connection and the mode lecture badge for the other cases (line38) --}}
                                {{-- Guest view --}}
                                <span class="badge">Mode Lecture</span>
                                <a href="{{ route('auth.register') }}" class="btn-action btn-outline">Apply for
                                    Access</a>
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
