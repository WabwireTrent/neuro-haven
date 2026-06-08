@extends('layouts.app')

@section('title', $treatmentPlan->title)
@section('page', 'treatment-plans-show')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>{{ $treatmentPlan->title }}</h1>
    <p>
      @if(auth()->user()->isTherapist())
        Patient: {{ $treatmentPlan->patient->name }}
      @else
        Therapist: {{ $treatmentPlan->therapist->name }}
      @endif
      &middot; Created {{ $treatmentPlan->created_at->format('M j, Y') }}
    </p>
  </div>
  <div class="page-header__actions">
    @if(auth()->user()->isTherapist())
      <form method="POST" action="{{ route('therapist.treatment-plans.status', $treatmentPlan) }}" style="display: flex; gap: 0.5rem;">
        @csrf
        <select name="status" class="form-input" style="width: auto; height: 32px; font-size: 0.8rem;">
          <option value="active" {{ $treatmentPlan->status === 'active' ? 'selected' : '' }}>Active</option>
          <option value="on-hold" {{ $treatmentPlan->status === 'on-hold' ? 'selected' : '' }}>On Hold</option>
          <option value="completed" {{ $treatmentPlan->status === 'completed' ? 'selected' : '' }}>Completed</option>
          <option value="cancelled" {{ $treatmentPlan->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <button type="submit" class="btn btn-sm btn-secondary">Update</button>
      </form>
    @endif
    <a href="{{ route(auth()->user()->isTherapist() ? 'therapist.treatment-plans.index' : 'patient.treatment-plans') }}" class="btn btn-ghost btn-sm">Back</a>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="stats-grid" style="margin-bottom: 1.5rem;">
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Status</p>
    <p class="stat-card__value" style="font-size: 0.95rem; text-transform: capitalize;">
      <span class="badge badge--{{ $treatmentPlan->status === 'active' ? 'success' : ($treatmentPlan->status === 'on-hold' ? 'warning' : ($treatmentPlan->status === 'completed' ? 'info' : 'neutral')) }}" style="font-size: 0.85rem; padding: 0.35rem 1rem;">
        {{ ucfirst($treatmentPlan->status) }}
      </span>
    </p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Milestones</p>
    <p class="stat-card__value">{{ $treatmentPlan->milestones->count() }}</p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Completed</p>
    <p class="stat-card__value">{{ $treatmentPlan->milestones->whereNotNull('completed_at')->count() }}</p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Started</p>
    <p class="stat-card__value" style="font-size: 0.85rem;">{{ $treatmentPlan->started_at?->format('M j, Y') ?? 'N/A' }}</p>
  </div>
</div>

<div class="widget-grid">
  <div>
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">Plan Details</h4>
      </div>
      <div class="card-body">
        @if($treatmentPlan->description)
          <p style="font-size: 0.9rem; margin-bottom: 1rem;">{{ $treatmentPlan->description }}</p>
        @endif
        <h5 style="font-size: 0.85rem; font-weight: 700; margin: 0 0 0.5rem;">Goals</h5>
        <div style="padding: 1rem; background: var(--color-surface-muted); border-radius: var(--radius-lg);">
          <p style="font-size: 0.85rem; margin: 0; white-space: pre-wrap;">{{ $treatmentPlan->goals }}</p>
        </div>
        @if($treatmentPlan->target_end_date)
          <p class="text-muted" style="font-size: 0.8rem; margin-top: 0.75rem;">Target completion: {{ $treatmentPlan->target_end_date->format('M j, Y') }}</p>
        @endif
      </div>
    </div>
  </div>

  <div>
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">Milestones</h4>
        @if(auth()->user()->isTherapist())
          <button class="btn btn-sm btn-primary" onclick="document.getElementById('add-milestone-form').style.display = document.getElementById('add-milestone-form').style.display === 'none' ? 'block' : 'none'">+ Add</button>
        @endif
      </div>
      <div class="card-body">
        @if(auth()->user()->isTherapist())
          <div id="add-milestone-form" style="display: none; margin-bottom: 1rem; padding: 1rem; background: var(--color-surface-muted); border-radius: var(--radius-lg);">
            <form method="POST" action="{{ route('therapist.treatment-plans.milestones.store', $treatmentPlan) }}" style="display: grid; gap: 0.75rem;">
              @csrf
              <div class="form-group">
                <label class="form-label" for="ms-title">Milestone Title</label>
                <input class="form-input" id="ms-title" name="title" type="text" placeholder="e.g. Complete PHQ-9 assessment" required>
              </div>
              <div class="form-group">
                <label class="form-label" for="ms-desc">Description</label>
                <textarea class="form-input" id="ms-desc" name="description" rows="2" placeholder="Optional details..."></textarea>
              </div>
              <div class="form-group">
                <label class="form-label" for="ms-date">Due Date</label>
                <input class="form-input" id="ms-date" name="due_date" type="date">
              </div>
              <button type="submit" class="btn btn-primary btn-sm">Add Milestone</button>
            </form>
          </div>
        @endif

        @if($treatmentPlan->milestones->count() > 0)
          <div class="activity-list">
            @foreach($treatmentPlan->milestones as $ms)
              <div class="activity-item" style="opacity: {{ $ms->completed_at ? 0.6 : 1 }};">
                <div class="activity-item__icon" style="background: {{ $ms->completed_at ? 'var(--color-success-soft)' : 'var(--color-surface-muted)' }}; color: {{ $ms->completed_at ? 'var(--color-success)' : 'var(--color-text-muted)' }};">
                  @if($ms->completed_at)
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                  @else
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg>
                  @endif
                </div>
                <div class="activity-item__content">
                  <p class="activity-item__text" style="text-decoration: {{ $ms->completed_at ? 'line-through' : 'none' }};">{{ $ms->title }}</p>
                  @if($ms->description)
                    <p class="activity-item__time">{{ $ms->description }}</p>
                  @endif
                  <p class="activity-item__time">{{ $ms->due_date ? 'Due: '.$ms->due_date->format('M j, Y') : '' }} {{ $ms->completed_at ? 'Completed: '.$ms->completed_at->format('M j, Y') : '' }}</p>
                </div>
                @if(!$ms->completed_at && auth()->user()->isTherapist())
                  <div class="activity-item__action">
                    <form method="POST" action="{{ route('therapist.treatment-plans.milestones.complete', $ms) }}">
                      @csrf
                      <button type="submit" class="btn btn-sm btn-ghost" title="Mark complete" style="font-size: 0.75rem;">Mark Done</button>
                    </form>
                  </div>
                @endif
              </div>
            @endforeach
          </div>
        @else
          <p class="text-muted text-sm">No milestones added yet.</p>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
