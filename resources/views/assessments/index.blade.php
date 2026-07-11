@extends('layouts.app')

@section('title', 'Assessments')
@section('page', 'assessments')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>Clinical Assessments</h1>
    <p>Standardized mental health screening tools</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('assessments.history') }}" class="btn btn-ghost btn-sm">View History</a>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($latest && in_array($latest->severity, ['severe', 'moderately-severe']))
  <div class="alert alert-warning">Your last assessment indicates significant symptoms. <a href="{{ route('assessments.report', $latest) }}">View detailed report</a> and share with your therapist.</div>
@endif

<div class="card-grid" style="grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));">
  <div class="card card--hoverable">
    <div class="card-body">
      <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
        <div style="width: 48px; height: 48px; border-radius: var(--radius-lg); background: var(--color-primary-soft); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-primary);"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 14l2 2 4-4"/></svg>
        </div>
        <div>
          <h3 style="margin: 0; font-size: 1.05rem;">PHQ-9</h3>
          <p class="text-muted" style="margin: 0.125rem 0 0; font-size: 0.8rem;">Patient Health Questionnaire</p>
        </div>
      </div>
      <p style="font-size: 0.85rem; margin-bottom: 1rem;">Assesses depression severity over the past two weeks. 9 questions, ~3 minutes.</p>
      <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
        <span class="badge badge--info" style="padding: 0.25rem 0.75rem;">{{ $results->get('phq-9', collect())->count() ? $results->get('phq-9')->count() . ' completed' : 'Not taken yet' }}</span>
        @if($results->get('phq-9') && $results->get('phq-9')->first())
          <span class="badge badge--{{ $results->get('phq-9')->first()->severity === 'none' ? 'success' : ($results->get('phq-9')->first()->severity === 'mild' ? 'warning' : 'danger') }}">
            Last: {{ $results->get('phq-9')->first()->score }}/27
          </span>
        @endif
      </div>
      <div style="display: flex; gap: 0.5rem;">
        @if($results->get('phq-9') && $results->get('phq-9')->first())
          <a href="{{ route('assessments.report', $results->get('phq-9')->first()) }}" class="btn btn-ghost" style="flex: 1;">View Report</a>
        @endif
        <a href="{{ route('assessments.take', 'phq-9') }}" class="btn btn-primary" style="flex: 1;">
          {{ $results->get('phq-9', collect())->count() ? 'Retake' : 'Start Assessment' }}
        </a>
      </div>
    </div>
  </div>

  <div class="card card--hoverable">
    <div class="card-body">
      <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
        <div style="width: 48px; height: 48px; border-radius: var(--radius-lg); background: var(--color-accent-soft); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--color-accent);"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        </div>
        <div>
          <h3 style="margin: 0; font-size: 1.05rem;">GAD-7</h3>
          <p class="text-muted" style="margin: 0.125rem 0 0; font-size: 0.8rem;">Generalized Anxiety Disorder</p>
        </div>
      </div>
      <p style="font-size: 0.85rem; margin-bottom: 1rem;">Assesses anxiety severity over the past two weeks. 7 questions, ~2 minutes.</p>
      <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
        <span class="badge badge--info" style="padding: 0.25rem 0.75rem;">{{ $results->get('gad-7', collect())->count() ? $results->get('gad-7')->count() . ' completed' : 'Not taken yet' }}</span>
        @if($results->get('gad-7') && $results->get('gad-7')->first())
          <span class="badge badge--{{ $results->get('gad-7')->first()->severity === 'none' ? 'success' : ($results->get('gad-7')->first()->severity === 'mild' ? 'warning' : 'danger') }}">
            Last: {{ $results->get('gad-7')->first()->score }}/21
          </span>
        @endif
      </div>
      <div style="display: flex; gap: 0.5rem;">
        @if($results->get('gad-7') && $results->get('gad-7')->first())
          <a href="{{ route('assessments.report', $results->get('gad-7')->first()) }}" class="btn btn-ghost" style="flex: 1;">View Report</a>
        @endif
        <a href="{{ route('assessments.take', 'gad-7') }}" class="btn btn-primary" style="flex: 1;">
          {{ $results->get('gad-7', collect())->count() ? 'Retake' : 'Start Assessment' }}
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
