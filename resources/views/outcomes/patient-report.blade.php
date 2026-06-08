@extends('layouts.app')

@section('title', $patient->name . ' - Outcome Report')
@section('page', 'outcomes-patient')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>{{ $patient->name }}</h1>
    <p>Clinical outcome report &middot; Member since {{ $patient->created_at->format('M Y') }}</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('outcomes.index') }}" class="btn btn-ghost btn-sm">Back to Outcomes</a>
  </div>
</div>

<div class="stats-grid" style="margin-bottom: 1.5rem;">
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Avg Mood</p>
    <p class="stat-card__value">{{ $stats['avg_mood'] }}/10</p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Streak</p>
    <p class="stat-card__value">{{ $stats['streak'] }} days</p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">VR Sessions</p>
    <p class="stat-card__value">{{ $stats['total_sessions'] }}</p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Completed</p>
    <p class="stat-card__value">{{ $stats['completed_sessions'] }}</p>
  </div>
</div>

<div class="widget-grid">
  <div>
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">Mood Trend</h4>
        <span class="badge badge--secondary">30 days</span>
      </div>
      <div class="card-body">
        @if($moodTrend->count() > 0)
          <div style="display: flex; align-items: flex-end; gap: 3px; height: 120px; padding: 0.5rem 0;">
            @foreach($moodTrend as $m)
              <div style="flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end;">
                <div style="width: 100%; border-radius: 2px 2px 0 0; height: {{ ($m->mood_scale / 10) * 100 }}%; background: {{ $m->mood_scale >= 7 ? 'var(--color-success)' : ($m->mood_scale >= 4 ? 'var(--color-accent)' : 'var(--color-danger)') }}; transition: height 0.3s ease; min-height: 3px;" title="{{ $m->mood_date->format('M j') }}: {{ $m->mood_scale }}/10"></div>
              </div>
            @endforeach
          </div>
        @else
          <p class="text-muted text-sm">No mood data available.</p>
        @endif
      </div>
    </div>

    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">Assessments</h4>
      </div>
      <div class="card-body">
        @if($assessments->count() > 0)
          @foreach($assessments as $type => $typeResults)
            <div style="margin-bottom: 0.75rem;">
              <h5 style="font-size: 0.85rem; font-weight: 700; margin: 0 0 0.5rem;">{{ strtoupper($type) }}</h5>
              <div class="activity-list">
                @foreach($typeResults->take(5) as $r)
                  <div class="activity-item" style="padding: 0.5rem 0;">
                    <div class="activity-item__icon" style="width: 28px; height: 28px; background: var(--color-primary-soft); color: var(--color-primary); font-size: 0.75rem; font-weight: 700;">{{ $r->score }}</div>
                    <div class="activity-item__content">
                      <p class="activity-item__time">{{ $r->completed_at->format('M j, Y') }} &middot; {{ ucfirst($r->severity) }}</p>
                    </div>
                    <span class="badge badge--{{ $r->severity === 'none' ? 'success' : ($r->severity === 'mild' ? 'warning' : 'danger') }}" style="font-size: 0.7rem;">{{ $r->severity }}</span>
                  </div>
                @endforeach
              </div>
            </div>
          @endforeach
        @else
          <p class="text-muted text-sm">No assessments completed.</p>
        @endif
      </div>
    </div>
  </div>

  <div>
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">VR Sessions</h4>
        <span class="badge badge--secondary">Last 20</span>
      </div>
      <div class="card-body">
        @if($sessions->count() > 0)
          <div class="activity-list">
            @foreach($sessions as $s)
              <div class="activity-item">
                <div class="activity-item__icon" style="background: var(--color-secondary-soft); color: var(--color-secondary);">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div class="activity-item__content">
                  <p class="activity-item__text">{{ $s->vr_asset_title ?? 'Session' }}</p>
                  <p class="activity-item__time">{{ $s->started_at->diffForHumans() }} &middot; {{ $s->session_duration ? round($s->session_duration/60,1).'m' : 'N/A' }}</p>
                </div>
                <span class="badge badge--{{ $s->is_completed ? 'success' : 'warning' }}">{{ $s->is_completed ? 'Done' : 'Active' }}</span>
              </div>
            @endforeach
          </div>
        @else
          <p class="text-muted text-sm">No VR sessions yet.</p>
        @endif
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h4 class="card-header__title">Summary</h4>
      </div>
      <div class="card-body">
        <div style="display: grid; gap: 0.75rem;">
          <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--color-border-light);">
            <span class="text-muted">Average Mood</span>
            <strong>{{ $stats['avg_mood'] }}/10</strong>
          </div>
          <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--color-border-light);">
            <span class="text-muted">Current Streak</span>
            <strong>{{ $stats['streak'] }} days</strong>
          </div>
          <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--color-border-light);">
            <span class="text-muted">Total Sessions</span>
            <strong>{{ $stats['total_sessions'] }}</strong>
          </div>
          <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--color-border-light);">
            <span class="text-muted">Completed Sessions</span>
            <strong>{{ $stats['completed_sessions'] }}</strong>
          </div>
          <div style="display: flex; justify-content: space-between; padding: 0.5rem 0;">
            <span class="text-muted">Assessments Taken</span>
            <strong>{{ $stats['total_assessments'] }}</strong>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
