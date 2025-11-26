<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Web Mading</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 260px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 0;
            z-index: 1000;
            transition: all 0.3s;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-brand {
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            text-decoration: none;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-menu a {
            display: block;
            padding: 15px 20px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            padding-left: 30px;
        }
        
        .sidebar-menu .dropdown-menu {
            background: rgba(0,0,0,0.2);
            border: none;
            margin: 0;
            border-radius: 0;
        }
        
        .sidebar-menu .dropdown-item {
            color: rgba(255,255,255,0.8);
            padding: 10px 40px;
        }
        
        .sidebar-menu .dropdown-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        /* Top Navbar */
        .top-navbar {
            position: fixed;
            top: 0;
            left: 260px;
            right: 0;
            height: 60px;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 999;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }
        
        .auth-buttons {
            margin-left: auto;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 260px;
            margin-top: 60px;
            padding: 0;
            min-height: calc(100vh - 60px);
            background-color: #f8f9fa;
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .top-navbar {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ url('/') }}" class="sidebar-brand">
                <i class="bi bi-newspaper"></i> Web Mading
            </a>
        </div>
        
        <ul class="sidebar-menu">
            <li class="d-md-none">
                <div class="p-3">
                    <form action="{{ url('/') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari artikel..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>
            </li>
            <li>
                <a href="{{ url('/') }}">
                    <i class="bi bi-house me-2"></i> Home
                </a>
            </li>
            <li>
                <a href="{{ route('articles.index') }}">
                    <i class="bi bi-journal-text me-2"></i> Artikel
                </a>
            </li>
            @auth
            <li>
                <a href="@if(Auth::user()->role == 'admin') {{ route('admin.dashboard') }} @elseif(Auth::user()->role == 'guru') {{ route('dashboard.guru') }} @else {{ route('dashboard.siswa') }} @endif">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('notifications.index') }}" id="notificationLink">
                    <i class="bi bi-bell me-2"></i> Notifikasi
                    <span class="badge bg-danger ms-2" id="notificationBadge" style="display: none;">0</span>
                </a>
            </li>
            @endauth
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-tags me-2"></i> Kategori
                </a>
                <ul class="dropdown-menu">
                    @foreach(App\Models\Kategori::all() as $kategori)
                        <li><a class="dropdown-item" href="{{ route('articles.kategori', $kategori->id_kategori) }}">{{ $kategori->nama_kategori }}</a></li>
                    @endforeach
                </ul>
            </li>

           
            
        </ul>
    </div>

    <!-- Top Navbar -->
    <div class="top-navbar">
        <button class="btn btn-outline-secondary d-md-none" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>
        
        <!-- Search Form -->
        <form action="{{ url('/') }}" method="GET" class="d-none d-md-flex me-3">
            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari artikel..." value="{{ request('search') }}" style="width: 200px;">
            <button type="submit" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-search"></i>
            </button>
        </form>
        
        <div class="auth-buttons">
            @auth
                <!-- Notification Dropdown -->
                @include('partials.notification-dropdown')
                
                <div class="dropdown ms-2">
                    @php
                        $roleColor = match(Auth::user()->role) {
                            'admin' => 'btn-outline-danger',
                            'guru' => 'btn-outline-success', 
                            'siswa' => 'btn-outline-primary',
                            default => 'btn-outline-secondary'
                        };
                        $roleIcon = match(Auth::user()->role) {
                            'admin' => 'bi-shield-check',
                            'guru' => 'bi-mortarboard',
                            'siswa' => 'bi-person-circle',
                            default => 'bi-person-circle'
                        };
                    @endphp
                    <button class="btn {{ $roleColor }} dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="{{ $roleIcon }} me-1"></i> {{ Auth::user()->name }} 
                        <span class="badge bg-{{ Auth::user()->role == 'admin' ? 'danger' : (Auth::user()->role == 'guru' ? 'success' : 'primary') }}">{{ strtoupper(Auth::user()->role) }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> Profil Saya</a></li>
                        <li><a class="dropdown-item" href="{{ route('articles.create') }}"><i class="bi bi-plus me-2"></i> Tulis Artikel</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#loginModal">Login</button>
            @endauth
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Login Sebagai</label>
                            <select class="form-select" name="role" required>
                                <option value="">Pilih Role</option>
                                <option value="siswa">Siswa</option>
                                <option value="guru">Guru</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                    <hr>
                    <div class="text-center">
                        <p class="text-muted mb-0">Belum punya akun? Hubungi admin untuk mendapatkan akses</p>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
    
    <script>
        // Mobile sidebar toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
        
        // Update notification badge
        @auth
        function updateNotificationBadge() {
            fetch('/api/notifications/unread-count')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notificationBadge');
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.style.display = 'inline';
                    } else {
                        badge.style.display = 'none';
                    }
                })
                .catch(error => console.log('Error updating notification badge:', error));
        }
        
        // Update badge on page load and every 30 seconds
        document.addEventListener('DOMContentLoaded', updateNotificationBadge);
        setInterval(updateNotificationBadge, 30000);
        @endauth
    </script>
</body>
</html>