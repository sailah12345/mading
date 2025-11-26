# 📚 LARAVEL GUIDE - Web Mading Project

## 🔥 **1. ARRAY di Laravel**

### **Array Associative**
```php
// Data user untuk form
$userData = [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'role' => 'siswa'
];

// Validation rules array
$rules = [
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users,email',
    'role' => 'required|in:siswa,guru,admin'
];
```

### **Array untuk View Data**
```php
// Kirim multiple data ke view
return view('admin.dashboard', compact(
    'totalUsers', 'totalArticles', 'pendingArticles'
));

// Atau menggunakan array
return view('admin.dashboard', [
    'users' => $users,
    'articles' => $articles,
    'statistics' => $stats
]);
```

### **Array Collection Methods**
```php
// Filter artikel berdasarkan status
$publishedArticles = $articles->where('status', 'published');

// Group artikel by kategori
$articlesByCategory = $articles->groupBy('kategori.nama_kategori');

// Map untuk transform data
$articleTitles = $articles->map(function($article) {
    return $article->title;
});
```

---

## 🎮 **2. CONTROLLER**

### **Basic Controller Structure**
```php
<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Method untuk tampilkan form
    public function create()
    {
        return view('admin.create');
    }
    
    // Method untuk proses data
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users'
        ]);
        
        // Create data
        User::create($request->all());
        
        // Redirect dengan message
        return redirect()->route('admin.dashboard')
                        ->with('success', 'Data berhasil disimpan!');
    }
}
```

### **Controller Methods Pattern**
```php
// CRUD Pattern
public function index()    // Tampilkan list data
public function create()   // Tampilkan form create
public function store()    // Proses create data
public function show($id)  // Tampilkan detail data
public function edit($id)  // Tampilkan form edit
public function update($id) // Proses update data
public function destroy($id) // Hapus data
```

### **Request Handling**
```php
public function storeUser(Request $request)
{
    // Get specific input
    $name = $request->input('name');
    $email = $request->email; // shorthand
    
    // Get all input
    $data = $request->all();
    
    // Get only specific fields
    $userData = $request->only(['name', 'email', 'role']);
    
    // Check if field exists
    if ($request->has('password')) {
        // Process password
    }
    
    // Check if field filled
    if ($request->filled('password')) {
        // Update password
    }
}
```

### **Response Types**
```php
// Redirect responses
return redirect()->route('admin.dashboard');
return redirect()->back();
return redirect()->route('users.show', $user->id);

// View responses
return view('admin.create');
return view('admin.edit', compact('user'));

// JSON responses
return response()->json(['status' => 'success']);
return response()->json($data, 200);
```

---

## 🗄️ **3. MIGRATION**

### **Create Migration**
```bash
php artisan make:migration create_users_table
php artisan make:migration add_column_to_users_table --table=users
```

### **Migration Structure**
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->enum('role', ['admin', 'guru', 'siswa'])->default('siswa');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
```

### **Column Types**
```php
// String types
$table->string('name', 100);        // VARCHAR(100)
$table->text('description');        // TEXT
$table->longText('content');        // LONGTEXT

// Numeric types
$table->integer('views');           // INT
$table->bigInteger('user_id');      // BIGINT
$table->decimal('price', 8, 2);     // DECIMAL(8,2)

// Date types
$table->date('birth_date');         // DATE
$table->datetime('created_at');     // DATETIME
$table->timestamp('updated_at');    // TIMESTAMP

// Boolean
$table->boolean('is_active')->default(true);

// Foreign key
$table->foreignId('user_id')->constrained();
$table->foreign('kategori_id')->references('id')->on('kategori');
```

### **Migration Commands**
```bash
php artisan migrate              # Run migrations
php artisan migrate:rollback     # Rollback last batch
php artisan migrate:reset        # Rollback all
php artisan migrate:fresh        # Drop all tables and re-run
php artisan migrate:status       # Check migration status
```

---

## 🏗️ **4. MODEL**

### **Basic Model**
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;
    
    // Table name (optional if follows convention)
    protected $table = 'articles';
    
    // Primary key (optional if 'id')
    protected $primaryKey = 'id';
    
    // Mass assignable fields
    protected $fillable = [
        'title', 'content', 'image', 'status', 'user_id', 'id_kategori'
    ];
    
    // Hidden fields (for JSON)
    protected $hidden = ['created_at', 'updated_at'];
    
    // Cast attributes
    protected $casts = [
        'created_at' => 'datetime',
        'is_published' => 'boolean'
    ];
}
```

### **Relationships**
```php
class Article extends Model
{
    // One-to-Many (Article belongs to User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // One-to-Many (Article belongs to Kategori)
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }
    
    // One-to-Many (Article has many Likes)
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    
    // Many-to-Many (Article has many Tags)
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}

class User extends Model
{
    // One-to-Many (User has many Articles)
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
    
    // One-to-Many (User has many Likes)
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
```

### **Query Methods**
```php
// Basic queries
$users = User::all();                    // Get all
$user = User::find(1);                   // Find by ID
$user = User::where('role', 'admin')->first(); // First match
$users = User::where('role', 'siswa')->get();  // Get collection

// With relationships
$articles = Article::with(['user', 'kategori'])->get();
$article = Article::with('likes')->find(1);

// Conditional queries
$articles = Article::where('status', 'published')
                  ->where('user_id', auth()->id())
                  ->orderBy('created_at', 'desc')
                  ->get();

// Aggregate functions
$count = Article::count();
$published = Article::where('status', 'published')->count();
```

### **Model Events**
```php
class Article extends Model
{
    protected static function boot()
    {
        parent::boot();
        
        // Before creating
        static::creating(function ($article) {
            $article->user_id = auth()->id();
        });
        
        // After creating
        static::created(function ($article) {
            // Log activity
        });
    }
}
```

---

## 🎨 **5. VIEW (Blade Templates)**

### **Basic Blade Syntax**
```blade
{{-- Comments --}}

{{-- Display data --}}
<h1>{{ $title }}</h1>
<p>{{ $article->content }}</p>

{{-- Raw HTML (be careful!) --}}
{!! $htmlContent !!}

{{-- Conditional --}}
@if($user->role === 'admin')
    <p>Admin Panel</p>
@elseif($user->role === 'guru')
    <p>Guru Dashboard</p>
@else
    <p>Student Area</p>
@endif

{{-- Loops --}}
@foreach($articles as $article)
    <div class="article">
        <h3>{{ $article->title }}</h3>
        <p>{{ $article->content }}</p>
    </div>
@endforeach

@forelse($articles as $article)
    <div>{{ $article->title }}</div>
@empty
    <p>No articles found</p>
@endforelse
```

### **Layout & Sections**
```blade
{{-- layouts/app.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Web Mading')</title>
    @stack('styles')
</head>
<body>
    <nav>@include('partials.navbar')</nav>
    
    <main>
        @yield('content')
    </main>
    
    <footer>@include('partials.footer')</footer>
    @stack('scripts')
</body>
</html>

{{-- articles/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Articles')

@push('styles')
    <link rel="stylesheet" href="custom.css">
@endpush

@section('content')
    <h1>Articles</h1>
    @foreach($articles as $article)
        <div>{{ $article->title }}</div>
    @endforeach
@endsection

@push('scripts')
    <script src="custom.js"></script>
@endpush
```

### **Forms**
```blade
<form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" 
               value="{{ old('title') }}" 
               class="form-control @error('title') is-invalid @enderror">
        @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="form-group">
        <label for="kategori">Kategori</label>
        <select name="id_kategori" class="form-control">
            @foreach($kategoris as $kategori)
                <option value="{{ $kategori->id_kategori }}"
                        {{ old('id_kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                    {{ $kategori->nama_kategori }}
                </option>
            @endforeach
        </select>
    </div>
    
    <button type="submit" class="btn btn-primary">Save</button>
</form>
```

### **Components & Includes**
```blade
{{-- Include partial --}}
@include('partials.alert')

{{-- Include with data --}}
@include('partials.article-card', ['article' => $article])

{{-- Component (Laravel 7+) --}}
<x-alert type="success" message="Article saved!" />

{{-- Conditional includes --}}
@includeIf('partials.sidebar')
@includeWhen($user->isAdmin(), 'partials.admin-menu')
```

### **Authentication Helpers**
```blade
@auth
    <p>Welcome, {{ auth()->user()->name }}!</p>
    
    @if(auth()->user()->role === 'admin')
        <a href="{{ route('admin.dashboard') }}">Admin Panel</a>
    @endif
@endauth

@guest
    <a href="{{ route('login') }}">Login</a>
@endguest

{{-- Role-based content --}}
@can('edit', $article)
    <a href="{{ route('articles.edit', $article) }}">Edit</a>
@endcan
```

### **Asset Helpers**
```blade
{{-- CSS/JS --}}
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<script src="{{ asset('js/app.js') }}"></script>

{{-- Images --}}
<img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}">

{{-- Routes --}}
<a href="{{ route('articles.show', $article) }}">View Article</a>
<form action="{{ route('articles.destroy', $article) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit">Delete</button>
</form>
```

---

## 🔗 **INTEGRASI SEMUA KOMPONEN**

### **Flow: Request → Controller → Model → View**

```php
// 1. Route
Route::get('/articles', [ArticleController::class, 'index']);

// 2. Controller
public function index()
{
    $articles = Article::with(['user', 'kategori'])
                      ->where('status', 'published')
                      ->latest()
                      ->get();
    
    return view('articles.index', compact('articles'));
}

// 3. Model (relationships)
class Article extends Model
{
    public function user() {
        return $this->belongsTo(User::class);
    }
}

// 4. View
@foreach($articles as $article)
    <h3>{{ $article->title }}</h3>
    <p>By: {{ $article->user->name }}</p>
@endforeach
```

---

## 🎯 **BEST PRACTICES**

### **Controller:**
- Keep controllers thin
- Use form requests for validation
- Return appropriate responses

### **Model:**
- Use relationships
- Define fillable/guarded
- Use accessors/mutators when needed

### **Migration:**
- Use descriptive names
- Always have down() method
- Use foreign key constraints

### **View:**
- Escape output with {{ }}
- Use layouts and partials
- Keep logic minimal

### **Array:**
- Use associative arrays for clarity
- Leverage collection methods
- Validate array inputs

---

**🚀 Happy Coding!**