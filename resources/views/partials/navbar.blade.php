<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">Web Mading</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('articles.index') }}">Articles</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Kategori
                    </a>
                    <ul class="dropdown-menu">
                        @foreach(App\Models\Kategori::all() as $kategori)
                            <li><a class="dropdown-item" href="{{ route('articles.kategori', $kategori->id_kategori) }}">{{ $kategori->nama_kategori }}</a></li>
                        @endforeach
                    </ul>
                </li>
            </ul>
            
            <form class="d-flex me-3" action="{{ route('articles.search') }}" method="GET">
                <input class="form-control me-2" type="search" name="q" placeholder="Cari artikel..." aria-label="Search" value="{{ request('q') }}">
                <button class="btn btn-outline-light" type="submit"><i class="bi bi-search"></i></button>
            </form>
            
            <ul class="navbar-nav">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            @if(Auth::user()->role == 'admin')
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                            @elseif(Auth::user()->role == 'guru')
                                <li><a class="dropdown-item" href="{{ route('dashboard.guru') }}">Dashboard Guru</a></li>
                            @else
                                <li><a class="dropdown-item" href="{{ route('dashboard.siswa') }}">Dashboard Siswa</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>

                @endauth
            </ul>
        </div>
    </div>
</nav>