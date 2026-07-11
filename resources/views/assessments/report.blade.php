@extends('layouts.app')

@section('title', 'Assessment Report')
@section('page', 'assessments-report')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>{{ $assessmentResult->assessment_type === 'phq-9' ? 'PHQ-9' : 'GAD-7' }} Clinical Report</h1>
    <p>Completed {{ $assessmentResult->completed_at->format('F d, Y \a\t g:i A') }}</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('assessments.results', $assessmentResult->assessment_type) }}" class="btn btn-ghost btn-sm">Back to Results</a>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Score Summary --}}
<div class="stats-grid" style="margin-bottom: 1.5rem;">
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Total Score</p>
    <p class="stat-card__value">{{ $assessmentResult->score }}/{{ $maxScore }}</p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Severity Level</p>
    <p class="stat-card__value" style="font-size: 1.1rem; text-transform: capitalize;">
      <span class="badge badge--{{ $assessmentResult->severity === 'none' ? 'success' : ($assessmentResult->severity === 'mild' ? 'warning' : ($assessmentResult->severity === 'moderate' ? 'warning' : 'danger')) }}" style="font-size: 0.85rem; padding: 0.35rem 0.75rem;">
        {{ str_replace('-', ' ', ucfirst($assessmentResult->severity)) }}
      </span>
    </p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Assessment Type</p>
    <p class="stat-card__value" style="font-size: 1rem;">{{ $assessmentResult->assessment_type === 'phq-9' ? 'PHQ-9' : 'GAD-7' }}</p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Completed</p>
    <p class="stat-card__value" style="font-size: 0.9rem;">{{ $assessmentResult->completed_at->format('M j, Y') }}</p>
  </div>
</div>

{{-- Responses Detail --}}
<div class="card" style="margin-bottom: 1.25rem;">
  <div class="card-header">
    <h4 class="card-header__title">Question Responses</h4>
    <span class="badge badge--primary">{{ count($assessmentResult->responses) }} items</span>
  </div>
  <div class="card-body" style="padding: 0;">
    <div style="display: grid;">
      @php $scoreLabels = ['Not at all', 'Several days', 'More than half the days', 'Nearly every day']; @endphp
      @foreach($assessmentResult->responses as $i => $response)
        <div style="padding: 0.85rem 1.25rem; border-bottom: 1px solid var(--color-border-light); {{ $loop->last ? 'border-bottom: none;' : '' }} display: flex; align-items: flex-start; gap: 1rem;">
          <div style="min-width: 36px; height: 36px; border-radius: 50%; background: {{ $response['score'] >= 2 ? 'var(--color-danger-soft, #fee2e2)' : 'var(--color-primary-soft, #dbeafe)' }}; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; color: {{ $response['score'] >= 2 ? 'var(--color-danger, #dc2626)' : 'var(--color-primary, #3b82f6)' }};">
            {{ $response['score'] }}
          </div>
          <div style="flex: 1; min-width: 0;">
            <p style="margin: 0 0 0.15rem; font-weight: 600; font-size: 0.9rem;">{{ $loop->iteration }}. {{ $response['question'] }}</p>
            <p style="margin: 0; font-size: 0.8rem; color: var(--color-text-muted);">{{ $scoreLabels[$response['score']] ?? $response['score'] }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>

{{-- Clinical Interpretation --}}
<div class="card" style="margin-bottom: 1.25rem; border-left: 4px solid {{ $assessmentResult->severity === 'none' ? '#22c55e' : ($assessmentResult->severity === 'mild' ? '#eab308' : ($assessmentResult->severity === 'moderate' ? '#f97316' : '#ef4444')) }};">
  <div class="card-header">
    <h4 class="card-header__title">Clinical Interpretation</h4>
  </div>
  <div class="card-body">
    <p style="margin: 0; line-height: 1.7; font-size: 0.95rem;">{{ $assessmentResult->interpretation ?? 'No clinical interpretation available for this assessment.' }}</p>
  </div>
</div>

{{-- Suggested Treatment Plan --}}
@if($assessmentResult->suggested_plan)
  <div class="card" style="margin-bottom: 1.25rem; border: 2px solid var(--color-primary, #3b82f6);">
    <div class="card-header" style="background: var(--color-primary-soft, #dbeafe);">
      <h4 class="card-header__title" style="color: var(--color-primary, #3b82f6);">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 0.35rem;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
        Recommended Treatment Plan
      </h4>
    </div>
    <div class="card-body">
      <h3 style="margin: 0 0 0.25rem; font-size: 1.1rem;">{{ $assessmentResult->suggested_plan['title'] }}</h3>
      <p style="color: var(--color-text-muted); font-size: 0.9rem; margin: 0 0 1rem;">{{ $assessmentResult->suggested_plan['description'] }}</p>

      @if(!empty($assessmentResult->suggested_plan['goals']))
        <h4 style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.04em; color: var(--color-text-muted); margin: 0 0 0.5rem;">Treatment Goals</h4>
        <ul style="margin: 0 0 1rem; padding-left: 1.25rem;">
          @foreach($assessmentResult->suggested_plan['goals'] as $goal)
            <li style="margin-bottom: 0.35rem; line-height: 1.5; font-size: 0.9rem;">{{ $goal }}</li>
          @endforeach
        </ul>
      @endif

      @if(!empty($assessmentResult->suggested_plan['recommended_frequency']))
        <div style="padding: 0.75rem 1rem; background: var(--color-surface-muted, #f1f5f9); border-radius: var(--radius-lg, 8px); display: flex; align-items: center; gap: 0.5rem;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; color: var(--color-primary, #3b82f6);"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          <span style="font-size: 0.85rem; font-weight: 500;">Recommended Frequency:</span>
          <span style="font-size: 0.85rem;">{{ $assessmentResult->suggested_plan['recommended_frequency'] }}</span>
        </div>
      @endif
    </div>
  </div>
@endif

{{-- Export Button --}}
<div style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.5rem;">
  <a href="{{ route('assessments.results', $assessmentResult->assessment_type) }}" class="btn btn-ghost">Back to Results</a>
</div>
@endsection
