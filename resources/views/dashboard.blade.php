@extends('layouts.app')

@section('title', 'Dashboard')
@section('page', 'dashboard')

@section('content')
<div class="container">
    <div class="dashboard-shell">
        <div class="dashboard-app">
            <header class="dashboard-header">
                <div class="dashboard-avatar" aria-hidden="true">{{ strtoupper(substr($user['name'], 0, 1)) }}</div>
                <div class="dashboard-greeting">
                    <h1 data-dashboard-greeting>Hi, {{ $user['name'] }}</h1>
                    <p class="dashboard-streak">🔥 {{ $user['streak'] }} Day Streak</p>
                </div>
                <div class="notif-anchor">
                    <button class="dashboard-icon-btn" type="button" aria-label="Notifications" aria-expanded="false" data-notif-toggle>
                        <svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                        <span class="notif-badge" data-notif-badge aria-hidden="true"></span>
                    </button>
                </div>
            </header>

            <section class="dashboard-main">
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-widget__head">
                        <h2>Today's Check-in</h2>
                        <span class="dashboard-widget__eyebrow">Quick Action</span>
                    </div>
                    <p class="dashboard-mood-prompt">How are you feeling right now?</p>
                    @if ($user['today_mood'])
                        <div class="surface p-4" style="background: rgba(29, 159, 118, 0.05); border-left: 4px solid var(--color-primary);">
                            <p style="margin: 0; font-weight: 600;">{{ ucfirst($user['today_mood']->mood) }}</p>
                            <p class="text-muted" style="margin: 0.25rem 0 0;">{{ $user['today_mood']->mood_scale }}/10</p>
                        </div>
                    @else
                        <a href="{{ route('mood.tracker') }}" class="btn btn-primary" style="display: inline-block; margin-top: 0.5rem;">Log Today's Mood</a>
                    @endif
                </section>

                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-widget__head">
                        <h2>This Week's Insights</h2>
                        <span class="dashboard-widget__eyebrow">7 Days</span>
                    </div>
                    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 1rem;">
                        <article class="surface p-4" style="text-align: center;">
                            <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Wellness Score</p>
                            <p style="margin: 0.5rem 0 0; font-size: 1.75rem; font-weight: 700; color: var(--color-primary);">{{ $user['mood_score'] }}%</p>
                        </article>
                        <article class="surface p-4" style="text-align: center;">
                            <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Logged Days</p>
                            <p style="margin: 0.5rem 0 0; font-size: 1.75rem; font-weight: 700; color: var(--color-primary);">{{ $user['week_moods']->count() }}/7</p>
                        </article>
                        <article class="surface p-4" style="text-align: center;">
                            <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Consistency</p>
                            <p style="margin: 0.5rem 0 0; font-size: 1.75rem; font-weight: 700; color: var(--color-primary);">{{ $user['week_percent'] }}%</p>
                        </article>
                    </div>
                </section>

                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-widget__head">
                        <h2>Weekly Mood Trend</h2>
                    </div>
                    @if ($user['week_moods']->count() > 0)
                        <div class="stack" style="gap: 0.75rem; margin-top: 1rem;">
                            @for ($i = 0; $i < 7; $i++)
                                @php
                                    $date = now()->subDays(6 - $i)->toDateString();
                                    $moodForDay = $user['week_moods']->firstWhere('mood_date', $date);
                                @endphp
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <span style="font-size: 0.875rem; color: var(--color-text-muted); min-width: 50px;">
                                        {{ \Carbon\Carbon::parse($date)->format('M d') }}
                                    </span>
                                    @if ($moodForDay)
                                        <div style="flex: 1; height: 32px; background: rgba(29, 159, 118, {{ $moodForDay->mood_scale / 10 }}); border-radius: var(--radius-md); display: flex; align-items: center; padding: 0 0.75rem;">
                                            <span style="font-weight: 600; font-size: 0.875rem;">{{ $moodForDay->mood_scale }}/10</span>
                                        </div>
                                    @else
                                        <div style="flex: 1; height: 32px; background: var(--color-surface-muted); border-radius: var(--radius-md); display: flex; align-items: center; padding: 0 0.75rem;">
                                            <span class="text-muted" style="font-size: 0.875rem;">—</span>
                                        </div>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    @else
                        <p class="text-muted">Start logging your mood to see trends.</p>
                    @endif
                </section>

                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-widget__head">
                        <h2>VR Status</h2>
                        <span class="dashboard-widget__eyebrow">Virtual Reality</span>
                    </div>
                    <div id="vr-status" class="surface p-4" style="text-align: center;">
                        <div class="vr-status-indicator">
                            <div class="vr-icon">🎧</div>
                            <p class="vr-status-text">Checking VR connection...</p>
                        </div>
                    </div>
                </section>

                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-section-head">
                        <h2>Quick Actions</h2>
                        <div class="cluster">
                            <a href="{{ route('mood.tracker') }}" class="btn btn-secondary">Log Mood</a>
                            <a href="{{ route('therapy.sessions') }}" class="btn btn-secondary">Sessions</a>
                            <a href="{{ route('progress.tracking') }}" class="btn btn-secondary">Progress</a>
                            <a href="{{ route('vr.assets') }}" class="btn btn-secondary">VR Assets</a>
                            <a href="{{ route('vr.analytics') }}" class="btn btn-secondary">VR Analytics</a>
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
    .dashboard-section-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
    }
    .cluster {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    .grid {
        display: grid;
    }
    .stack {
        display: flex;
        flex-direction: column;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', async function() {
    const vrStatusDiv = document.getElementById('vr-status');
    const vrIcon = vrStatusDiv.querySelector('.vr-icon');
    const vrText = vrStatusDiv.querySelector('.vr-status-text');

    if (!navigator.xr) {
        vrIcon.textContent = '❌';
        vrText.textContent = 'VR not supported';
        vrStatusDiv.style.background = 'rgba(239, 68, 68, 0.05)';
        vrStatusDiv.style.borderLeft = '4px solid #ef4444';
        return;
    }

    try {
        const supported = await navigator.xr.isSessionSupported('immersive-vr');
        if (supported) {
            vrIcon.textContent = '✅';
            vrText.textContent = 'VR Ready';
            vrStatusDiv.style.background = 'rgba(29, 159, 118, 0.05)';
            vrStatusDiv.style.borderLeft = '4px solid var(--color-primary)';
        } else {
            vrIcon.textContent = '⚠️';
            vrText.textContent = 'VR headset not detected';
            vrStatusDiv.style.background = 'rgba(245, 158, 11, 0.05)';
            vrStatusDiv.style.borderLeft = '4px solid #f59e0b';
        }
    } catch (error) {
        console.error('Error checking VR support:', error);
        vrIcon.textContent = '❓';
        vrText.textContent = 'Unable to check VR status';
        vrStatusDiv.style.background = 'rgba(156, 163, 175, 0.05)';
        vrStatusDiv.style.borderLeft = '4px solid #9ca3af';
    }
});
</script>
@endsection
