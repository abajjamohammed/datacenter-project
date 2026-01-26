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
                    {{-- LOGIC: Normalize the status to handle 'En attente', 'EN_ATTENTE', etc. --}}
                    @php
                        $status = strtoupper(trim($res->reservation_status));
                        $isFinished =
                            $status === 'APPROUVÉE' ||
                            $status === 'REFUSÉE' ||
                            $status === 'APPROVED' ||
                            $status === 'REJECTED';
                    @endphp

                    <tr>
                        <td>
                            <span
                                class="status-badge {{ !$isFinished ? 'maintenance' : ($status === 'REFUSÉE' ? 'occupied' : 'available') }}">
                                {{ $res->reservation_status }}
                            </span>
                        </td>
                        <td>{{ $res->user->name }}</td>
                        <td>{{ $res->resource->name }}</td>
                        <td>{{ $res->justification }}</td>
                        <td>
                            {{-- IF NOT FINISHED, SHOW BUTTONS --}}
                            @if (!$isFinished)
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
                                <span style="color: gray;">Processed</span>
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
