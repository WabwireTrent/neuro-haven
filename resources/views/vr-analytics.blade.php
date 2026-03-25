@extends('layouts.app')

@section('title', 'VR Analytics')
@section('page', 'vr-analytics')

@section('content')
<div class="container">
    <div class="dashboard-shell">
        <div class="dashboard-app">
            <header class="dashboard-header">
                <div class="dashboard-avatar" aria-hidden="true">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="dashboard-greeting">
                    <h1>VR Analytics</h1>
                    <p class="dashboard-streak">Your VR therapy insights</p>
                </div>
            </header>

            <section class="dashboard-main">
                <!-- Overview Stats -->
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-widget__head">
                        <h2>VR Session Overview</h2>
                        <span class="dashboard-widget__eyebrow">Your Progress</span>
                    </div>
                    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-top: 1rem;">
                        <article class="surface p-4" style="text-align: center;">
                            <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Total Sessions</p>
                            <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: var(--color-primary);">{{ $analytics['total_sessions'] }}</p>
                        </article>
                        <article class="surface p-4" style="text-align: center;">
                            <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Completed</p>
                            <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: var(--color-primary);">{{ $analytics['completed_sessions'] }}</p>
                        </article>
                        <article class="surface p-4" style="text-align: center;">
                            <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Total Time</p>
                            <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: var(--color-primary);">{{ $analytics['total_duration_minutes'] }}m</p>
                        </article>
                        <article class="surface p-4" style="text-align: center;">
                            <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Avg Quality</p>
                            <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: var(--color-primary);">{{ $analytics['avg_session_quality'] }}/5</p>
                        </article>
                    </div>
                </section>

                <!-- Weekly/Monthly Stats -->
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-widget__head">
                        <h2>Activity Summary</h2>
                        <span class="dashboard-widget__eyebrow">Recent Usage</span>
                    </div>
                    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
                        <article class="surface p-4">
                            <h3 style="margin: 0 0 1rem; font-size: 1.125rem;">This Week</h3>
                            <p style="margin: 0; font-size: 2rem; font-weight: 700; color: var(--color-primary);">{{ $analytics['week_sessions'] }}</p>
                            <p class="text-muted" style="margin: 0.25rem 0 0;">sessions completed</p>
                        </article>
                        <article class="surface p-4">
                            <h3 style="margin: 0 0 1rem; font-size: 1.125rem;">This Month</h3>
                            <p style="margin: 0; font-size: 2rem; font-weight: 700; color: var(--color-primary);">{{ $analytics['month_sessions'] }}</p>
                            <p class="text-muted" style="margin: 0.25rem 0 0;">sessions completed</p>
                        </article>
                        <article class="surface p-4">
                            <h3 style="margin: 0 0 1rem; font-size: 1.125rem;">Mood Improvement</h3>
                            <p style="margin: 0; font-size: 2rem; font-weight: 700; color: {{ $analytics['avg_mood_improvement'] >= 0 ? 'var(--color-primary)' : '#ef4444' }};">
                                {{ $analytics['avg_mood_improvement'] >= 0 ? '+' : '' }}{{ $analytics['avg_mood_improvement'] }}
                            </p>
                            <p class="text-muted" style="margin: 0.25rem 0 0;">average change ({{ $analytics['sessions_with_mood_data'] }} sessions)</p>
                        </article>
                    </div>
                </section>

                <!-- Popular VR Assets -->
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-widget__head">
                        <h2>Most Used VR Experiences</h2>
                        <span class="dashboard-widget__eyebrow">Your Preferences</span>
                    </div>
                    @if($analytics['popular_assets']->count() > 0)
                        <div class="stack" style="gap: 1rem; margin-top: 1rem;">
                            @foreach($analytics['popular_assets'] as $asset)
                            <article class="surface p-4" style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <h3 style="margin: 0 0 0.25rem; font-size: 1.125rem;">{{ $asset->vr_asset_title }}</h3>
                                    <p class="text-muted" style="margin: 0;">{{ $asset->sessions_count }} sessions</p>
                                </div>
                                <div style="text-align: right;">
                                    <div style="font-size: 1.25rem; color: var(--color-primary);">
                                        {'★'.repeat(round($asset->avg_quality ?? 0))}{'☆'.repeat(5 - round($asset->avg_quality ?? 0))}
                                    </div>
                                    <p class="text-muted" style="margin: 0; font-size: 0.875rem;">{{ round($asset->avg_quality ?? 0, 1) }}/5</p>
                                </div>
                            </article>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted" style="margin-top: 1rem;">Complete some VR sessions to see your most used experiences here.</p>
                    @endif
                </section>

                <!-- Quick Actions -->
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-section-head">
                        <h2>VR Actions</h2>
                        <div class="cluster">
                            <a href="{{ route('vr.assets') }}" class="btn btn-secondary">Start VR Session</a>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
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
</style>
@endsection