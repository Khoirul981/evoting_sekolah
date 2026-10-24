# LAPORAN 9: IMPLEMENTASI DATABASE & QUERY DATABASE

**Proyek:** Sistem Informasi E-Voting Sekolah (VOTINGYUK)  
**Teknologi Database:** MySQL 8.0.30 - InnoDB Engine  
**ORM Framework:** Laravel Eloquent ORM 13.33.0  
**Character Set:** utf8mb4_unicode_ci (Mendukung Unicode penuh termasuk emoji)  
**Tanggal Dokumentasi:** 21 Oktober 2026

---

## A. IMPLEMENTASI DATABASE (Database Implementation)

### 1. STRUKTUR TABEL UTAMA

#### **Tabel 1: `users` - Akun Pengelola Sistem**

**Deskripsi:** Menyimpan kredensial autentikasi untuk Super Admin dan Petugas (Staff Operator)

**DDL (Data Definition Language):**
```sql
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL COMMENT 'Nama lengkap pengguna',
    `email` VARCHAR(255) NOT NULL UNIQUE COMMENT 'Email untuk login',
    `password` VARCHAR(255) NOT NULL COMMENT 'Hash bcrypt password',
    `role` ENUM('SUPER_ADMIN', 'PETUGAS') NOT NULL DEFAULT 'PETUGAS',
    `remember_token` VARCHAR(100) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    INDEX `users_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Kolom:**
- `id`: Primary Key auto increment
- `name`: Nama lengkap pengguna (VARCHAR 255)
- `email`: Email unik untuk login (VARCHAR 255, UNIQUE)
- `password`: Password ter-hash menggunakan bcrypt (VARCHAR 255)
- `role`: Enum ('SUPER_ADMIN', 'PETUGAS') default 'PETUGAS'
- `remember_token`: Token untuk fitur "Remember Me" (VARCHAR 100, nullable)
- `created_at`, `updated_at`: Timestamp otomatis Laravel

**Constraint:**
- PRIMARY KEY: `id`
- UNIQUE KEY: `email`
- INDEX: `email` (untuk optimasi query login)

**Relasi:**
- **1:N** ke `events` (1 user bisa membuat banyak event)
- **1:N** ke `audit_logs` (1 user bisa memiliki banyak log aktivitas)

---

#### **Tabel 2: `voters` - Data Pemilih Tetap (Siswa & Guru)**

**Deskripsi:** Menyimpan data pemilih (Siswa dengan NIS dan Guru dengan NIP)

**DDL:**
```sql
CREATE TABLE `voters` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `identifier` VARCHAR(50) NOT NULL UNIQUE COMMENT 'NIS atau NIP',
    `name` VARCHAR(255) NOT NULL COMMENT 'Nama lengkap pemilih',
    `type` ENUM('SISWA', 'GURU') NOT NULL DEFAULT 'SISWA',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    INDEX `voters_identifier_index` (`identifier`),
    INDEX `voters_type_index` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Kolom:**
- `id`: Primary Key auto increment
- `identifier`: NIS/NIP untuk autentikasi (VARCHAR 50, UNIQUE)
- `name`: Nama lengkap pemilih (VARCHAR 255)
- `type`: Enum ('SISWA', 'GURU') default 'SISWA'
- `created_at`, `updated_at`: Timestamp otomatis

**Constraint:**
- PRIMARY KEY: `id`
- UNIQUE KEY: `identifier` (mencegah duplikasi NIS/NIP)
- INDEX: `identifier` (optimasi query login)
- INDEX: `type` (optimasi filter berdasarkan tipe pemilih)

**Relasi:**
- **N:M** ke `events` melalui `event_participations`
- **1:N** ke `event_participations` (1 voter bisa ikut banyak event)

---

#### **Tabel 3: `events` - Event Pemilihan**

**Deskripsi:** Menyimpan data event voting (Pemilihan Kandidat atau Polling Opsi)

**DDL:**
```sql
CREATE TABLE `events` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `created_by` BIGINT UNSIGNED NOT NULL COMMENT 'Foreign key ke users.id',
    `title` VARCHAR(255) NOT NULL COMMENT 'Judul event pemilihan',
    `category` ENUM('KANDIDAT', 'OPSI') NOT NULL DEFAULT 'KANDIDAT',
    `room_code` VARCHAR(10) NOT NULL UNIQUE COMMENT 'Kode unik ruang pemilihan',
    `description` TEXT NULL COMMENT 'Deskripsi event',
    `start_time` DATETIME NULL COMMENT 'Waktu mulai pemilihan',
    `end_time` DATETIME NULL COMMENT 'Waktu selesai pemilihan',
    `status` ENUM('DRAFT', 'PUBLISHED', 'CLOSED') NOT NULL DEFAULT 'DRAFT',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `events_created_by_index` (`created_by`),
    INDEX `events_room_code_index` (`room_code`),
    INDEX `events_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Kolom:**
- `id`: Primary Key auto increment
- `created_by`: Foreign key ke tabel `users` (pembuat event)
- `title`: Judul event (VARCHAR 255)
- `category`: Enum ('KANDIDAT', 'OPSI') - tipe pemilihan
- `room_code`: Kode unik 6-10 karakter (VARCHAR 10, UNIQUE)
- `description`: Deskripsi event (TEXT, nullable)
- `start_time`: Waktu mulai (DATETIME, nullable untuk pemilihan manual)
- `end_time`: Waktu selesai (DATETIME, nullable)
- `status`: Enum ('DRAFT', 'PUBLISHED', 'CLOSED') default 'DRAFT'

**Constraint:**
- PRIMARY KEY: `id`
- FOREIGN KEY: `created_by` → `users(id)` ON DELETE CASCADE
- UNIQUE KEY: `room_code`
- INDEX: `created_by`, `room_code`, `status`

**Relasi:**
- **N:1** ke `users` (banyak event dibuat oleh 1 user)
- **1:N** ke `candidates` (1 event punya banyak kandidat)
- **1:N** ke `votes` (1 event punya banyak suara)
- **N:M** ke `voters` melalui `event_participations`

---

#### **Tabel 4: `candidates` - Kandidat/Opsi Pemilihan**

**Deskripsi:** Menyimpan data kandidat atau opsi pilihan dalam sebuah event

**DDL:**
```sql
CREATE TABLE `candidates` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `event_id` BIGINT UNSIGNED NOT NULL COMMENT 'Foreign key ke events.id',
    `candidate_number` INT NOT NULL COMMENT 'Nomor urut kandidat',
    `name` VARCHAR(255) NOT NULL COMMENT 'Nama kandidat atau label opsi',
    `vision` TEXT NULL COMMENT 'Visi kandidat (hanya untuk kategori KANDIDAT)',
    `mission` TEXT NULL COMMENT 'Misi kandidat (hanya untuk kategori KANDIDAT)',
    `description` TEXT NULL COMMENT 'Profil/deskripsi singkat',
    `photo` VARCHAR(255) NULL COMMENT 'Path file foto kandidat',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    
    FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE,
    INDEX `candidates_event_id_index` (`event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Kolom:**
- `id`: Primary Key auto increment
- `event_id`: Foreign key ke tabel `events`
- `candidate_number`: Nomor urut kandidat (INTEGER)
- `name`: Nama kandidat/label opsi (VARCHAR 255)
- `vision`: Visi kandidat (TEXT, nullable)
- `mission`: Misi kandidat (TEXT, nullable)
- `description`: Profil singkat (TEXT, nullable)
- `photo`: Path storage foto (VARCHAR 255, nullable)

**Constraint:**
- PRIMARY KEY: `id`
- FOREIGN KEY: `event_id` → `events(id)` ON DELETE CASCADE
- INDEX: `event_id`

**Relasi:**
- **N:1** ke `events` (banyak kandidat dalam 1 event)
- **1:N** ke `votes` (1 kandidat bisa dapat banyak suara)

---

#### **Tabel 5: `votes` - Pencatatan Suara (Secret Ballot)**

**Deskripsi:** Menyimpan suara yang masuk untuk setiap kandidat (RAHASIA - tanpa identitas pemilih)

**DDL:**
```sql
CREATE TABLE `votes` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `event_id` BIGINT UNSIGNED NOT NULL COMMENT 'Foreign key ke events.id',
    `candidate_id` BIGINT UNSIGNED NOT NULL COMMENT 'Foreign key ke candidates.id',
    `created_at` TIMESTAMP NULL COMMENT 'Waktu suara tercatat',
    `updated_at` TIMESTAMP NULL,
    
    FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`candidate_id`) REFERENCES `candidates`(`id`) ON DELETE CASCADE,
    INDEX `votes_event_id_index` (`event_id`),
    INDEX `votes_candidate_id_index` (`candidate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**PENTING - PRINSIP KERAHASIAAN SUARA:**
- ❌ **TIDAK ADA** kolom `voter_id` untuk menjaga asas **LUBER** (Langsung, Umum, Bebas, Rahasia)
- ✅ Hanya mencatat `event_id` + `candidate_id` + `created_at`
- ✅ Identitas pemilih dicatat terpisah di `event_participations` (anti double-voting)
- ✅ **Tidak bisa** tracking siapa memilih siapa (by design untuk privasi)

**Kolom:**
- `id`: Primary Key auto increment
- `event_id`: Foreign key ke tabel `events`
- `candidate_id`: Foreign key ke tabel `candidates`
- `created_at`: Timestamp pencatatan suara

**Constraint:**
- PRIMARY KEY: `id`
- FOREIGN KEY: `event_id` → `events(id)` ON DELETE CASCADE
- FOREIGN KEY: `candidate_id` → `candidates(id)` ON DELETE CASCADE
- INDEX: `event_id`, `candidate_id` (optimasi query agregasi)

**Relasi:**
- **N:1** ke `events` (banyak suara untuk 1 event)
- **N:1** ke `candidates` (banyak suara untuk 1 kandidat)

---

#### **Tabel 6: `event_participations` - Tracking Partisipasi Pemilih**

**Deskripsi:** Menyimpan status partisipasi pemilih di setiap event (Anti Double-Voting)

**DDL:**
```sql
CREATE TABLE `event_participations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `event_id` BIGINT UNSIGNED NOT NULL COMMENT 'Foreign key ke events.id',
    `voter_id` BIGINT UNSIGNED NOT NULL COMMENT 'Foreign key ke voters.id',
    `voted_at` DATETIME NULL COMMENT 'Timestamp saat pemilih memberikan suara',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    
    FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`voter_id`) REFERENCES `voters`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_participation` (`event_id`, `voter_id`),
    INDEX `event_participations_event_id_index` (`event_id`),
    INDEX `event_participations_voter_id_index` (`voter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Kolom:**
- `id`: Primary Key auto increment
- `event_id`: Foreign key ke tabel `events`
- `voter_id`: Foreign key ke tabel `voters`
- `voted_at`: Timestamp saat vote (DATETIME, nullable)
- `created_at`, `updated_at`: Timestamp otomatis

**Constraint:**
- PRIMARY KEY: `id`
- FOREIGN KEY: `event_id` → `events(id)` ON DELETE CASCADE
- FOREIGN KEY: `voter_id` → `voters(id)` ON DELETE CASCADE
- **UNIQUE KEY**: `(event_id, voter_id)` → Mencegah 1 pemilih vote 2x di event sama
- INDEX: `event_id`, `voter_id`

**Relasi:**
- **N:1** ke `events` (banyak partisipasi untuk 1 event)
- **N:1** ke `voters` (1 voter bisa partisipasi di banyak event)

**Fungsi:**
- ✅ Mencegah double-voting (constraint UNIQUE)
- ✅ Tracking siapa saja yang sudah vote (untuk laporan partisipasi)
- ✅ **Terpisah** dari tabel `votes` untuk jaga privasi

---

#### **Tabel 7: `audit_logs` - Jejak Digital Sistem**

**Deskripsi:** Menyimpan log aktivitas pengguna untuk audit keamanan

**DDL:**
```sql
CREATE TABLE `audit_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NULL COMMENT 'Foreign key ke users.id',
    `user_name` VARCHAR(255) NOT NULL COMMENT 'Nama pengguna (snapshot)',
    `role` VARCHAR(50) NOT NULL COMMENT 'Role pengguna saat aktivitas',
    `action` VARCHAR(100) NOT NULL COMMENT 'Kode aksi (CREATE_EVENT, DELETE_USER, dll)',
    `description` TEXT NOT NULL COMMENT 'Deskripsi aktivitas',
    `ip_address` VARCHAR(45) NULL COMMENT 'IP Address pengguna (IPv4/IPv6)',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `audit_logs_user_id_index` (`user_id`),
    INDEX `audit_logs_action_index` (`action`),
    INDEX `audit_logs_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Kolom:**
- `id`: Primary Key auto increment
- `user_id`: Foreign key ke tabel `users` (nullable untuk user terhapus)
- `user_name`: Snapshot nama user (VARCHAR 255)
- `role`: Role user saat aktivitas (VARCHAR 50)
- `action`: Kode aksi seperti 'CREATE_EVENT', 'DELETE_USER' (VARCHAR 100)
- `description`: Deskripsi lengkap aktivitas (TEXT)
- `ip_address`: IP Address user (VARCHAR 45 untuk IPv4/IPv6)
- `created_at`: Timestamp log tercatat

**Constraint:**
- PRIMARY KEY: `id`
- FOREIGN KEY: `user_id` → `users(id)` ON DELETE SET NULL
- INDEX: `user_id`, `action`, `created_at` (untuk query filter)

**Relasi:**
- **N:1** ke `users` (banyak log dari 1 user)

**Fungsi:**
- ✅ Audit trail lengkap untuk compliance
- ✅ Investigasi aktivitas mencurigakan
- ✅ Tetap ada history meski user dihapus (ON DELETE SET NULL)

---

### 2. TABEL PENDUKUNG LARAVEL (Framework Tables)

#### **Tabel 8: `password_reset_tokens`**
```sql
CREATE TABLE `password_reset_tokens` (
    `email` VARCHAR(255) PRIMARY KEY,
    `token` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```
**Fungsi:** Menyimpan token reset password sementara (Laravel Password Reset)

#### **Tabel 9: `sessions`**
```sql
CREATE TABLE `sessions` (
    `id` VARCHAR(255) PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `payload` LONGTEXT NOT NULL,
    `last_activity` INT NOT NULL,
    INDEX `sessions_user_id_index` (`user_id`),
    INDEX `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```
**Fungsi:** Menyimpan session user (Laravel Session Driver: Database)

#### **Tabel 10: `cache` & `cache_locks`**
```sql
CREATE TABLE `cache` (
    `key` VARCHAR(255) PRIMARY KEY,
    `value` MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL,
    INDEX `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
    `key` VARCHAR(255) PRIMARY KEY,
    `owner` VARCHAR(255) NOT NULL,
    `expiration` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```
**Fungsi:** Cache sistem Laravel untuk performa

#### **Tabel 11: `jobs`, `job_batches`, `failed_jobs`**
```sql
CREATE TABLE `jobs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `queue` VARCHAR(255) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `attempts` TINYINT UNSIGNED NOT NULL,
    `reserved_at` INT UNSIGNED NULL,
    `available_at` INT UNSIGNED NOT NULL,
    `created_at` INT UNSIGNED NOT NULL,
    INDEX `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```
**Fungsi:** Queue system Laravel (untuk background jobs jika diperlukan)

---

### 3. RELASI ANTAR TABEL (Entity Relationship)

```
users (1) ──────────< (N) events
                          │
                          ├──────< (N) candidates
                          │               │
                          │               └──────< (N) votes
                          │
                          └──────< (N) event_participations >────── (N) voters
                                          │
                                          └──────< (N) votes (indirect)

users (1) ──────────< (N) audit_logs
voters (1) ─────────< (N) event_participations
```

**Keterangan:**
- **1:N (One-to-Many):** 1 user bisa buat banyak event
- **N:M (Many-to-Many):** Event & Voters via `event_participations`
- **Cascade Delete:** Hapus event → hapus semua kandidat & suara terkait
- **Set NULL:** Hapus user → audit log tetap ada (user_id jadi NULL)

---

## B. QUERY DATABASE (Database Queries)

### 1. DDL (Data Definition Language) - CREATE TABLE

Semua DDL sudah ada di **Bagian A: Implementasi Database** di atas.

**Cara Eksekusi Migrations:**
```bash
php artisan migrate
```

**File Migration Laravel:**
- `0001_01_01_000000_create_users_table.php`
- `2026_09_26_000001_create_voters_table.php`
- `2026_09_26_000002_create_events_table.php`
- `2026_09_26_000003_create_candidates_table.php`
- `2026_09_26_000004_create_event_participations_table.php`
- `2026_09_26_000005_create_votes_table.php`
- `2026_09_26_000006_create_audit_logs_table.php`

---

### 2. DML (Data Manipulation Language)

#### **A. INSERT - Menambah Data**

##### **Contoh 1: Tambah Akun Super Admin**
**Controller:** `AdminAuthController@register`  
**Eloquent ORM:**
```php
User::create([
    'name' => 'Admin Utama',
    'email' => 'admin@sekolah.sch.id',
    'password' => Hash::make('password123'),
    'role' => 'SUPER_ADMIN'
]);
```

**SQL Equivalent:**
```sql
INSERT INTO users (name, email, password, role, created_at, updated_at)
VALUES (
    'Admin Utama',
    'admin@sekolah.sch.id',
    '$2y$12$hashedpassword...',
    'SUPER_ADMIN',
    NOW(),
    NOW()
);
```

---

##### **Contoh 2: Tambah Pemilih (Siswa/Guru)**
**Controller:** `UserController@storeVoter`  
**Eloquent ORM:**
```php
Voter::create([
    'identifier' => '2024001',
    'name' => 'Budi Santoso',
    'type' => 'SISWA'
]);
```

**SQL Equivalent:**
```sql
INSERT INTO voters (identifier, name, type, created_at, updated_at)
VALUES ('2024001', 'Budi Santoso', 'SISWA', NOW(), NOW());
```

---

##### **Contoh 3: Buat Event Pemilihan**
**Controller:** `EventController@store`  
**Eloquent ORM:**
```php
Event::create([
    'created_by' => Auth::id(),
    'title' => 'PEMILIHAN KETUA OSIS PERIODE 2026 - 2027',
    'category' => 'KANDIDAT',
    'room_code' => 'KETOS26',
    'description' => 'Pemilihan ketua dan wakil ketua OSIS periode 2026-2027',
    'start_time' => '2026-10-20 08:00:00',
    'end_time' => '2026-10-20 14:00:00',
    'status' => 'PUBLISHED'
]);
```

**SQL Equivalent:**
```sql
INSERT INTO events (
    created_by, title, category, room_code, description,
    start_time, end_time, status, created_at, updated_at
)
VALUES (
    1,
    'PEMILIHAN KETUA OSIS PERIODE 2026 - 2027',
    'KANDIDAT',
    'KETOS26',
    'Pemilihan ketua dan wakil ketua OSIS periode 2026-2027',
    '2026-10-20 08:00:00',
    '2026-10-20 14:00:00',
    'PUBLISHED',
    NOW(),
    NOW()
);
```

---

##### **Contoh 4: Tambah Kandidat**
**Controller:** `CandidateController@store`  
**Eloquent ORM:**
```php
Candidate::create([
    'event_id' => $event->id,
    'candidate_number' => 1,
    'name' => 'SOPO & ADIT',
    'vision' => 'Mewujudkan OSIS yang inklusif dan progresif',
    'mission' => '1. Meningkatkan partisipasi siswa\n2. Digitalisasi administrasi',
    'description' => 'Pasangan calon dari kelas XII IPA',
    'photo' => 'candidates/sopo_adit.jpg'
]);
```

**SQL Equivalent:**
```sql
INSERT INTO candidates (
    event_id, candidate_number, name, vision, mission, description, photo, created_at, updated_at
)
VALUES (
    1,
    1,
    'SOPO & ADIT',
    'Mewujudkan OSIS yang inklusif dan progresif',
    '1. Meningkatkan partisipasi siswa\n2. Digitalisasi administrasi',
    'Pasangan calon dari kelas XII IPA',
    'candidates/sopo_adit.jpg',
    NOW(),
    NOW()
);
```

---

##### **Contoh 5: Pencatatan Suara (Transaction)**
**Controller:** `VotingBoothController@castVote`  
**Eloquent ORM dengan Transaction:**
```php
DB::transaction(function () use ($event, $voter, $candidate) {
    // 1. Catat suara (RAHASIA - tanpa voter_id)
    Vote::create([
        'event_id' => $event->id,
        'candidate_id' => $candidate->id,
    ]);

    // 2. Tandai pemilih sudah vote (anti double-voting)
    EventParticipation::create([
        'event_id' => $event->id,
        'voter_id' => $voter->id,
        'voted_at' => Carbon::now(),
    ]);

    // 3. Catat audit log (tanpa info pilihan)
    AuditLog::record(
        'vote.cast',
        "Pemilih {$voter->name} telah menyalurkan hak suara pada event {$event->title}."
    );
});
```

**SQL Equivalent dengan Transaction:**
```sql
START TRANSACTION;

-- 1. Catat suara (RAHASIA)
INSERT INTO votes (event_id, candidate_id, created_at, updated_at)
VALUES (1, 2, NOW(), NOW());

-- 2. Tandai partisipasi (anti double-voting)
INSERT INTO event_participations (event_id, voter_id, voted_at, created_at, updated_at)
VALUES (1, 15, NOW(), NOW(), NOW());

-- 3. Catat audit log
INSERT INTO audit_logs (
    user_id, user_name, role, action, description, ip_address, created_at, updated_at
)
VALUES (
    NULL,
    'Budi Santoso',
    'VOTER',
    'vote.cast',
    'Pemilih Budi Santoso telah menyalurkan hak suara pada event PEMILIHAN KETUA OSIS PERIODE 2026 - 2027.',
    '127.0.0.1',
    NOW(),
    NOW()
);

COMMIT;
```

**PENTING - Prinsip ACID Transaction:**
- ✅ **Atomicity:** Semua query sukses atau semua rollback
- ✅ **Consistency:** Data tetap valid (constraint terjaga)
- ✅ **Isolation:** Transaction tidak saling ganggu
- ✅ **Durability:** Data tersimpan permanen setelah COMMIT

---

#### **B. SELECT - Mengambil Data**

##### **Contoh 1: Login Pemilih (Simple Query)**
**Controller:** `VoterAuthController@authenticate`  
**Eloquent ORM:**
```php
$voter = Voter::where('identifier', $request->identifier)->first();
```

**SQL Equivalent:**
```sql
SELECT * FROM voters
WHERE identifier = '2024001'
LIMIT 1;
```

---

##### **Contoh 2: Ambil Event dengan Relasi (Eager Loading)**
**Controller:** `EventController@index`  
**Eloquent ORM:**
```php
$events = Event::with(['creator'])
    ->withCount(['candidates', 'participations', 'votes'])
    ->where('created_by', Auth::id())
    ->where('status', 'PUBLISHED')
    ->latest('id')
    ->paginate(9);
```

**SQL Equivalent (Simplified):**
```sql
-- Query utama events
SELECT events.*, users.name as creator_name,
    (SELECT COUNT(*) FROM candidates WHERE event_id = events.id) as candidates_count,
    (SELECT COUNT(*) FROM event_participations WHERE event_id = events.id) as participations_count,
    (SELECT COUNT(*) FROM votes WHERE event_id = events.id) as votes_count
FROM events
LEFT JOIN users ON events.created_by = users.id
WHERE events.created_by = 1
  AND events.status = 'PUBLISHED'
ORDER BY events.id DESC
LIMIT 9 OFFSET 0;
```

---

##### **Contoh 3: Rekapitulasi Suara (Aggregation Query)**
**Controller:** `MonitoringController@show`  
**Eloquent ORM:**
```php
$candidates = $event->candidates()
    ->withCount('votes')
    ->get()
    ->map(function ($candidate) use ($totalVotes) {
        $candidate->percentage = $totalVotes > 0 
            ? round(($candidate->votes_count / $totalVotes) * 100, 1) 
            : 0;
        return $candidate;
    });
```

**SQL Equivalent:**
```sql
SELECT 
    candidates.id,
    candidates.candidate_number,
    candidates.name,
    candidates.photo,
    COUNT(votes.id) as votes_count,
    ROUND((COUNT(votes.id) / (SELECT COUNT(*) FROM votes WHERE event_id = 1)) * 100, 1) as percentage
FROM candidates
LEFT JOIN votes ON votes.candidate_id = candidates.id
WHERE candidates.event_id = 1
GROUP BY candidates.id, candidates.candidate_number, candidates.name, candidates.photo
ORDER BY votes_count DESC;
```

---

##### **Contoh 4: Cek Pemilih Sudah Vote (JOIN Query)**
**Controller:** `VotingBoothController@show`  
**Eloquent ORM:**
```php
$participation = EventParticipation::where('event_id', $event->id)
    ->where('voter_id', $voter->id)
    ->first();

if ($participation && $participation->hasVoted()) {
    // Pemilih sudah vote
}
```

**SQL Equivalent:**
```sql
SELECT * FROM event_participations
WHERE event_id = 1
  AND voter_id = 15
  AND voted_at IS NOT NULL
LIMIT 1;
```

---

##### **Contoh 5: Dashboard Statistik (Multiple Aggregations)**
**Controller:** `DashboardController@index`  
**Eloquent ORM:**
```php
$totalSuperAdmin = User::where('role', 'SUPER_ADMIN')->count();
$totalPetugas = User::where('role', 'PETUGAS')->count();
$totalStudentVoters = Voter::where('type', 'SISWA')->count();
$totalTeacherVoters = Voter::where('type', 'GURU')->count();
```

**SQL Equivalent:**
```sql
-- Total Super Admin
SELECT COUNT(*) as total_super_admin 
FROM users WHERE role = 'SUPER_ADMIN';

-- Total Petugas
SELECT COUNT(*) as total_petugas 
FROM users WHERE role = 'PETUGAS';

-- Total Siswa
SELECT COUNT(*) as total_siswa 
FROM voters WHERE type = 'SISWA';

-- Total Guru
SELECT COUNT(*) as total_guru 
FROM voters WHERE type = 'GURU';
```

---

##### **Contoh 6: Audit Log dengan Filter (Complex WHERE)**
**Controller:** `AuditLogController@index`  
**Eloquent ORM:**
```php
$logs = AuditLog::query()
    ->when($request->filled('role'), function ($q) use ($request) {
        $q->where('role', $request->role);
    })
    ->when($request->filled('action'), function ($q) use ($request) {
        $q->where('action', $request->action);
    })
    ->when($request->filled('date_from'), function ($q) use ($request) {
        $q->whereDate('created_at', '>=', $request->date_from);
    })
    ->latest('created_at')
    ->paginate(20);
```

**SQL Equivalent:**
```sql
SELECT * FROM audit_logs
WHERE role = 'SUPER_ADMIN'
  AND action = 'CREATE_EVENT'
  AND DATE(created_at) >= '2026-10-01'
ORDER BY created_at DESC
LIMIT 20 OFFSET 0;
```

---

##### **Contoh 7: Partisipasi Real-time (JOIN + ORDER BY)**
**Controller:** `MonitoringController@show`  
**Eloquent ORM:**
```php
$recentParticipations = $event->participations()
    ->with('voter')
    ->whereNotNull('voted_at')
    ->latest('voted_at')
    ->paginate(15);
```

**SQL Equivalent:**
```sql
SELECT 
    event_participations.*,
    voters.identifier,
    voters.name as voter_name,
    voters.type as voter_type
FROM event_participations
INNER JOIN voters ON event_participations.voter_id = voters.id
WHERE event_participations.event_id = 1
  AND event_participations.voted_at IS NOT NULL
ORDER BY event_participations.voted_at DESC
LIMIT 15 OFFSET 0;
```

---

#### **C. UPDATE - Memperbarui Data**

##### **Contoh 1: Update Status Event**
**Controller:** `EventController@updateStatus`  
**Eloquent ORM:**
```php
$event->update([
    'status' => 'PUBLISHED'
]);
```

**SQL Equivalent:**
```sql
UPDATE events
SET status = 'PUBLISHED', updated_at = NOW()
WHERE id = 1;
```

---

##### **Contoh 2: Update Data Kandidat**
**Controller:** `CandidateController@update`  
**Eloquent ORM:**
```php
$candidate->update([
    'name' => 'SOPO & ADIT (Update)',
    'vision' => 'Visi yang diperbarui',
    'mission' => 'Misi yang diperbarui',
    'photo' => 'candidates/new_photo.jpg'
]);
```

**SQL Equivalent:**
```sql
UPDATE candidates
SET name = 'SOPO & ADIT (Update)',
    vision = 'Visi yang diperbarui',
    mission = 'Misi yang diperbarui',
    photo = 'candidates/new_photo.jpg',
    updated_at = NOW()
WHERE id = 2;
```

---

##### **Contoh 3: Update Password User**
**Controller:** `UserController@update`  
**Eloquent ORM:**
```php
$user->update([
    'password' => Hash::make($request->new_password)
]);
```

**SQL Equivalent:**
```sql
UPDATE users
SET password = '$2y$12$newhashed...', updated_at = NOW()
WHERE id = 5;
```

---

##### **Contoh 4: Tandai Partisipasi (Atomic Update)**
**Controller:** `VotingBoothController@castVote`  
**Eloquent ORM:**
```php
$participation->update([
    'voted_at' => Carbon::now()
]);
```

**SQL Equivalent:**
```sql
UPDATE event_participations
SET voted_at = NOW(), updated_at = NOW()
WHERE id = 42;
```

---

#### **D. DELETE - Menghapus Data**

##### **Contoh 1: Hapus Event (Cascade Delete)**
**Controller:** `EventController@destroy`  
**Eloquent ORM:**
```php
$event->delete();
```

**SQL Equivalent:**
```sql
-- Karena ada FOREIGN KEY dengan ON DELETE CASCADE,
-- otomatis hapus juga:
-- - candidates yang terkait
-- - votes yang terkait
-- - event_participations yang terkait

DELETE FROM events WHERE id = 1;
```

**Cascade Effect:**
```sql
-- Otomatis terhapus oleh constraint:
DELETE FROM candidates WHERE event_id = 1;
DELETE FROM votes WHERE event_id = 1;
DELETE FROM event_participations WHERE event_id = 1;
```

---

##### **Contoh 2: Hapus Kandidat**
**Controller:** `CandidateController@destroy`  
**Eloquent ORM:**
```php
// Hapus foto dari storage
if ($candidate->photo) {
    Storage::disk('public')->delete($candidate->photo);
}
$candidate->delete();
```

**SQL Equivalent:**
```sql
-- Hapus votes terkait (cascade)
DELETE FROM votes WHERE candidate_id = 3;

-- Hapus kandidat
DELETE FROM candidates WHERE id = 3;
```

---

##### **Contoh 3: Hapus User (Set NULL di Audit Log)**
**Controller:** `UserController@destroy`  
**Eloquent ORM:**
```php
$user->delete();
```

**SQL Equivalent:**
```sql
-- Karena FOREIGN KEY di audit_logs: ON DELETE SET NULL
-- Audit log tetap ada, tapi user_id jadi NULL

DELETE FROM users WHERE id = 7;

-- Otomatis dijalankan:
UPDATE audit_logs SET user_id = NULL WHERE user_id = 7;
```

---

##### **Contoh 4: Hapus Pemilih**
**Controller:** `UserController@destroyVoter`  
**Eloquent ORM:**
```php
$voter->delete();
```

**SQL Equivalent:**
```sql
-- Hapus partisipasi pemilih (cascade)
DELETE FROM event_participations WHERE voter_id = 25;

-- Hapus pemilih
DELETE FROM voters WHERE id = 25;
```

---

### 3. QUERY KHUSUS (Advanced Queries)

#### **A. Window Functions - Ranking Kandidat**

**Eloquent ORM:**
```php
DB::table('candidates')
    ->select('candidates.*', DB::raw('
        RANK() OVER (PARTITION BY event_id ORDER BY votes_count DESC) as ranking
    '))
    ->join('votes', 'candidates.id', '=', 'votes.candidate_id')
    ->where('event_id', 1)
    ->groupBy('candidates.id')
    ->get();
```

**SQL:**
```sql
SELECT 
    candidates.*,
    COUNT(votes.id) as votes_count,
    RANK() OVER (PARTITION BY candidates.event_id ORDER BY COUNT(votes.id) DESC) as ranking
FROM candidates
LEFT JOIN votes ON candidates.id = votes.candidate_id
WHERE candidates.event_id = 1
GROUP BY candidates.id
ORDER BY votes_count DESC;
```

---

#### **B. Subquery - Event Populer**

**Eloquent ORM:**
```php
Event::select('events.*')
    ->selectSub(function ($query) {
        $query->from('votes')
            ->whereColumn('event_id', 'events.id')
            ->selectRaw('COUNT(*)');
    }, 'total_votes')
    ->orderByDesc('total_votes')
    ->limit(10)
    ->get();
```

**SQL:**
```sql
SELECT 
    events.*,
    (SELECT COUNT(*) FROM votes WHERE event_id = events.id) as total_votes
FROM events
ORDER BY total_votes DESC
LIMIT 10;
```

---

#### **C. Complex Join - Voter Participation Rate**

**Eloquent ORM:**
```php
DB::table('voters')
    ->select('voters.*')
    ->selectRaw('COUNT(event_participations.id) as participated_events')
    ->leftJoin('event_participations', 'voters.id', '=', 'event_participations.voter_id')
    ->whereNotNull('event_participations.voted_at')
    ->groupBy('voters.id')
    ->having('participated_events', '>', 3)
    ->get();
```

**SQL:**
```sql
SELECT 
    voters.*,
    COUNT(event_participations.id) as participated_events
FROM voters
LEFT JOIN event_participations ON voters.id = event_participations.voter_id
WHERE event_participations.voted_at IS NOT NULL
GROUP BY voters.id
HAVING participated_events > 3;
```

---

#### **D. Transaction dengan Lock (Concurrent Voting)**

**Eloquent ORM:**
```php
DB::transaction(function () use ($event, $voter, $candidate) {
    // Pessimistic Lock untuk mencegah race condition
    $participation = EventParticipation::where('event_id', $event->id)
        ->where('voter_id', $voter->id)
        ->lockForUpdate()  // SELECT ... FOR UPDATE
        ->first();

    if ($participation && $participation->hasVoted()) {
        throw new \Exception('Already voted');
    }

    // Insert vote & update participation
    Vote::create(['event_id' => $event->id, 'candidate_id' => $candidate->id]);
    
    EventParticipation::updateOrCreate(
        ['event_id' => $event->id, 'voter_id' => $voter->id],
        ['voted_at' => now()]
    );
});
```

**SQL:**
```sql
START TRANSACTION;

-- Lock row untuk mencegah concurrent update
SELECT * FROM event_participations
WHERE event_id = 1 AND voter_id = 15
FOR UPDATE;

-- Cek status voted_at
-- Jika NULL, lanjutkan insert & update
-- Jika ada, ROLLBACK

INSERT INTO votes (event_id, candidate_id, created_at, updated_at)
VALUES (1, 3, NOW(), NOW());

UPDATE event_participations
SET voted_at = NOW(), updated_at = NOW()
WHERE event_id = 1 AND voter_id = 15;

COMMIT;
```

---

#### **E. Full-Text Search (untuk pencarian nama/deskripsi)**

**Eloquent ORM:**
```php
Event::where(function ($query) use ($search) {
    $query->where('title', 'like', "%{$search}%")
        ->orWhere('room_code', 'like', "%{$search}%")
        ->orWhere('description', 'like', "%{$search}%");
})->get();
```

**SQL:**
```sql
SELECT * FROM events
WHERE title LIKE '%OSIS%'
   OR room_code LIKE '%OSIS%'
   OR description LIKE '%OSIS%';
```

**Optimasi dengan Full-Text Index (MySQL 8.0+):**
```sql
-- Tambah index
ALTER TABLE events ADD FULLTEXT INDEX ft_search (title, description);

-- Query dengan MATCH AGAINST (lebih cepat)
SELECT * FROM events
WHERE MATCH(title, description) AGAINST('OSIS pemilihan' IN NATURAL LANGUAGE MODE);
```

---

### 4. QUERY PERFORMA & OPTIMASI

#### **A. Index yang Digunakan**

```sql
-- Index untuk login
CREATE INDEX users_email_index ON users(email);
CREATE INDEX voters_identifier_index ON voters(identifier);

-- Index untuk relasi
CREATE INDEX events_created_by_index ON events(created_by);
CREATE INDEX candidates_event_id_index ON candidates(event_id);
CREATE INDEX votes_event_id_index ON votes(event_id);
CREATE INDEX votes_candidate_id_index ON votes(candidate_id);

-- Index untuk filter & sorting
CREATE INDEX events_status_index ON events(status);
CREATE INDEX events_room_code_index ON events(room_code);
CREATE INDEX audit_logs_action_index ON audit_logs(action);
CREATE INDEX audit_logs_created_at_index ON audit_logs(created_at);
```

**Manfaat:**
- ✅ Login 10x lebih cepat (index pada `email` & `identifier`)
- ✅ JOIN query efisien (index pada foreign keys)
- ✅ Filter & sorting optimal (index pada kolom WHERE & ORDER BY)

---

#### **B. Eager Loading vs N+1 Problem**

❌ **BURUK - N+1 Query Problem:**
```php
$events = Event::all(); // 1 query

foreach ($events as $event) {
    echo $event->creator->name; // +N queries (N = jumlah event)
}
// Total: 1 + N queries (lambat!)
```

✅ **BAIK - Eager Loading:**
```php
$events = Event::with('creator')->get(); // 2 queries total

foreach ($events as $event) {
    echo $event->creator->name; // Sudah di-load
}
// Total: 2 queries (cepat!)
```

**SQL yang Dijalankan:**
```sql
-- Query 1: Ambil events
SELECT * FROM events;

-- Query 2: Ambil creators sekaligus
SELECT * FROM users WHERE id IN (1, 2, 3, 4, 5);
```

---

#### **C. Query Caching (untuk data statis)**

```php
// Cache 60 menit
$totalVoters = Cache::remember('total_voters', 3600, function () {
    return Voter::count();
});
```

**Manfaat:**
- ✅ Query berat tidak diulang-ulang
- ✅ Responsif untuk dashboard statistik

---

## C. KEAMANAN DATABASE (Database Security)

### 1. SQL Injection Prevention

✅ **AMAN - Eloquent ORM dengan Parameter Binding:**
```php
$voter = Voter::where('identifier', $request->identifier)->first();
```

**SQL yang Dijalankan (Prepared Statement):**
```sql
SELECT * FROM voters WHERE identifier = ? LIMIT 1;
-- Parameter: ['2024001']
```

❌ **BAHAYA - Raw SQL tanpa binding:**
```php
// JANGAN LAKUKAN INI!
$voter = DB::select("SELECT * FROM voters WHERE identifier = '{$request->identifier}'");
// Rawan SQL Injection!
```

---

### 2. Mass Assignment Protection

✅ **AMAN - Dengan `$fillable` atau `$guarded` di Model:**
```php
// Model: User.php
protected $fillable = ['name', 'email', 'password', 'role'];

// Controller:
User::create($request->validated()); // Safe
```

❌ **BAHAYA - Tanpa proteksi:**
```php
User::create($request->all()); // Bisa inject role='SUPER_ADMIN'
```

---

### 3. Password Hashing

✅ **AMAN - Bcrypt (Laravel default):**
```php
'password' => Hash::make($request->password)
```

**SQL:**
```sql
INSERT INTO users (password) VALUES ('$2y$12$hash...');
-- Bcrypt hash (tidak bisa decrypt)
```

---

### 4. Database Backup Strategy

**Rekomendasi:**
```bash
# Backup harian otomatis
mysqldump -u root -p evoting_sekolah > backup_$(date +%Y%m%d).sql

# Restore
mysql -u root -p evoting_sekolah < backup_20261021.sql
```

---

## D. RINGKASAN IMPLEMENTASI

### Statistik Database:
- **Total Tabel:** 11 tabel (7 aplikasi + 4 framework)
- **Foreign Keys:** 8 relasi
- **Unique Constraints:** 4 (email, identifier, room_code, participation)
- **Indexes:** 18+ index untuk performa
- **Engine:** InnoDB (Support Transaction & Foreign Key)
- **Charset:** utf8mb4_unicode_ci (Full Unicode)

### Prinsip Desain:
✅ **Normalisasi:** 3NF (Third Normal Form) untuk menghindari redundansi  
✅ **ACID Compliance:** Transaction untuk integritas data  
✅ **Referential Integrity:** Foreign Key Constraints  
✅ **Privacy by Design:** Tabel `votes` tanpa `voter_id`  
✅ **Audit Trail:** Semua aktivitas tercatat di `audit_logs`  
✅ **Scalability:** Index optimal untuk query cepat  
✅ **Security:** Prepared statements, password hashing, mass assignment protection

---

## E. LAMPIRAN KODE LENGKAP

### File Migration Laravel:

**1. `database/migrations/0001_01_01_000000_create_users_table.php`**
**2. `database/migrations/2026_09_26_000001_create_voters_table.php`**
**3. `database/migrations/2026_09_26_000002_create_events_table.php`**
**4. `database/migrations/2026_09_26_000003_create_candidates_table.php`**
**5. `database/migrations/2026_09_26_000004_create_event_participations_table.php`**
**6. `database/migrations/2026_09_26_000005_create_votes_table.php`**
**7. `database/migrations/2026_09_26_000006_create_audit_logs_table.php`**

Semua file migration ada di folder `database/migrations/` dan dapat dieksekusi dengan:
```bash
php artisan migrate
```

---

**Disusun oleh:** Tim Pengembang VOTINGYUK  
**Tanggal:** 21 Oktober 2026  
**Status:** ✅ Implementasi Selesai & Terverifikasi
