@extends('layouts.app')
@section('title', 'Manage Reservations')

@section('content')
<div class="reservation-manager">
    <h1>Reservation Requests</h1>
    <table class="resource-table">
        <thead>
            <tr><th>Status</th><th>User</th><th>Resource</th><th>Justification</th><th>Actions</th></tr>
        </thead>
        <tbody>
            @foreach($reservations as $res)
            <tr>
                <td><span class="status-badge {{ $res->reservation_status == 'En attente' ? 'maintenance' : 'available' }}">{{ $res->reservation_status }}</span></td>
                <td>{{ $res->user->name }}</td>
                <td>{{ $res->resource->name }}</td>
                <td>{{ $res->justification }}</td>
                <td>
                    @if($res->reservation_status == 'En attente')
                    <div class="flex-gap-5">
                        <form action="{{ route('manager.reservations.approve', $res->id) }}" method="POST">@csrf <button class="btn-primary-small btn-green">Approve</button></form>
                        <button onclick="document.getElementById('reject-{{$res->id}}').style.display='table-row'" class="btn-primary-small btn-red">Reject</button>
                    </div>
                    @else Processed @endif
                </td>
            </tr>
            <tr id="reject-{{$res->id}}" class="row-reject" style="display:none;">
                <td colspan="5">
                    <form action="{{ route('manager.reservations.reject', $res->id) }}" method="POST" class="form-reject">
                        @csrf <input type="text" name="rejection_reason" required placeholder="Reason..." class="input-flex-grow">
                        <button type="submit" class="btn-primary-small btn-red">Confirm</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection