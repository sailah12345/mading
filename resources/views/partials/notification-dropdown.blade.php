@auth
<div class="notification-dropdown position-relative">
    <div class="dropdown">
        <button class="btn btn-outline-secondary position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown">
            <i class="bi bi-bell"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationCount" style="display: none;">
                0
            </span>
        </button>
        <div class="dropdown-menu dropdown-menu-end p-0" style="width: 350px; max-height: 400px; overflow-y: auto;">
            <div class="dropdown-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Notifikasi</h6>
                <a href="#" class="text-decoration-none small" id="markAllRead">Tandai Semua Dibaca</a>
            </div>
            <div id="notificationList">
                <div class="text-center p-3">
                    <div class="spinner-border spinner-border-sm" role="status"></div>
                    <small class="d-block mt-2">Memuat notifikasi...</small>
                </div>
            </div>
            <div class="dropdown-divider m-0"></div>
            <div class="dropdown-item-text text-center">
                <a href="{{ route('notifications.index') }}" class="text-decoration-none">Lihat Semua Notifikasi</a>
            </div>
            <div class="dropdown-item-text text-center">
                <a href="/test-notifications" class="text-decoration-none small text-muted">Debug Notifikasi</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadNotifications();
    
    // Auto refresh setiap 30 detik
    setInterval(loadNotifications, 30000);
    
    // Mark all as read
    document.getElementById('markAllRead').addEventListener('click', function(e) {
        e.preventDefault();
        fetch('/notifications/mark-all-read', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(() => loadNotifications())
        .catch(error => console.error('Error marking all as read:', error));
    });
});

function loadNotifications() {
    fetch('/api/notifications/recent', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        updateNotificationBadge(data.unread_count);
        renderNotifications(data.notifications);
    })
    .catch(error => {
        console.error('Error loading notifications:', error);
        // Tampilkan pesan error di dropdown
        document.getElementById('notificationList').innerHTML = `
            <div class="text-center p-3 text-danger">
                <i class="bi bi-exclamation-triangle fs-4"></i>
                <div class="small mt-2">Gagal memuat notifikasi</div>
            </div>
        `;
    });
}

function updateNotificationBadge(count) {
    const badge = document.getElementById('notificationCount');
    if (count > 0) {
        badge.textContent = count;
        badge.style.display = 'inline';
    } else {
        badge.style.display = 'none';
    }
}

function renderNotifications(notifications) {
    const container = document.getElementById('notificationList');
    
    if (!notifications || notifications.length === 0) {
        container.innerHTML = `
            <div class="text-center p-3 text-muted">
                <i class="bi bi-bell-slash fs-4"></i>
                <div class="small mt-2">Tidak ada notifikasi</div>
            </div>
        `;
        return;
    }
    
    container.innerHTML = notifications.map(notification => `
        <div class="dropdown-item ${!notification.is_read ? 'bg-light' : ''}" style="white-space: normal; cursor: pointer;" onclick="markAsRead(${notification.id})">
            <div class="d-flex">
                <div class="flex-shrink-0 me-2">
                    <i class="bi bi-${getNotificationIcon(notification.type)} text-${getNotificationColor(notification.type)}"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1 small">${escapeHtml(notification.title)}</h6>
                    <p class="mb-1 small text-muted">${escapeHtml(notification.message)}</p>
                    <small class="text-muted">${notification.created_at}</small>
                    ${!notification.is_read ? '<span class="badge bg-primary ms-2">Baru</span>' : ''}
                </div>
            </div>
        </div>
    `).join('');
}

function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/read`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(() => loadNotifications())
    .catch(error => console.error('Error marking as read:', error));
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function getNotificationIcon(type) {
    switch(type) {
        case 'success': return 'check-circle';
        case 'danger': return 'x-circle';
        case 'warning': return 'exclamation-triangle';
        default: return 'info-circle';
    }
}

function getNotificationColor(type) {
    switch(type) {
        case 'success': return 'success';
        case 'danger': return 'danger';
        case 'warning': return 'warning';
        default: return 'info';
    }
}
</script>
@endauth