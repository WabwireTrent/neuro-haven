@extends('layouts.app')

@section('title', 'Notifications')
@section('page', 'notifications')

@section('content')
<div class="page-header">
  <div class="page-header__title">
    <h1>Notifications</h1>
    <p>Stay updated on your therapy journey</p>
  </div>
  <div class="page-header__actions">
    <button class="btn btn-secondary" onclick="markAllAsRead()">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 12 7 18 13 12"/><polyline points="9 18 16 12 22 18"/></svg>
      Mark All Read
    </button>
    <button class="btn btn-secondary" onclick="deleteAllRead()">Delete Read</button>
  </div>
</div>

<div class="card">
  @if($notifications->count() > 0)
    <div style="display: flex; flex-direction: column;">
      @foreach($notifications as $notification)
        <div style="display: flex; justify-content: space-between; align-items: flex-start; padding: 1.25rem; border-bottom: 1px solid var(--color-border-light); transition: background var(--transition-fast); {{ $notification->read_at ? '' : 'background: var(--color-primary-soft); border-left: 3px solid var(--color-primary);' }}"
             onmouseover="this.style.background='var(--color-surface-muted)'"
             onmouseout="this.style.background='{{ $notification->read_at ? '' : 'var(--color-primary-soft)' }}'">
          <div style="flex: 1; min-width: 0;">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
              <h5 style="margin: 0; font-size: 1rem;">{{ $notification->title }}</h5>
              @if(!$notification->read_at)
                <span class="badge badge--primary">New</span>
              @endif
              @if($notification->severity === 'critical')
                <span class="badge badge--danger">Critical</span>
              @endif
            </div>
            <p style="margin: 0.25rem 0; color: var(--color-text-secondary); font-size: 0.875rem;">{{ $notification->message }}</p>
            <p class="text-muted" style="font-size: 0.8rem;">{{ $notification->created_at->diffForHumans() }}</p>
          </div>
          <div style="display: flex; gap: 0.5rem; flex-shrink: 0; margin-left: 1rem;">
            @if(!$notification->read_at)
              <button type="button" class="btn btn-sm btn-secondary" onclick="markAsRead('{{ $notification->id }}')">Mark Read</button>
            @endif
            <button type="button" class="btn btn-sm btn-secondary" onclick="deleteNotification('{{ $notification->id }}')">Delete</button>
          </div>
        </div>
      @endforeach
    </div>
    @if(method_exists($notifications, 'links'))
      <div style="padding: 1rem; border-top: 1px solid var(--color-border-light);">
        {{ $notifications->links() }}
      </div>
    @endif
  @else
    <div class="empty-state">
      <div class="empty-state__icon">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
      </div>
      <p class="empty-state__title">All caught up!</p>
      <p class="empty-state__desc">You have no notifications at this time.</p>
    </div>
  @endif
</div>

<script>
function markAsRead(id) {
  fetch('/api/notifications/' + id + '/read', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      'Content-Type': 'application/json'
    }
  }).then(function(r) { return r.json(); }).then(function(d) { location.reload(); }).catch(function(e) { console.error(e); });
}

function markAllAsRead() {
  fetch('/api/notifications/mark-all-read', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      'Content-Type': 'application/json'
    }
  }).then(function(r) { return r.json(); }).then(function(d) { location.reload(); }).catch(function(e) { console.error(e); });
}

function deleteNotification(id) {
  if (confirm('Delete this notification?')) {
    fetch('/api/notifications/' + id, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json'
      }
    }).then(function(r) { return r.json(); }).then(function(d) { location.reload(); }).catch(function(e) { console.error(e); });
  }
}

function deleteAllRead() {
  if (confirm('Delete all read notifications?')) {
    fetch('/api/notifications/delete-all-read', {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json'
      }
    }).then(function(r) { return r.json(); }).then(function(d) { location.reload(); }).catch(function(e) { console.error(e); });
  }
}
</script>
@endsection
