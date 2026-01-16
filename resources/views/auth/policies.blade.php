@extends('layouts.app')
@section('title', 'Usage Policies')
@section('content')
<link rel="stylesheet" href="{{ asset('css/policies.css') }}">

<div class="dashboard-content">
    <div class="content-header">
        <div class="header-text">
            <h1>Usage Policies</h1>
            <p>Terms and conditions for AlphaFold Data Center Resource Management</p>
        </div>
        <span class="status-badge">MODE LECTURE</span>
    </div>

    <div class="policy-grid">
        <div class="policy-card">
            <div class="icon-box">🔐</div>
            <h3>Account Integrity</h3>
            <p>Access is strictly personal. Sharing credentials or bypassing role-based permissions is a violation of system security protocols.</p>
        </div>

        <div class="policy-card">
            <div class="icon-box">📊</div>
            <h3>Justified Allocation</h3>
            <p>All internal reservations must include a valid scientific justification. Resources are allocated based on availability and priority.</p>
        </div>

        <div class="policy-card">
            <div class="icon-box">👁️</div>
            <h3>Traceability & Logging</h3>
            <p>Every system action, including reservation requests and status changes, is logged in our database for transparent auditing.</p>
        </div>

        <div class="policy-card">
            <div class="icon-box">⚡</div>
            <h3>Resource Stewardship</h3>
            <p>Users must release high-performance resources (CPU, RAM, GPU) immediately upon project completion to maintain system efficiency.</p>
        </div>

        <div class="policy-card">
            <div class="icon-box">⚠️</div>
            <h3>Incident Reporting</h3>
            <p>Any technical hardware malfunction or software conflict must be reported immediately via the official support interface.</p>
        </div>

        <div class="policy-card">
            <div class="icon-box">🛠️</div>
            <h3>Maintenance Windows</h3>
            <p>Users must comply with scheduled maintenance periods. Resources marked as 'Indisponible' are strictly off-limits during these times.</p>
        </div>

        <div class="policy-card">
            <div class="icon-box">📜</div>
            <h3>Scientific Attribution</h3>
            <p>Any research or publications resulting from the use of this Data Center must cite the "AlphaFold DC Management System."</p>
        </div>
    </div>
</div>
@endsection