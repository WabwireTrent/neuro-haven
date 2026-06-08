@extends('layouts.app')

@section('title', 'User Management')
@section('page', 'admin-users')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>User Management</h1>
    <p>Manage platform users and roles</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">Admin Dashboard</a>
  </div>
</div>

@if(session('success'))
  <div class="alert alert--success slide-up">{{ session('success') }}</div>
@endif
@if(session('error'))
  <div class="alert alert--danger slide-up">{{ session('error') }}</div>
@endif

<div class="card" style="margin-bottom: 1.25rem;">
  <div class="card-header">
    <h4 class="card-header__title">Create New User</h4>
    <span class="badge badge--primary">Add User</span>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('admin.user.create') }}">
      @csrf
      <div class="form-row" style="margin-bottom: 1rem;">
        <div class="form-group">
          <label for="name" class="form-label">Name</label>
          <input type="text" id="name" name="name" class="form-input" required>
        </div>
        <div class="form-group">
          <label for="email" class="form-label">Email</label>
          <input type="email" id="email" name="email" class="form-input" required>
        </div>
      </div>
      <div class="form-row" style="margin-bottom: 1rem;">
        <div class="form-group">
          <label for="password" class="form-label">Password</label>
          <input type="password" id="password" name="password" class="form-input" required>
        </div>
        <div class="form-group">
          <label for="role" class="form-label">Role</label>
          <select id="role" name="role" class="form-select" required>
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
  </div>
</div>

<div class="card">
  <div class="card-header">
    <h4 class="card-header__title">All Users</h4>
    <span class="badge badge--neutral">{{ $users->total() }} total</span>
  </div>
  <div class="card-body" style="padding: 0;">
    @if($users->count() > 0)
      <div style="display: flex; flex-direction: column;">
        @foreach($users as $user)
          <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem 1.25rem; border-bottom: 1px solid var(--color-border-light); transition: background var(--transition-fast);"
               onmouseover="this.style.background='var(--color-surface-muted)'"
               onmouseout="this.style.background=''">
            <div style="flex: 1; min-width: 0;">
              <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.25rem;">
                <div class="avatar-initials avatar-initials--sm" style="width: 32px; height: 32px; font-size: 0.7rem;">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <h5 style="margin: 0; font-size: 0.9rem;">{{ $user->name }}</h5>
                <span class="badge badge--primary" style="text-transform: capitalize; font-size: 0.6rem;">{{ $user->role }}</span>
              </div>
              <p style="margin: 0; font-size: 0.8rem; color: var(--color-text-muted);">
                {{ $user->email }} &middot; Joined {{ $user->created_at->format('M j, Y') }}
              </p>
              <div style="display: flex; gap: 1rem; margin-top: 0.375rem; font-size: 0.75rem; color: var(--color-text-muted);">
                <span>{{ $user->moods_count }} moods</span>
                <span>{{ $user->vr_sessions_count }} VR sessions</span>
              </div>
            </div>
            <div style="display: flex; gap: 0.5rem; align-items: center; flex-shrink: 0; margin-left: 1rem;">
              <a href="{{ route('admin.user.details', $user) }}" class="btn btn-sm btn-ghost">View</a>
              <form method="POST" action="{{ route('admin.user.update-role', $user) }}" style="display: inline;">
                @csrf
                @method('PATCH')
                <select name="role" onchange="this.form.submit()" class="form-select" style="width: auto; min-width: 90px; padding: 0.2rem 0.5rem; font-size: 0.75rem; height: 28px;">
                  <option value="patient" {{ $user->role === 'patient' ? 'selected' : '' }}>Patient</option>
                  <option value="therapist" {{ $user->role === 'therapist' ? 'selected' : '' }}>Therapist</option>
                  <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
              </form>
              @if($user->id !== auth()->id())
                <form method="POST" action="{{ route('admin.user.delete', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')" style="display: inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
              @endif
            </div>
          </div>
        @endforeach
      </div>
      @if(method_exists($users, 'hasPages') && $users->hasPages())
        <div class="card-footer">
          {{ $users->links() }}
        </div>
      @endif
    @else
      <div class="empty-state">
        <div class="empty-state__icon">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        </div>
        <p class="empty-state__title">No Users Found</p>
        <p class="empty-state__desc">Create your first user to get started.</p>
      </div>
    @endif
  </div>
</div>
@endsection
