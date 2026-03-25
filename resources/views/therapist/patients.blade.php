@extends('layouts.app')

@section('title', 'My Patients')
@section('page', 'therapist-patients')

@section('content')
<div class="container">
    <div class="dashboard-shell">
        <div class="dashboard-app">
            <header class="dashboard-header">
                <div class="dashboard-avatar" aria-hidden="true">👥</div>
                <div class="dashboard-greeting">
                    <h1>My Patients</h1>
                    <p class="dashboard-streak">Monitor and manage your patients' therapy progress</p>
                </div>
            </header>

            <section class="dashboard-main">
                <!-- Patients Grid -->
                @if($patients->count() > 0)
                    <section class="card dashboard-widget p-6 mb-4">
                        <div class="grid" style="grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem;">
                            @foreach($patients as $patient)
                            <a href="{{ route('therapist.patient.details', $patient) }}" class="surface p-6" style="text-decoration: none; color: inherit; cursor: pointer; transition: all 180ms ease; display: flex; flex-direction: column;">
                                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                                    <div style="width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, var(--color-primary), var(--color-secondary)); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1.5rem;">
                                        {{ strtoupper(substr($patient->name, 0, 1)) }}
                                    </div>
                                    <div style="flex: 1; min-width: 0;">
                                        <h3 style="margin: 0 0 0.25rem; font-size: 1.125rem; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $patient->name }}</h3>
                                        <p style="margin: 0; font-size: 0.875rem; color: var(--color-text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $patient->email }}</p>
                                    </div>
                                </div>

                                <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                                    <div style="padding: 1rem; background: var(--color-surface-muted); border-radius: var(--radius-md); text-align: center;">
                                        <p class="text-muted" style="margin: 0; font-size: 0.75rem;">Total Moods Logged</p>
                                        <p style="margin: 0.5rem 0 0; font-size: 1.5rem; font-weight: 700; color: var(--color-primary);">{{ $patient->moods_count }}</p>
                                    </div>
                                    <div style="padding: 1rem; background: var(--color-surface-muted); border-radius: var(--radius-md); text-align: center;">
                                        <p class="text-muted" style="margin: 0; font-size: 0.75rem;">VR Sessions</p>
                                        <p style="margin: 0.5rem 0 0; font-size: 1.5rem; font-weight: 700; color: var(--color-primary);">{{ $patient->vr_sessions_count }}</p>
                                    </div>
                                </div>

                                @if($patient->moods->count() > 0)
                                    <div style="padding: 1rem; background: rgba(29, 159, 118, 0.05); border-radius: var(--radius-md); flex: 1; display: flex; flex-direction: column; justify-content: flex-end;">
                                        <p class="text-muted" style="margin: 0 0 0.5rem; font-size: 0.75rem;">Latest Mood</p>
                                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                                            <span style="font-size: 2rem;">
                                                @php
                                                    $latestMood = $patient->moods->first();
                                                    $moodEmoji = [1 => '😢', 2 => '😞', 3 => '😐', 4 => '🙂', 5 => '😊', 6 => '😄', 7 => '😄', 8 => '😄', 9 => '😄', 10 => '🤩'];
                                                @endphp
                                                {{ $moodEmoji[$latestMood->mood_scale] ?? '😐' }}
                                            </span>
                                            <div>
                                                <p style="margin: 0; font-weight: 700; font-size: 1.125rem;">{{ $latestMood->mood_scale }}/10</p>
                                                <p class="text-muted" style="margin: 0; font-size: 0.75rem;">{{ $latestMood->mood_date->format('M j') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div style="padding: 1rem; background: rgba(107, 114, 128, 0.05); border-radius: var(--radius-md);">
                                        <p class="text-muted" style="margin: 0; font-size: 0.875rem;">No mood data yet</p>
                                    </div>
                                @endif
                            </a>
                            @endforeach
                        </div>
                    </section>

                    <!-- Pagination -->
                    @if($patients->hasPages())
                    <section class="card dashboard-widget p-6 mb-4">
                        <div style="display: flex; justify-content: center; gap: 0.5rem;">
                            {{ $patients->links() }}
                        </div>
                    </section>
                    @endif
                @else
                    <section class="card dashboard-widget p-6 mb-4">
                        <div style="text-align: center; padding: 2rem;">
                            <div style="font-size: 3rem; margin-bottom: 1rem;">👤</div>
                            <h3 style="margin: 0 0 0.5rem;">No Patients Yet</h3>
                            <p class="text-muted" style="margin: 0;">Patients will appear here once they're assigned to you.</p>
                        </div>
                    </section>
                @endif

                <!-- Back Button -->
                <section class="card dashboard-widget p-6 mb-4">
                    <div style="text-align: center;">
                        <a href="{{ route('therapist.dashboard') }}" class="btn btn-secondary">Back to Therapist Dashboard</a>
                    </div>
                </section>
            </section>
        </div>
    </div>
</div>

<style>
    .mb-4 { margin-bottom: var(--space-4); }
    .p-6 { padding: var(--space-6); }
    .grid {
        display: grid;
    }
    .text-muted {
        color: var(--color-text-muted);
    }
</style>
@endsection
