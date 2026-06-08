@extends('layouts.app')

@section('title', 'Create Treatment Plan')
@section('page', 'treatment-plans-create')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>Create Treatment Plan</h1>
    <p>Design a structured therapy plan for your patient</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('therapist.treatment-plans.index') }}" class="btn btn-ghost btn-sm">Back</a>
  </div>
</div>

<div class="card" style="max-width: 720px;">
  <div class="card-body">
    <form method="POST" action="{{ route('therapist.treatment-plans.store') }}" style="display: grid; gap: 1.25rem;">
      @csrf
      <div class="form-group">
        <label class="form-label" for="tp-patient">Patient</label>
        <select class="form-input" id="tp-patient" name="patient_id" required>
          <option value="">Select a patient</option>
          @foreach($patients as $p)
            <option value="{{ $p->id }}" {{ old('patient_id') == $p->id ? 'selected' : '' }}>{{ $p->name }} ({{ $p->email }})</option>
          @endforeach
        </select>
        @error('patient_id')<p class="field-error">{{ $message }}</p>@enderror
      </div>
      <div class="form-group">
        <label class="form-label" for="tp-title">Plan Title</label>
        <input class="form-input" id="tp-title" name="title" type="text" value="{{ old('title') }}" placeholder="e.g. Anxiety Management Plan" required>
        @error('title')<p class="field-error">{{ $message }}</p>@enderror
      </div>
      <div class="form-group">
        <label class="form-label" for="tp-description">Description</label>
        <textarea class="form-input" id="tp-description" name="description" rows="3" placeholder="Brief overview of the treatment approach...">{{ old('description') }}</textarea>
        @error('description')<p class="field-error">{{ $message }}</p>@enderror
      </div>
      <div class="form-group">
        <label class="form-label" for="tp-goals">Treatment Goals</label>
        <textarea class="form-input" id="tp-goals" name="goals" rows="4" placeholder="List the primary goals for this treatment plan..." required>{{ old('goals') }}</textarea>
        <p class="form-help">Describe specific, measurable goals for this treatment period.</p>
        @error('goals')<p class="field-error">{{ $message }}</p>@enderror
      </div>
      <div class="form-group">
        <label class="form-label" for="tp-date">Target End Date (optional)</label>
        <input class="form-input" id="tp-date" name="target_end_date" type="date" value="{{ old('target_end_date') }}">
        @error('target_end_date')<p class="field-error">{{ $message }}</p>@enderror
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary">Create Plan</button>
        <a href="{{ route('therapist.treatment-plans.index') }}" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
