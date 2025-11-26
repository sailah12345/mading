# 🎯 CHEAT SHEET PRESENTASI - WEB MADING

## 📋 **STRUKTUR CODINGAN UTAMA**

### **1. 🏠 HOMEPAGE/ARTIKEL (articles/index.blade.php)**

#### **A. Blade Template Structure:**
```php
@extends('index')           // Extend layout utama
@section('content')         // Define content section
// HTML content here
@endsection
@push('scripts')           // Push JavaScript ke layout
```

#### **B. Loop Artikel:**
```php
@if(isset($articles) && $articles->count() > 0)
    @foreach($articles as $article)
        // Display each article
    @endforeach
@else
    // No articles message
@endif
```

#### **C. Like System Logic:**
```php
@auth
    @php
        $isLiked = $article->likes->where('user_id', Auth::id())->count() > 0;
        $likesCount = $article->likes->count();
    @endphp
    <button class="btn {{ $isLiked ? 'btn-danger' : 'btn-outline-danger' }} like-btn">
@else
    <span class="btn btn-outline-secondary disabled">
@endauth
```

#### **D. JavaScript Like System:**
```javascript
fetch(`/like/${articleId}`, {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json'
    }
})
.then(response => response.json())
.then(data => {
    // Update UI based on response
});
```

---

### **2. 👨‍💼 ADMIN DASHBOARD (dashboard/admin.blade.php)**

#### **A. Statistics Cards:**
```php
<h3>{{ App\Models\User::count() }}</h3>           // Total users
<h3>{{ App\Models\Article::count() }}</h3>        // Total articles
<h3>{{ App\Models\Like::count() }}</h3>           // Total likes
```

#### **B. User Management Table:**
```php
@foreach(App\Models\User::latest()->take(5)->get() as $user)
<tr>
    <td>{{ $user->name }}</td>
    <td>{{ $user->email }}</td>
    <td>
        <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : 'info' }}">
            {{ $user->role }}
        </span>
    </td>
</tr>
@endforeach
```

#### **C. Article Verification Modal:**
```php
@foreach(App\Models\Article::where('status', 'pending')->get() as $article)
<tr>
    <td>{{ $article->title }}</td>
    <td>{{ $article->user->name }}</td>
    <td>
        <form action="{{ route('admin.articles.approve', $article->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">Approve</button>
        </form>
    </td>
</tr>
@endforeach
```

---

### **3. 👨‍🏫 GURU DASHBOARD (dashboard/guru.blade.php)**

#### **A. Article Moderation:**
```php
@foreach(App\Models\Article::where('status', 'pending')->latest()->get() as $article)
<tr>
    <td>
        @if($article->photo)
            <img src="{{ asset('storage/' . $article->photo) }}" style="width: 50px;">
        @endif
    </td>
    <td>{{ $article->title }}</td>
    <td>{{ $article->user->name }}</td>
    <td>
        <form action="{{ route('guru.articles.approve', $article->id) }}" method="POST">
            @csrf
            <button class="btn btn-success">Approve</button>
        </form>
    </td>
</tr>
@endforeach
```

#### **B. Student Statistics:**
```php
@foreach(App\Models\User::where('role', 'siswa')->latest()->get() as $siswa)
<tr>
    <td>{{ $siswa->name }}</td>
    <td>{{ $siswa->articles->count() }}</td>
    <td>{{ $siswa->articles->where('status', 'published')->count() }}</td>
    <td>{{ $siswa->articles->where('status', 'pending')->count() }}</td>
</tr>
@endforeach
```

---

### **4. 👨‍🎓 SISWA DASHBOARD (dashboard/siswa.blade.php)**

#### **A. Personal Articles:**
```php
@foreach(Auth::user()->articles()->latest()->get() as $article)
<tr>
    <td>{{ $article->title }}</td>
    <td>
        <span class="badge bg-{{ $article->status == 'published' ? 'success' : 'warning' }}">
            {{ ucfirst($article->status) }}
        </span>
    </td>
    <td>{{ $article->likes->count() }}</td>
    <td>{{ $article->created_at->format('d/m/Y') }}</td>
</tr>
@endforeach
```

---

### **5. 🎛️ CONTROLLER LOGIC**

#### **A. AdminController.php - User Management:**
```php
public function storeUser(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'role' => 'required|in:siswa,guru',
        'password' => 'required|min:8|confirmed',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'password' => Hash::make($request->password),
    ]);

    return redirect()->route('admin.dashboard')->with('success', 'User berhasil ditambahkan!');
}
```

#### **B. ArticleController.php - Article CRUD:**
```php
public function store(Request $request)
{
    $request->validate([
        'title' => 'required|max:255',
        'content' => 'required',
        'id_kategori' => 'required|exists:kategori,id_kategori',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    $photoPath = null;
    if ($request->hasFile('photo')) {
        $photoPath = $request->file('photo')->store('articles', 'public');
    }

    Article::create([
        'title' => $request->title,
        'content' => $request->content,
        'id_kategori' => $request->id_kategori,
        'user_id' => Auth::id(),
        'photo' => $photoPath,
        'status' => 'pending'
    ]);

    return redirect()->route('articles.index')->with('success', 'Artikel berhasil dibuat!');
}
```

#### **C. SimpleLikeController.php - Like System:**
```php
public function like(Request $request, $articleId)
{
    if (!Auth::check()) {
        return response()->json(['success' => false, 'message' => 'Login required'], 401);
    }
    
    $userId = Auth::id();
    $existingLike = Like::where('user_id', $userId)
                       ->where('article_id', $articleId)
                       ->first();
    
    if ($existingLike) {
        $existingLike->delete();
        $liked = false;
    } else {
        Like::create([
            'user_id' => $userId,
            'article_id' => $articleId
        ]);
        $liked = true;
    }
    
    $likesCount = Like::where('article_id', $articleId)->count();
    
    return response()->json([
        'success' => true,
        'liked' => $liked,
        'likes_count' => $likesCount
    ]);
}
```

---

### **6. 🗃️ MODEL RELATIONSHIPS**

#### **A. User.php:**
```php
protected $fillable = [
    'name', 'email', 'password', 'role',
];

protected $hidden = [
    'password', 'remember_token',
];

public function articles()
{
    return $this->hasMany(Article::class);
}
```

#### **B. Article.php:**
```php
protected $fillable = [
    'title', 'content', 'photo', 'status', 'user_id', 'id_kategori',
];

public function user()
{
    return $this->belongsTo(User::class);
}

public function kategori()
{
    return $this->belongsTo(Kategori::class, 'id_kategori');
}

public function likes()
{
    return $this->hasMany(Like::class);
}
```

---

### **7. 🛣️ ROUTES (web.php)**

#### **A. Authentication Routes:**
```php
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
```

#### **B. Article Routes:**
```php
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');

Route::middleware('auth')->group(function () {
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::post('/like/{articleId}', [SimpleLikeController::class, 'like'])->name('simple.like');
});
```

#### **C. Admin Routes:**
```php
Route::middleware('auth')->group(function () {
    Route::post('/admin/articles/{id}/approve', [AdminController::class, 'approveArticle']);
    Route::post('/admin/articles/{id}/reject', [AdminController::class, 'rejectArticle']);
});
```

---

## 🎤 **PENJELASAN UNTUK PRESENTASI**

### **Ketika Ditanya tentang Kode:**

#### **1. "Bagaimana sistem like bekerja?"**
> "Sistem like menggunakan Ajax untuk real-time update. Ketika user klik tombol like, JavaScript mengirim POST request ke `/like/{articleId}`, controller mengecek apakah user sudah like atau belum, lalu toggle status like di database dan return JSON response untuk update UI."

#### **2. "Bagaimana sistem role bekerja?"**
> "Sistem role menggunakan middleware dan blade directive. Di route ada `middleware('auth')`, di blade ada `@auth` dan `@if(Auth::user()->role == 'admin')`, dan di controller ada validation role untuk akses tertentu."

#### **3. "Bagaimana upload foto artikel?"**
> "Upload foto menggunakan `$request->file('photo')->store('articles', 'public')` yang menyimpan file ke `storage/app/public/articles`, lalu path disimpan di database dan ditampilkan dengan `asset('storage/' . $article->photo)`."

#### **4. "Bagaimana sistem moderasi?"**
> "Artikel siswa otomatis status 'pending', guru/admin bisa approve jadi 'published' atau reject jadi 'rejected'. Hanya artikel 'published' yang tampil di halaman publik."

---

## 🚨 **TIPS MENJAWAB PERTANYAAN KODE:**

1. **Tunjukkan file yang relevan** - Buka file di editor
2. **Jelaskan flow logic** - Dari route → controller → model → view
3. **Highlight security** - Validation, authentication, CSRF
4. **Sebutkan best practices** - MVC pattern, relationships, middleware

**Siap untuk presentasi! 🚀**