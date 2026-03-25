@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('page', 'admin-dashboard')

@section('content')
<div class="container">
    <div class="dashboard-shell">
        <div class="dashboard-app">
            <header class="dashboard-header">
                <div class="dashboard-avatar" aria-hidden="true">👑</div>
                <div class="dashboard-greeting">
                    <h1>Admin Dashboard</h1>
                    <p class="dashboard-streak">System overview and management</p>
                </div>
            </header>

            <section class="dashboard-main">
                <!-- System Overview -->
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-widget__head">
                        <h2>System Overview</h2>
                        <span class="dashboard-widget__eyebrow">Platform Statistics</span>
                    </div>
                    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-top: 1rem;">
                        <article class="surface p-4" style="text-align: center;">
                            <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Total Users</p>
                            <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: var(--color-primary);">{{ $analytics['total_users'] }}</p>
                        </article>
                        <article class="surface p-4" style="text-align: center;">
                            <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Total Moods</p>
                            <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: var(--color-primary);">{{ $analytics['total_moods'] }}</p>
                        </article>
                        <article class="surface p-4" style="text-align: center;">
                            <p class="text-muted" style="margin: 0; font-size: 0.875rem;">VR Sessions</p>
                            <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: var(--color-primary);">{{ $analytics['total_vr_sessions'] }}</p>
                        </article>
                        <article class="surface p-4" style="text-align: center;">
                            <p class="text-muted" style="margin: 0; font-size: 0.875rem;">Active This Week</p>
                            <p style="margin: 0.5rem 0 0; font-size: 2rem; font-weight: 700; color: var(--color-primary);">{{ $analytics['active_users_week'] }}</p>
                        </article>
                    </div>
                </section>

                <!-- User Roles Distribution -->
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-widget__head">
                        <h2>User Distribution</h2>
                        <span class="dashboard-widget__eyebrow">By Role</span>
                    </div>
                    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-top: 1rem;">
                        @foreach(['patient' => '👤', 'therapist' => '👨‍⚕️', 'admin' => '👑'] as $role => $icon)
                        <article class="surface p-4" style="text-align: center;">
                            <div style="font-size: 2rem; margin-bottom: 0.5rem;">{{ $icon }}</div>
                            <h3 style="margin: 0 0 0.25rem; text-transform: capitalize;">{{ $role }}s</h3>
                            <p style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--color-primary);">{{ $analytics['user_roles'][$role] ?? 0 }}</p>
                        </article>
                        @endforeach
                    </div>
                </section>

                <!-- Popular VR Assets -->
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-widget__head">
                        <h2>Popular VR Experiences</h2>
                        <span class="dashboard-widget__eyebrow">System-wide</span>
                    </div>
                    @if($analytics['popular_vr_assets']->count() > 0)
                        <div class="stack" style="gap: 1rem; margin-top: 1rem;">
                            @foreach($analytics['popular_vr_assets'] as $asset)
                            <article class="surface p-4" style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <h3 style="margin: 0 0 0.25rem; font-size: 1.125rem;">{{ $asset->vr_asset_title }}</h3>
                                    <p class="text-muted" style="margin: 0;">{{ $asset->sessions_count }} total sessions</p>
                                </div>
                                <div style="text-align: right;">
                                    <div style="font-size: 1.25rem; color: var(--color-primary);">
                                        📊
                                    </div>
                                </div>
                            </article>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted" style="margin-top: 1rem;">No VR sessions recorded yet.</p>
                    @endif
                </section>

                <!-- Recent Activity -->
                <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                    <!-- Recent Users -->
                    <section class="card dashboard-widget p-6">
                        <div class="dashboard-widget__head">
                            <h2>Recent Users</h2>
                            <span class="dashboard-widget__eyebrow">Latest Registrations</span>
                        </div>
                        @if($analytics['recent_users']->count() > 0)
                            <div class="stack" style="gap: 1rem; margin-top: 1rem;">
                                @foreach($analytics['recent_users'] as $user)
                                <article class="surface p-4" style="display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <h4 style="margin: 0 0 0.25rem;">{{ $user->name }}</h4>
                                        <p class="text-muted" style="margin: 0; font-size: 0.875rem;">{{ $user->email }}</p>
                                    </div>
                                    <div style="text-align: right;">
                                        <span class="badge" style="text-transform: capitalize;">{{ $user->role }}</span>
                                        <p class="text-muted" style="margin: 0.25rem 0 0; font-size: 0.75rem;">{{ $user->created_at->diffForHumans() }}</p>
                                    </div>
                                </article>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted" style="margin-top: 1rem;">No users registered yet.</p>
                        @endif
                    </section>

                    <!-- Recent VR Sessions -->
                    <section class="card dashboard-widget p-6">
                        <div class="dashboard-widget__head">
                            <h2>Recent VR Sessions</h2>
                            <span class="dashboard-widget__eyebrow">Latest Activity</span>
                        </div>
                        @if($analytics['recent_vr_sessions']->count() > 0)
                            <div class="stack" style="gap: 1rem; margin-top: 1rem;">
                                @foreach($analytics['recent_vr_sessions'] as $session)
                                <article class="surface p-4">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                                        <h4 style="margin: 0; font-size: 1rem;">{{ $session->vr_asset_title }}</h4>
                                        <span class="badge">{{ $session->is_completed ? 'Completed' : 'In Progress' }}</span>
                                    </div>
                                    <p class="text-muted" style="margin: 0; font-size: 0.875rem;">{{ $session->user->name }}</p>
                                    <p class="text-muted" style="margin: 0.25rem 0 0; font-size: 0.75rem;">{{ $session->started_at->diffForHumans() }}</p>
                                </article>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted" style="margin-top: 1rem;">No VR sessions recorded yet.</p>
                        @endif
                    </section>
                </div>

                <!-- Quick Actions -->
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-section-head">
                        <h2>Admin Actions</h2>
                        <div class="cluster">
                            <a href="{{ route('admin.users') }}" class="btn btn-secondary">Manage Users</a>
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">User Dashboard</a>
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