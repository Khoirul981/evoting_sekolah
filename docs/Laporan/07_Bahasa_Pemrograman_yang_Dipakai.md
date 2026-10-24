# Laporan 7: Bahasa Pemrograman yang Dipakai

**Proyek:** Sistem E-Voting Sekolah  
**Tanggal:** 18 Oktober 2026  
**Unit Kompetensi:** 
- UK 3: Menganalisis tools untuk software development
- UK 9: Menginstall dan mengkonfigurasi tools untuk software development
- UK 10: Menganalisis serta mengoperasikan tools untuk software development

---

## 1. Overview

Sistem E-Voting Sekolah dibangun menggunakan **3 bahasa pemrograman utama** yang saling berintegrasi:

1. **PHP 8.3.33** → Backend logic, business rules, database operations
2. **JavaScript ES2023** → Frontend interactivity, client-side validations
3. **SQL (MySQL 8.0)** → Database queries, schema definitions, data manipulation

---

## 2. PHP 8.3.33

### 2.1. Informasi Versi

```plaintext
PHP Version: 8.3.33
Architecture: x64
Thread Safety: Zend Thread Safety (ZTS) enabled
Compiler: Visual C++ 2019
Zend Engine: v4.3.33
```

### 2.2. Fitur PHP 8.3 yang Digunakan

#### A. **Typed Properties**
Semua model menggunakan type declaration untuk keamanan tipe data.

**Contoh di `app/Models/Event.php`:**
```php
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

protected function casts(): array
{
    return [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
}
```

#### B. **Return Type Declarations**
Method signatures menggunakan return types untuk code predictability.

**Contoh di `app/Models/Event.php`:**
```php
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

public function checkAndUpdateAutoStatus(): void
{
    // Logic untuk auto-update status berdasarkan waktu
}
```

#### C. **Union Types**
Controller menggunakan union types untuk flexible return values.

**Contoh di `app/Http/Controllers/Voter/VotingBoothController.php`:**
```php
public function show(string $room_code): View|RedirectResponse
{
    $normalizedCode = strtoupper(trim($room_code));
    $event = Event::where('room_code', $normalizedCode)->first();
    
    if (!$event) {
        return redirect()->route('voter.dashboard')
            ->with('error', 'Kode ruangan pemilihan tidak ditemukan.');
    }
    
    // ... logic lainnya
    
    return view('voter.voting-booth', compact('event', 'candidates', 'voter'));
}
```

Method ini bisa return `View` (jika sukses) atau `RedirectResponse` (jika gagal validasi).

#### D. **Named Arguments & Validation**
Validation rules menggunakan array syntax modern dan named parameters.

**Contoh di `app/Http/Controllers/Admin/UserController.php`:**
```php
$validated = $request->validate([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
    'password' => ['required', 'string', 'min:6', 'confirmed'],
    'role' => ['required', Rule::in(['SUPER_ADMIN', 'PETUGAS'])],
], [
    'name.required' => 'Nama lengkap wajib diisi.',
    'email.unique' => 'Email sudah terdaftar pada sistem.',
    'password.min' => 'Password minimal 6 karakter.',
    'role.in' => 'Role pengguna tidak valid.',
]);
```

#### E. **Eloquent Relationships**
Laravel Eloquent ORM menggunakan PHP 8 type hints untuk relationships.

**Contoh di `app/Models/Event.php`:**
```php
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

public function creator(): BelongsTo
{
    return $this->belongsTo(User::class, 'created_by');
}

public function candidates(): HasMany
{
    return $this->hasMany(Candidate::class)->orderBy('candidate_number', 'asc');
}

public function voters(): BelongsToMany
{
    return $this->belongsToMany(Voter::class, 'event_participations')
        ->withPivot('voted_at')
        ->withTimestamps();
}
```

#### F. **Null Safety dengan Nullable Types**
Migration schema menggunakan nullable columns.

**Contoh di `database/migrations/2026_09_26_000002_create_events_table.php`:**
```php
$table->text('description')->nullable();
$table->dateTime('start_time')->nullable();
$table->dateTime('end_time')->nullable();
```

#### G. **Anonymous Classes**
Migration files menggunakan anonymous class syntax (PHP 8 feature).

```php
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            // Schema definition
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
```

### 2.3. PHP Extensions yang Diaktifkan

Project memerlukan ekstensi PHP berikut (dicek via `php.ini`):

| Extension | Fungsi |
|-----------|--------|
| `pdo_mysql` | MySQL database driver |
| `mbstring` | Multibyte string handling (untuk nama dengan karakter khusus) |
| `openssl` | Enkripsi password dan HTTPS support |
| `fileinfo` | File type detection untuk upload foto kandidat |
| `tokenizer` | Laravel Blade template compilation |
| `xml` | XML parsing untuk export data |
| `ctype` | Character type checking |
| `json` | JSON encoding/decoding untuk API responses |
| `bcmath` | Precise mathematics untuk vote counting |

---

## 3. JavaScript ES2023

### 3.1. Informasi Versi

```plaintext
JavaScript Engine: V8 (via Chrome/Node.js)
ECMAScript Standard: ES2023 (ES14)
Runtime: Browser (Chrome 155.0) + Node.js 22.23.1
```

### 3.2. Fitur ES2023 yang Digunakan

#### A. **ES Modules (import/export)**
Project menggunakan ES Modules untuk modular code organization.

**File: `resources/js/app.js`**
```javascript
import './bootstrap';
```

Vite build tool otomatis compile ES modules menjadi browser-compatible code.

#### B. **Arrow Functions**
Digunakan untuk callback functions dan event handlers.

**Contoh penggunaan di frontend:**
```javascript
// Event listener dengan arrow function
document.querySelectorAll('.candidate-card').forEach(card => {
    card.addEventListener('click', () => {
        // Handle candidate selection
    });
});

// Array manipulation
const candidateNumbers = candidates.map(c => c.candidate_number);
const totalVotes = votes.reduce((sum, vote) => sum + vote.count, 0);
```

#### C. **Template Literals**
Untuk dynamic string generation di client-side.

```javascript
const roomCode = 'ABC123';
const message = `Kode ruangan: ${roomCode}`;

// Multi-line strings
const errorHTML = `
    <div class="alert alert-error">
        <p>Terjadi kesalahan saat memproses suara Anda.</p>
    </div>
`;
```

#### D. **Destructuring Assignment**
Extract data dari objects/arrays dengan syntax modern.

```javascript
// Object destructuring
const { name, identifier, type } = voterData;

// Array destructuring
const [firstCandidate, secondCandidate, ...others] = candidates;
```

#### E. **Async/Await untuk AJAX**
Handling asynchronous operations (future implementation untuk real-time monitoring).

```javascript
async function fetchVoteResults(eventId) {
    try {
        const response = await fetch(`/api/events/${eventId}/results`);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Gagal memuat hasil voting:', error);
    }
}
```

#### F. **Optional Chaining (?.)**
Safe property access untuk prevent undefined errors.

```javascript
const voterName = voter?.name ?? 'Anonymous';
const eventTitle = event?.title ?? 'Untitled Event';
```

#### G. **Nullish Coalescing (??)**
Default value assignment yang lebih precise.

```javascript
const pageSize = params.pageSize ?? 10;
const searchQuery = params.search ?? '';
```

### 3.3. Browser API yang Digunakan

| API | Kegunaan di Project |
|-----|---------------------|
| **DOM Manipulation** | Update UI elements, show/hide sections |
| **Event Handling** | Click events, form submissions |
| **Fetch API** | AJAX requests untuk dynamic data loading |
| **Local Storage** | Client-side caching (optional) |
| **Console API** | Debugging dan error logging |

---

## 4. SQL (MySQL 8.0 Dialect)

### 4.1. Informasi Database

```plaintext
Database Engine: MySQL Community Server
Version: 8.0.30
Storage Engine: InnoDB (default)
Character Set: utf8mb4_unicode_ci
Collation: utf8mb4_unicode_ci (support emoji dan karakter unicode)
```

### 4.2. SQL Features yang Digunakan

#### A. **Data Definition Language (DDL)**

**1. CREATE TABLE dengan Foreign Keys**
```sql
CREATE TABLE `events` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `created_by` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `category` ENUM('KANDIDAT', 'OPSI') DEFAULT 'KANDIDAT',
    `room_code` VARCHAR(10) UNIQUE NOT NULL,
    `description` TEXT NULL,
    `start_time` DATETIME NULL,
    `end_time` DATETIME NULL,
    `status` ENUM('DRAFT', 'PUBLISHED', 'CLOSED') DEFAULT 'DRAFT',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**2. CREATE TABLE dengan ENUM Types**
```sql
CREATE TABLE `voters` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `identifier` VARCHAR(50) UNIQUE NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `type` ENUM('SISWA', 'GURU') DEFAULT 'SISWA',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
) ENGINE=InnoDB;
```

**3. CREATE TABLE dengan Composite Unique Index**
```sql
CREATE TABLE `votes` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `event_id` BIGINT UNSIGNED NOT NULL,
    `candidate_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`candidate_id`) REFERENCES `candidates`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB;
```

#### B. **Data Manipulation Language (DML)**

**1. INSERT - Pencatatan Suara (Anonymous Voting)**
```sql
INSERT INTO `votes` (`event_id`, `candidate_id`, `created_at`, `updated_at`)
VALUES (1, 3, NOW(), NOW());
```

**2. UPDATE - Perbarui Status Event**
```sql
UPDATE `events`
SET `status` = 'PUBLISHED', `updated_at` = NOW()
WHERE `id` = 1 AND `status` = 'DRAFT';
```

**3. DELETE - Hapus Event dengan Cascade**
```sql
DELETE FROM `events` WHERE `id` = 5;
-- Otomatis hapus: candidates, event_participations, votes (via CASCADE)
```

#### C. **Data Query Language (DQL)**

**1. SELECT dengan JOIN - Ambil Event dengan Creator**
```sql
SELECT 
    e.id, e.title, e.room_code, e.status,
    u.name AS creator_name, u.email AS creator_email
FROM `events` e
INNER JOIN `users` u ON e.created_by = u.id
WHERE e.status = 'PUBLISHED'
ORDER BY e.created_at DESC;
```

**2. COUNT dengan GROUP BY - Hitung Perolehan Suara**
```sql
SELECT 
    c.candidate_number,
    c.name AS candidate_name,
    COUNT(v.id) AS total_votes
FROM `candidates` c
LEFT JOIN `votes` v ON c.id = v.candidate_id
WHERE c.event_id = 1
GROUP BY c.id, c.candidate_number, c.name
ORDER BY c.candidate_number ASC;
```

**3. SUBQUERY - Cek Pemilih yang Sudah Voting**
```sql
SELECT *
FROM `event_participations`
WHERE `event_id` = 1
  AND `voter_id` = 25
  AND `voted_at` IS NOT NULL;
```

**4. LIKE Search dengan Wildcard**
```sql
SELECT * FROM `voters`
WHERE `name` LIKE '%Ahmad%'
   OR `identifier` LIKE '%12345%'
ORDER BY `created_at` DESC
LIMIT 10;
```

**5. BETWEEN - Filter Event Berdasarkan Tanggal**
```sql
SELECT * FROM `events`
WHERE `start_time` BETWEEN '2026-10-01 00:00:00' AND '2026-10-31 23:59:59'
  AND `status` = 'PUBLISHED';
```

#### D. **Transactions untuk Data Integrity**

Eloquent ORM otomatis wrap operasi voting dalam transaction:

```sql
START TRANSACTION;

-- 1. Cek apakah voter sudah voting
SELECT * FROM `event_participations`
WHERE `event_id` = 1 AND `voter_id` = 25
FOR UPDATE; -- Lock row

-- 2. Insert vote (anonymous)
INSERT INTO `votes` (`event_id`, `candidate_id`, `created_at`)
VALUES (1, 3, NOW());

-- 3. Update participation timestamp
UPDATE `event_participations`
SET `voted_at` = NOW()
WHERE `event_id` = 1 AND `voter_id` = 25;

-- 4. Log audit trail
INSERT INTO `audit_logs` (`user_id`, `action`, `description`, `created_at`)
VALUES (NULL, 'VOTE_CAST', 'Vote submitted for event #1', NOW());

COMMIT;
```

Jika ada error di salah satu step → otomatis `ROLLBACK`.

#### E. **MySQL 8 Specific Features**

**1. JSON Data Type (Optional Future Use)**
```sql
-- Bisa digunakan untuk menyimpan metadata event
ALTER TABLE `events` ADD COLUMN `settings` JSON NULL;

INSERT INTO `events` (..., `settings`)
VALUES (..., '{"allow_revote": false, "show_realtime": true}');
```

**2. Window Functions untuk Ranking**
```sql
-- Rank kandidat berdasarkan jumlah suara
SELECT 
    c.name,
    COUNT(v.id) AS votes,
    RANK() OVER (ORDER BY COUNT(v.id) DESC) AS ranking
FROM `candidates` c
LEFT JOIN `votes` v ON c.id = v.candidate_id
WHERE c.event_id = 1
GROUP BY c.id, c.name;
```

### 4.3. Database Constraints

| Constraint | Implementasi di Project |
|------------|-------------------------|
| **PRIMARY KEY** | Semua tabel punya `id` auto-increment |
| **FOREIGN KEY** | Semua relasi antar tabel (dengan CASCADE) |
| **UNIQUE** | `room_code` (events), `identifier` (voters), `email` (users) |
| **NOT NULL** | Field wajib seperti `title`, `name`, `category` |
| **DEFAULT** | `status` default 'DRAFT', `type` default 'SISWA' |
| **ENUM** | Limit nilai field ke pilihan valid (status, category, type, role) |
| **ON DELETE CASCADE** | Auto-delete relasi saat parent dihapus |

---

## 5. Interaksi Antar Bahasa

### 5.1. Request Flow

```
Browser (JavaScript) 
    ↓ HTTP Request
PHP Laravel Router (routes/web.php)
    ↓ Dispatch to Controller
PHP Controller (app/Http/Controllers)
    ↓ Business Logic
PHP Model (app/Models) + Eloquent ORM
    ↓ SQL Query
MySQL Database
    ↓ Result Set
PHP Controller (format data)
    ↓ Pass to View
Blade Template Engine (compile to PHP)
    ↓ Render HTML
Browser (JavaScript handle interactions)
```

### 5.2. Contoh Flow: Cast Vote

**1. JavaScript (Frontend Validation)**
```javascript
document.getElementById('voteForm').addEventListener('submit', (e) => {
    const selectedCandidate = document.querySelector('input[name="candidate_id"]:checked');
    if (!selectedCandidate) {
        e.preventDefault();
        alert('Pilih kandidat terlebih dahulu!');
    }
});
```

**2. PHP (Controller Processing)**
```php
public function castVote(Request $request, string $room_code): RedirectResponse
{
    $request->validate([
        'candidate_id' => ['required', 'integer', 'exists:candidates,id'],
    ]);
    
    // Business logic di sini
}
```

**3. SQL (Data Persistence)**
```sql
INSERT INTO `votes` (`event_id`, `candidate_id`) VALUES (1, 3);
UPDATE `event_participations` SET `voted_at` = NOW() WHERE ...;
```

---

## 6. Development Tools

### 6.1. Code Editors & Syntax Support

| Tool | Digunakan Untuk |
|------|-----------------|
| **VS Code 1.142.0** | Primary code editor |
| **PHP Intelephense** | PHP autocomplete, go-to-definition |
| **ESLint (optional)** | JavaScript code linting |
| **MySQL Workbench** | Visual database design |

### 6.2. Debugging Tools

| Language | Debugging Method |
|----------|------------------|
| **PHP** | `dd()`, `dump()`, Laravel Telescope, error logs |
| **JavaScript** | Browser DevTools Console, `console.log()`, breakpoints |
| **SQL** | Laravel Query Log, MySQL Slow Query Log, `EXPLAIN` statements |

---

## 7. Kesimpulan

### 7.1. Pemilihan Bahasa Pemrograman

| Bahasa | Alasan Pemilihan |
|--------|------------------|
| **PHP 8.3** | ✅ Laravel framework native language<br>✅ Strong typing untuk code safety<br>✅ Mature ecosystem (Composer packages)<br>✅ Excellent ORM (Eloquent) untuk database operations |
| **JavaScript ES2023** | ✅ Browser native language<br>✅ Modern syntax (arrow functions, async/await)<br>✅ Ecosystem besar (NPM packages)<br>✅ Seamless integration dengan Vite build tool |
| **SQL (MySQL)** | ✅ Relational data cocok untuk voting system<br>✅ ACID compliance untuk data integrity<br>✅ Transaction support untuk atomic voting operations<br>✅ Fast read performance untuk real-time vote counting |

### 7.2. Versi yang Digunakan

```plaintext
✅ PHP 8.3.33 (ZTS, Visual C++ 2019, x64)
✅ JavaScript ES2023 (via Node.js 22.23.1 + Chrome 155.0)
✅ MySQL 8.0.30 (Community Server, InnoDB, utf8mb4)
```

### 7.3. Kesesuaian dengan Unit Kompetensi

| Unit Kompetensi | Status |
|-----------------|--------|
| **UK 3: Menganalisis tools** | ✅ Ketiga bahasa dianalisis dari sisi fitur, syntax, dan use case |
| **UK 9: Instalasi** | ✅ PHP, Node.js, MySQL terinstall dan dikonfigurasi dengan benar |
| **UK 10: Pengoperasian** | ✅ Bahasa digunakan secara optimal sesuai modern best practices |

---

**Catatan:** Laporan ini fokus pada **bahasa pemrograman murni**. Untuk penjelasan tentang **framework (Laravel, Tailwind)** dan **templating engine (Blade)**, lihat **Laporan 8: Framework & Library yang Dipakai**.
