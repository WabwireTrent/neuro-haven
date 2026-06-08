@extends('layouts.app')

@section('title', 'VR Analytics')
@section('page', 'analytics')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>VR Analytics</h1>
    <p>Your VR therapy insights and usage statistics</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('vr.assets') }}" class="btn btn-primary">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Start Session
    </a>
  </div>
</div>

{{-- Overview Stats --}}
<div class="stats-grid" style="grid-template-columns: repeat(4, 1fr);">
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Total Sessions</p>
    <p class="stat-card__value">{{ $analytics['total_sessions'] }}</p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Completed</p>
    <p class="stat-card__value">{{ $analytics['completed_sessions'] }}</p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Total Time</p>
    <p class="stat-card__value">{{ $analytics['total_duration_minutes'] }}m</p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Avg Quality</p>
    <p class="stat-card__value">{{ $analytics['avg_session_quality'] }}/5</p>
  </div>
</div>

<div class="widget-grid">
  <div>
    {{-- Activity Summary --}}
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">Activity Summary</h4>
      </div>
      <div class="card-body">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
          <div style="padding: 1rem; background: var(--color-surface-muted); border-radius: var(--radius-lg); text-align: center;">
            <p class="text-muted" style="font-size: 0.75rem; font-weight: 600; margin: 0;">This Week</p>
            <p style="font-size: 1.75rem; font-weight: 800; margin: 0.5rem 0 0; color: var(--color-primary);">{{ $analytics['week_sessions'] }}</p>
            <p class="text-muted" style="font-size: 0.75rem; margin: 0.25rem 0 0;">sessions</p>
          </div>
          <div style="padding: 1rem; background: var(--color-surface-muted); border-radius: var(--radius-lg); text-align: center;">
            <p class="text-muted" style="font-size: 0.75rem; font-weight: 600; margin: 0;">This Month</p>
            <p style="font-size: 1.75rem; font-weight: 800; margin: 0.5rem 0 0; color: var(--color-primary);">{{ $analytics['month_sessions'] }}</p>
            <p class="text-muted" style="font-size: 0.75rem; margin: 0.25rem 0 0;">sessions</p>
          </div>
          <div style="padding: 1rem; background: var(--color-surface-muted); border-radius: var(--radius-lg); text-align: center; grid-column: 1 / -1;">
            <p class="text-muted" style="font-size: 0.75rem; font-weight: 600; margin: 0;">Mood Improvement</p>
            <p style="font-size: 1.75rem; font-weight: 800; margin: 0.5rem 0 0; color: {{ $analytics['avg_mood_improvement'] >= 0 ? 'var(--color-success)' : 'var(--color-danger)' }};">
              {{ $analytics['avg_mood_improvement'] >= 0 ? '+' : '' }}{{ $analytics['avg_mood_improvement'] }}
            </p>
            <p class="text-muted" style="font-size: 0.75rem; margin: 0.25rem 0 0;">average change ({{ $analytics['sessions_with_mood_data'] }} sessions)</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div>
    {{-- Most Used VR Experiences --}}
    <div class="card">
      <div class="card-header">
        <h4 class="card-header__title">Most Used Experiences</h4>
      </div>
      <div class="card-body">
        @if($analytics['popular_assets']->count() > 0)
          <div class="activity-list">
            @foreach($analytics['popular_assets'] as $asset)
              <div class="activity-item">
                <div class="activity-item__icon" style="background: var(--color-secondary-soft); color: var(--color-secondary);">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 10c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c0-1.1-.9-2-2-2"/></svg>
                </div>
                <div class="activity-item__content">
                  <p class="activity-item__text">{{ $asset->vr_asset_title }}</p>
                  <p class="activity-item__time">{{ $asset->sessions_count }} sessions</p>
                </div>
                @if($asset->avg_quality)
                  <div style="text-align: right;">
                    <span style="font-size: 0.85rem; font-weight: 700;">{{ round($asset->avg_quality, 1) }}/5</span>
                  </div>
                @endif
              </div>
            @endforeach
          </div>
        @else
          <div class="empty-state" style="padding: 1.5rem;">
            <p class="text-muted">Complete some VR sessions to see your most used experiences here.</p>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
