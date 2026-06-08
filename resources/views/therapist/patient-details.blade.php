@extends('layouts.app')

@section('title', $patient->name . ' - Patient Details')
@section('page', 'patient-details')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>{{ $patient->name }}</h1>
    <p>{{ $patient->email }} &middot; Member since {{ $patient->created_at->format('M j, Y') }}</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('therapist.patients') }}" class="btn btn-secondary btn-sm">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
      All Patients
    </a>
    <a href="{{ route('therapist.dashboard') }}" class="btn btn-ghost btn-sm">Dashboard</a>
  </div>
</div>

<div class="stats-grid" style="margin-bottom: 1.5rem;">
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Total Moods</p>
    <p class="stat-card__value">{{ $moodStats['total_moods'] }}</p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Average Mood</p>
    <p class="stat-card__value">{{ $moodStats['avg_mood'] }}/10</p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">VR Sessions</p>
    <p class="stat-card__value">{{ $vrStats['total_sessions'] }}</p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Completed</p>
    <p class="stat-card__value">{{ $vrStats['completed_sessions'] }}</p>
  </div>
</div>

<div class="widget-grid">
  <div>
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">Mood Analytics</h4>
        <span class="badge badge--secondary">Last 30 days</span>
      </div>
      <div class="card-body">
        <div class="kpi-grid" style="margin-bottom: 0;">
          <div class="kpi-card" style="margin: 0;">
            <div class="kpi-card__value">{{ $moodStats['total_moods'] }}</div>
            <div class="kpi-card__label">Total Moods</div>
          </div>
          <div class="kpi-card" style="margin: 0;">
            <div class="kpi-card__value">{{ round($moodStats['avg_mood'], 1) }}</div>
            <div class="kpi-card__label">Average Mood</div>
          </div>
          <div class="kpi-card" style="margin: 0;">
            <div class="kpi-card__value">{{ $moodStats['week_moods'] }}</div>
            <div class="kpi-card__label">This Week</div>
          </div>
          <div class="kpi-card" style="margin: 0;">
            <div class="kpi-card__value">{{ $moodStats['month_moods'] }}</div>
            <div class="kpi-card__label">This Month</div>
          </div>
        </div>
      </div>
    </div>

    @if($patient->moods->count() > 0)
      <div class="card" style="margin-bottom: 1.25rem;">
        <div class="card-header">
          <h4 class="card-header__title">Recent Mood Entries</h4>
        </div>
        <div class="card-body">
          <div class="activity-list">
            @foreach($patient->moods->take(10) as $mood)
              <div class="activity-item">
                <div class="activity-item__icon" style="background:var(--color-primary-soft);color:var(--color-primary);font-size:1.1rem;">
                  @php $moodEmoji = [1=>'😢',2=>'😞',3=>'😐',4=>'🙂',5=>'😊',6=>'😄',7=>'😄',8=>'😄',9=>'😄',10=>'🤩']; @endphp
                  {{ $moodEmoji[$mood->mood_scale] ?? '😐' }}
                </div>
                <div class="activity-item__content">
                  <p class="activity-item__text">{{ ucfirst($mood->mood) }} &mdash; {{ $mood->mood_scale }}/10</p>
                  <p class="activity-item__time">{{ $mood->mood_date->format('M j, Y') }} &middot; {{ $mood->created_at->diffForHumans() }}</p>
                  @if($mood->note)
                    <div style="margin: 0.375rem 0 0; padding: 0.5rem; background: var(--color-surface-muted); border-radius: var(--radius-md); font-size: 0.8rem; color: var(--color-text); border-left: 3px solid var(--color-primary);">
                      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 0.25rem; opacity: 0.6;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                      {{ $mood->note }}
                    </div>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    @endif
  </div>

  <div>
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">VR Therapy</h4>
        <span class="badge badge--secondary">{{ $vrStats['completed_sessions'] }} completed</span>
      </div>
      <div class="card-body">
        <div class="kpi-grid" style="margin-bottom: 0;">
          <div class="kpi-card" style="margin: 0;">
            <div class="kpi-card__value">{{ $vrStats['total_sessions'] }}</div>
            <div class="kpi-card__label">Total Sessions</div>
          </div>
          <div class="kpi-card" style="margin: 0;">
            <div class="kpi-card__value">{{ round($vrStats['total_duration'] / 60, 1) }}m</div>
            <div class="kpi-card__label">Total Time</div>
          </div>
          <div class="kpi-card" style="margin: 0;">
            <div class="kpi-card__value">{{ round($vrStats['avg_quality'], 1) }}</div>
            <div class="kpi-card__label">Avg Quality</div>
          </div>
          <div class="kpi-card" style="margin: 0;">
            <div class="kpi-card__value">{{ $vrStats['completed_sessions'] }}</div>
            <div class="kpi-card__label">Completed</div>
          </div>
        </div>
      </div>
    </div>

    @if($patient->vrSessions->count() > 0)
      <div class="card" style="margin-bottom: 1.25rem;">
        <div class="card-header">
          <h4 class="card-header__title">Recent VR Sessions</h4>
        </div>
        <div class="card-body">
          <div class="activity-list">
            @foreach($patient->vrSessions->take(10) as $session)
              <a href="{{ route('therapist.vr-session.report', $session) }}" class="activity-item" style="text-decoration: none; color: inherit;">
                <div class="activity-item__icon" style="background:var(--color-secondary-soft);color:var(--color-secondary);">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div class="activity-item__content">
                  <p class="activity-item__text">{{ $session->vr_asset_title }}</p>
                  <p class="activity-item__time">
                    {{ $session->session_duration ? round($session->session_duration/60,1).'m' : 'N/A' }} &middot;
                    {{ $session->session_quality ? $session->session_quality.'/5' : 'N/A' }} &middot;
                    {{ $session->started_at->diffForHumans() }}
                  </p>
                </div>
                <div class="activity-item__action" style="display:flex;align-items:center;gap:0.375rem;">
                  <span class="badge badge--{{ $session->is_completed ? 'success' : 'warning' }}">{{ $session->is_completed ? 'Done' : 'Active' }}</span>
                  @if($session->is_completed)
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--color-text-muted)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                  @endif
                </div>
              </a>
            @endforeach
          </div>
        </div>
      </div>
    @endif
  </div>
</div>
@endsection
