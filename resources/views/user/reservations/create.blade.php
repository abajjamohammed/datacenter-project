@extends('layouts.app')
{{--created by mohammed 09/01--}}
{{--this is the reservation form--}}
@section('title', 'New Reservation')

@section('styles')
    {{-- im using the filter css bcs it has nice input styles (.filter-input) --}}
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboardcss/guestboard.css') }}">
@endsection

@section('content')

<div class="dashboard-wrapper">
    
    <div class="page-header">
        <h1>Request <span>Reservation</span></h1>
        <p class="subtitle"><strong>Resource : </strong> {{ $resource->name }}</p>
    </div>

    <div class="page-card">
        <form method="POST" action="{{ route('reservations.store') }}">
            @csrf
            
            {{-- Hidden ID: i did it just to be sent with the form so i can use it late   :mohammed 09/01 --}}
            <input type="hidden" name="resource_id" value="{{ $resource->id }}">

            {{-- Resource Info--}}
            <div class="info-box">   {{--css from guestboard.css--}}
                <p><strong>Category:</strong> {{ $resource->category->name ?? 'General' }}</p>
                <p><strong>Location:</strong> {{ $resource->location }}</p>
            </div>
            <br>

            {{-- Start Date --}}
            <div class="filter-group" style="margin-bottom: 20px;">
                <label class="booking-label" >Start Date & Time</label>
                <input type="datetime-local" name="start_date" class="filter-input" required min="{{ now()->format('Y-m-d\TH:i') }}">
                @error('start_date')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            {{-- End Date --}}
            <div class="filter-group" style="margin-bottom: 20px;">
                <label class="booking-label">End Date & Time</label>
                <input type="datetime-local" name="end_date" class="filter-input" required min="{{ now()->format('Y-m-d\TH:i') }}">
                @error('end_date')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            {{-- Justification --}}
            <div class="filter-group" style="margin-bottom: 30px;">
                <label class="booking-label" >Justification</label>
                <textarea name="justification" rows="4" class="filter-input" required placeholder="Why do you need this resource?"></textarea>
                @error('justification')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="card-actions">
                <button type="submit" class="btn-catalog-primary">Confirm Request</button>
                <a href="{{ route('catalog.index') }}" class="btn-catalog-outline">Cancel</a>
            </div>

        </form>
    </div>
</div>
@endsection