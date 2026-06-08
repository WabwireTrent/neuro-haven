@extends('layouts.app')

@section('title', 'Treatment Plans')
@section('page', 'treatment-plans')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>Treatment Plans</h1>
    <p>{{ auth()->user()->isTherapist() ? 'Manage patient treatment plans' : 'Your treatment plans' }}</p>
  </div>
  <div class="page-header__actions">
    @if(auth()->user()->isTherapist())
      <a href="{{ route('therapist.treatment-plans.create') }}" class="btn btn-primary btn-sm">+ New Plan</a>
    @endif
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($suggestedSession && auth()->user()->isPatient())
  <div class="card" style="margin-bottom: 1.25rem; border: 2px solid var(--color-primary);">
    <div class="card-header">
      <h4 class="card-header__title">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 0.375rem;"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
        Recommended Session Based on Your Mood
      </h4>
    </div>
    <div class="card-body">
      <div style="display: flex; align-items: flex-start; gap: 1rem;">
        <div style="width: 48px; height: 48px; border-radius: var(--radius-lg); background: var(--color-primary-soft); display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.5rem;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--color-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 10c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c0-1.1-.9-2-2-2"/></svg>
        </div>
        <div style="flex: 1;">
          <p style="font-weight: 700; margin: 0 0 0.125rem; font-size: 1rem;">{{ $suggestedSession->title }}</p>
          <p style="font-size: 0.85rem; color: var(--color-text-muted); margin: 0 0 0.375rem;">{{ $suggestedSession->category }} &middot; {{ $suggestedSession->duration_minutes }} min</p>
          <p style="font-size: 0.85rem; margin: 0 0 0.75rem; font-style: italic;">{{ $suggestionReason }}</p>
          <a href="{{ route('vr.assets.show', $suggestedSession) }}" class="btn btn-primary btn-sm" style="font-size: 0.8rem; padding: 0.5rem 1rem;">
            Start This Session
          </a>
        </div>
      </div>
    </div>
  </div>
@endif

@if($plans->count() > 0)
  <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 1.25rem;">
    @foreach($plans as $plan)
      <div class="card card--hoverable">
        <div class="card-body">
          <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 0.75rem;">
            <div>
              <h4 style="margin: 0; font-size: 1rem;">{{ $plan->title }}</h4>
              <p class="text-muted text-sm" style="margin: 0.125rem 0 0;">
                @if(auth()->user()->isTherapist())
                  Patient: {{ $plan->patient->name }}
                @else
                  Therapist: {{ $plan->therapist->name }}
                @endif
              </p>
            </div>
            <span class="badge badge--{{ $plan->status === 'active' ? 'success' : ($plan->status === 'on-hold' ? 'warning' : ($plan->status === 'completed' ? 'info' : 'neutral')) }}">
              {{ ucfirst($plan->status) }}
            </span>
          </div>
          @if($plan->milestones->count() > 0)
            @php $total = $plan->milestones->count(); $done = $plan->milestones->whereNotNull('completed_at')->count(); $pct = $total > 0 ? round(($done/$total)*100) : 0; @endphp
            <div style="margin-bottom: 0.75rem;">
              <div style="display: flex; justify-content: space-between; font-size: 0.78rem; margin-bottom: 0.25rem;">
                <span class="text-muted">Progress</span>
                <span class="text-muted">{{ $done }}/{{ $total }} milestones</span>
              </div>
              <div style="height: 4px; background: var(--color-border); border-radius: 2px; overflow: hidden;">
                <div style="width: {{ $pct }}%; height: 100%; background: var(--color-primary); border-radius: 2px; transition: width 0.5s ease;"></div>
              </div>
            </div>
          @endif
          <div style="display: flex; justify-content: space-between; font-size: 0.78rem;">
            <span class="text-muted">Started {{ $plan->started_at?->format('M j, Y') ?? 'N/A' }}</span>
            @if($plan->target_end_date)
              <span class="text-muted">Due {{ $plan->target_end_date->format('M j, Y') }}</span>
            @endif
          </div>
        </div>
        <div style="padding: 0.75rem 1.25rem; border-top: 1px solid var(--color-border-light);">
          <a href="{{ route(auth()->user()->isTherapist() ? 'therapist.treatment-plans.show' : 'patient.treatment-plans.show', $plan) }}" class="btn btn-ghost btn-sm" style="width: 100%;">View Plan</a>
        </div>
      </div>
    @endforeach
  </div>
  @if(method_exists($plans, 'hasPages') && $plans->hasPages())
    <div style="margin-top: 1.25rem; display: flex; justify-content: center;">{{ $plans->links() }}</div>
  @endif
@else
  <div class="card">
    <div class="empty-state">
      <div class="empty-state__icon">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      </div>
      <p class="empty-state__title">No Treatment Plans</p>
      <p class="empty-state__desc">{{ auth()->user()->isTherapist() ? 'Create your first treatment plan for a patient.' : 'Your therapist hasn\'t created a plan for you yet.' }}</p>
      @if(auth()->user()->isTherapist())
        <a href="{{ route('therapist.treatment-plans.create') }}" class="btn btn-primary">Create Plan</a>
      @endif
    </div>
  </div>
@endif
@endsection
