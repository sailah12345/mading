<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Dashboard - Web Mading</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Sidebar Styles - Pink Soft Theme */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 50%, #fecfef 100%) !important;
            padding: 0;
            z-index: 1000;
            transition: all 0.3s;
        }
        
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }
        
        .sidebar-brand {
            color: #333;
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
            border-bottom: 1px solid rgba(0,0,0,0.1);
        }
        
        .sidebar-menu a {
            display: block;
            padding: 15px 20px;
            color: rgba(0,0,0,0.8);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover {
            background: rgba(255,255,255,0.3);
            color: #333;
            padding-left: 30px;
        }
        
        .sidebar-menu .dropdown-menu {
            background: rgba(255,255,255,0.2);
            border: none;
            margin: 0;
            border-radius: 0;
        }
        
        .sidebar-menu .dropdown-item {
            color: rgba(0,0,0,0.7);
            padding: 10px 40px;
        }
        
        .sidebar-menu .dropdown-item:hover {
            background: rgba(255,255,255,0.3);
            color: #333;
        }
        
        /* Top Navbar */
        .top-navbar {
            position: fixed;
            top: 0;
            left: 250px;
            right: 0;
            height: 60px;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 999;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }
        
        .user-info {
            margin-left: auto;
        }
        
        .user-dropdown .dropdown-toggle {
            background: none;
            border: none;
            color: #333;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 250px;
            margin-top: 60px;
            padding: 20px;
            min-height: calc(100vh - 60px);
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
            <li>
                <a href="{{ url('/') }}">
                    <i class="bi bi-house me-2"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('articles.index') }}">
                    <i class="bi bi-journal-text me-2"></i> Artikel Saya
                </a>
            </li>
            <li>
                <a href="{{ route('articles.create') }}">
                    <i class="bi bi-plus-circle me-2"></i> Tulis Artikel
                </a>
            </li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-tags me-2"></i> Kategori
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Informasi Sekolah</a></li>
                    <li><a class="dropdown-item" href="#">Prestasi</a></li>
                    <li><a class="dropdown-item" href="#">Opini</a></li>
                    <li><a class="dropdown-item" href="#">Kegiatan</a></li>
                </ul>
            </li>
            <li>
                <a href="#">
                    <i class="bi bi-chat-dots me-2"></i> Komentar
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="bi bi-bar-chart me-2"></i> Statistik
                </a>
            </li>
        </ul>
    </div>

    <!-- Top Navbar -->
    <div class="top-navbar">
        <button class="btn btn-outline-secondary d-md-none" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>
        
        <div class="user-info">
            <div class="dropdown user-dropdown">
                <button class="dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle me-1"></i> {{ Auth::check() ? Auth::user()->name : 'Guest' }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> Profil Saya</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i> Pengaturan</a></li>
                    <li><a class="dropdown-item" href="{{ route('articles.index') }}"><i class="bi bi-eye me-2"></i> Lihat Website</a></li>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Mobile sidebar toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
</body>
</html>