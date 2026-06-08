@extends('layouts.app')

@section('title', 'Assessment Results')
@section('page', 'assessments-results')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>{{ $type === 'phq-9' ? 'PHQ-9' : 'GAD-7' }} Results</h1>
    <p>Your assessment history and trends</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('assessments.take', $type) }}" class="btn btn-primary btn-sm">Take Again</a>
    <a href="{{ route('assessments.index') }}" class="btn btn-ghost btn-sm">All Assessments</a>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($results->count() > 0)
  <div class="stats-grid" style="margin-bottom: 1.5rem;">
    <div class="stat-card" style="text-align: center;">
      <p class="stat-card__label">Total Taken</p>
      <p class="stat-card__value">{{ $results->count() }}</p>
    </div>
    <div class="stat-card" style="text-align: center;">
      <p class="stat-card__label">Latest Score</p>
      <p class="stat-card__value">{{ $results->first()->score }}/{{ $type === 'phq-9' ? 27 : 21 }}</p>
    </div>
    <div class="stat-card" style="text-align: center;">
      <p class="stat-card__label">Latest Severity</p>
      <p class="stat-card__value" style="font-size: 0.95rem; text-transform: capitalize;">{{ $results->first()->severity }}</p>
    </div>
    <div class="stat-card" style="text-align: center;">
      <p class="stat-card__label">Last Taken</p>
      <p class="stat-card__value" style="font-size: 0.85rem;">{{ $results->first()->completed_at->format('M j') }}</p>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h4 class="card-header__title">Score History</h4>
    </div>
    <div class="card-body">
      <div class="activity-list">
        @foreach($results as $result)
          <div class="activity-item">
            <div class="activity-item__icon" style="background: var(--color-primary-soft); color: var(--color-primary); font-weight: 700; font-size: 0.85rem;">
              {{ $result->score }}
            </div>
            <div class="activity-item__content">
              <p class="activity-item__text">
                Score: {{ $result->score }}/{{ $type === 'phq-9' ? 27 : 21 }}
                &middot; <span style="text-transform: capitalize;">{{ $result->severity }}</span>
              </p>
              <p class="activity-item__time">{{ $result->completed_at->format('M j, Y g:i A') }}</p>
            </div>
            <div>
              <span class="badge badge--{{ $result->severity === 'none' ? 'success' : ($result->severity === 'mild' ? 'warning' : 'danger') }}">
                {{ ucfirst($result->severity) }}
              </span>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
@else
  <div class="card">
    <div class="empty-state">
      <div class="empty-state__icon">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      </div>
      <p class="empty-state__title">No Results Yet</p>
      <p class="empty-state__desc">Complete the assessment to see your results here.</p>
      <a href="{{ route('assessments.take', $type) }}" class="btn btn-primary">Take Assessment</a>
    </div>
  </div>
@endif
@endsection
