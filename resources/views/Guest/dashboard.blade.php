@extends('layouts.app')

@section('title', 'Guest Dashboard')

@section('styles')
    <link rel="stylesheet" href="{{ asset('dashboardcss/guestboard.css') }}">
@endsection

@section('content')
<div class="dashboard-wrapper">
    {{-- Header section matching catalog style --}}
    <div class="page-header">
        <h1>Welcome, <span class="highlight-blue">{{ Auth::user()->name ?? 'Guest'}}</span></h1>
        <p class="subtitle">AlphaFold Data Center Management System</p>
    </div>

    <div class="card-container">
        {{-- Card 1: Registration --}}
        <div class="catalog-style-card">
            <div class="card-accent-line"></div>
            <div class="card-content">
                <h2>Complete Your Profile</h2>
                <div class="info-box">
                    <p>Status: <span class="badge-lecture">MODE LECTURE</span></p>
                </div>
                <p class="description">To reserve high-performance resources like Server Alpha, you must first submit an official access request for review.</p>
                <div class="card-actions">
                    <a href="{{ route('guest.register.show') }}" class="btn-catalog-primary">Apply for Access</a>
                </div>
            </div>
        </div>

        {{-- Card 2: Catalog Guide --}}
        <div class="catalog-style-card">
            <div class="card-accent-line"></div>
            <div class="card-content">
                <h2>Quick Guide</h2>
                <div class="info-box">
                    <p>Access Level: <strong>Guest</strong></p>
                </div>
                <p class="description">You may browse the technical specifications of all active data center units in our resource library.</p>
                <div class="card-actions">
                    <a href="{{ route('catalog.index') }}" class="btn-catalog-outline">Browse Catalog</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection