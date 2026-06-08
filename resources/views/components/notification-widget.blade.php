<!-- Notification Widget for Dashboard -->
<div id="notification-widget" style="position: fixed; top: 1rem; right: 1rem; max-width: 400px; z-index: 50;">
</div>

<script>
// WebSocket connection for real-time notifications
let ws = null;
const userId = @json(auth()->id() ?? null);

function setupWebSocket() {
    if (!userId) return;
    
    // Detect protocol (ws or wss depending on current location)
    const protocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
    const wsUrl = `${protocol}//${window.location.host.split(':')[0]}:3000`;
    
    try {
        ws = new WebSocket(wsUrl);
        
        ws.onopen = function() {
            console.log('[WebSocket] Connected');
            // Authenticate this connection
            ws.send(JSON.stringify({
                type: 'auth',
                userId: userId
            }));
        };
        
        ws.onmessage = function(event) {
            const notification = JSON.parse(event.data);
            displayNotification(notification);
            updateNotificationBadge();
        };
        
        ws.onerror = function(error) {
            console.error('[WebSocket] Error:', error);
        };
        
        ws.onclose = function() {
            console.log('[WebSocket] Disconnected. Attempting to reconnect...');
            setTimeout(setupWebSocket, 5000); // Reconnect after 5 seconds
        };
    } catch (error) {
        console.error('[WebSocket] Connection failed:', error);
    }
}

function displayNotification(notification) {
    const widget = document.getElementById('notification-widget');
    const element = document.createElement('div');
    
    const colors = {
        critical: '#dc2626',
        warning: '#f59e0b',
        info: '#3b82f6'
    };
    
    const icons = {
        crisis_alert: '🚨',
        milestone: '🎉',
        streak_warning: '⏰',
        assignment: '👤',
        vr_session_report: '📋',
        general: 'ℹ️'
    };
    
    element.className = 'notification-toast';
    element.className = 'notification-toast ' + (notification.severity || 'info');
    element.style.cssText = `
        background: var(--color-surface, #ffffff);
        border-left: 4px solid ${colors[notification.severity] || colors.info};
        border-radius: 0.75rem;
        padding: 1rem;
        margin-bottom: 0.5rem;
        box-shadow: var(--shadow-lg, 0 10px 15px -3px rgba(0,0,0,0.1));
        animation: slideIn 0.3s ease;
        cursor: pointer;
        border: 1px solid var(--color-border, #e2e8f0);
    `;
    
    element.innerHTML = `
        <div style="display: flex; gap: 0.75rem;">
            <span style="font-size: 1.25rem; flex-shrink: 0;">${icons[notification.type] || icons.general}</span>
            <div style="flex: 1; min-width: 0;">
                <strong style="font-size: 0.875rem;">${notification.title}</strong>
                <p style="margin: 0.25rem 0 0; color: var(--color-text-muted, #6b7280); font-size: 0.8rem;">${notification.message}</p>
            </div>
        </div>
    `;
    
    widget.insertBefore(element, widget.firstChild);
    
    // Auto-remove after 8 seconds
    setTimeout(() => {
        element.style.animation = 'fadeOut 0.3s ease';
        setTimeout(() => element.remove(), 300);
    }, 8000);
}

function updateNotificationBadge() {
    fetch('/api/notifications/unread-count')
        .then(r => r.json())
        .then(data => {
            const badge = document.querySelector('[data-notif-badge]');
            if (badge) {
                badge.textContent = data.unread_count || '';
                badge.style.display = data.unread_count > 0 ? 'block' : 'none';
            }
        })
        .catch(error => console.error('Error fetching unread count:', error));
}

// Setup WebSocket and initialize
document.addEventListener('DOMContentLoaded', function() {
    setupWebSocket();
    updateNotificationBadge();
    
    // Update badge periodically (every 30 seconds as fallback)
    setInterval(updateNotificationBadge, 30000);
});

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes fadeOut {
        from {
            opacity: 1;
        }
        to {
            opacity: 0;
        }
    }
    
    .notification-toast {
        max-width: 100%;
    }
    
    @media (max-width: 768px) {
        #notification-widget {
            left: 1rem;
            right: 1rem;
            max-width: none;
        }
    }
`;
document.head.appendChild(style);
</script>
