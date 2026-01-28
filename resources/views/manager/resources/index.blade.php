@extends('layouts.app')
@section('title', 'My Resources')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/manager.css') }}">


@endsection

@section('content')
    <div class="manager-resources">

        {{-- Header --}}
        <div class="page-header-flex" style="align-items: center; margin-bottom: 30px;">
            <div>
                <h1 style="margin: 0; color: #1e293b;">My Infrastructure</h1>
                <p style="color: #64748b; margin-top: 5px;">Manage the resources assigned to you.</p>
            </div>
            <button onclick="document.getElementById('add-modal').style.display='block'" class="btn-primary-small">
                <i class="fas fa-plus"></i> Add Resource
            </button>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        {{-- Table --}}
        <div style="background: white; border-radius: 10px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); overflow: hidden;">
            <table class="resource-table" style="width: 100%; border-collapse: collapse;">
                <thead style="background-color: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <tr>
                        <th style="text-align: left; padding: 15px; color: #475569;">Name & Description</th>
                        <th style="text-align: left; padding: 15px; color: #475569;">Category</th>
                        <th style="text-align: left; padding: 15px; color: #475569;">Location</th>
                        <th style="text-align: center; padding: 15px; color: #475569;">Status</th>
                        <th style="text-align: right; padding: 15px; color: #475569;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resources as $resource)
                        <tr style="border-bottom: 1px solid #f1f5f9;"
                            class="{{ !$resource->is_active ? 'row-inactive' : '' }}">
                            <td style="padding: 15px;">
                                <strong style="color: #0f172a; font-size: 1rem;">{{ $resource->name }}</strong>
                                <div style="color: #64748b; font-size: 0.85rem; margin-top: 4px;">
                                    {{ Str::limit($resource->description, 50) }}</div>
                            </td>
                            <td style="padding: 15px;">
                                <span
                                    style="background: #e0f2fe; color: #0284c7; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;">
                                    {{ $resource->category->name }}
                                </span>
                            </td>
                            <td style="padding: 15px; color: #334155;">
                                <i class="fas fa-map-marker-alt" style="color: #94a3b8;"></i> {{ $resource->location }}
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                @if ($resource->resource_status == 'disponible')
                                    <span
                                        style="background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: bold;">Available</span>
                                @elseif($resource->resource_status == 'maintenance')
                                    <span
                                        style="background: #fef3c7; color: #b45309; padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: bold;">Maintenance</span>
                                @else
                                    <span
                                        style="background: #fee2e2; color: #b91c1c; padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: bold;">{{ ucfirst($resource->resource_status) }}</span>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: right;">
                                <div class="flex-gap-5" style="justify-content: flex-end;">
                                    {{-- Maintenance Logic --}}
                                    @if ($resource->resource_status == 'maintenance')
                                        {{-- Button to END maintenance --}}
                                        <form action="{{ route('manager.resources.maintenance', $resource->id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            <button class="btn-primary-small btn-tiny" style="background-color: #10b981;"
                                                title="Complete Maintenance">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @else
                                        {{-- Button to START maintenance (Triggers Modal) --}}
                                        <button
                                            onclick="document.getElementById('maint-modal-{{ $resource->id }}').style.display='block'"
                                            class="btn-primary-small btn-amber btn-tiny" title="Start Maintenance">
                                            <i class="fas fa-tools"></i>
                                        </button>
                                    @endif

                                    {{-- Edit --}}
                                    <button
                                        onclick="document.getElementById('edit-modal-{{ $resource->id }}').style.display='block'"
                                        class="btn-primary-small btn-blue btn-tiny" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    {{-- Disable --}}
                                    @if ($resource->is_active)
                                        <form action="{{ route('manager.resources.destroy', $resource->id) }}"
                                            method="POST" onsubmit="return confirm('Disable this resource?');">
                                            @csrf @method('DELETE')
                                            <button class="btn-primary-small btn-red btn-tiny" title="Disable">
                                                <i class="fas fa-power-off"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <div id="maint-modal-{{ $resource->id }}" class="modal-overlay">
                            <div class="modal-box">
                                <div
                                    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                                    <h3 style="margin: 0;">Set Maintenance Schedule</h3>
                                    <button
                                        onclick="document.getElementById('maint-modal-{{ $resource->id }}').style.display='none'"
                                        style="background:none; border:none; font-size: 1.2rem; cursor: pointer;">&times;</button>
                                </div>

                                <form action="{{ route('manager.resources.maintenance', $resource->id) }}" method="POST">
                                    @csrf
                                    <div style="margin-bottom: 15px;">
                                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Maintenance End
                                            Date</label>
                                        <input type="datetime-local" name="end_date" required class="form-input-full"
                                            style="padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                                    </div>

                                    <div style="margin-bottom: 20px;">
                                        <label
                                            style="display: block; margin-bottom: 5px; font-weight: 600;">Description</label>
                                        <textarea name="description" class="form-textarea-full" rows="3" required
                                            style="padding: 10px; border: 1px solid #ddd; border-radius: 6px;">Routine maintenance.</textarea>
                                    </div>

                                    <div style="text-align: right;">
                                        <button type="button"
                                            onclick="document.getElementById('maint-modal-{{ $resource->id }}').style.display='none'"
                                            style="margin-right: 10px; padding: 8px 15px; background: #e2e8f0; border: none; border-radius: 5px; cursor: pointer;">Cancel</button>
                                        <button type="submit" class="btn-primary-small btn-amber">Confirm
                                            Maintenance</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Edit Modal -->
                        <div id="edit-modal-{{ $resource->id }}" class="modal-overlay">
                            <div class="modal-box">
                                <div
                                    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                                    <h3 style="margin: 0;">Edit Resource</h3>
                                    <button
                                        onclick="document.getElementById('edit-modal-{{ $resource->id }}').style.display='none'"
                                        style="background:none; border:none; font-size: 1.2rem; cursor: pointer;">&times;</button>
                                </div>

                                <form action="{{ route('manager.resources.update', $resource->id) }}" method="POST">
                                    @csrf @method('PUT')

                                    <div style="margin-bottom: 15px;">
                                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Name</label>
                                        <input type="text" name="name" value="{{ $resource->name }}" required
                                            class="form-input-full"
                                            style="padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                                    </div>

                                    <div style="margin-bottom: 15px;">
                                        <label
                                            style="display: block; margin-bottom: 5px; font-weight: 600;">Location</label>
                                        <input type="text" name="location" value="{{ $resource->location }}" required
                                            class="form-input-full"
                                            style="padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                                    </div>

                                    <div style="margin-bottom: 20px;">
                                        <label
                                            style="display: block; margin-bottom: 5px; font-weight: 600;">Description</label>
                                        <textarea name="description" class="form-textarea-full" rows="3"
                                            style="padding: 10px; border: 1px solid #ddd; border-radius: 6px;">{{ $resource->description }}</textarea>
                                    </div>

                                    <div style="text-align: right;">
                                        <button type="button"
                                            onclick="document.getElementById('edit-modal-{{ $resource->id }}').style.display='none'"
                                            style="margin-right: 10px; padding: 8px 15px; background: #e2e8f0; border: none; border-radius: 5px; cursor: pointer;">Cancel</button>
                                        <button type="submit" class="btn-primary-small btn-blue">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #94a3b8;">
                                <i class="fas fa-server"
                                    style="font-size: 2rem; margin-bottom: 10px; opacity: 0.5;"></i><br>
                                You don't manage any resources yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Add Modal -->
        <div id="add-modal" class="modal-overlay">
            <div class="modal-box">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="margin: 0;">Add New Resource</h3>
                    <button onclick="document.getElementById('add-modal').style.display='none'"
                        style="background:none; border:none; font-size: 1.2rem; cursor: pointer;">&times;</button>
                </div>

                <form action="{{ route('manager.resources.store') }}" method="POST">
                    @csrf
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Resource Name</label>
                        <input type="text" name="name" required class="form-input-full"
                            placeholder="e.g. Server Alpha"
                            style="padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Category</label>
                            <select name="category_id" required class="form-input-full"
                                style="padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 5px; font-weight: 600;">Location</label>
                            <input type="text" name="location" required class="form-input-full"
                                placeholder="e.g. Rack A1"
                                style="padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 5px; font-weight: 600;">Description</label>
                        <textarea name="description" class="form-textarea-full" rows="3" placeholder="Technical specs..."
                            style="padding: 10px; border: 1px solid #ddd; border-radius: 6px;"></textarea>
                    </div>

                    <div style="text-align: right;">
                        <button type="button" onclick="document.getElementById('add-modal').style.display='none'"
                            style="margin-right: 10px; padding: 8px 15px; background: #e2e8f0; border: none; border-radius: 5px; cursor: pointer;">Cancel</button>
                        <button type="submit" class="btn-primary-small">Create Resource</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
