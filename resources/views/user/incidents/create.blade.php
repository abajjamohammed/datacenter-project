@extends('layouts.app')
{{-- This is the report technical issue page. Created by mohammed 12/01 --}}
@section('title', 'Report Technical Issue')

@section('styles')
    {{-- Reusing your existing filter CSS for the inputs --}}
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboardcss/guestboard.css') }}">
@endsection

@section('content')
<div class="dashboard-wrapper">
    
    <div class="page-header">
        <h1>Report <span>Technical Issue</span></h1>
        <p class="subtitle">Please fill out the form below to report a technical issue.</p>
    </div>

    <div class="page-card">
        <form method="POST" action="{{ route('incidents.store') }}">
            @csrf

            {{--TITLE--}}
            <div class="filter-group" style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom:8px; font-weight:600; color:#64748b;">Incident Title</label>
                <input type="text" name="title" class="filter-input" required placeholder="Brief summary of the issue ( Just to use it as a Title)">
                @error('title')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            {{-- 2. Resource Selection --}}
            <div class="filter-group" style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom:8px; font-weight:600; color:#64748b;">Affected Resource</label>
                <select name="resource_id" class="filter-input" required>
                    <option value="" disabled selected>Select a resource</option>
                    @foreach($resources as $resource)
                        <option value="{{ $resource->id }}">{{ $resource->name }}</option>
                    @endforeach
                </select>
                @error('resource_id')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            {{-- 3. Priority Selection --}}
            <div class="filter-group" style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom:8px; font-weight:600; color:#64748b;">Priority</label>
                <select name="priority" class="filter-input" required>
                    <option value="" disabled selected>Select priority</option>
                    <option value="basse">Low</option>
                    <option value="moyenne">Medium</option>
                    <option value="haute">High</option>
                </select>
                @error('priority')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            {{-- 4. Issue Description --}}
            <div class="filter-group" style="margin-bottom: 30px;">
                <label style="display:block; margin-bottom:8px; font-weight:600; color:#64748b;">Issue Description</label>
                <textarea name="description" rows="4" class="filter-input" required placeholder="Describe the technical issue you are experiencing..."></textarea>
                @error('description')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="card-actions">
                <button type="submit" class="btn-catalog-primary">Submit Report</button>
                <a href="{{ route('user.dashboard') }}" class="btn-catalog-outline">Cancel</a>
            </div>

        </form>
    </div>
</div>
@endsection