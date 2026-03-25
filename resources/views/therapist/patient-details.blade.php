@extends('layouts.app')

@section('title', $patient->name . ' - Patient Details')
@section('page', 'patient-details')

@section('content')
<div class="container">
    <div class="dashboard-shell">
        <div class="dashboard-app">
            <header class="dashboard-header">
                <div class="dashboard-avatar" aria-hidden="true">👤</div>
                <div class="dashboard-greeting">
                    <h1>{{ $patient->name }}</h1>
                    <p class="dashboard-streak">Patient therapy progress and analytics</p>
                </div>
            </header>

            <section class="dashboard-main">
                <!-- Patient Info -->
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-widget__head">
                        <h2>Patient Information</h2>
                    </div>
                    <div class="grid" style="grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-top: 1rem;">
                        <article class="surface p-4">
                            <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Email</p>
                            <p style="margin: 0.5rem 0 0;">{{ $patient->email }}</p>
                        </article>
                        <article class="surface p-4">
                            <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Member Since</p>
                            <p style="margin: 0.5rem 0 0;">{{ $patient->created_at->format('M j, Y') }}</p>
                        </article>
                    </div>
                </section>

                <!-- Mood Statistics -->
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-widget__head">
                        <h2>Mood Analytics</h2>
                        <span class="dashboard-widget__eyebrow">Patient's emotional patterns</span>
                    </div>
                    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-top: 1rem;">
                        <article class="surface p-4" style="text-align: center;">
                            <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Total Moods Logged</p>
                            <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: var(--color-primary);">{{ $moodStats['total_moods'] }}</p>
                        </article>
                        <article class="surface p-4" style="text-align: center;">
                            <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Average Mood</p>
                            <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: var(--color-primary);">{{ $moodStats['avg_mood'] }}/10</p>
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

                <!-- VR Session Statistics -->
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-widget__head">
                        <h2>VR Therapy Sessions</h2>
                        <span class="dashboard-widget__eyebrow">Session performance</span>
                    </div>
                    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-top: 1rem;">
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
                            <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: var(--color-primary);">{{ round($vrStats['total_duration'] / 60) }}m</p>
                        </article>
                        <article class="surface p-4" style="text-align: center;">
                            <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Avg Quality</p>
                            <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: var(--color-primary);">{{ $vrStats['avg_quality'] }}/5</p>
                        </article>
                    </div>
                </section>

                <!-- Mood Trend -->
                @if($moodTrend->count() > 0)
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-widget__head">
                        <h2>Mood Trend (Last 14 Days)</h2>
                        <span class="dashboard-widget__eyebrow">Emotional progression</span>
                    </div>
                    <div style="margin-top: 1rem; padding: 1rem; background: var(--color-surface-muted); border-radius: var(--radius-md); overflow-x: auto;">
                        <div style="display: flex; gap: 1rem; align-items: flex-end; min-height: 150px; padding: 1rem 0;">
                            @foreach($moodTrend as $entry)
                                <div style="flex: 1; text-align: center; display: flex; flex-direction: column; justify-content: flex-end;">
                                    <div style="background: var(--color-primary); height: {{ ($entry->mood_scale / 10) * 100 }}px; border-radius: var(--radius-sm); margin-bottom: 0.5rem;"></div>
                                    <p style="margin: 0; font-size: 0.75rem; font-weight: 600;">{{ $entry->mood_date->format('M j') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
                @endif

                <!-- Recent Moods -->
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-widget__head">
                        <h2>Mood History</h2>
                        <span class="dashboard-widget__eyebrow">Recent entries</span>
                    </div>
                    @if($patient->moods->count() > 0)
                        <div class="stack" style="gap: 1rem; margin-top: 1rem;">
                            @foreach($patient->moods as $mood)
                            <article class="surface p-4">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <p class="text-muted" style="margin: 0; font-size: 0.875rem;">{{ $mood->mood_date->format('M j, Y g:i A') }}</p>
                                    </div>
                                    <div style="text-align: right;">
                                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">
                                            @php
                                                $moodEmoji = [1 => '😢', 2 => '😞', 3 => '😐', 4 => '🙂', 5 => '😊', 6 => '😄', 7 => '😄', 8 => '😄', 9 => '😄', 10 => '🤩'];
                                            @endphp
                                            {{ $moodEmoji[$mood->mood_scale] ?? '😐' }}
                                        </div>
                                        <p style="margin: 0; font-weight: 700; font-size: 1.25rem;">{{ $mood->mood_scale }}/10</p>
                                    </div>
                                </div>
                            </article>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted" style="margin-top: 1rem;">No mood entries yet.</p>
                    @endif
                </section>

                <!-- Recent VR Sessions -->
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-widget__head">
                        <h2>VR Sessions</h2>
                        <span class="dashboard-widget__eyebrow">Therapy history</span>
                    </div>
                    @if($patient->vrSessions->count() > 0)
                        <div class="stack" style="gap: 1rem; margin-top: 1rem;">
                            @foreach($patient->vrSessions as $session)
                            <article class="surface p-4">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                                    <h3 style="margin: 0; font-size: 1.125rem;">{{ $session->vr_asset_title }}</h3>
                                    <span class="badge">{{ $session->is_completed ? 'Completed' : 'In Progress' }}</span>
                                </div>
                                <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 1rem;">
                                    <div>
                                        <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Duration</p>
                                        <p style="margin: 0.25rem 0 0; font-weight: 600;">{{ $session->session_duration ? round($session->session_duration / 60, 1) . 'm' : 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Quality</p>
                                        <p style="margin: 0.25rem 0 0; font-weight: 600;">{{ $session->session_quality ? $session->session_quality . '/5' : 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Date</p>
                                        <p style="margin: 0.25rem 0 0; font-weight: 600;">{{ $session->started_at->format('M j') }}</p>
                                    </div>
                                </div>
                            </article>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted" style="margin-top: 1rem;">No VR sessions yet.</p>
                    @endif
                </section>

                <!-- Back Button -->
                <section class="card dashboard-widget p-6 mb-4">
                    <div style="text-align: center;">
                        <a href="{{ route('therapist.patients') }}" class="btn btn-secondary">Back to Patients</a>
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
    .text-muted {
        color: var(--color-text-muted);
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
