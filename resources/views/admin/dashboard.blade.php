@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page', 'admin')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>Admin Dashboard</h1>
    <p>System overview and management</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('admin.users') }}" class="btn btn-secondary">Manage Users</a>
    <a href="{{ route('admin.vr-assets.list') }}" class="btn btn-secondary">VR Assets</a>
  </div>
</div>

{{-- Stats Overview --}}
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-card__top">
      <div>
        <p class="stat-card__label">Total Users</p>
        <p class="stat-card__value">{{ $analytics['total_users'] }}</p>
      </div>
      <div class="stat-card__icon stat-card__icon--primary">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
      </div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-card__top">
      <div>
        <p class="stat-card__label">Total Moods</p>
        <p class="stat-card__value">{{ $analytics['total_moods'] }}</p>
      </div>
      <div class="stat-card__icon stat-card__icon--accent">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/></svg>
      </div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-card__top">
      <div>
        <p class="stat-card__label">VR Sessions</p>
        <p class="stat-card__value">{{ $analytics['total_vr_sessions'] }}</p>
      </div>
      <div class="stat-card__icon stat-card__icon--secondary">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
      </div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-card__top">
      <div>
        <p class="stat-card__label">Active This Week</p>
        <p class="stat-card__value">{{ $analytics['active_users_week'] }}</p>
      </div>
      <div class="stat-card__icon stat-card__icon--success">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
      </div>
    </div>
  </div>
</div>

{{-- Main Grid --}}
<div class="widget-grid">
  {{-- Left Column --}}
  <div>
    {{-- User Distribution --}}
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">User Distribution</h4>
      </div>
      <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;">
          @foreach(['patient' => 'Patients', 'therapist' => 'Therapists', 'admin' => 'Admins'] as $role => $label)
          <div style="text-align: center; padding: 1rem; background: var(--color-surface-muted); border-radius: var(--radius-lg);">
            <p style="font-size: 1.75rem; font-weight: 800; margin: 0; color: var(--color-primary);">{{ $analytics['user_roles'][$role] ?? 0 }}</p>
            <p class="text-muted" style="font-size: 0.75rem; margin: 0.25rem 0 0; font-weight: 600;">{{ $label }}</p>
          </div>
          @endforeach
        </div>
      </div>
    </div>

    {{-- Recent Users --}}
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">Recent Users</h4>
        <a href="{{ route('admin.users') }}" class="section-header__link">View All</a>
      </div>
      <div class="card-body">
        @if($analytics['recent_users']->count() > 0)
          <div class="activity-list">
            @foreach($analytics['recent_users'] as $user)
              <div class="activity-item">
                <div class="topbar__avatar" style="width: 36px; height: 36px; font-size: 0.75rem;">
                  {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="activity-item__content">
                  <p class="activity-item__text">{{ $user->name }}</p>
                  <p class="activity-item__time">{{ $user->email }} • {{ $user->created_at->diffForHumans() }}</p>
                </div>
                <div class="activity-item__action">
                  <span class="badge badge--primary" style="text-transform: capitalize;">{{ $user->role }}</span>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="empty-state" style="padding: 1.5rem;">
            <p class="text-muted">No users registered yet.</p>
          </div>
        @endif
      </div>
    </div>
  </div>

  {{-- Right Column --}}
  <div>
    {{-- Popular VR Assets --}}
    <div class="card" style="margin-bottom: 1.25rem;">
      <div class="card-header">
        <h4 class="card-header__title">Popular VR Experiences</h4>
      </div>
      <div class="card-body">
        @if($analytics['popular_vr_assets']->count() > 0)
          <div class="activity-list">
            @foreach($analytics['popular_vr_assets'] as $asset)
              <div class="activity-item">
                <div class="activity-item__icon" style="background: var(--color-secondary-soft); color: var(--color-secondary);">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 10c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2v-4c0-1.1-.9-2-2-2"/></svg>
                </div>
                <div class="activity-item__content">
                  <p class="activity-item__text">{{ $asset->vr_asset_title }}</p>
                  <p class="activity-item__time">{{ $asset->sessions_count }} total sessions</p>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="empty-state" style="padding: 1.5rem;">
            <p class="text-muted">No VR sessions recorded yet.</p>
          </div>
        @endif
      </div>
    </div>

    {{-- Recent VR Sessions --}}
    <div class="card">
      <div class="card-header">
        <h4 class="card-header__title">Recent VR Sessions</h4>
      </div>
      <div class="card-body">
        @if($analytics['recent_vr_sessions']->count() > 0)
          <div class="activity-list">
            @foreach($analytics['recent_vr_sessions'] as $session)
              <div class="activity-item">
                <div class="activity-item__icon" style="background: var(--color-primary-soft); color: var(--color-primary);">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div class="activity-item__content">
                  <p class="activity-item__text">{{ $session->vr_asset_title }}</p>
                  <p class="activity-item__time">{{ $session->user->name }} • {{ $session->started_at->diffForHumans() }}</p>
                </div>
                <div class="activity-item__action">
                  <span class="badge badge--{{ $session->is_completed ? 'success' : 'warning' }}">{{ $session->is_completed ? 'Completed' : 'In Progress' }}</span>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="empty-state" style="padding: 1.5rem;">
            <p class="text-muted">No VR sessions recorded yet.</p>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
