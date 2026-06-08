@extends('layouts.app')

@section('title', 'Crisis Alerts')
@section('page', 'crisis-alerts')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>Crisis Alerts</h1>
    <p>Monitor and respond to patient safety alerts</p>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

@php
  $isStaff = auth()->user()->isTherapist() || auth()->user()->isAdmin();
  $unresolved = $alerts->where('is_resolved', false);
  $resolved = $alerts->where('is_resolved', true);
@endphp

<div class="stats-grid" style="margin-bottom: 1.5rem;">
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Total Alerts</p>
    <p class="stat-card__value">{{ $alerts->total() }}</p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Unresolved</p>
    <p class="stat-card__value" style="color: var(--color-danger);">{{ $unresolved->count() }}</p>
  </div>
  <div class="stat-card" style="text-align: center;">
    <p class="stat-card__label">Resolved</p>
    <p class="stat-card__value" style="color: var(--color-success);">{{ $resolved->count() }}</p>
  </div>
</div>

@if($unresolved->where('severity', 'critical')->count() > 0)
  <div class="alert alert-danger" style="font-weight: 600;">
    {{ $unresolved->where('severity', 'critical')->count() }} critical alert(s) require immediate attention.
  </div>
@endif

<div class="card">
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          @if($isStaff)<th>Patient</th>@endif
          <th>Trigger</th>
          <th>Severity</th>
          <th>Message</th>
          <th>Date</th>
          <th>Status</th>
          @if($isStaff)<th></th>@endif
        </tr>
      </thead>
      <tbody>
        @forelse($alerts as $alert)
          <tr style="{{ !$alert->is_resolved ? 'background: var(--color-danger-soft);' : '' }}">
            @if($isStaff)
              <td>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                  <div class="topbar__avatar" style="width: 28px; height: 28px; font-size: 0.7rem;">{{ strtoupper(substr($alert->user->name ?? '?', 0, 1)) }}</div>
                  <span style="font-weight: 600; font-size: 0.85rem;">{{ $alert->user->name ?? 'Unknown' }}</span>
                </div>
              </td>
            @endif
            <td><span class="badge badge--info">{{ ucfirst(str_replace('_', ' ', $alert->triggered_by)) }}</span></td>
            <td>
              <span class="badge badge--{{ $alert->severity === 'critical' ? 'danger' : ($alert->severity === 'high' ? 'warning' : ($alert->severity === 'medium' ? 'info' : 'neutral')) }}">
                {{ ucfirst($alert->severity) }}
              </span>
            </td>
            <td style="max-width: 250px; white-space: normal; font-size: 0.85rem;">{{ $alert->message }}</td>
            <td class="text-sm text-muted">{{ $alert->created_at->diffForHumans() }}</td>
            <td>
              @if($alert->is_resolved)
                <span class="badge badge--success">Resolved</span>
              @else
                <span class="badge badge--danger">Active</span>
              @endif
            </td>
            @if($isStaff)
              <td class="actions-cell">
                @if(!$alert->is_resolved)
                  <form method="POST" action="{{ route('crisis-alerts.resolve', $alert) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success">Resolve</button>
                  </form>
                @endif
              </td>
            @endif
          </tr>
        @empty
          <tr><td colspan="{{ $isStaff ? 7 : 5 }}" class="empty-cell">No crisis alerts.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if(method_exists($alerts, 'hasPages') && $alerts->hasPages())
    <div style="padding: 1rem; border-top: 1px solid var(--color-border-light); display: flex; justify-content: center;">{{ $alerts->links() }}</div>
  @endif
</div>
@endsection
