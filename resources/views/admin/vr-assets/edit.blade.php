@extends('layouts.dashboard')

@section('title', 'Edit VR Asset')
@section('page', 'admin-vr-asset-form')

@section('content')
<div class="page-header">
  <h1>Edit VR Asset</h1>
  <p>Update this therapeutic VR experience</p>
</div>

<div class="card">
  <div class="card-body">
    <form method="POST" action="{{ route('admin.vr-assets.update', $vrAsset) }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="form-group">
        <label for="title" class="form-label">Asset Title *</label>
        <input type="text" id="title" name="title" class="form-input" required value="{{ old('title', $vrAsset->title) }}">
        @error('title') <span class="form-error">{{ $message }}</span> @enderror
      </div>

      <div class="form-group">
        <label for="description" class="form-label">Description *</label>
        <textarea id="description" name="description" class="form-input" rows="4" required>{{ old('description', $vrAsset->description) }}</textarea>
        @error('description') <span class="form-error">{{ $message }}</span> @enderror
      </div>

      <div class="form-group">
        <label for="category" class="form-label">Category *</label>
        <select id="category" name="category" class="form-input" required>
          <option value="">Select Category</option>
          @foreach($categories as $key => $label)
            <option value="{{ $key }}" {{ old('category', $vrAsset->category) === $key ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
        @error('category') <span class="form-error">{{ $message }}</span> @enderror
      </div>

      <div class="form-group">
        <label for="duration_minutes" class="form-label">Duration (minutes) *</label>
        <input type="number" id="duration_minutes" name="duration_minutes" class="form-input" min="1" max="120" required value="{{ old('duration_minutes', $vrAsset->duration_minutes) }}">
        @error('duration_minutes') <span class="form-error">{{ $message }}</span> @enderror
      </div>

      <div class="form-group">
        <label for="file_type" class="form-label">File Type *</label>
        <select id="file_type" name="file_type" class="form-input" required>
          <option value="video" {{ old('file_type', $vrAsset->file_type) === 'video' ? 'selected' : '' }}>Video</option>
          <option value="audio" {{ old('file_type', $vrAsset->file_type) === 'audio' ? 'selected' : '' }}>Audio</option>
          <option value="model" {{ old('file_type', $vrAsset->file_type) === 'model' ? 'selected' : '' }}>3D Model</option>
          <option value="interactive" {{ old('file_type', $vrAsset->file_type) === 'interactive' ? 'selected' : '' }}>Interactive</option>
        </select>
        @error('file_type') <span class="form-error">{{ $message }}</span> @enderror
      </div>

      <div class="form-group">
        <label for="difficulty_level" class="form-label">Difficulty Level (1-5) *</label>
        <input type="number" id="difficulty_level" name="difficulty_level" class="form-input" min="1" max="5" required value="{{ old('difficulty_level', $vrAsset->difficulty_level) }}">
        @error('difficulty_level') <span class="form-error">{{ $message }}</span> @enderror
      </div>

      <div class="form-group">
        <label for="therapeutic_benefits" class="form-label">Therapeutic Benefits</label>
        <textarea id="therapeutic_benefits" name="therapeutic_benefits" class="form-input" rows="3" placeholder="Stress relief, anxiety reduction, mindfulness...">{{ old('therapeutic_benefits', $vrAsset->therapeutic_benefits) }}</textarea>
        @error('therapeutic_benefits') <span class="form-error">{{ $message }}</span> @enderror
      </div>

      <div class="form-group">
        <label for="image" class="form-label">Asset Image/Thumbnail</label>
        @if($vrAsset->image_path)
          <div class="file-preview">
            <img src="{{ asset('storage/' . $vrAsset->image_path) }}" alt="{{ $vrAsset->title }}">
          </div>
        @endif
        <input type="file" id="image" name="image" class="form-input" accept="image/*">
        <p class="form-help">Max 5MB. Formats: JPEG, PNG, JPG, GIF</p>
        @error('image') <span class="form-error">{{ $message }}</span> @enderror
      </div>

      <div class="form-group">
        <label for="file" class="form-label">VR Experience File</label>
        @if($vrAsset->file_path)
          <p class="form-help">Current file: {{ basename($vrAsset->file_path) }}</p>
        @endif
        <input type="file" id="file" name="file" class="form-input">
        <p class="form-help">Max 100MB. Video, audio, or 3D model files</p>
        @error('file') <span class="form-error">{{ $message }}</span> @enderror
      </div>

      <div class="form-group">
        <label class="checkbox-label">
          <input type="checkbox" name="is_active" value="1" {{ $vrAsset->is_active ? 'checked' : '' }}>
          <span>Active (Available to users)</span>
        </label>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Update Asset</button>
        <a href="{{ route('admin.vr-assets.list') }}" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
