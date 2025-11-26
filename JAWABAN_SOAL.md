# JAWABAN SOAL WEB MADING - DOKUMENTASI KODE

## 🔍 CARA MENCARI JAWABAN DI KODE:
- **SOAL 1** = Cari komentar `<!-- SOAL1: ... -->`
- **SOAL 2** = Cari komentar `<!-- SOAL2: ... -->`  
- **SOAL 3** = Cari komentar `<!-- SOAL3: ... -->`
- **SOAL 4** = Cari komentar `<!-- SOAL4: ... -->`
- **SOAL 5** = Cari komentar `<!-- SOAL5: ... -->`

---

## 📝 SOAL 1: TIPE DATA DAN CONTROL PROGRAM

### Tipe Data:
- **String**: `Auth::user()->name`, `$article->title`
- **Integer**: `Auth::id()`, `count()`
- **Boolean**: `Auth::check()`, `$isLiked`
- **Array/Collection**: `$articles`, `$article->likes`
- **Null Coalescing**: `$article->kategori ?? 'Artikel'`

### Control Program:
- **Conditional**: `@if`, `@else`, `@auth`
- **Looping**: `@forelse`, `@endforelse`
- **Ternary Operator**: `condition ? true : false`

---

## 📝 SOAL 2: STRUKTUR DATA DAN AKSES

### Struktur Data:
- **Database Relations**: One-to-Many, Many-to-Many
- **Eloquent Models**: User, Article, Like, Kategori
- **Collections**: Laravel Collections

### Akses Data:
- **Direct Access**: `$article->title`
- **Relationship Access**: `$article->user->name`
- **Collection Methods**: `$articles->count()`
- **Query Builder**: `Article::where()`

---

## 📝 SOAL 3: AKSES FILE

### File Operations:
- **Asset Access**: `asset('img/file.jpg')`
- **File Upload**: `$request->file('photo')`
- **File Validation**: `'photo' => 'image|mimes:jpeg,png'`
- **Error Handling**: `onerror="fallback"`

---

## 📝 SOAL 4: KOMPRESI PROGRAM

### Optimizations:
- **Blade Compilation**: Template caching
- **String Limiting**: `Str::limit()`
- **Eager Loading**: `with(['user', 'likes'])`
- **Asset Optimization**: Minified CSS/JS

---

## 📝 SOAL 5: IDENTIFIKASI HASIL EKSEKUSI

### Monitoring:
- **AJAX Responses**: JSON success/error
- **Real-time Updates**: Like counter
- **Error Handling**: Try-catch blocks
- **User Feedback**: Success messages