@extends('layouts.app')
@section('title', 'Manage Reservations')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/manager.css') }}">
    <link rel="stylesheet" href="{{ asset('css/guest.css') }}">
@endsection

@section('content')
    <div class="reservation-manager">
        <div style="margin-bottom: 20px;">
            <h1>Reservation Requests</h1>
        </div>

        <table class="resource-table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>User</th>
                    <th>Resource</th>
                    <th>Justification</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
  @foreach ($reservations as $res)
                    {{-- LOGIC: Check if it is PENDING --}}
                    @php
                        // Convert to uppercase and remove spaces
                        $rawStatus = strtoupper(trim($res->reservation_status));
                        
                        // We check if it is explicitly waiting. Everything else is considered processed.
                        $isPending = $rawStatus === 'EN_ATTENTE' || $rawStatus === 'PENDING';
                    @endphp

                    <tr>
                        <td>
                            {{-- Badge Logic: Yellow if pending, Green if Approved, Red if Rejected --}}
                            <span class="status-badge 
                                {{ $isPending ? 'maintenance' : 
                                   ($rawStatus === 'REFUSÉE' || $rawStatus === 'REJECTED' ? 'occupied' : 'available') }}">
                                {{ $res->reservation_status }}
                            </span>
                        </td>
                        <td>{{ $res->user->name }}</td>
                        <td>{{ $res->resource->name }}</td>
                        <td>{{ $res->justification }}</td>
                        <td>
                            {{-- LOGIC: Only show buttons if it is PENDING --}}
                            @if ($isPending)
                                <div style="display: flex; gap: 5px;">
                                    {{-- Approve Button --}}
                                    <button
                                        onclick="document.getElementById('approve-row-{{ $res->id }}').style.display='table-row'; document.getElementById('reject-row-{{ $res->id }}').style.display='none'"
                                        class="btn-primary-small" style="background-color: #10b981; color: white;">
                                        Accept
                                    </button>
                                    {{-- Reject Button --}}
                                    <button
                                        onclick="document.getElementById('reject-row-{{ $res->id }}').style.display='table-row'; document.getElementById('approve-row-{{ $res->id }}').style.display='none'"
                                        class="btn-primary-small" style="background-color: #ef4444; color: white;">
                                        Reject
                                    </button>
                                </div>
                            @else
                                {{-- If not pending (Approved/Rejected/Terminated), show text --}}
                                <span style="color: gray; font-weight: bold; font-size: 0.9em;">
                                    <i class="fas fa-check-circle"></i> Processed
                                </span>
                            @endif
                        </td>
                    </tr>

                    {{-- Hidden Approve Row --}}
                    <tr id="approve-row-{{ $res->id }}" style="display: none; background-color: #ecfdf5;">
                        <td colspan="5" style="padding: 10px;">
                            <form action="{{ route('manager.reservations.approve', $res->id) }}" method="POST"
                                style="display: flex; gap: 10px;">
                                @csrf
                                <strong style="color: #047857; align-self:center;">Note:</strong>
                                <input type="text" name="approval_comment" required placeholder="Approval note..."
                                    style="flex: 1; padding: 5px;">
                                <button type="submit" class="btn-primary-small"
                                    style="background-color: #10b981;">Confirm</button>
                                <button type="button"
                                    onclick="document.getElementById('approve-row-{{ $res->id }}').style.display='none'"
                                    style="background:none; border:none; cursor:pointer;">Cancel</button>
                            </form>
                        </td>
                    </tr>

                    {{-- Hidden Reject Row --}}
                    <tr id="reject-row-{{ $res->id }}" style="display: none; background-color: #fef2f2;">
                        <td colspan="5" style="padding: 10px;">
                            <form action="{{ route('manager.reservations.reject', $res->id) }}" method="POST"
                                style="display: flex; gap: 10px;">
                                @csrf
                                <strong style="color: #991b1b; align-self:center;">Reason:</strong>
                                <input type="text" name="rejection_reason" required placeholder="Rejection reason..."
                                    style="flex: 1; padding: 5px;">
                                <button type="submit" class="btn-primary-small"
                                    style="background-color: #ef4444;">Confirm</button>
                                <button type="button"
                                    onclick="document.getElementById('reject-row-{{ $res->id }}').style.display='none'"
                                    style="background:none; border:none; cursor:pointer;">Cancel</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
