@extends('layouts.app')

@section('title', 'Edit Resource')

@section('content')
<div style="max-width: 900px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #2c3e50; margin:0;">Edit Resource</h2>
        <a href="{{ route('admin.resources.index') }}" style="color: #7f8c8d; text-decoration: none;">&larr; Back to List</a>
    </div>

    <form action="{{ route('admin.resources.update', $resource->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Section 1: Basic Info --}}
        <h3 style="font-size: 1rem; color: #3498db; border-bottom: 2px solid #f1f1f1; padding-bottom: 10px; margin-bottom: 20px;">
            <i class="fas fa-info-circle"></i> Basic Information
        </h3>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Resource Name</label>
                <input type="text" name="name" value="{{ $resource->name }}" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Category</label>
                <select name="category_id" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; background: white;">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $resource->category_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Physical Location</label>
                <input type="text" name="location" value="{{ $resource->location }}" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600;">Assigned Manager</label>
                <select name="responsable_id" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; background: white;">
                    <option value="">-- Select Technical Manager --</option>
                    @foreach($managers as $manager)
                        <option value="{{ $manager->id }}" {{ $resource->responsable_id == $manager->id ? 'selected' : '' }}>
                            {{ $manager->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="margin-bottom: 30px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Description</label>
            <textarea name="description" rows="3" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; resize: vertical;">{{ $resource->description }}</textarea>
        </div>

        {{-- Section 2: Technical Specs --}}
        <h3 style="font-size: 1rem; color: #3498db; border-bottom: 2px solid #f1f1f1; padding-bottom: 10px; margin-bottom: 20px;">
            <i class="fas fa-microchip"></i> Technical Characteristics
        </h3>

        {{-- We access the specifications JSON array safely using the null coalescing operator (??) --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #555;">CPU / Cores</label>
                <input type="text" name="cpu" value="{{ $resource->specifications['CPU'] ?? '' }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #555;">RAM Memory</label>
                <input type="text" name="ram" value="{{ $resource->specifications['RAM'] ?? '' }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #555;">Storage Capacity</label>
                <input type="text" name="storage" value="{{ $resource->specifications['Storage'] ?? '' }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div>
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #555;">Operating System</label>
                <input type="text" name="os" value="{{ $resource->specifications['OS'] ?? '' }}" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
            </div>
        </div>

        <div style="margin-top: 30px; text-align: right;">
            <button type="submit" class="btn-primary-small" style="padding: 12px 30px; font-size: 1rem;">
                <i class="fas fa-save"></i> Update Resource
            </button>
        </div>
    </form>
</div>
@endsection