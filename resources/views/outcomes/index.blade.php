@extends('layouts.app')

@section('title', 'Clinical Outcomes')
@section('page', 'outcomes')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>Clinical Outcomes</h1>
    <p>{{ $isTherapist ? 'Aggregated patient outcome metrics' : 'Your personal progress and outcomes' }}</p>
  </div>
</div>

<div class="stats-grid" style="margin-bottom: 1.5rem;">
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">{{ $isTherapist ? 'Total Patients' : 'Your Streak' }}</p>
    <p class="stat-card__value">{{ $isTherapist ? $totalPatients : (auth()->user()->getCurrentStreak() ?? '0') }}</p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Avg Mood Score</p>
    <p class="stat-card__value">{{ round($avgMoodAll, 1) }}/10</p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Session Completion</p>
    <p class="stat-card__value">{{ $completionRate }}%</p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Active Alerts</p>
    <p class="stat-card__value" style="color: {{ $activeAlerts > 0 ? 'var(--color-danger)' : 'var(--color-success)' }};">{{ $activeAlerts }}</p>
  </div>
</div>

<div class="widget-grid">
  <div>
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">Mood Trends (30 Days)</h4>
      </div>
      <div class="card-body">
        @if($moodData->count() > 0)
          <div style="display: flex; align-items: flex-end; gap: 2px; height: 140px; padding: 0.5rem 0;">
            @foreach($moodData as $point)
              <div style="flex: 1; display: flex; flex-direction: column; align-items: center; gap: 2px; height: 100%; justify-content: flex-end;">
                <div style="width: 100%; max-width: 12px; border-radius: 3px 3px 0 0; height: {{ ($point->avg_mood / 10) * 100 }}%; background: {{ $point->avg_mood >= 7 ? 'var(--color-success)' : ($point->avg_mood >= 4 ? 'var(--color-accent)' : 'var(--color-danger)') }}; transition: height 0.3s ease; min-height: 4px;" title="{{ round($point->avg_mood, 1) }}/10"></div>
              </div>
            @endforeach
          </div>
          <p class="text-muted text-sm" style="margin-top: 0.5rem;">Daily average mood scores over the last 30 days</p>
        @else
          <p class="text-muted text-sm">Not enough mood data to display trends.</p>
        @endif
      </div>
    </div>

    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">Assessment Scores</h4>
      </div>
      <div class="card-body">
        @if($assessmentScores->count() > 0)
          <div class="activity-list">
            @foreach($assessmentScores as $score)
              <div class="activity-item">
                <div class="activity-item__icon" style="background: var(--color-primary-soft); color: var(--color-primary); font-weight: 700; font-size: 0.85rem;">
                  {{ round($score->avg_score, 1) }}
                </div>
                <div class="activity-item__content">
                  <p class="activity-item__text">{{ strtoupper($score->assessment_type) }}</p>
                  <p class="activity-item__time">{{ $score->total }} assessment(s) completed</p>
                </div>
                <span class="badge badge--info">{{ $score->total }} total</span>
              </div>
            @endforeach
          </div>
        @else
          <p class="text-muted text-sm">No assessment data available.</p>
        @endif
      </div>
    </div>
  </div>

  <div>
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">Engagement Overview</h4>
      </div>
      <div class="card-body">
        <div class="kpi-grid">
          <div class="kpi-card">
            <div class="kpi-card__value">{{ $totalSessions }}</div>
            <div class="kpi-card__label">Total VR Sessions</div>
          </div>
          <div class="kpi-card">
            <div class="kpi-card__value">{{ $completedSessions }}</div>
            <div class="kpi-card__label">Completed</div>
          </div>
          <div class="kpi-card">
            <div class="kpi-card__value">{{ $completionRate }}%</div>
            <div class="kpi-card__label">Completion Rate</div>
          </div>
          <div class="kpi-card">
            <div class="kpi-card__value">{{ $activeAlerts }}</div>
            <div class="kpi-card__label">Active Alerts</div>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h4 class="card-header__title">Weekly Engagement</h4>
      </div>
      <div class="card-body">
        @php $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']; @endphp
        <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px;">
          @foreach($days as $i => $day)
            <div style="text-align: center;">
              <div style="font-size: 0.65rem; color: var(--color-text-muted); margin-bottom: 4px;">{{ substr($day, 0, 3) }}</div>
              <div style="width: 100%; aspect-ratio: 1; border-radius: var(--radius-lg); background: {{ isset($engagementByDay[$i]) ? 'var(--color-primary)' : 'var(--color-surface-muted)' }}; opacity: {{ isset($engagementByDay[$i]) ? min(0.3 + ($engagementByDay[$i]->count / 10), 1) : 0.3 }}; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700; color: {{ isset($engagementByDay[$i]) ? '#fff' : 'var(--color-text-muted)' }};">
                {{ isset($engagementByDay[$i]) ? $engagementByDay[$i]->count : '-' }}
              </div>
            </div>
          @endforeach
        </div>
        <p class="text-muted text-sm" style="margin-top: 0.75rem;">Activity intensity by day of the week (30 days)</p>
      </div>
    </div>
  </div>
</div>
@endsection
