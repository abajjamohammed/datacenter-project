@extends('layouts.app')
@section('title', 'Moderation')

@section('content')
<h1 class="text-danger-header">🛡️ Moderation</h1>
@foreach($alerts as $alert)
<div class="moderation-card">
    <div>
        <h3>{{ $alert->title }}</h3>
        <p>"{{ $alert->description }}" - Reported by {{ $alert->reporter->name }}</p>
    </div>
    <div class="flex-col-gap-5">
        <form action="{{ route('manager.incidents.resolve', $alert->id) }}" method="POST">
            @csrf <button class="btn-primary-small btn-green btn-full">✓ Valid</button>
        </form>
        <form action="{{ route('manager.moderation.delete', $alert->id) }}" method="POST" onsubmit="return confirm('Delete?');">
            @csrf @method('DELETE') <button class="btn-primary-small btn-red btn-full">🗑️ Delete</button>
        </form>
    </div>
</div>
@endforeach
@endsection