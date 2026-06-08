@extends('layouts.app')

@section('title', 'User Details - ' . $user->name)
@section('page', 'admin-user-details')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>{{ $user->name }}</h1>
    <p>{{ $user->email }} &middot; {{ ucfirst($user->role) }} &middot; Joined {{ $user->created_at->format('M j, Y') }}</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('admin.users') }}" class="btn btn-secondary btn-sm">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
      Back to Users
    </a>
  </div>
</div>

<div class="stats-grid" style="margin-bottom: 1.5rem;">
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Total Moods</p>
    <p class="stat-card__value">{{ $moodStats['total_moods'] }}</p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Average Mood</p>
    <p class="stat-card__value">{{ round($moodStats['avg_mood'], 1) }}/10</p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">VR Sessions</p>
    <p class="stat-card__value">{{ $vrStats['total_sessions'] }}</p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Avg Quality</p>
    <p class="stat-card__value">{{ round($vrStats['avg_quality'], 1) }}/5</p>
  </div>
</div>

<div class="widget-grid">
  <div>
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">Mood Overview</h4>
        <span class="badge badge--secondary">{{ $moodStats['week_moods'] }} this week</span>
      </div>
      <div class="card-body">
        <div class="stats-grid" style="grid-template-columns: repeat(2, 1fr); margin-bottom: 0;">
          <div class="kpi-card" style="margin: 0;">
            <div class="kpi-card__value">{{ $moodStats['total_moods'] }}</div>
            <div class="kpi-card__label">Total Moods</div>
          </div>
          <div class="kpi-card" style="margin: 0;">
            <div class="kpi-card__value">{{ round($moodStats['avg_mood'], 1) }}</div>
            <div class="kpi-card__label">Average</div>
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

    @if($user->moods->count() > 0)
      <div class="card" style="margin-bottom: 1.25rem;">
        <div class="card-header">
          <h4 class="card-header__title">Recent Mood Entries</h4>
          <span class="badge badge--primary">Last 30 days</span>
        </div>
        <div class="card-body">
          <div class="activity-list">
            @foreach($user->moods->take(8) as $mood)
              <div class="activity-item">
                <div class="activity-item__icon" style="background: var(--color-primary-soft); color: var(--color-primary); font-size: 1.1rem;">
                  @php $moodEmoji = [1=>'😢',2=>'😞',3=>'😐',4=>'🙂',5=>'😊',6=>'😄',7=>'😄',8=>'😄',9=>'😄',10=>'🤩']; @endphp
                  {{ $moodEmoji[$mood->mood_scale] ?? '😐' }}
                </div>
                <div class="activity-item__content">
                  <p class="activity-item__text">{{ ucfirst($mood->mood) }} &mdash; {{ $mood->mood_scale }}/10</p>
                  <p class="activity-item__time">{{ $mood->mood_date->format('M j, Y') }} &middot; {{ $mood->created_at->diffForHumans() }}</p>
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
        <h4 class="card-header__title">VR Activity</h4>
        <span class="badge badge--secondary">{{ $vrStats['completed_sessions'] }} completed</span>
      </div>
      <div class="card-body">
        <div class="stats-grid" style="grid-template-columns: repeat(2, 1fr); margin-bottom: 0;">
          <div class="kpi-card" style="margin: 0;">
            <div class="kpi-card__value">{{ $vrStats['total_sessions'] }}</div>
            <div class="kpi-card__label">Total</div>
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

    @if($user->vrSessions->count() > 0)
      <div class="card">
        <div class="card-header">
          <h4 class="card-header__title">Recent VR Sessions</h4>
        </div>
        <div class="card-body">
          <div class="activity-list">
            @foreach($user->vrSessions->take(5) as $session)
              <div class="activity-item">
                <div class="activity-item__icon" style="background: var(--color-secondary-soft); color: var(--color-secondary);">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div class="activity-item__content">
                  <p class="activity-item__text">{{ $session->vr_asset_title }}</p>
                  <p class="activity-item__time">
                    {{ $session->session_duration ? round($session->session_duration / 60, 1) . 'm' : 'N/A' }} &middot;
                    {{ $session->session_quality ? $session->session_quality . '/5' : 'N/A' }} &middot;
                    {{ $session->started_at->diffForHumans() }}
                  </p>
                </div>
                <div class="activity-item__action">
                  <span class="badge badge--{{ $session->is_completed ? 'success' : 'warning' }}">{{ $session->is_completed ? 'Done' : 'In Progress' }}</span>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    @endif
  </div>
</div>
@endsection
