@extends('layouts.app')

@section('title', 'Therapist Dashboard')
@section('page', 'therapist-dashboard')

@section('content')
<div class="container">
    <div class="dashboard-shell">
        <div class="dashboard-app">
            <header class="dashboard-header">
                <div class="dashboard-avatar" aria-hidden="true">👨‍⚕️</div>
                <div class="dashboard-greeting">
                    <h1>Therapist Dashboard</h1>
                    <p class="dashboard-streak">Monitor your patients' progress</p>
                </div>
            </header>

            <section class="dashboard-main">
                <!-- Key Metrics -->
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-widget__head">
                        <h2>Overview</h2>
                        <span class="dashboard-widget__eyebrow">Patient Analytics</span>
                    </div>
                    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-top: 1rem;">
                        <article class="surface p-4" style="text-align: center;">
                            <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Total Patients</p>
                            <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: var(--color-primary);">{{ $analytics['total_patients'] }}</p>
                        </article>
                        <article class="surface p-4" style="text-align: center;">
                            <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Active This Week</p>
                            <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: var(--color-primary);">{{ $analytics['active_patients_week'] }}</p>
                        </article>
                        <article class="surface p-4" style="text-align: center;">
                            <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Avg Mood Today</p>
                            <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: var(--color-primary);">{{ $analytics['avg_mood_today'] }}/10</p>
                        </article>
                        <article class="surface p-4" style="text-align: center;">
                            <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Avg Mood Week</p>
                            <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: var(--color-primary);">{{ $analytics['avg_mood_week'] }}/10</p>
                        </article>
                    </div>
                </section>

                <!-- Popular VR Assets -->
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-widget__head">
                        <h2>Most Used VR Assets</h2>
                        <span class="dashboard-widget__eyebrow">By Your Patients</span>
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
                                    @if($asset->avg_quality)
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <span style="font-size: 1.25rem;">⭐</span>
                                        <span style="font-weight: 600;">{{ round($asset->avg_quality, 1) }}/5</span>
                                    </div>
                                    @endif
                                </div>
                            </article>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted" style="margin-top: 1rem;">No VR session data available yet.</p>
                    @endif
                </section>

                <!-- Recent Patient Activity -->
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-widget__head">
                        <h2>Recent Patient Activity</h2>
                        <span class="dashboard-widget__eyebrow">Latest Moods & Sessions</span>
                    </div>
                    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-top: 1rem;">
                        <!-- Recent Moods -->
                        <div>
                            <h3 style="margin: 0 0 1rem; font-size: 1.125rem;">Latest Mood Entries</h3>
                            @if($analytics['recent_moods']->count() > 0)
                                <div class="stack" style="gap: 0.75rem;">
                                    @foreach($analytics['recent_moods']->take(5) as $mood)
                                    <article class="surface p-3" style="font-size: 0.875rem;">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <div>
                                                <strong>{{ $mood->user->name }}</strong>
                                                <p class="text-muted" style="margin: 0.25rem 0 0;">{{ $mood->mood_date->format('M j, g:i A') }}</p>
                                            </div>
                                            <div style="font-size: 1.5rem;">
                                                @php
                                                    $moodEmoji = [1 => '😢', 2 => '😞', 3 => '😐', 4 => '🙂', 5 => '😊', 6 => '😄', 7 => '😄', 8 => '😄', 9 => '😄', 10 => '🤩'];
                                                @endphp
                                                {{ $moodEmoji[$mood->mood_scale] ?? '😐' }}
                                            </div>
                                        </div>
                                        <p style="margin: 0.5rem 0 0; font-weight: 600;">{{ $mood->mood_scale }}/10</p>
                                    </article>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">No mood entries yet.</p>
                            @endif
                        </div>

                        <!-- Recent VR Sessions -->
                        <div>
                            <h3 style="margin: 0 0 1rem; font-size: 1.125rem;">Latest VR Sessions</h3>
                            @if($analytics['recent_vr_sessions']->count() > 0)
                                <div class="stack" style="gap: 0.75rem;">
                                    @foreach($analytics['recent_vr_sessions']->take(5) as $session)
                                    <article class="surface p-3" style="font-size: 0.875rem;">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <div>
                                                <strong>{{ $session->user->name }}</strong>
                                                <p class="text-muted" style="margin: 0.25rem 0 0;">{{ $session->vr_asset_title }}</p>
                                            </div>
                                            <span class="badge">{{ $session->is_completed ? 'Done' : 'Active' }}</span>
                                        </div>
                                    </article>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">No VR sessions yet.</p>
                            @endif
                        </div>
                    </div>
                </section>

                <!-- Patients List -->
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-widget__head">
                        <h2>Patients</h2>
                        <a href="{{ route('therapist.patients') }}" class="btn btn-secondary" style="font-size: 0.875rem;">View All</a>
                    </div>
                    @if($patients->count() > 0)
                        <div class="grid" style="grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
                            @foreach($patients->take(6) as $patient)
                            <a href="{{ route('therapist.patient.details', $patient) }}" class="surface p-4" style="text-decoration: none; color: inherit; cursor: pointer; transition: all 180ms ease;">
                                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                        {{ strtoupper(substr($patient->name, 0, 1)) }}
                                    </div>
                                    <div style="min-width: 0;">
                                        <h3 style="margin: 0; font-size: 1rem; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $patient->name }}</h3>
                                        <p style="margin: 0.25rem 0 0; font-size: 0.75rem; color: var(--color-text-muted);">{{ $patient->email }}</p>
                                    </div>
                                </div>
                                <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 0.75rem; font-size: 0.875rem;">
                                    <div>
                                        <p class="text-muted" style="margin: 0; font-size: 0.75rem;">Moods</p>
                                        <p style="margin: 0; font-weight: 600;">{{ $patient->moods_count }}</p>
                                    </div>
                                    <div>
                                        <p class="text-muted" style="margin: 0; font-size: 0.75rem;">Sessions</p>
                                        <p style="margin: 0; font-weight: 600;">{{ $patient->vr_sessions_count }}</p>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted" style="margin-top: 1rem;">No patients assigned yet.</p>
                    @endif
                </section>

                <!-- Quick Actions -->
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-section-head">
                        <h2>Actions</h2>
                        <div class="cluster">
                            <a href="{{ route('therapist.patients') }}" class="btn btn-secondary">View All Patients</a>
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
    .p-3 { padding: var(--space-3); }
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
