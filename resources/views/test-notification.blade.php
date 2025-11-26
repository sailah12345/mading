<!DOCTYPE html>
<html>
<head>
    <title>Test Notifikasi</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Test Notifikasi</h2>
        
        @auth
        <p>Login sebagai: {{ Auth::user()->name }} ({{ Auth::user()->role }})</p>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Test API Endpoints</h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-primary mb-2" onclick="testUnreadCount()">Test Unread Count</button>
                        <button class="btn btn-success mb-2" onclick="testRecentNotifications()">Test Recent Notifications</button>
                        <button class="btn btn-warning mb-2" onclick="testMarkAllRead()">Test Mark All Read</button>
                        
                        <div id="result" class="mt-3"></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Notifikasi dari Database</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $notifications = App\Models\Notification::where('user_id', Auth::id())->latest()->take(5)->get();
                        @endphp
                        
                        @if($notifications->count() > 0)
                            @foreach($notifications as $notification)
                            <div class="alert alert-{{ $notification->type == 'success' ? 'success' : ($notification->type == 'danger' ? 'danger' : 'info') }} alert-sm">
                                <strong>{{ $notification->title }}</strong><br>
                                {{ $notification->message }}<br>
                                <small>{{ $notification->created_at->diffForHumans() }} - {{ $notification->is_read ? 'Dibaca' : 'Belum dibaca' }}</small>
                            </div>
                            @endforeach
                        @else
                            <p>Tidak ada notifikasi</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="alert alert-warning">
            Silakan login terlebih dahulu untuk test notifikasi.
            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
        </div>
        @endauth
    </div>

    <script>
        function showResult(title, data) {
            document.getElementById('result').innerHTML = `
                <h6>${title}</h6>
                <pre class="bg-light p-2">${JSON.stringify(data, null, 2)}</pre>
            `;
        }

        function testUnreadCount() {
            fetch('/api/notifications/unread-count', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                showResult('Unread Count Response', data);
            })
            .catch(error => {
                showResult('Unread Count Error', error.toString());
                console.error('Error:', error);
            });
        }

        function testRecentNotifications() {
            fetch('/api/notifications/recent', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                showResult('Recent Notifications Response', data);
            })
            .catch(error => {
                showResult('Recent Notifications Error', error.toString());
                console.error('Error:', error);
            });
        }

        function testMarkAllRead() {
            fetch('/notifications/mark-all-read', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.text();
            })
            .then(data => {
                showResult('Mark All Read Response', data);
            })
            .catch(error => {
                showResult('Mark All Read Error', error.toString());
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>