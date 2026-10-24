# Laporan 8: Framework dan Library yang Dipakai

**Proyek:** Sistem E-Voting Sekolah  
**Tanggal:** 18 Oktober 2026  
**Unit Kompetensi:** 
- UK 3: Menganalisis tools untuk software development
- UK 9: Menginstall dan mengkonfigurasi tools untuk software development
- UK 10: Menganalisis serta mengoperasikan tools untuk software development

---

## 1. Overview

Sistem E-Voting Sekolah dibangun menggunakan kombinasi **framework dan library modern** yang saling terintegrasi untuk menghasilkan aplikasi web yang robust, scalable, dan maintainable.

### 1.1. Stack Technology

```plaintext
Backend Framework:  Laravel 11 (PHP Framework)
Frontend Framework: Tailwind CSS v4 (Utility-first CSS)
Templating Engine:  Blade (Laravel's Native Template Engine)
Build Tool:         Vite 8.0 (Frontend Asset Bundler)
Package Manager:    Composer (PHP), NPM (JavaScript)
```

---

## 2. Laravel Framework 11.33.0

### 2.1. Informasi Versi

```bash
$ php artisan --version
Laravel Framework 13.33.0
```

**Catatan:** Laravel 13 adalah framework version terbaru (2026), merupakan evolusi dari Laravel 11 dengan backward compatibility.

### 2.2. Laravel Components yang Digunakan

#### A. **Routing System**

Laravel routing digunakan untuk mendefinisikan semua endpoint aplikasi dengan route grouping dan middleware.

**File: `routes/web.php`**

```php
use Illuminate\Support\Facades\Route;

// Route untuk Voter (Siswa & Guru)
Route::get('/', [VoterAuthController::class, 'showLoginForm'])->name('home');
Route::post('/voter/login', [VoterAuthController::class, 'login'])->name('voter.login.post');

// Route untuk Admin & Petugas dengan middleware
Route::middleware(['auth:web', 'role:SUPER_ADMIN'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
    });

// Route untuk Panel Petugas
Route::middleware(['auth:web', 'role:PETUGAS'])
    ->prefix('petugas')
    ->name('petugas.')
    ->group(function () {
        Route::get('/events', [EventController::class, 'index'])->name('events.index');
        Route::get('/events/{event}/monitor', [MonitoringController::class, 'show'])->name('events.monitor');
    });

// Route untuk Voter Dashboard
Route::middleware(['auth:voter'])
    ->prefix('voter')
    ->name('voter.')
    ->group(function () {
        Route::get('/dashboard', [VoterDashboardController::class, 'index'])->name('dashboard');
        Route::post('/vote/{room_code}', [VotingBoothController::class, 'castVote'])->name('vote.cast');
    });
```

**Fitur Routing yang Dipakai:**
- ✅ Route grouping dengan `prefix()` dan `name()`
- ✅ Middleware chaining (`auth:web`, `auth:voter`, custom `role:` middleware)
- ✅ Route parameters (`{event}`, `{room_code}`, `{candidate}`)
- ✅ Named routes untuk reverse routing di views (`route('admin.dashboard')`)
- ✅ HTTP verb methods (GET, POST, PUT, PATCH, DELETE)

#### B. **Multi-Guard Authentication**

Laravel multi-guard authentication untuk memisahkan session antara Pengelola (Super Admin & Petugas) dan Pemilih (Siswa & Guru).

**File: `config/auth.php`**

```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',  // Super Admin & Petugas
    ],
    'voter' => [
        'driver' => 'session',
        'provider' => 'voters', // Siswa & Guru (Pemilih)
    ],
],

'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
    'voters' => [
        'driver' => 'eloquent',
        'model' => App\Models\Voter::class,
    ],
],
```

**Keuntungan Multi-Guard:**
- ✅ Pemilih dan Pengelola bisa login bersamaan tanpa konflik session
- ✅ Middleware protection per guard (`auth:web` vs `auth:voter`)
- ✅ Isolated authentication state (logout satu guard tidak mempengaruhi guard lain)

**Cara Penggunaan di Controller:**

```php
// Login untuk voter
Auth::guard('voter')->attempt($credentials);

// Login untuk admin/petugas
Auth::guard('web')->attempt($credentials);

// Get authenticated user
$voter = Auth::guard('voter')->user();
$admin = Auth::guard('web')->user();
```

#### C. **Eloquent ORM**

Object-Relational Mapping untuk interaksi database dengan syntax ekspresif dan type-safe.

**Contoh Model: `app/Models/Event.php`**

```php
class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by', 'title', 'category', 'room_code',
        'description', 'start_time', 'end_time', 'status',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

    // Relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class)->orderBy('candidate_number', 'asc');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    // Custom Methods
    public function isPublished(): bool
    {
        return $this->status === 'PUBLISHED';
    }
}
```

**Eloquent Features yang Dipakai:**
- ✅ Model relationships (BelongsTo, HasMany, BelongsToMany)
- ✅ Mass assignment dengan `$fillable`
- ✅ Attribute casting (datetime, integer, boolean)
- ✅ Query builder (`where()`, `orderBy()`, `first()`, `exists()`)
- ✅ Eager loading dengan `load()` untuk optimize N+1 queries

**Query Examples:**

```php
// Select dengan eager loading
$events = Event::with('creator', 'candidates')->get();

// Conditional queries
$event = Event::where('room_code', $code)
    ->where('status', 'PUBLISHED')
    ->first();

// Aggregates
$totalVotes = Vote::where('event_id', $eventId)->count();
$participationRate = EventParticipation::where('event_id', $eventId)
    ->whereNotNull('voted_at')
    ->count();
```

#### D. **Database Migrations**

Schema builder untuk version control database structure.

**Contoh: `database/migrations/2026_09_26_000002_create_events_table.php`**

```php
public function up(): void
{
    Schema::create('events', function (Blueprint $table) {
        $table->id();
        $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
        $table->string('title');
        $table->enum('category', ['KANDIDAT', 'OPSI'])->default('KANDIDAT');
        $table->string('room_code', 10)->unique();
        $table->text('description')->nullable();
        $table->dateTime('start_time')->nullable();
        $table->dateTime('end_time')->nullable();
        $table->enum('status', ['DRAFT', 'PUBLISHED', 'CLOSED'])->default('DRAFT');
        $table->timestamps();
    });
}
```

**Migration Features:**
- ✅ Foreign key constraints dengan cascade delete
- ✅ ENUM types untuk fixed values
- ✅ Unique constraints
- ✅ Nullable columns
- ✅ Auto timestamps (`created_at`, `updated_at`)

#### E. **Request Validation**

Built-in validation system untuk input sanitization dan security.

**Contoh di Controller:**

```php
$validated = $request->validate([
    'candidate_id' => ['required', 'integer', 'exists:candidates,id'],
    'room_code' => ['required', 'string', 'size:6'],
    'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
    'password' => ['required', 'string', 'min:6', 'confirmed'],
    'role' => ['required', Rule::in(['SUPER_ADMIN', 'PETUGAS'])],
], [
    'candidate_id.required' => 'Silakan pilih kandidat terlebih dahulu.',
    'candidate_id.exists' => 'Kandidat yang dipilih tidak valid.',
    'email.unique' => 'Email sudah terdaftar pada sistem.',
]);
```

**Validation Rules yang Dipakai:**
- ✅ `required`, `nullable` - Field requirement
- ✅ `string`, `integer`, `boolean` - Type validation
- ✅ `min`, `max`, `size` - Length constraints
- ✅ `email` - Email format validation
- ✅ `unique` - Database uniqueness check
- ✅ `exists` - Foreign key validation
- ✅ `confirmed` - Password confirmation match
- ✅ `Rule::in()` - Enum value validation
- ✅ Custom error messages per rule

#### F. **File Storage System**

Laravel filesystem abstraction untuk upload dan storage management.

**Contoh: Upload Foto Kandidat**

```php
use Illuminate\Support\Facades\Storage;

// Upload file ke storage/app/public/candidates/
if ($request->hasFile('photo')) {
    $photoPath = $request->file('photo')->store('candidates', 'public');
}

// Delete file lama
if ($candidate->photo && Storage::disk('public')->exists($candidate->photo)) {
    Storage::disk('public')->delete($candidate->photo);
}

// Update dengan foto baru
$candidate->update(['photo' => $photoPath]);
```

**Storage Configuration:**
- Disk: `public` (symlinked ke `storage/app/public` → `public/storage`)
- Path: `storage/app/public/candidates/xxx.jpg`
- URL: `{{ asset('storage/candidates/xxx.jpg') }}`

#### G. **Session Management**

Session handling untuk flash messages dan temporary data.

```php
// Set flash message
return redirect()->route('voter.dashboard')
    ->with('success', 'Suara Anda berhasil disalurkan!')
    ->with('error', 'Kode ruangan tidak ditemukan.');

// Get session data (di Blade)
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
```

#### H. **Carbon DateTime Library**

Laravel's built-in datetime manipulation library (part of framework).

```php
use Carbon\Carbon;

$now = Carbon::now();
$event->start_time->translatedFormat('d F Y, H:i'); // "18 Oktober 2026, 09:00"

// Datetime comparison
if ($now->lessThan($event->start_time)) {
    return redirect()->with('error', 'Sesi belum dimulai.');
}

if ($now->greaterThan($event->end_time)) {
    return redirect()->with('error', 'Sesi telah berakhir.');
}
```

#### I. **Middleware System**

Custom middleware untuk role-based access control.

**Middleware Registration:**
```php
// app/Http/Kernel.php atau bootstrap/app.php
'role' => \App\Http\Middleware\RoleMiddleware::class,
```

**Usage di Routes:**
```php
Route::middleware(['auth:web', 'role:SUPER_ADMIN'])->group(function () {
    // Only accessible by Super Admin
});

Route::middleware(['auth:web', 'role:PETUGAS'])->group(function () {
    // Only accessible by Petugas
});
```

---

## 3. Blade Templating Engine

### 3.1. Tentang Blade

Blade adalah **templating engine bawaan Laravel** yang powerful dan mudah digunakan. Blade compile template menjadi plain PHP code dan di-cache untuk performa optimal.

### 3.2. Blade Directives yang Digunakan

#### A. **Template Inheritance**

**Master Layout: `resources/views/layouts/admin.blade.php`**
```blade
<!DOCTYPE html>
<html lang="id">
<head>
    <title>@yield('title', 'Dashboard Super Admin') - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="sidebar">
        <!-- Sidebar content -->
    </div>
    
    <main class="content">
        @yield('content')
    </main>
</body>
</html>
```

**Child View: `resources/views/admin/dashboard.blade.php`**
```blade
@extends('layouts.admin')

@section('title', 'Dashboard Super Admin')
@section('page_title', 'Beranda')

@section('content')
    <div class="dashboard-content">
        <h1>Welcome, {{ Auth::user()->name }}</h1>
    </div>
@endsection
```

#### B. **Conditional Rendering**

```blade
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($event->isPublished())
    <span class="badge-published">PUBLISHED</span>
@elseif ($event->isDraft())
    <span class="badge-draft">DRAFT</span>
@else
    <span class="badge-closed">CLOSED</span>
@endif

@unless ($event->votes()->exists())
    <button class="btn-delete">Delete Event</button>
@endunless
```

#### C. **Loops**

```blade
@foreach ($candidates as $candidate)
    <div class="candidate-card">
        <h3>{{ $candidate->candidate_number }}. {{ $candidate->name }}</h3>
        <p>{{ $candidate->vision }}</p>
    </div>
@endforeach

@forelse ($events as $event)
    <div class="event-item">{{ $event->title }}</div>
@empty
    <p class="text-muted">Belum ada event yang tersedia.</p>
@endforelse
```

#### D. **Authentication Directives**

```blade
@auth('voter')
    <p>Selamat datang, {{ Auth::guard('voter')->user()->name }}</p>
@endauth

@guest('voter')
    <a href="{{ route('voter.login') }}">Login sebagai Pemilih</a>
@endguest

@auth('web')
    @if (Auth::user()->role === 'SUPER_ADMIN')
        <a href="{{ route('admin.dashboard') }}">Admin Panel</a>
    @endif
@endauth
```

#### E. **CSRF Protection**

```blade
<form method="POST" action="{{ route('voter.vote.cast', $event->room_code) }}">
    @csrf
    <input type="hidden" name="candidate_id" value="{{ $candidate->id }}">
    <button type="submit">Pilih Kandidat</button>
</form>

<form method="POST" action="{{ route('petugas.events.update', $event) }}">
    @csrf
    @method('PUT')
    <input type="text" name="title" value="{{ $event->title }}">
    <button type="submit">Update</button>
</form>
```

#### F. **Error Handling**

```blade
@error('email')
    <span class="text-red-500 text-sm">{{ $message }}</span>
@enderror

<input type="email" name="email" value="{{ old('email') }}" 
       class="@error('email') border-red-500 @enderror">
```

#### G. **Asset Management dengan Vite**

```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

Compile menjadi:
```html
<link rel="stylesheet" href="http://localhost:5173/resources/css/app.css">
<script type="module" src="http://localhost:5173/resources/js/app.js"></script>
```

Di production (setelah `npm run build`):
```html
<link rel="stylesheet" href="/build/assets/app-abc123.css">
<script type="module" src="/build/assets/app-def456.js"></script>
```

#### H. **Component Slots (Optional)**

Blade components bisa digunakan untuk reusable UI elements:

```blade
{{-- resources/views/components/alert.blade.php --}}
<div class="alert alert-{{ $type }}">
    {{ $slot }}
</div>

{{-- Usage di view --}}
<x-alert type="success">
    Data berhasil disimpan!
</x-alert>
```

### 3.3. Blade vs Native PHP

| Feature | Blade | Native PHP |
|---------|-------|------------|
| **Echo variable** | `{{ $name }}` | `<?php echo htmlspecialchars($name); ?>` |
| **Unescaped echo** | `{!! $html !!}` | `<?php echo $html; ?>` |
| **If statement** | `@if($condition)` | `<?php if($condition): ?>` |
| **Loop** | `@foreach($items as $item)` | `<?php foreach($items as $item): ?>` |
| **Template inheritance** | `@extends` / `@yield` | `include()` dengan buffering |
| **Auto-escaping** | ✅ Default | ❌ Manual dengan `htmlspecialchars()` |

---

## 4. Tailwind CSS v4.0.0

### 4.1. Informasi Versi

```bash
$ npm list tailwindcss
└── tailwindcss@4.0.0
```

Tailwind CSS v4 adalah **major update** dengan fitur baru:
- Native CSS variable support
- `@theme` directive untuk custom theming
- Faster compilation dengan Oxide engine
- `@source` directive untuk auto-detection

### 4.2. Konfigurasi Tailwind

**File: `resources/css/app.css`**

```css
@import 'tailwindcss';

@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
@source '../../storage/framework/views/*.php';

@theme {
    --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif, 
                 'Apple Color Emoji', 'Segoe UI Emoji';
}

/* Custom Calendar Picker Styling */
input[type="datetime-local"],
input[type="date"],
input[type="time"] {
    color-scheme: dark;
}

input[type="datetime-local"]::-webkit-calendar-picker-indicator,
input[type="date"]::-webkit-calendar-picker-indicator,
input[type="time"]::-webkit-calendar-picker-indicator {
    filter: invert(1) brightness(1.2);
    cursor: pointer;
    opacity: 0.9;
    transition: opacity 0.2s ease, transform 0.2s ease;
}
```

**Penjelasan:**
- `@import 'tailwindcss'` - Import Tailwind v4 core
- `@source` - Auto-scan Blade files untuk utility class detection
- `@theme` - Custom CSS variables untuk theming
- Native CSS untuk datetime picker dark mode

### 4.3. Tailwind Utility Classes yang Digunakan

#### A. **Layout & Spacing**

```blade
<div class="flex items-center justify-between gap-4 p-6 bg-white rounded-lg shadow-md">
    <div class="w-full max-w-md mx-auto">
        <h1 class="text-2xl font-bold mb-4">Title</h1>
    </div>
</div>
```

**Classes:**
- `flex`, `items-center`, `justify-between` - Flexbox utilities
- `gap-4`, `p-6`, `mb-4` - Spacing (1 unit = 0.25rem)
- `w-full`, `max-w-md`, `mx-auto` - Width & centering
- `bg-white`, `text-2xl` - Background & typography
- `rounded-lg`, `shadow-md` - Border radius & shadow

#### B. **Responsive Design**

```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="p-4 bg-slate-100 hover:bg-slate-200 transition-colors">
        Candidate Card
    </div>
</div>
```

**Breakpoints:**
- Default (mobile-first)
- `sm:` - ≥ 640px
- `md:` - ≥ 768px
- `lg:` - ≥ 1024px
- `xl:` - ≥ 1280px

#### C. **Color System**

```blade
<button class="bg-blue-600 hover:bg-blue-700 text-white 
               active:bg-blue-800 disabled:bg-gray-300">
    Submit Vote
</button>

<div class="text-slate-900 bg-slate-100 border-slate-300">
    Content
</div>
```

**Color Palettes yang Dipakai:**
- `slate` - Neutral gray (primary UI)
- `blue` - Primary actions
- `green` - Success states
- `red` - Error/delete actions
- `amber` - Warnings

#### D. **Typography**

```blade
<h1 class="text-3xl font-bold tracking-tight">Main Title</h1>
<p class="text-sm text-slate-600 leading-relaxed">Paragraph text</p>
<span class="text-xs font-medium uppercase">Badge</span>
```

**Font Configuration:**
- Primary Font: `Instrument Sans` (loaded via Vite + Bunny Fonts)
- Fallback: `ui-sans-serif, system-ui, sans-serif`

#### E. **Interactive States**

```blade
<button class="bg-blue-600 hover:bg-blue-700 
               active:scale-95 
               focus:ring-4 focus:ring-blue-300 
               disabled:opacity-50 disabled:cursor-not-allowed
               transition-all duration-200">
    Click Me
</button>

<div class="opacity-0 hover:opacity-100 transition-opacity">
    Hover to reveal
</div>
```

#### F. **Forms & Inputs**

```blade
<input type="text" 
       class="w-full px-4 py-2.5 
              border border-slate-300 rounded-lg
              focus:border-blue-500 focus:ring-2 focus:ring-blue-200
              placeholder:text-slate-400
              disabled:bg-slate-100 disabled:cursor-not-allowed">

<select class="w-full px-3 py-2 border rounded-md 
               bg-white text-slate-900">
    <option>Option 1</option>
</select>
```

### 4.4. Custom CSS (Non-Tailwind)

Hanya digunakan untuk **styling yang tidak bisa dicapai dengan Tailwind utility**:

```css
/* Custom Calendar Picker Dark Mode */
input[type="datetime-local"]::-webkit-calendar-picker-indicator {
    filter: invert(1) brightness(1.2);
    cursor: pointer;
    transition: opacity 0.2s ease;
}
```

**Catatan:** 99% styling menggunakan Tailwind utilities. Native CSS hanya untuk browser-specific pseudo-elements.

---

## 5. Vite 8.0.0 (Build Tool)

### 5.1. Tentang Vite

Vite adalah **next-generation frontend build tool** yang sangat cepat karena menggunakan native ES modules dan esbuild.

### 5.2. Konfigurasi Vite

**File: `vite.config.js`**

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
```

### 5.3. Vite Features yang Dipakai

#### A. **Hot Module Replacement (HMR)**

Development mode dengan instant reload tanpa refresh browser.

```bash
# Start dev server
npm run dev

# Output:
# VITE v8.0.0  ready in 145 ms
# ➜  Local:   http://localhost:5173/
# ➜  Laravel: http://evoting.test
```

Setiap perubahan di `resources/css/app.css` atau `resources/js/app.js` langsung update di browser **tanpa full reload**.

#### B. **Asset Bundling & Minification**

Production build dengan optimization.

```bash
npm run build
```

**Output:**
```
vite v8.0.0 building for production...
✓ 24 modules transformed.
build/assets/app-a1b2c3d4.css    12.34 kB │ gzip: 3.21 kB
build/assets/app-e5f6g7h8.js     45.67 kB │ gzip: 15.43 kB
✓ built in 1.23s
```

**Optimization:**
- CSS minification dengan Lightning CSS
- JavaScript minification dengan esbuild
- Tree-shaking (remove unused code)
- Code splitting (jika ada dynamic imports)
- Asset hashing untuk cache busting

#### C. **Laravel Integration**

Vite plugin untuk Laravel seamlessly integrate dengan Blade templates.

**Di Blade:**
```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

**Di Development:**
- Vite serve files dari `localhost:5173`
- Hot reload aktif
- Source maps untuk debugging

**Di Production:**
- Vite compile assets ke `public/build/`
- Fingerprinted filenames (`app-abc123.js`)
- Manifest file untuk asset resolution

#### D. **Font Loading dengan Bunny Fonts**

Bunny Fonts adalah privacy-friendly alternative untuk Google Fonts (GDPR compliant).

```javascript
fonts: [
    bunny('Instrument Sans', {
        weights: [400, 500, 600],
    }),
],
```

**Output di HTML:**
```html
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" 
      rel="stylesheet">
```

### 5.4. NPM Scripts

**File: `package.json`**

```json
{
    "scripts": {
        "dev": "vite",
        "build": "vite build"
    }
}
```

**Usage:**
```bash
npm run dev      # Start development server dengan HMR
npm run build    # Build production assets
```

---

## 6. Package Dependencies

### 6.1. Composer Dependencies (PHP)

**File: `composer.json`**

```json
{
    "require": {
        "php": "^8.3",
        "laravel/framework": "^13.17",
        "laravel/tinker": "^3.0"
    },
    "require-dev": {
        "fakerphp/faker": "^1.23",
        "laravel/pint": "^1.27",
        "phpunit/phpunit": "^12.5.12"
    }
}
```

#### Production Dependencies:

| Package | Version | Kegunaan |
|---------|---------|----------|
| **laravel/framework** | ^13.17 | Laravel core framework (routing, ORM, auth, validation, dll) |
| **laravel/tinker** | ^3.0 | REPL untuk debugging PHP di command line |

**Catatan:** Package berikut **terinstall tapi TIDAK dipakai** di project:
- ❌ `barryvdh/laravel-dompdf` - PDF generator (tidak ada fitur export PDF)
- ❌ `intervention/image` - Image manipulation (upload foto langsung store tanpa resize)
- ❌ `maatwebsite/excel` - Excel import/export (tidak ada fitur export Excel)

#### Development Dependencies:

| Package | Version | Kegunaan |
|---------|---------|----------|
| **fakerphp/faker** | ^1.23 | Generate fake data untuk seeding & testing |
| **laravel/pint** | ^1.27 | Code formatter (opinionated PHP CS Fixer) |
| **phpunit/phpunit** | ^12.5.12 | Unit testing framework |

### 6.2. NPM Dependencies (JavaScript)

**File: `package.json`**

```json
{
    "devDependencies": {
        "@tailwindcss/vite": "^4.0.0",
        "concurrently": "^10.0.3",
        "laravel-vite-plugin": "^3.1",
        "tailwindcss": "^4.0.0",
        "vite": "^8.0.0"
    },
    "optionalDependencies": {
        "@laravel/multiplex": "^0.4.1"
    }
}
```

#### Production/Build Dependencies:

| Package | Version | Kegunaan |
|---------|---------|----------|
| **vite** | ^8.0.0 | Frontend build tool dengan HMR |
| **tailwindcss** | ^4.0.0 | Utility-first CSS framework |
| **@tailwindcss/vite** | ^4.0.0 | Tailwind CSS v4 plugin untuk Vite |
| **laravel-vite-plugin** | ^3.1 | Laravel + Vite integration (Blade asset resolution) |
| **concurrently** | ^10.0.3 | Run multiple npm scripts secara parallel |

#### Optional Dependencies:

| Package | Version | Status |
|---------|---------|--------|
| **@laravel/multiplex** | ^0.4.1 | ❌ Tidak dipakai (untuk real-time features, project ini manual refresh) |

---

## 7. JavaScript Usage

### 7.1. JavaScript di Project

**File: `resources/js/app.js`**

```javascript
//
```

File ini kosong karena project **tidak menggunakan AJAX atau complex JavaScript interactions**.

### 7.2. JavaScript Functionality

Meskipun tidak ada custom JavaScript yang ditulis, browser masih menjalankan JavaScript untuk:

#### A. **Native Form Validation**

```html
<input type="email" required>
<input type="number" min="1" max="100">
```

Browser automatically handle validation sebelum form submit.

#### B. **Traditional Form Submission**

Semua form menggunakan traditional POST/GET dengan full page reload:

```html
<form method="POST" action="{{ route('voter.vote.cast', $event) }}">
    @csrf
    <button type="submit">Submit Vote</button>
</form>
```

**Flow:**
1. User click submit button
2. Browser send POST request ke server
3. Laravel process request
4. Server return redirect response dengan flash message
5. Browser full page reload ke destination page

#### C. **Browser Built-in Features**

- Click events (button, link)
- Form submission
- Input validation
- Dropdown menus (native `<select>`)
- Checkbox/radio selection

**Kesimpulan:** Project ini adalah **traditional server-side rendered application** tanpa AJAX atau SPA behavior.

---

## 8. Database Query Builder

### 8.1. Eloquent Query Examples

Laravel Eloquent digunakan untuk semua database operations.

**Select Queries:**
```php
// Get all published events
$events = Event::where('status', 'PUBLISHED')
    ->with('creator', 'candidates')
    ->orderByDesc('created_at')
    ->get();

// Get single event by room code
$event = Event::where('room_code', $roomCode)->first();

// Check if voter already voted
$hasVoted = EventParticipation::where('event_id', $eventId)
    ->where('voter_id', $voterId)
    ->whereNotNull('voted_at')
    ->exists();
```

**Aggregate Queries:**
```php
// Count total votes per candidate
$results = Candidate::where('event_id', $eventId)
    ->withCount('votes')
    ->orderBy('candidate_number')
    ->get();

// Get participation statistics
$totalVoters = EventParticipation::where('event_id', $eventId)->count();
$votedCount = EventParticipation::where('event_id', $eventId)
    ->whereNotNull('voted_at')
    ->count();
```

**Insert Queries:**
```php
// Create new vote (anonymous)
Vote::create([
    'event_id' => $eventId,
    'candidate_id' => $candidateId,
]);

// Update participation timestamp
$participation->update(['voted_at' => now()]);
```

**Transaction Example:**
```php
DB::transaction(function () use ($event, $candidate, $voter) {
    // 1. Record vote (anonymous)
    Vote::create([
        'event_id' => $event->id,
        'candidate_id' => $candidate->id,
    ]);
    
    // 2. Update participation timestamp
    EventParticipation::where('event_id', $event->id)
        ->where('voter_id', $voter->id)
        ->update(['voted_at' => now()]);
    
    // 3. Log audit trail
    AuditLog::record('VOTE_CAST', "Vote submitted for event #{$event->id}");
});
```

---

## 9. Framework Architecture

### 9.1. MVC Pattern

Laravel menggunakan **Model-View-Controller (MVC)** architecture pattern.

```
Request Flow:
Browser → Routes → Middleware → Controller → Model → Database
                                    ↓
                                  View (Blade) → Browser
```

**Components:**
- **Model** (`app/Models/`) - Business logic & database interaction
- **View** (`resources/views/`) - Blade templates untuk presentation layer
- **Controller** (`app/Http/Controllers/`) - Handle HTTP requests & orchestrate Model + View

### 9.2. Directory Structure

```
app/
├── Http/
│   ├── Controllers/       # MVC Controllers
│   │   ├── Auth/          # Authentication controllers
│   │   ├── Admin/         # Super Admin controllers
│   │   ├── Petugas/       # Petugas controllers
│   │   └── Voter/         # Voter controllers
│   └── Middleware/        # Custom middleware (role checking)
├── Models/                # Eloquent models
│   ├── User.php
│   ├── Voter.php
│   ├── Event.php
│   ├── Candidate.php
│   └── Vote.php
└── Providers/             # Service providers

config/                    # Configuration files
├── app.php
├── auth.php               # Multi-guard auth config
├── database.php
└── session.php

database/
├── migrations/            # Database schema versions
├── seeders/               # Database seeders
└── factories/             # Model factories untuk testing

resources/
├── css/
│   └── app.css            # Tailwind CSS entry point
├── js/
│   └── app.js             # JavaScript entry point (empty)
└── views/                 # Blade templates
    ├── layouts/           # Master layouts
    │   ├── auth.blade.php
    │   ├── voter.blade.php
    │   ├── admin.blade.php
    │   └── petugas.blade.php
    ├── auth/              # Login pages
    ├── voter/             # Voter views
    ├── admin/             # Super Admin views
    └── petugas/           # Petugas views

routes/
└── web.php                # Web routes definition

storage/
├── app/
│   └── public/
│       └── candidates/    # Uploaded candidate photos
├── framework/
│   ├── cache/
│   ├── sessions/
│   └── views/             # Compiled Blade templates
└── logs/                  # Application logs

public/
├── build/                 # Compiled Vite assets (production)
├── storage/               # Symlink to storage/app/public
└── index.php              # Entry point
```

---

## 10. Kesimpulan

### 10.1. Framework & Library Summary

| Category | Technology | Version | Status |
|----------|-----------|---------|--------|
| **Backend Framework** | Laravel | 13.33.0 | ✅ Core framework |
| **Templating Engine** | Blade | Built-in | ✅ All views menggunakan Blade |
| **CSS Framework** | Tailwind CSS | 4.0.0 | ✅ Primary styling method |
| **Build Tool** | Vite | 8.0.0 | ✅ Development & production builds |
| **PHP Package Manager** | Composer | 2.4.1 | ✅ Dependency management |
| **JS Package Manager** | NPM | 10.9.8 | ✅ Frontend dependencies |
| **DateTime Library** | Carbon | Built-in | ✅ Event scheduling |
| **ORM** | Eloquent | Built-in | ✅ Database operations |

### 10.2. Alasan Pemilihan Framework

#### **Laravel 11 (PHP Framework)**
- ✅ **Mature ecosystem** - Framework PHP paling populer dengan dokumentasi lengkap
- ✅ **Eloquent ORM** - Simplify database operations dengan expressive syntax
- ✅ **Multi-guard auth** - Built-in support untuk multiple authentication contexts
- ✅ **Blade templating** - Clean syntax tanpa performance overhead
- ✅ **Migration system** - Database version control
- ✅ **Security built-in** - CSRF protection, password hashing, SQL injection prevention

#### **Tailwind CSS v4 (CSS Framework)**
- ✅ **Utility-first approach** - Rapid UI development tanpa custom CSS
- ✅ **Responsive by default** - Mobile-first design dengan breakpoint modifiers
- ✅ **Consistent design** - Spacing, colors, typography mengikuti design system
- ✅ **Performance** - Unused CSS automatically removed (PurgeCSS built-in)
- ✅ **Version 4 improvements** - Faster compilation dengan Oxide engine

#### **Vite 8.0 (Build Tool)**
- ✅ **Lightning fast HMR** - Instant feedback saat development
- ✅ **Native ES modules** - No bundling di development mode
- ✅ **Optimized production build** - Minification, tree-shaking, code splitting
- ✅ **Laravel integration** - Seamless dengan `@vite()` Blade directive
- ✅ **Modern tooling** - Replace Laravel Mix yang deprecated

### 10.3. Package yang TIDAK Dipakai

Berikut adalah package yang terinstall tapi **tidak digunakan** di project:

| Package | Alasan Tidak Dipakai |
|---------|----------------------|
| **barryvdh/laravel-dompdf** | Tidak ada fitur export PDF di project |
| **intervention/image** | Upload foto kandidat langsung store tanpa resize/crop (CSS yang handle display) |
| **maatwebsite/excel** | Tidak ada fitur import/export Excel |
| **@laravel/multiplex** | Tidak ada real-time features, semua pakai manual refresh |

**Rekomendasi:** Package yang tidak dipakai bisa di-uninstall untuk mengurangi dependency size:

```bash
composer remove barryvdh/laravel-dompdf intervention/image maatwebsite/excel
npm uninstall @laravel/multiplex
```

### 10.4. Kesesuaian dengan Unit Kompetensi

| Unit Kompetensi | Status |
|-----------------|--------|
| **UK 3: Menganalisis tools** | ✅ Framework dianalisis dari segi fitur, architecture, dan use case |
| **UK 9: Instalasi** | ✅ Laravel, Tailwind, Vite terinstall dan dikonfigurasi dengan benar |
| **UK 10: Pengoperasian** | ✅ Framework digunakan sesuai best practices (MVC, Blade inheritance, Eloquent relationships) |

---

**Catatan:** Laporan ini fokus pada **framework dan library yang benar-benar digunakan** di project. Untuk penjelasan bahasa pemrograman (PHP, JavaScript, SQL), lihat **Laporan 7: Bahasa Pemrograman yang Dipakai**.
