@extends('layouts.app')

@section('title', 'Notifications')
@section('page', 'notifications')

@section('content')
<div class="container">
    <div class="dashboard-shell">
        <div class="dashboard-app">
            <header class="dashboard-header">
                <h1>Notifications</h1>
                <p class="dashboard-streak">Stay updated on your therapy journey</p>
            </header>

            <section class="dashboard-main">
                <div class="notifications-toolbar" style="margin-bottom: 2rem; display: flex; gap: 1rem;">
                    <button class="btn btn-secondary" onclick="markAllAsRead()">Mark All as Read</button>
                    <button class="btn btn-secondary" onclick="deleteAllRead()">Delete Read</button>
                </div>

                @if($notifications->count() > 0)
                    <div class="notifications-list" style="display: flex; flex-direction: column; gap: 1rem;">
                        @foreach($notifications as $notification)
                            <article 
                                class="card notification-item {{ $notification->read_at ? 'is-read' : 'is-unread' }}" 
                                style="padding: 1.5rem; display: flex; justify-content: space-between; align-items: flex-start; border-left: 4px solid var(--color-{{ $notification->severity == 'critical' ? 'danger' : 'primary' }});"
                            >
                                <div style="flex: 1;">
                                    <h3 style="margin: 0 0 0.5rem; font-size: 1.125rem;">
                                        {{ $notification->title }}
                                        @if(!$notification->read_at)
                                            <span style="display: inline-block; width: 8px; height: 8px; background: var(--color-primary); border-radius: 50%; margin-left: 0.5rem;"></span>
                                        @endif
                                    </h3>
                                    <p style="margin: 0 0 0.5rem; color: var(--text-secondary);">
                                        {{ $notification->message }}
                                    </p>
                                    <p class="text-muted" style="margin: 0; font-size: 0.875rem;">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                <div style="display: flex; gap: 0.5rem; margin-left: 1rem;">
                                    @if(!$notification->read_at)
                                        <button 
                                            type="button" 
                                            class="btn btn-sm btn-secondary" 
                                            onclick="markAsRead('{{ $notification->id }}')"
                                        >
                                            Mark as Read
                                        </button>
                                    @endif
                                    <button 
                                        type="button" 
                                        class="btn btn-sm btn-secondary" 
                                        onclick="deleteNotification('{{ $notification->id }}')"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div style="margin-top: 2rem;">
                        {{ $notifications->links() }}
                    </div>
                @else
                    <div class="card" style="padding: 3rem; text-align: center;">
                        <p class="text-muted" style="font-size: 1.125rem;">No notifications yet. You're all set!</p>
                    </div>
                @endif
            </section>
        </div>
    </div>
</div>

<script>
function markAsRead(notificationId) {
    fetch(`/api/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    }).then(response => response.json())
      .then(data => location.reload())
      .catch(error => console.error('Error:', error));
}

function markAllAsRead() {
    fetch('/api/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    }).then(response => response.json())
      .then(data => location.reload())
      .catch(error => console.error('Error:', error));
}

function deleteNotification(notificationId) {
    if (confirm('Delete this notification?')) {
        fetch(`/api/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        }).then(response => response.json())
          .then(data => location.reload())
          .catch(error => console.error('Error:', error));
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
        }).then(response => response.json())
          .then(data => location.reload())
          .catch(error => console.error('Error:', error));
    }
}
</script>

<style>
    .notification-item {
        transition: background-color 0.2s ease;
    }

    .notification-item.is-unread {
        background-color: rgba(59, 130, 246, 0.05);
    }

    .notification-item.is-read {
        opacity: 0.7;
    }

    .notifications-toolbar {
        display: flex;
        gap: 1rem;
    }

    @media (max-width: 768px) {
        .notification-item {
            flex-direction: column;
        }

        .notification-item > div:last-child {
            margin-left: 0;
            margin-top: 1rem;
            width: 100%;
        }

        .notifications-toolbar {
            flex-direction: column;
        }
    }
</style>
@endsection
