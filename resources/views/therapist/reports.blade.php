@extends('layouts.app')

@section('title', 'Patient Reports')
@section('page', 'therapist-reports')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>Patient Reports</h1>
    <p>View and analyze your patients' therapy data</p>
  </div>
  <div class="page-header__actions">
    <a href="{{ route('therapist.dashboard') }}" class="btn btn-secondary btn-sm">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
      Dashboard
    </a>
  </div>
</div>

<div class="stats-grid" style="margin-bottom: 1.5rem;">
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Total Patients</p>
    <p class="stat-card__value">{{ $summary['total'] }}</p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Total Mood Entries</p>
    <p class="stat-card__value">{{ $summary['total_moods'] }}</p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Total VR Sessions</p>
    <p class="stat-card__value">{{ $summary['total_sessions'] }}</p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Avg Mood Score</p>
    <p class="stat-card__value">{{ $summary['avg_mood_all'] }}/10</p>
  </div>
</div>

<div class="card">
  <div class="table-toolbar">
    <div class="table-toolbar__left">
      <form method="GET" action="{{ route('therapist.reports') }}" style="display: flex; gap: 0.5rem;">
        <input type="text" name="search" class="form-input" style="width: 260px;" placeholder="Search patients..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
        @if(request('search'))
          <a href="{{ route('therapist.reports') }}" class="btn btn-ghost btn-sm">Clear</a>
        @endif
      </form>
    </div>
    <div class="table-toolbar__right">
      <span class="text-muted text-sm">{{ $patients->total() }} patients</span>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>
            <a href="{{ route('therapist.reports', array_merge(request()->query(), ['sort' => 'name', 'dir' => $sort === 'name' && $dir === 'asc' ? 'desc' : 'asc'])) }}" style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 0.25rem;">
              Patient @if($sort === 'name')<span style="font-size: 0.65rem;">{{ $dir === 'asc' ? '▲' : '▼' }}</span>@endif
            </a>
          </th>
          <th>
            <a href="{{ route('therapist.reports', array_merge(request()->query(), ['sort' => 'moods_count', 'dir' => $sort === 'moods_count' && $dir === 'asc' ? 'desc' : 'asc'])) }}" style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 0.25rem;">
              Moods @if($sort === 'moods_count')<span style="font-size: 0.65rem;">{{ $dir === 'asc' ? '▲' : '▼' }}</span>@endif
            </a>
          </th>
          <th>
            <a href="{{ route('therapist.reports', array_merge(request()->query(), ['sort' => 'avg_mood', 'dir' => $sort === 'avg_mood' && $dir === 'asc' ? 'desc' : 'asc'])) }}" style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 0.25rem;">
              Avg Mood @if($sort === 'avg_mood')<span style="font-size: 0.65rem;">{{ $dir === 'asc' ? '▲' : '▼' }}</span>@endif
            </a>
          </th>
          <th>
            <a href="{{ route('therapist.reports', array_merge(request()->query(), ['sort' => 'sessions_count', 'dir' => $sort === 'sessions_count' && $dir === 'asc' ? 'desc' : 'asc'])) }}" style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 0.25rem;">
              Sessions @if($sort === 'sessions_count')<span style="font-size: 0.65rem;">{{ $dir === 'asc' ? '▲' : '▼' }}</span>@endif
            </a>
          </th>
          <th>
            <a href="{{ route('therapist.reports', array_merge(request()->query(), ['sort' => 'completed_sessions', 'dir' => $sort === 'completed_sessions' && $dir === 'asc' ? 'desc' : 'asc'])) }}" style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 0.25rem;">
              Completed @if($sort === 'completed_sessions')<span style="font-size: 0.65rem;">{{ $dir === 'asc' ? '▲' : '▼' }}</span>@endif
            </a>
          </th>
          <th>
            <a href="{{ route('therapist.reports', array_merge(request()->query(), ['sort' => 'last_session_at', 'dir' => $sort === 'last_session_at' && $dir === 'asc' ? 'desc' : 'asc'])) }}" style="color: inherit; text-decoration: none; display: flex; align-items: center; gap: 0.25rem;">
              Last Activity @if($sort === 'last_session_at')<span style="font-size: 0.65rem;">{{ $dir === 'asc' ? '▲' : '▼' }}</span>@endif
            </a>
          </th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($patients as $patient)
          <tr>
            <td>
              <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div class="avatar-initials" style="width: 34px; height: 34px; font-size: 0.8rem; flex-shrink: 0;">{{ strtoupper(substr($patient->name, 0, 1)) }}</div>
                <div>
                  <p style="margin: 0; font-weight: 600; font-size: 0.85rem;">{{ $patient->name }}</p>
                  <p class="text-muted text-sm" style="margin: 0;">{{ $patient->email }}</p>
                </div>
              </div>
            </td>
            <td><span class="badge badge--primary">{{ $patient->moods_count }}</span></td>
            <td><strong>{{ $patient->avg_mood ?? 'N/A' }}</strong></td>
            <td>{{ $patient->sessions_count }}</td>
            <td>
              @if($patient->sessions_count > 0)
                <span class="badge badge--{{ $patient->completed_sessions === $patient->sessions_count ? 'success' : 'warning' }}">
                  {{ $patient->completed_sessions }}/{{ $patient->sessions_count }}
                </span>
              @else
                <span class="badge badge--neutral">—</span>
              @endif
            </td>
            <td class="text-sm text-muted">
              {{ $patient->last_session_at ? \Carbon\Carbon::parse($patient->last_session_at)->diffForHumans() : 'Never' }}
            </td>
            <td class="actions-cell">
              <a href="{{ route('therapist.patient.details', $patient) }}" class="btn btn-ghost btn-sm">View Report</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="empty-cell">No patients assigned yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if(method_exists($patients, 'hasPages') && $patients->hasPages())
    <div style="padding: 1rem 1.25rem; border-top: 1px solid var(--color-border-light); display: flex; justify-content: center;">
      {{ $patients->links() }}
    </div>
  @endif
</div>
@endsection