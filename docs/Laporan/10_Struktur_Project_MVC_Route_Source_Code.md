# LAPORAN 10: STRUKTUR PROJECT - MVC, ROUTE & SOURCE CODE

**Proyek:** Sistem Informasi E-Voting Sekolah (VOTINGYUK)  
**Framework:** Laravel 13.33.0 (MVC Architecture)  
**Pattern:** Model-View-Controller (MVC) + Repository Pattern  
**Routing:** RESTful Routes dengan Named Routes  
**Tanggal Dokumentasi:** 22 Oktober 2026

---

## A. STRUKTUR FOLDER PROJECT (Directory Structure)

### 1. ROOT PROJECT STRUCTURE

```
evoting_sekolah/
│
├── app/                          # Aplikasi utama (MVC Core)
│   ├── Http/
│   │   ├── Controllers/          # Controller Layer (Business Logic)
│   │   │   ├── Controller.php    # Base Controller
│   │   │   ├── Admin/            # Controllers untuk Super Admin
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── UserController.php
│   │   │   │   └── AuditLogController.php
│   │   │   ├── Auth/             # Controllers untuk Authentication
│   │   │   │   ├── AdminAuthController.php
│   │   │   │   └── VoterAuthController.php
│   │   │   ├── Petugas/          # Controllers untuk Petugas
│   │   │   │   ├── EventController.php
│   │   │   │   ├── CandidateController.php
│   │   │   │   └── MonitoringController.php
│   │   │   └── Voter/            # Controllers untuk Pemilih
│   │   │       ├── VoterDashboardController.php
│   │   │       └── VotingBoothController.php
│   │   │
│   │   └── Middleware/           # Custom Middleware
│   │       └── RoleMiddleware.php
│   │
│   ├── Models/                   # Model Layer (Database ORM)
│   │   ├── User.php              # Model untuk users table
│   │   ├── Voter.php             # Model untuk voters table
│   │   ├── Event.php             # Model untuk events table
│   │   ├── Candidate.php         # Model untuk candidates table
│   │   ├── Vote.php              # Model untuk votes table
│   │   ├── EventParticipation.php
│   │   └── AuditLog.php
│   │
│   └── Providers/                # Service Providers
│       └── AppServiceProvider.php
│
├── bootstrap/                    # Bootstrap Framework
│   ├── app.php                   # Bootstrap aplikasi
│   ├── providers.php             # Register providers
│   └── cache/                    # Cache compiled files
│
├── config/                       # Konfigurasi Aplikasi
│   ├── app.php                   # Config aplikasi
│   ├── auth.php                  # Config autentikasi (multi-guard)
│   ├── database.php              # Config database
│   ├── filesystems.php           # Config storage
│   ├── mail.php
│   ├── queue.php
│   └── session.php
│
├── database/                     # Database Layer
│   ├── migrations/               # Database migrations (DDL)
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 2026_09_26_000001_create_voters_table.php
│   │   ├── 2026_09_26_000002_create_events_table.php
│   │   ├── 2026_09_26_000003_create_candidates_table.php
│   │   ├── 2026_09_26_000004_create_event_participations_table.php
│   │   ├── 2026_09_26_000005_create_votes_table.php
│   │   └── 2026_09_26_000006_create_audit_logs_table.php
│   │
│   ├── seeders/                  # Database seeders (Sample data)
│   │   └── DatabaseSeeder.php
│   │
│   └── factories/                # Model factories
│       └── UserFactory.php
│
├── public/                       # Document Root (Web Server Entry Point)
│   ├── index.php                 # Front Controller (Entry point)
│   └── storage/                  # Symlink ke storage/app/public
│
├── resources/                    # View & Assets
│   ├── views/                    # View Layer (Blade Templates)
│   │   ├── layouts/              # Master layouts
│   │   │   ├── admin.blade.php   # Layout untuk Super Admin
│   │   │   ├── petugas.blade.php # Layout untuk Petugas
│   │   │   ├── voter.blade.php   # Layout untuk Pemilih
│   │   │   └── auth.blade.php    # Layout untuk halaman login
│   │   │
│   │   ├── admin/                # Views untuk Super Admin
│   │   │   ├── dashboard.blade.php
│   │   │   ├── users/
│   │   │   │   └── index.blade.php
│   │   │   └── audit_logs/
│   │   │       └── index.blade.php
│   │   │
│   │   ├── petugas/              # Views untuk Petugas
│   │   │   ├── events/
│   │   │   │   └── index.blade.php
│   │   │   ├── candidates/
│   │   │   │   └── index.blade.php
│   │   │   └── monitoring/
│   │   │       └── show.blade.php
│   │   │
│   │   ├── voter/                # Views untuk Pemilih
│   │   │   ├── dashboard.blade.php
│   │   │   ├── voting-booth.blade.php
│   │   │   └── success.blade.php
│   │   │
│   │   ├── auth/                 # Views untuk Authentication
│   │   │   ├── admin-login.blade.php
│   │   │   └── voter-login.blade.php
│   │   │
│   │   └── welcome.blade.php     # Landing page
│   │
│   ├── css/                      # Stylesheets
│   │   └── app.css               # Tailwind CSS v4 config
│   │
│   └── js/                       # JavaScript
│       └── app.js                # JavaScript entry point
│
├── routes/                       # Route Definitions
│   ├── web.php                   # Web routes (utama)
│   └── console.php               # Artisan console routes
│
├── storage/                      # Storage (File uploads, logs, cache)
│   ├── app/
│   │   ├── public/               # Public storage (accessible via web)
│   │   │   └── candidates/       # Folder foto kandidat
│   │   └── private/              # Private storage
│   ├── framework/                # Framework cache
│   │   ├── cache/
│   │   ├── sessions/
│   │   └── views/                # Compiled Blade templates
│   └── logs/                     # Application logs
│       └── laravel.log
│
├── tests/                        # Unit & Feature Tests
│   ├── Unit/
│   └── Feature/
│
├── vendor/                       # Composer dependencies
│
├── .env                          # Environment variables
├── .env.example                  # Environment template
├── artisan                       # Artisan CLI
├── composer.json                 # PHP dependencies
├── package.json                  # Node.js dependencies
├── vite.config.js                # Vite build config
└── phpunit.xml                   # PHPUnit config
```

---

## B. ARSITEKTUR MVC (Model-View-Controller)

### 1. KONSEP MVC DI LARAVEL

```
┌─────────────┐
│   Browser   │
│   (Client)  │
└──────┬──────┘
       │ HTTP Request
       ▼
┌─────────────────────────────────────────────┐
│          ROUTES (routes/web.php)            │
│  - Menerima request dari client             │
│  - Mengarahkan ke Controller yang tepat     │
└──────┬──────────────────────────────────────┘
       │
       ▼
┌─────────────────────────────────────────────┐
│      CONTROLLER (app/Http/Controllers/)     │
│  - Menerima input dari user                 │
│  - Validasi data                            │
│  - Berinteraksi dengan Model                │
│  - Mengirim data ke View                    │
└──────┬─────────────────────┬────────────────┘
       │                     │
       ▼                     ▼
┌──────────────┐      ┌──────────────┐
│    MODEL     │      │     VIEW     │
│ (app/Models/)│      │(resources/   │
│              │      │  views/)     │
│- Eloquent ORM│      │              │
│- Database    │      │- Blade       │
│- Business    │      │  Templates   │
│  Logic       │      │- HTML/CSS/JS │
└──────┬───────┘      └──────┬───────┘
       │                     │
       │ Data               │ HTML Response
       ▼                     │
┌─────────────┐             │
│  DATABASE   │             │
│  (MySQL)    │             │
└─────────────┘             │
                            ▼
                   ┌─────────────┐
                   │   Browser   │
                   │  (Client)   │
                   └─────────────┘
```

---

### 2. PENJELASAN SETIAP KOMPONEN MVC

#### **A. MODEL (Data Layer)**

**Lokasi:** `app/Models/`

**Fungsi:**
- Representasi tabel database dalam bentuk class PHP
- Eloquent ORM untuk query database
- Business logic terkait data
- Relasi antar tabel (hasMany, belongsTo, dll)
- Accessor & Mutator untuk manipulasi data

**Contoh Model:** `User.php`, `Event.php`, `Candidate.php`, `Vote.php`

---

#### **B. VIEW (Presentation Layer)**

**Lokasi:** `resources/views/`

**Fungsi:**
- Template HTML dengan Blade Templating Engine
- Menampilkan data dari Controller
- Interaksi UI dengan user (form, button, dll)
- Tidak boleh ada business logic (hanya presentasi)

**Blade Template Features:**
- Layout inheritance (`@extends`, `@section`, `@yield`)
- Directives (`@if`, `@foreach`, `@auth`, dll)
- Component-based (`@include`, `@component`)
- Template caching untuk performa

---

#### **C. CONTROLLER (Business Logic Layer)**

**Lokasi:** `app/Http/Controllers/`

**Fungsi:**
- Menerima request dari route
- Validasi input dari user
- Berinteraksi dengan Model untuk ambil/simpan data
- Mengirim data ke View untuk ditampilkan
- Return response (view, redirect, JSON)

**Controller Naming Convention:**
- `index()` → Tampilkan daftar data
- `create()` → Form tambah data
- `store()` → Proses simpan data baru
- `show($id)` → Tampilkan detail data
- `edit($id)` → Form edit data
- `update($id)` → Proses update data
- `destroy($id)` → Hapus data

---

## C. DETAIL MODEL (Data Layer)

### 1. **Model: User.php**

**Lokasi:** `app/Models/User.php`

**Deskripsi:** Model untuk tabel `users` (Super Admin & Petugas)

**Source Code:**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Mass Assignment Protection (kolom yang boleh diisi)
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Hidden attributes (tidak muncul di JSON response)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Type Casting (auto convert tipe data)
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',  // Auto hash password dengan bcrypt
        ];
    }

    /**
     * Helper Method: Cek apakah user adalah Super Admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'SUPER_ADMIN';
    }

    /**
     * Helper Method: Cek apakah user adalah Petugas
     */
    public function isPetugas(): bool
    {
        return $this->role === 'PETUGAS';
    }

    /**
     * Relasi: 1 User bisa membuat banyak Event
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    /**
     * Relasi: 1 User bisa memiliki banyak Audit Log
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'user_id');
    }
}
```

**Penjelasan:**
- ✅ **Extends Authenticatable** untuk fitur autentikasi Laravel
- ✅ **$fillable** untuk mass assignment protection (keamanan)
- ✅ **$hidden** untuk menyembunyikan password di JSON response
- ✅ **casts()** untuk auto hashing password dengan bcrypt
- ✅ **Helper methods** untuk cek role user
- ✅ **Relasi** ke tabel events dan audit_logs

---

### 2. **Model: Event.php**

**Lokasi:** `app/Models/Event.php`

**Deskripsi:** Model untuk tabel `events` (Event Pemilihan)

**Source Code:**
```php
<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'title',
        'category',
        'room_code',
        'description',
        'start_time',
        'end_time',
        'status',
    ];

    /**
     * Type Casting (auto convert ke tipe data tertentu)
     */
    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',  // Auto parse ke Carbon instance
            'end_time' => 'datetime',
        ];
    }

    /**
     * Relasi: N:1 ke User (pembuat event)
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi: 1:N ke Candidates
     */
    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class)->orderBy('candidate_number', 'asc');
    }

    /**
     * Relasi: 1:N ke EventParticipation
     */
    public function participations(): HasMany
    {
        return $this->hasMany(EventParticipation::class);
    }

    /**
     * Relasi: N:M ke Voters (via event_participations)
     */
    public function voters(): BelongsToMany
    {
        return $this->belongsToMany(Voter::class, 'event_participations')
            ->withPivot('voted_at')
            ->withTimestamps();
    }

    /**
     * Relasi: 1:N ke Votes
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Helper: Cek status event
     */
    public function isDraft(): bool
    {
        return $this->status === 'DRAFT';
    }

    public function isPublished(): bool
    {
        return $this->status === 'PUBLISHED';
    }

    public function isClosed(): bool
    {
        return $this->status === 'CLOSED';
    }

    /**
     * Business Logic: Auto update status berdasarkan start_time & end_time
     */
    public function checkAndUpdateAutoStatus(): void
    {
        $now = Carbon::now();

        // DRAFT → PUBLISHED (jika start_time sudah lewat)
        if ($this->status === 'DRAFT' && $this->start_time && $now->greaterThanOrEqualTo($this->start_time)) {
            if ($this->end_time && $now->greaterThanOrEqualTo($this->end_time)) {
                $this->status = 'CLOSED';
            } else {
                $this->status = 'PUBLISHED';
            }
            $this->save();
        }
        // PUBLISHED → CLOSED (jika end_time sudah lewat)
        elseif ($this->status === 'PUBLISHED' && $this->end_time && $now->greaterThanOrEqualTo($this->end_time)) {
            $this->status = 'CLOSED';
            $this->save();
        }
    }
}
```

**Penjelasan:**
- ✅ **Casts datetime** untuk auto parsing ke Carbon (library date PHP)
- ✅ **Relasi lengkap** ke User, Candidates, Votes, Voters
- ✅ **Helper methods** untuk cek status event
- ✅ **Business logic** untuk auto update status berdasarkan waktu

---

### 3. **Model: Vote.php** (PENTING - Prinsip LUBER)

**Lokasi:** `app/Models/Vote.php`

**Deskripsi:** Model untuk tabel `votes` (Pencatatan Suara RAHASIA)

**Source Code:**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    use HasFactory;

    /**
     * PENTING: TIDAK ADA voter_id untuk jaga kerahasiaan suara!
     * Prinsip LUBER (Langsung, Umum, Bebas, Rahasia)
     */
    protected $fillable = [
        'event_id',
        'candidate_id',
        // ❌ TIDAK ADA 'voter_id' → Suara RAHASIA!
    ];

    /**
     * Relasi: N:1 ke Event
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Relasi: N:1 ke Candidate
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
```

**PENTING - Prinsip Kerahasiaan:**
- ❌ **TIDAK ADA** kolom `voter_id` di tabel votes
- ✅ Identitas pemilih dicatat terpisah di `event_participations` (anti double-voting)
- ✅ **Tidak bisa** tracking siapa memilih siapa (by design untuk privasi)
- ✅ Memenuhi asas **LUBER** (Langsung, Umum, Bebas, Rahasia)

---

### 4. **Model: AuditLog.php** (Helper Method untuk Logging)

**Lokasi:** `app/Models/AuditLog.php`

**Deskripsi:** Model untuk tabel `audit_logs` dengan helper method `record()`

**Source Code (Partial):**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'role',
        'action',
        'description',
        'ip_address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper untuk mencatat aktivitas dengan mudah
     * 
     * Usage:
     * AuditLog::record('CREATE_EVENT', 'Membuat event baru: PILKETOS 2026');
     */
    public static function record(string $action, string $description): self
    {
        $user = Auth::guard('web')->check() 
            ? Auth::guard('web')->user() 
            : Auth::guard('voter')->user();

        return self::create([
            'user_id' => $user->id ?? null,
            'user_name' => $user->name,
            'role' => $user->role ?? 'VOTER',
            'action' => $action,
            'description' => $description,
            'ip_address' => Request::ip(),
        ]);
    }
}
```

**Penjelasan:**
- ✅ **Static method `record()`** untuk mudahkan logging
- ✅ Auto detect user dari guard 'web' atau 'voter'
- ✅ Auto ambil IP address dari request

---

## D. DETAIL CONTROLLER (Business Logic Layer)

### 1. **Controller: VotingBoothController.php**

**Lokasi:** `app/Http/Controllers/Voter/VotingBoothController.php`

**Deskripsi:** Handle bilik suara dan pencatatan suara dengan transaction

**Source Code (Method castVote):**
```php
<?php

namespace App\Http\Controllers\Voter;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Candidate;
use App\Models\Event;
use App\Models\EventParticipation;
use App\Models\Vote;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VotingBoothController extends Controller
{
    /**
     * Eksekusi Pencatatan Suara secara Atomik (ACID Transaction)
     */
    public function castVote(Request $request, string $room_code): RedirectResponse
    {
        // 1. Validasi kode room
        $normalizedCode = strtoupper(trim($room_code));
        $event = Event::where('room_code', $normalizedCode)->first();

        if (!$event) {
            return redirect()->route('voter.dashboard')
                ->with('error', 'Kode ruangan pemilihan tidak ditemukan.');
        }

        // 2. Validasi input kandidat
        $request->validate([
            'candidate_id' => ['required', 'integer', 'exists:candidates,id'],
        ]);

        // 3. Verifikasi kandidat milik event ini
        $candidate = Candidate::where('id', $request->candidate_id)
            ->where('event_id', $event->id)
            ->first();

        if (!$candidate) {
            return back()->with('error', 'Kandidat tidak valid.');
        }

        $voter = Auth::guard('voter')->user();

        // 4. TRANSACTION ATOMIK (ACID Compliance)
        try {
            DB::transaction(function () use ($event, $voter, $candidate) {
                // Lock row untuk mencegah race condition (concurrent voting)
                $participation = EventParticipation::where('event_id', $event->id)
                    ->where('voter_id', $voter->id)
                    ->lockForUpdate()  // SELECT ... FOR UPDATE
                    ->first();

                // Cek double-voting
                if ($participation && $participation->hasVoted()) {
                    throw new \DomainException('Anda sudah memberikan suara.');
                }

                // a. Catat Suara (RAHASIA - tanpa voter_id)
                Vote::create([
                    'event_id' => $event->id,
                    'candidate_id' => $candidate->id,
                ]);

                // b. Tandai Partisipasi (Anti Double-Voting)
                if ($participation) {
                    $participation->update(['voted_at' => Carbon::now()]);
                } else {
                    EventParticipation::create([
                        'event_id' => $event->id,
                        'voter_id' => $voter->id,
                        'voted_at' => Carbon::now(),
                    ]);
                }

                // c. Audit Log (tanpa info pilihan kandidat)
                AuditLog::record(
                    'vote.cast',
                    "Pemilih {$voter->name} telah menyalurkan hak suara pada event {$event->title}."
                );
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses suara.');
        }

        return redirect()->route('voter.events.success', $event->room_code)
            ->with('success', 'Hak suara Anda telah berhasil dicatat!');
    }
}
```

**Penjelasan:**
- ✅ **DB::transaction()** untuk ACID compliance
- ✅ **lockForUpdate()** untuk mencegah race condition
- ✅ **3 operasi atomik**: Catat vote → Update participation → Audit log
- ✅ **Exception handling** dengan rollback otomatis

---

### 2. **Controller: EventController.php**

**Lokasi:** `app/Http/Controllers/Petugas/EventController.php`

**Deskripsi:** CRUD event dengan eager loading & filter

**Source Code (Method index):**
```php
public function index(Request $request): View
{
    // 1. Eager Loading untuk mencegah N+1 Query Problem
    $query = Event::with(['creator'])
        ->withCount(['candidates', 'participations', 'votes'])
        ->where('created_by', Auth::id());

    // 2. Filter Pencarian
    if ($request->filled('search')) {
        $search = trim($request->search);
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('room_code', 'like', "%{$search}%");
        });
    }

    // 3. Filter Status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // 4. Pagination dengan Query String
    $events = $query->latest('id')->paginate(9)->withQueryString();

    // 5. Statistik Metrik
    $metrics = [
        'total' => Event::where('created_by', Auth::id())->count(),
        'published' => Event::where('created_by', Auth::id())
            ->where('status', 'PUBLISHED')->count(),
        'draft' => Event::where('created_by', Auth::id())
            ->where('status', 'DRAFT')->count(),
    ];

    return view('petugas.events.index', compact('events', 'metrics'));
}
```

**Penjelasan:**
- ✅ **Eager Loading** dengan `with()` dan `withCount()`
- ✅ **Query Builder** dengan filter dinamis
- ✅ **Pagination** dengan `paginate(9)`
- ✅ **Compact()** untuk kirim data ke view

---

### 3. **Controller: UserController.php** (Validation)

**Lokasi:** `app/Http/Controllers/Admin/UserController.php`

**Source Code (Method storeUser):**
```php
public function storeUser(Request $request): RedirectResponse
{
    // 1. Request Validation
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'string', 'min:6', 'confirmed'],
        'role' => ['required', Rule::in(['SUPER_ADMIN', 'PETUGAS'])],
    ], [
        'name.required' => 'Nama lengkap wajib diisi.',
        'email.unique' => 'Email sudah terdaftar.',
        'password.min' => 'Password minimal 6 karakter.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
    ]);

    // 2. Create User dengan Mass Assignment
    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
    ]);

    // 3. Audit Log
    AuditLog::record('CREATE_USER', "Membuat akun {$validated['role']}: {$user->name}");

    // 4. Redirect dengan Flash Message
    return redirect()->route('admin.users.index')
        ->with('success', "Akun {$user->name} berhasil ditambahkan!");
}
```

**Penjelasan:**
- ✅ **Validation** dengan custom error messages
- ✅ **Hash::make()** untuk hash password
- ✅ **Audit logging** otomatis
- ✅ **Flash message** dengan `with('success', ...)`

---

## E. ROUTING (Route Definitions)

### 1. **File: routes/web.php**

**Lokasi:** `routes/web.php`

**Deskripsi:** Definisi semua route aplikasi dengan middleware & grouping

**Source Code Lengkap:**
```php
<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\VoterAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - VOTINGYUK
|--------------------------------------------------------------------------
*/

// =========================================================================
// 1. AUTENTIKASI VOTER (PEMILIH: SISWA & GURU)
// =========================================================================
Route::get('/', [VoterAuthController::class, 'showLoginForm'])->name('home');
Route::get('/voter/login', [VoterAuthController::class, 'showLoginForm'])->name('voter.login');
Route::post('/voter/login', [VoterAuthController::class, 'login'])->name('voter.login.post');
Route::post('/voter/logout', [VoterAuthController::class, 'logout'])->name('voter.logout');

// =========================================================================
// 2. AUTENTIKASI PENGELOLA (SUPER ADMIN & PETUGAS)
// =========================================================================
Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.post');
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// =========================================================================
// 3. PANEL SUPER ADMIN (HAK AKSES ADMINISTRATOR SISTEM)
// =========================================================================
Route::middleware(['auth:web', 'role:SUPER_ADMIN'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->name('dashboard');

    // Manajemen Akun (Super Admin, Petugas)
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])
        ->name('users.index');
    Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'storeUser'])
        ->name('users.store');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'updateUser'])
        ->name('users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroyUser'])
        ->name('users.destroy');

    // Manajemen Pemilih (Siswa & Guru)
    Route::post('/voters', [\App\Http\Controllers\Admin\UserController::class, 'storeVoter'])
        ->name('voters.store');
    Route::put('/voters/{voter}', [\App\Http\Controllers\Admin\UserController::class, 'updateVoter'])
        ->name('voters.update');
    Route::delete('/voters/{voter}', [\App\Http\Controllers\Admin\UserController::class, 'destroyVoter'])
        ->name('voters.destroy');

    // Audit Log
    Route::get('/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])
        ->name('audit-logs.index');
});

// =========================================================================
// 4. PANEL PETUGAS (MANAJEMEN EVENT, KANDIDAT & HASIL VOTING)
// =========================================================================
Route::middleware(['auth:web', 'role:PETUGAS'])->prefix('petugas')->name('petugas.')->group(function () {
    // Event Management
    Route::get('/events', [\App\Http\Controllers\Petugas\EventController::class, 'index'])
        ->name('events.index');
    Route::post('/events', [\App\Http\Controllers\Petugas\EventController::class, 'store'])
        ->name('events.store');
    Route::put('/events/{event}', [\App\Http\Controllers\Petugas\EventController::class, 'update'])
        ->name('events.update');
    Route::patch('/events/{event}/status', [\App\Http\Controllers\Petugas\EventController::class, 'updateStatus'])
        ->name('events.status');
    Route::delete('/events/{event}', [\App\Http\Controllers\Petugas\EventController::class, 'destroy'])
        ->name('events.destroy');

    // Candidate Management
    Route::get('/events/{event}/candidates', [\App\Http\Controllers\Petugas\CandidateController::class, 'index'])
        ->name('candidates.index');
    Route::post('/events/{event}/candidates', [\App\Http\Controllers\Petugas\CandidateController::class, 'store'])
        ->name('candidates.store');
    Route::put('/events/{event}/candidates/{candidate}', [\App\Http\Controllers\Petugas\CandidateController::class, 'update'])
        ->name('candidates.update');
    Route::delete('/events/{event}/candidates/{candidate}', [\App\Http\Controllers\Petugas\CandidateController::class, 'destroy'])
        ->name('candidates.destroy');

    // Monitoring & Export
    Route::get('/events/{event}/monitor', [\App\Http\Controllers\Petugas\MonitoringController::class, 'show'])
        ->name('events.monitor');
    Route::get('/events/{event}/monitor/data', [\App\Http\Controllers\Petugas\MonitoringController::class, 'data'])
        ->name('events.monitor.data');
    Route::get('/events/{event}/export', [\App\Http\Controllers\Petugas\MonitoringController::class, 'exportCsv'])
        ->name('events.export');
});

// =========================================================================
// 5. PANEL PEMILIH (VOTER DASHBOARD, KODE RUANGAN & BILIK SUARA)
// =========================================================================
Route::middleware(['auth:voter'])->prefix('voter')->name('voter.')->group(function () {
    // Dashboard & Join Room
    Route::get('/dashboard', [\App\Http\Controllers\Voter\VoterDashboardController::class, 'index'])
        ->name('dashboard');
    Route::post('/join', [\App\Http\Controllers\Voter\VoterDashboardController::class, 'joinRoom'])
        ->name('join.post');

    // Voting Booth
    Route::get('/events/{room_code}/vote', [\App\Http\Controllers\Voter\VotingBoothController::class, 'show'])
        ->name('events.vote');
    Route::post('/events/{room_code}/vote', [\App\Http\Controllers\Voter\VotingBoothController::class, 'castVote'])
        ->name('events.vote.post');
    Route::get('/events/{room_code}/success', [\App\Http\Controllers\Voter\VotingBoothController::class, 'success'])
        ->name('events.success');
});
```

---

### 2. **PENJELASAN ROUTING CONCEPTS**

#### **A. Named Routes**
```php
Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->name('admin.dashboard');
```
**Keuntungan:**
- URL bisa diubah tanpa mengubah kode di view
- Generate URL dengan `route('admin.dashboard')`

#### **B. Route Grouping**
```php
Route::middleware(['auth:web'])->prefix('admin')->name('admin.')->group(function () {
    // Semua route di sini punya prefix 'admin' dan middleware 'auth:web'
});
```
**Keuntungan:**
- DRY (Don't Repeat Yourself)
- Mudah manage middleware untuk banyak route

#### **C. Route Parameters**
```php
Route::get('/events/{event}/candidates', ...)
Route::put('/events/{event}/candidates/{candidate}', ...)
```
**Keuntungan:**
- RESTful URL design
- Auto binding ke Model (Route Model Binding)

#### **D. HTTP Methods**
```php
Route::get('/events', ...)      // Tampilkan daftar
Route::post('/events', ...)     // Tambah data baru
Route::put('/events/{id}', ...) // Update data
Route::delete('/events/{id}', ...) // Hapus data
```

---

### 3. **MIDDLEWARE (Route Protection)**

#### **A. Middleware: auth:web**
**Fungsi:** Cek apakah user sudah login dengan guard 'web' (Super Admin & Petugas)

**Guard 'web':**
- Provider: `users` table
- Model: `User::class`
- Role: `SUPER_ADMIN` atau `PETUGAS`

#### **B. Middleware: auth:voter**
**Fungsi:** Cek apakah voter sudah login dengan guard 'voter' (Siswa & Guru)

**Guard 'voter':**
- Provider: `voters` table
- Model: `Voter::class`
- Type: `SISWA` atau `GURU`

#### **C. Middleware: role:SUPER_ADMIN**

**Lokasi:** `app/Http/Middleware/RoleMiddleware.php`

**Source Code:**
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * 
     * @param  string  ...$roles  (e.g., 'SUPER_ADMIN', 'PETUGAS')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::guard('web')->check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $user = Auth::guard('web')->user();

        // 2. Cek apakah role user sesuai
        if (!in_array($user->role, $roles)) {
            abort(403, 'Akses Ditolak. Anda tidak memiliki izin.');
        }

        return $next($request);
    }
}
```

**Penggunaan:**
```php
Route::middleware(['auth:web', 'role:SUPER_ADMIN'])->group(...);
Route::middleware(['auth:web', 'role:PETUGAS'])->group(...);
```

**Penjelasan:**
- ✅ Cek login dulu (`Auth::guard('web')->check()`)
- ✅ Cek role dengan `in_array($user->role, $roles)`
- ✅ Redirect ke login jika belum login
- ✅ Abort 403 jika role tidak sesuai

---

## F. DETAIL VIEW (Presentation Layer)

### 1. **BLADE TEMPLATING ENGINE**

#### **A. Layout Master: admin.blade.php**

**Lokasi:** `resources/views/layouts/admin.blade.php`

**Konsep:**
```blade
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title') - VOTINGYUK</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <!-- Sidebar Navigation -->
    <aside>
        <nav>
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.users.index') }}">Kelola Akun</a>
            <a href="{{ route('admin.audit-logs.index') }}">Audit Log</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
</body>
</html>
```

**Penjelasan:**
- ✅ **@yield('title')** → Placeholder untuk title
- ✅ **@yield('content')** → Placeholder untuk konten
- ✅ **@vite()** → Load CSS & JS dengan Vite
- ✅ **{{ route('...') }}** → Generate URL dari named route

---

#### **B. Child View: dashboard.blade.php**

**Lokasi:** `resources/views/admin/dashboard.blade.php`

**Konsep:**
```blade
@extends('layouts.admin')

@section('title', 'Dashboard Super Admin')

@section('content')
<div class="container">
    <h1>Dashboard Super Admin</h1>
    
    <!-- Statistik Card -->
    <div class="grid">
        <div class="card">
            <h3>Total Super Admin</h3>
            <p>{{ $totalSuperAdmin }}</p>
        </div>
        <div class="card">
            <h3>Total Petugas</h3>
            <p>{{ $totalPetugas }}</p>
        </div>
    </div>

    <!-- Tabel Audit Log -->
    <table>
        <thead>
            <tr>
                <th>Waktu</th>
                <th>Pengguna</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentAuditLogs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $log->user_name }}</td>
                    <td>{{ $log->action }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Belum ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
```

**Blade Directives:**
- ✅ **@extends('layouts.admin')** → Pakai layout master
- ✅ **@section('content')** → Isi konten ke yield
- ✅ **{{ $variable }}** → Echo variable (auto escaped)
- ✅ **@forelse ... @empty ... @endforelse** → Loop dengan fallback
- ✅ **@if ... @else ... @endif** → Conditional

---

### 2. **PASSING DATA FROM CONTROLLER TO VIEW**

**Controller:**
```php
public function index()
{
    $totalSuperAdmin = User::where('role', 'SUPER_ADMIN')->count();
    $totalPetugas = User::where('role', 'PETUGAS')->count();
    $recentAuditLogs = AuditLog::latest()->take(10)->get();

    return view('admin.dashboard', compact('totalSuperAdmin', 'totalPetugas', 'recentAuditLogs'));
}
```

**View (Blade):**
```blade
<p>Total Super Admin: {{ $totalSuperAdmin }}</p>
<p>Total Petugas: {{ $totalPetugas }}</p>

@foreach($recentAuditLogs as $log)
    <p>{{ $log->action }}</p>
@endforeach
```

**Penjelasan:**
- ✅ **compact()** → Shortcut untuk kirim variable
- ✅ Variable otomatis tersedia di view
- ✅ **{{ $var }}** → Auto escaped untuk keamanan XSS

---

## G. ALUR REQUEST-RESPONSE (MVC Flow)

### Contoh: User Akses Dashboard Super Admin

```
1. Browser → GET /admin/dashboard

2. routes/web.php:
   Route::middleware(['auth:web', 'role:SUPER_ADMIN'])
       ->get('/admin/dashboard', [DashboardController::class, 'index']);

3. Middleware Check:
   - auth:web → Cek apakah user sudah login dengan guard 'web'
   - role:SUPER_ADMIN → Cek apakah role = 'SUPER_ADMIN'

4. Controller: DashboardController@index
   - Query database via Model
   - Ambil data: $totalSuperAdmin, $totalPetugas, $recentAuditLogs
   - Return view dengan data

5. View: resources/views/admin/dashboard.blade.php
   - Render HTML dengan data dari controller
   - Loop audit logs, tampilkan statistik

6. Response → HTML dikirim ke Browser

7. Browser → Tampilkan halaman dashboard
```

---

## H. SECURITY & BEST PRACTICES

### 1. **CSRF Protection**
```blade
<form method="POST" action="{{ route('admin.users.store') }}">
    @csrf  <!-- Generate CSRF token -->
    <input type="text" name="name">
    <button type="submit">Submit</button>
</form>
```

### 2. **SQL Injection Prevention**
```php
// ✅ AMAN (Eloquent ORM dengan Parameter Binding)
User::where('email', $request->email)->first();

// ❌ BAHAYA (Raw SQL tanpa binding)
DB::select("SELECT * FROM users WHERE email = '{$request->email}'");
```

### 3. **XSS Protection**
```blade
<!-- ✅ AMAN (Auto escaped) -->
<p>{{ $user->name }}</p>

<!-- ❌ BAHAYA (Tidak di-escape) -->
<p>{!! $user->name !!}</p>
```

### 4. **Mass Assignment Protection**
```php
// Model: User.php
protected $fillable = ['name', 'email', 'password', 'role'];

// Controller:
User::create($request->only('name', 'email', 'password', 'role'));
// ✅ Hanya field di $fillable yang bisa diisi
```

### 5. **Authorization (Policy & Gates)**
```php
// Cek apakah user bisa edit event
if (Auth::user()->id === $event->created_by) {
    // Allow edit
} else {
    abort(403);
}
```

---

## I. NAMING CONVENTIONS & CODING STANDARDS

### 1. **File & Class Naming**
```
Controllers:  PascalCase + Controller suffix
              EventController.php, UserController.php

Models:       PascalCase, Singular
              User.php, Event.php, Candidate.php

Views:        kebab-case.blade.php
              admin-login.blade.php, voting-booth.blade.php

Routes:       snake_case untuk prefix, dot notation untuk name
              prefix('admin') → name('admin.dashboard')
```

### 2. **Method Naming**
```php
// Controller methods: camelCase
public function index() {}
public function store() {}
public function castVote() {}

// Model methods: camelCase
public function isSuperAdmin() {}
public function checkAndUpdateAutoStatus() {}

// Helper methods: snake_case (optional)
function generate_room_code() {}
```

### 3. **Route Naming**
```php
// Pattern: {guard}.{resource}.{action}
admin.dashboard
admin.users.index
admin.users.store
petugas.events.index
petugas.events.monitor
voter.dashboard
voter.events.vote
```

---

## J. SUMMARY & BEST PRACTICES

### ✅ **Advantages of MVC Architecture:**
1. **Separation of Concerns** → Model, View, Controller terpisah
2. **Reusability** → Model bisa dipakai di banyak controller
3. **Maintainability** → Mudah maintain karena struktur jelas
4. **Testability** → Mudah unit test karena logic terpisah
5. **Scalability** → Mudah tambah fitur baru

### ✅ **Laravel MVC Best Practices:**
1. **Controller:** Thin controller, fat model (logic di model)
2. **Model:** Business logic & database query
3. **View:** Pure presentation, no business logic
4. **Route:** RESTful naming, route grouping
5. **Middleware:** Authentication & authorization
6. **Validation:** Request validation di controller
7. **Eloquent ORM:** Eager loading untuk performa
8. **Transaction:** Gunakan DB::transaction() untuk operasi atomik

### ✅ **Security Checklist:**
- ✅ CSRF Protection (`@csrf` di form)
- ✅ SQL Injection Prevention (Eloquent ORM)
- ✅ XSS Protection (Auto escaped `{{ }}`)
- ✅ Mass Assignment Protection (`$fillable`)
- ✅ Password Hashing (Bcrypt via `Hash::make()`)
- ✅ Middleware untuk authorization
- ✅ HTTPS untuk production

---

## K. STRUKTUR FILE LENGKAP PROJECT

### **Quick Reference:**
```
app/
├── Http/Controllers/      → Business Logic
│   ├── Admin/            → Super Admin features
│   ├── Auth/             → Authentication
│   ├── Petugas/          → Petugas features
│   └── Voter/            → Voter features
├── Models/               → Data Layer (Eloquent ORM)
└── Middleware/           → Route protection

resources/views/          → Presentation Layer (Blade)
├── layouts/             → Master templates
├── admin/               → Super Admin views
├── petugas/             → Petugas views
├── voter/               → Voter views
└── auth/                → Login pages

routes/web.php           → Route definitions

database/migrations/     → Database schema (DDL)

config/                  → App configuration
├── auth.php            → Multi-guard config
├── database.php        → DB connection
└── app.php             → General config
```

---

**Disusun oleh:** Tim Pengembang VOTINGYUK  
**Tanggal:** 22 Oktober 2026  
**Status:** ✅ Dokumentasi Lengkap & Terverifikasi

---

**Next:** Laporan 11 - Hasil/Eksekusi/Running (Screenshots & Testing)
