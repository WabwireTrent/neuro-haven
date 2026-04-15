@extends('layouts.dashboard')

@section('title', 'Edit VR Asset')
@section('page', 'admin-vr-asset-form')

@section('content')
<header class="dashboard-header">
  <h1>Edit VR Asset</h1>
  <p class="dashboard-streak">Update this therapeutic VR experience</p>
</header>

<section class="dashboard-main">
  <div class="card">
    <form method="POST" action="{{ route('admin.vr-assets.update', $vrAsset) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div style="max-width: 800px;">
        <!-- Title -->
        <div style="margin-bottom: 1.5rem;">
          <label for="title" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Asset Title *</label>
          <input type="text" id="title" name="title" required style="width: 100%; padding: 0.75rem; border: 1px solid #e3e3e0; border-radius: 0.375rem;" 
                 value="{{ old('title', $vrAsset->title) }}">
          @error('title')
            <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
          @enderror
        </div>

        <!-- Description -->
        <div style="margin-bottom: 1.5rem;">
          <label for="description" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Description *</label>
          <textarea id="description" name="description" rows="4" required style="width: 100%; padding: 0.75rem; border: 1px solid #e3e3e0; border-radius: 0.375rem;">{{ old('description', $vrAsset->description) }}</textarea>
          @error('description')
            <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
          @enderror
        </div>

        <!-- Category -->
        <div style="margin-bottom: 1.5rem;">
          <label for="category" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Category *</label>
          <select id="category" name="category" required style="width: 100%; padding: 0.75rem; border: 1px solid #e3e3e0; border-radius: 0.375rem;">
            <option value="">Select Category</option>
            @foreach($categories as $key => $label)
              <option value="{{ $key }}" {{ old('category', $vrAsset->category) === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
          @error('category')
            <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
          @enderror
        </div>

        <!-- Duration -->
        <div style="margin-bottom: 1.5rem;">
          <label for="duration_minutes" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Duration (minutes) *</label>
          <input type="number" id="duration_minutes" name="duration_minutes" min="1" max="120" required style="width: 100%; padding: 0.75rem; border: 1px solid #e3e3e0; border-radius: 0.375rem;" 
                 value="{{ old('duration_minutes', $vrAsset->duration_minutes) }}">
          @error('duration_minutes')
            <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
          @enderror
        </div>

        <!-- File Type -->
        <div style="margin-bottom: 1.5rem;">
          <label for="file_type" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">File Type *</label>
          <select id="file_type" name="file_type" required style="width: 100%; padding: 0.75rem; border: 1px solid #e3e3e0; border-radius: 0.375rem;">
            <option value="video" {{ old('file_type', $vrAsset->file_type) === 'video' ? 'selected' : '' }}>Video</option>
            <option value="audio" {{ old('file_type', $vrAsset->file_type) === 'audio' ? 'selected' : '' }}>Audio</option>
            <option value="model" {{ old('file_type', $vrAsset->file_type) === 'model' ? 'selected' : '' }}>3D Model</option>
            <option value="interactive" {{ old('file_type', $vrAsset->file_type) === 'interactive' ? 'selected' : '' }}>Interactive</option>
          </select>
          @error('file_type')
            <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
          @enderror
        </div>

        <!-- Difficulty Level -->
        <div style="margin-bottom: 1.5rem;">
          <label for="difficulty_level" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Difficulty Level (1-5) *</label>
          <input type="number" id="difficulty_level" name="difficulty_level" min="1" max="5" required style="width: 100%; padding: 0.75rem; border: 1px solid #e3e3e0; border-radius: 0.375rem;" 
                 value="{{ old('difficulty_level', $vrAsset->difficulty_level) }}">
          @error('difficulty_level')
            <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
          @enderror
        </div>

        <!-- Therapeutic Benefits -->
        <div style="margin-bottom: 1.5rem;">
          <label for="therapeutic_benefits" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Therapeutic Benefits</label>
          <textarea id="therapeutic_benefits" name="therapeutic_benefits" rows="3" placeholder="Stress relief, anxiety reduction, mindfulness..." style="width: 100%; padding: 0.75rem; border: 1px solid #e3e3e0; border-radius: 0.375rem;">{{ old('therapeutic_benefits', $vrAsset->therapeutic_benefits) }}</textarea>
          @error('therapeutic_benefits')
            <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
          @enderror
        </div>

        <!-- Asset Image -->
        <div style="margin-bottom: 1.5rem;">
          <label for="image" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Asset Image/Thumbnail</label>
          @if($vrAsset->image_path)
            <div style="margin-bottom: 1rem;">
              <img src="{{ asset('storage/' . $vrAsset->image_path) }}" alt="{{ $vrAsset->title }}" style="max-width: 200px; border-radius: 0.375rem;">
            </div>
          @endif
          <input type="file" id="image" name="image" accept="image/*" style="width: 100%; padding: 0.75rem; border: 1px solid #e3e3e0; border-radius: 0.375rem;">
          <p style="font-size: 0.875rem; color: #706f6c; margin: 0.5rem 0;">Max 5MB. Formats: JPEG, PNG, JPG, GIF</p>
          @error('image')
            <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
          @enderror
        </div>

        <!-- VR File -->
        <div style="margin-bottom: 1.5rem;">
          <label for="file" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">VR Experience File</label>
          @if($vrAsset->file_path)
            <p style="margin-bottom: 0.5rem; font-size: 0.875rem;">Current file: {{ basename($vrAsset->file_path) }}</p>
          @endif
          <input type="file" id="file" name="file" style="width: 100%; padding: 0.75rem; border: 1px solid #e3e3e0; border-radius: 0.375rem;">
          <p style="font-size: 0.875rem; color: #706f6c; margin: 0.5rem 0;">Max 100MB. Video, audio, or 3D model files</p>
          @error('file')
            <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
          @enderror
        </div>

        <!-- Status -->
        <div style="margin-bottom: 1.5rem;">
          <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
            <input type="checkbox" name="is_active" value="1" style="width: 1rem; height: 1rem;" 
                   {{ $vrAsset->is_active ? 'checked' : '' }}>
            <span>Active (Available to users)</span>
          </label>
        </div>

        <!-- Buttons -->
        <div style="display: flex; gap: 1rem;">
          <button type="submit" class="btn btn-primary">Update Asset</button>
          <a href="{{ route('admin.vr-assets.list') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </div>
    </form>
  </div>
</section>
@endsection
