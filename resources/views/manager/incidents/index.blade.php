@extends('layouts.app')
@section('title', 'Incident Management')

@section('content')
<h1>Reported Incidents</h1>
<table class="resource-table">
    <thead><tr><th>Status</th><th>Resource</th><th>Issue</th><th>Action</th></tr></thead>
    <tbody>
        @foreach($incidents as $inc)
        <tr>
            <td>{{ $inc->incident_status }}</td>
            <td>{{ $inc->resource->name }}</td>
            <td><strong>{{ $inc->title }}</strong>: {{ $inc->description }}</td>
            <td>
                @if($inc->incident_status != 'Resolved')
                <form action="{{ route('manager.incidents.resolve', $inc->id) }}" method="POST">
                    @csrf <button class="btn-primary-small btn-green">Mark Resolved</button>
                </form>
                @else Fixed @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection