@extends('layouts.app')

@section('title', 'User Management')
@section('page', 'admin-users')

@section('content')
<div class="container">
    <div class="dashboard-shell">
        <div class="dashboard-app">
            <header class="dashboard-header">
                <div class="dashboard-avatar" aria-hidden="true">👥</div>
                <div class="dashboard-greeting">
                    <h1>User Management</h1>
                    <p class="dashboard-streak">Manage platform users and roles</p>
                </div>
            </header>

            <section class="dashboard-main">
                <!-- Create User Form -->
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-widget__head">
                        <h2>Create New User</h2>
                        <span class="dashboard-widget__eyebrow">Add User</span>
                    </div>
                    <form method="POST" action="{{ route('admin.user.create') }}" class="stack" style="gap: 1rem; margin-top: 1rem;">
                        @csrf
                        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                            <div>
                                <label for="name" class="form-label">Name</label>
                                <input type="text" id="name" name="name" class="form-input" required>
                            </div>
                            <div>
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-input" required>
                            </div>
                            <div>
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-input" required>
                            </div>
                            <div>
                                <label for="role" class="form-label">Role</label>
                                <select id="role" name="role" class="form-input" required>
                                    <option value="patient">Patient</option>
                                    <option value="therapist">Therapist</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: flex-end;">
                            <button type="submit" class="btn btn-primary">Create User</button>
                        </div>
                    </form>
                </section>

                <!-- Users List -->
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-widget__head">
                        <h2>All Users</h2>
                        <span class="dashboard-widget__eyebrow">{{ $users->total() }} total users</span>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success" style="margin-top: 1rem; padding: 1rem; background: rgba(29, 159, 118, 0.1); border: 1px solid var(--color-primary); border-radius: var(--radius-md);">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-error" style="margin-top: 1rem; padding: 1rem; background: rgba(239, 68, 68, 0.1); border: 1px solid #ef4444; border-radius: var(--radius-md);">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="stack" style="gap: 1rem; margin-top: 1rem;">
                        @foreach($users as $user)
                        <article class="surface p-4" style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                                    <h3 style="margin: 0;">{{ $user->name }}</h3>
                                    <span class="badge" style="text-transform: capitalize;">{{ $user->role }}</span>
                                </div>
                                <p class="text-muted" style="margin: 0 0 0.25rem;">{{ $user->email }}</p>
                                <div class="cluster" style="font-size: 0.875rem; color: var(--color-text-muted);">
                                    <span>{{ $user->moods_count }} moods logged</span>
                                    <span>{{ $user->vr_sessions_count }} VR sessions</span>
                                    <span>Joined {{ $user->created_at->format('M j, Y') }}</span>
                                </div>
                            </div>
                            <div class="cluster">
                                <a href="{{ route('admin.user.details', $user) }}" class="btn btn-secondary btn-sm">View Details</a>

                                <!-- Role Update Form -->
                                <form method="POST" action="{{ route('admin.user.update-role', $user) }}" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role" onchange="this.form.submit()" class="form-input" style="padding: 0.25rem 0.5rem; font-size: 0.875rem;">
                                        <option value="patient" {{ $user->role === 'patient' ? 'selected' : '' }}>Patient</option>
                                        <option value="therapist" {{ $user->role === 'therapist' ? 'selected' : '' }}>Therapist</option>
                                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </form>

                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.user.delete', $user) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')"
                                      style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                                @endif
                            </div>
                        </article>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($users->hasPages())
                        <div style="margin-top: 2rem; display: flex; justify-content: center;">
                            {{ $users->links() }}
                        </div>
                    @endif
                </section>

                <!-- Quick Actions -->
                <section class="card dashboard-widget p-6 mb-4">
                    <div class="dashboard-section-head">
                        <h2>Navigation</h2>
                        <div class="cluster">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Admin Dashboard</a>
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
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    .btn-danger {
        background: #ef4444;
        color: white;
    }
    .btn-danger:hover {
        background: #dc2626;
    }
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--color-text);
    }
    .form-input {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        background: var(--color-surface);
        color: var(--color-text);
    }
    .form-input:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 2px rgba(29, 159, 118, 0.1);
    }
</style>
@endsection