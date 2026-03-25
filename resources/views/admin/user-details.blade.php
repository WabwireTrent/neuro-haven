@extends('layouts.app')

@section('title', 'User Details - ' . $user->name)
@section('page', 'admin-user-details')

@section('content')
<div class="container">
    <div class="dashboard-shell">
        <div class="dashboard-app">
            <header class="dashboard-header">
                <div class="dashboard-avatar" aria-hidden="true">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <div class="dashboard-greeting">
                    <h1>{{ $user->name }}</h1>
                    <p class="dashboard-streak">{{ $user->email }} • {{ ucfirst($user->role) }}</p>
                </div>
            </header>

            <section class="dashboard-main">
                <!-- User Stats Overview -->
                <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                    <!-- Mood Stats -->
                    <section class="card dashboard-widget p-6">
                        <div class="dashboard-widget__head">
                            <h2>Mood Tracking</h2>
                            <span class="dashboard-widget__eyebrow">Activity Summary</span>
                        </div>
                        <div class="grid" style="grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-top: 1rem;">
                            <article class="surface p-4" style="text-align: center;">
                                <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Total Moods</p>
                                <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: var(--color-primary);">{{ $moodStats['total_moods'] }}</p>
                            </article>
                            <article class="surface p-4" style="text-align: center;">
                                <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Average Mood</p>
                                <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: var(--color-primary);">{{ round($moodStats['avg_mood'], 1) }}/10</p>
                            </article>
                            <article class="surface p-4" style="text-align: center;">
                                <p class="text-muted" style="margin: 0; font-size: 0.875rem;">This Week</p>
                                <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: var(--color-primary);">{{ $moodStats['week_moods'] }}</p>
                            </article>
                            <article class="surface p-4" style="text-align: center;">
                                <p class="text-muted" style="margin: 0; font-size: 0.875rem;">This Month</p>
                                <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: var(--color-primary);">{{ $moodStats['month_moods'] }}</p>
                            </article>
                        </div>
                    </section>

                    <!-- VR Stats -->
                    <section class="card dashboard-widget p-6">
                        <div class="dashboard-widget__head">
                            <h2>VR Activity</h2>
                            <span class="dashboard-widget__eyebrow">Therapy Sessions</span>
                        </div>
                        <div class="grid" style="grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-top: 1rem;">
                            <article class="surface p-4" style="text-align: center;">
                                <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Total Sessions</p>
                                <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: var(--color-primary);">{{ $vrStats['total_sessions'] }}</p>
                            </article>
                            <article class="surface p-4" style="text-align: center;">
                                <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Completed</p>
                                <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: var(--color-primary);">{{ $vrStats['completed_sessions'] }}</p>
                            </article>
                            <article class="surface p-4" style="text-align: center;">
                                <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Total Time</p>
                                <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: var(--color-primary);">{{ round($vrStats['total_duration'] / 60, 1) }}m</p>
                            </article>
                            <article class="surface p-4" style="text-align: center;">
                                <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Avg Quality</p>
                                <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: var(--color-primary);">{{ round($vrStats['avg_quality'], 1) }}/5</p>
                            </article>
                        </div>
                    </section>
                </div>

                <!-- Recent Moods -->
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-widget__head">
                        <h2>Recent Mood Entries</h2>
                        <span class="dashboard-widget__eyebrow">Last 30 Days</span>
                    </div>
                    @if($user->moods->count() > 0)
                        <div class="stack" style="gap: 1rem; margin-top: 1rem;">
                            @foreach($user->moods as $mood)
                            <article class="surface p-4" style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <h3 style="margin: 0 0 0.25rem; font-size: 1.125rem;">{{ ucfirst($mood->mood) }}</h3>
                                    <p class="text-muted" style="margin: 0; font-size: 0.875rem;">{{ $mood->mood_date->format('M j, Y') }}</p>
                                </div>
                                <div style="text-align: right;">
                                    <div style="font-size: 1.5rem; font-weight: 700; color: var(--color-primary);">{{ $mood->mood_scale }}/10</div>
                                    <p class="text-muted" style="margin: 0.25rem 0 0; font-size: 0.75rem;">{{ $mood->created_at->diffForHumans() }}</p>
                                </div>
                            </article>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted" style="margin-top: 1rem;">No mood entries recorded yet.</p>
                    @endif
                </section>

                <!-- Recent VR Sessions -->
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-widget__head">
                        <h2>Recent VR Sessions</h2>
                        <span class="dashboard-widget__eyebrow">Therapy Activity</span>
                    </div>
                    @if($user->vrSessions->count() > 0)
                        <div class="stack" style="gap: 1rem; margin-top: 1rem;">
                            @foreach($user->vrSessions as $session)
                            <article class="surface p-4">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                                    <h3 style="margin: 0; font-size: 1.125rem;">{{ $session->vr_asset_title }}</h3>
                                    <span class="badge">{{ $session->is_completed ? 'Completed' : 'In Progress' }}</span>
                                </div>
                                <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-top: 0.5rem;">
                                    <div>
                                        <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Duration</p>
                                        <p style="margin: 0.25rem 0 0; font-weight: 600;">{{ $session->session_duration ? round($session->session_duration / 60, 1) . 'm' : 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Quality</p>
                                        <p style="margin: 0.25rem 0 0; font-weight: 600;">{{ $session->session_quality ? $session->session_quality . '/5' : 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Started</p>
                                        <p style="margin: 0.25rem 0 0; font-weight: 600;">{{ $session->started_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                @if($session->notes)
                                    <div style="margin-top: 0.5rem; padding: 0.5rem; background: var(--color-surface-muted); border-radius: var(--radius-sm);">
                                        <p class="text-muted" style="margin: 0; font-size: 0.875rem; font-style: italic;">"{{ $session->notes }}"</p>
                                    </div>
                                @endif
                            </article>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted" style="margin-top: 1rem;">No VR sessions recorded yet.</p>
                    @endif
                </section>

                <!-- Quick Actions -->
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-section-head">
                        <h2>Actions</h2>
                        <div class="cluster">
                            <a href="{{ route('admin.users') }}" class="btn btn-secondary">Back to Users</a>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Admin Dashboard</a>
                        </div>
                    </div>
                </section>
            </section>
        </div>
    </div>
</div>

<style>
    .mb-4 { margin-bottom: var(--space-4); }
    .p-6 { padding: var(--space-6); }
    .p-4 { padding: var(--space-4); }
    .grid {
        display: grid;
    }
    .stack {
        display: flex;
        flex-direction: column;
    }
    .cluster {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    .dashboard-section-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }
    .badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        background: var(--color-surface-muted);
        color: var(--color-text-muted);
        border-radius: var(--radius-sm);
        font-size: 0.75rem;
        font-weight: 500;
    }
</style>
@endsection