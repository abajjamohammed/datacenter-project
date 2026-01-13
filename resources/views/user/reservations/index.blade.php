@extends('layouts.app')
{{--this is the reservation history page. created by mohammed--}}
@section('title', 'My Reservations')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
@endsection

@section('content')
    <div class="dashboard-wrapper">
        <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1>Reservation <span>History</span></h1> <br>
                <p class="subtitle">Track the status of your resource requests.</p> <br>
            </div>
            <a href="{{ route('catalog.index') }}" class="btn-primary-small" style="text-decoration: none;">+ New Reservation</a>
        </div>

        {{-- Filter Section --}}
        <form method="GET" action="{{ route('reservations.index') }}" class="filter-bar">

            {{-- Filter by Resource Name --}}
            <div class="filter-group">
                <label>Resource Name</label>
                <input type="text" name="resource" value="{{ request('resource') }}" class="filter-input"
                    placeholder="e.g. Server Alpha">
            </div>

            {{-- Filter by Status --}}
            <div class="filter-group">
                <label>Status</label>
                <select name="status" class="filter-input">
                    <option value="">All Statuses</option>
                    <option value="en_attente" {{ request('status') == 'en_attente' ? 'selected' : '' }}>Pending</option>
                    <option value="approuvée" {{ request('status') == 'approuvée' ? 'selected' : '' }}>Approved</option>
                    <option value="refusée" {{ request('status') == 'refusée' ? 'selected' : '' }}>Rejected</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="terminée" {{ request('status') == 'terminée' ? 'selected' : '' }}>Finished</option>
                </select>
            </div>

            {{-- Filter by Date --}}
            <div class="filter-group">
                <label>Start Date</label>
                <input type="date" name="date" value="{{ request('date') }}" class="filter-input">
            </div>

            {{-- Actions --}}
            <div class="filter-actions">
                <button type="submit" class="btn-primary-small">Filter</button>
                <a href="{{ route('reservations.index') }}" class="filter-reset">Reset</a>
            </div>
        </form>

        {{-- Main Card --}}
        <div class="page-card" style="padding: 0; overflow: hidden;">

            @if ($reservations->isEmpty())
                <div style="padding: 40px; text-align: center; color: #64748b;">
                    <p style="font-size: 1.2rem; margin-bottom: 15px;">You haven't made any reservations yet.</p>
                    <a href="{{ route('catalog.index') }}"
                        style="color: #0096FF; font-weight: 600; text-decoration: none;">
                        Browse the Catalog
                    </a>
                </div>
            @else
                <table class="resource-table">
                    <thead>
                        <tr>
                            <th>Resource Name</th>
                            <th>Category</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservations as $reservation)
                            <tr>
                                <td>
                                    <span class="bold-text">{{ $reservation->resource->name }}</span>
                                </td>
                                <td>
                                    {{ $reservation->resource->category->name ?? 'N/A' }}
                                </td>
                                <td>
                                    {{ $reservation->start_date->format('M d, Y H:i') }}
                                </td>
                                <td>
                                    {{ $reservation->end_date->format('M d, Y H:i') }}
                                </td>
                                <td>
                                    {{-- Logic to choose the right badge color --}}
                                    @php
                                        $statusClass = match ($reservation->reservation_status) {
                                            'approved' => 'status-available', // Green
                                            'rejected' => 'status-unavailable', // Red
                                            'finished' => 'status-badge', // Grey/Default
                                            default => 'status-maintenance', // Yellow (for Pending)
                                        };

                                        // Make the text prettier (e.g., 'en_attente' -> 'En Attente')
                                        $statusLabel = ucfirst(str_replace('_', ' ', $reservation->reservation_status));
                                    @endphp

                                    <span class="status-badge {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td>
                                    {{-- We can add a "Cancel" button here later if status is Pending --}}
                                    @if ($reservation->reservation_status === 'en_attente')
                                        <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            {{-- Javascript confirm to prevent accidental clicks --}}
                                            <button type="submit" class="btn-cancel"
                                                onclick="return confirm('Are you sure you want to cancel this request?');">
                                                Cancel
                                            </button>
                                        </form>
                                    @else
                                        <span style="color: #cbd5e1;">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
