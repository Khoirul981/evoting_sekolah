# Laporan Rancangan Database: ERD & PDM
## Sistem E-Voting Sekolah "VOTINGYUK"

---

## 1. Gambaran Umum Database

Sistem **VOTINGYUK** menggunakan **MySQL/MariaDB** sebagai DBMS dengan **7 tabel utama** yang saling berelasi. Database dirancang untuk mendukung sistem multi-user dengan pemisahan role yang jelas dan menjaga integritas data voting.

### Karakteristik Database:
- **Nama Database**: `evoting_sekolah`
- **DBMS**: MySQL 8.0+ / MariaDB 10.x
- **Engine**: InnoDB (mendukung foreign key & transaction)
- **Charset**: utf8mb4_unicode_ci (mendukung emoji & karakter internasional)
- **Total Tabel**: 7 tabel core + 3 tabel support (password_reset_tokens, sessions, cache)

---
``
┌─────────────────┐
│     USERS       │
│  (Admin/Pet.)   │
│─────────────────│
│ PK: id          │
│     name        │
│     email       │
│     password    │
│     role        │
│ remember_token  │
└────────┬────────┘
         │ 1
         │ created_by
         │
         │ N
┌────────▼────────────────┐           ┌──────────────────┐
│       EVENTS            │           │   VOTERS         │
│─────────────────────────│           │──────────────────│
│ PK: id                  │           │ PK: id           │
│ FK: created_by (users)  │           │     identifier   │
│     title               │           │     name         │
│     category            │           │     type         │
│     room_code (UNIQUE)  │           │     is_active    │
│     description         │           └────────┬─────────┘
│     start_time          │                    │
│     end_time            │                    │ voter_id
│     status              │                    │
└────┬────────────────────┘                    │
     │ 1                                       │
     │                                         │
     │ N                          ┌────────────▼──────────────┐
┌────▼──────────────────┐         │  EVENT_PARTICIPATIONS     │
│    CANDIDATES         │         │───────────────────────────│
│───────────────────────│         │ PK: id                    │
│ PK: id                │ 1       │ FK: event_id (events)     │
│ FK: event_id (events) ├─────────┤ FK: voter_id (voters)     │
│     candidate_number  │         │     voted_at              │
│     name              │         │ UNIQUE(event_id,voter_id) │
│     vision            │ N       └───────────────────────────┘
│     mission           │         
│     photo             │         
└────┬──────────────────┘         
     │ 1                          
     │                            
     │ N                          
┌────▼──────────────────┐         
│       VOTES           │         
│───────────────────────│         
│ PK: id                │         
│ FK: event_id (events) │         
│ FK: candidate_id      │         
│     (candidates)      │         
└───────────────────────┘         


┌──────────────────────────────┐
│       AUDIT_LOGS             │
│──────────────────────────────│
│ PK: id                       │
│ FK: user_id (users) NULLABLE │
│     user_name                │
│     role                     │
│     action                   │
│     description              │
│     ip_address               │
└──────────────────────────────┘
```
## 2. Entity Relationship Diagram (ERD)

### 2.1 Diagram Relasi

`

### 2.2 Jenis Relasi

| Parent Table | Child Table | Relasi | Constraint | Deskripsi |
|--------------|-------------|--------|------------|-----------|
| **users** | **events** | 1:N | `created_by` → `users.id` CASCADE DELETE | 1 Petugas bisa membuat banyak event |
| **events** | **candidates** | 1:N | `event_id` → `events.id` CASCADE DELETE | 1 Event memiliki banyak kandidat |
| **events** | **votes** | 1:N | `event_id` → `events.id` CASCADE DELETE | 1 Event memiliki banyak vote |
| **events** | **event_participations** | 1:N | `event_id` → `events.id` CASCADE DELETE | 1 Event memiliki banyak partisipasi |
| **candidates** | **votes** | 1:N | `candidate_id` → `candidates.id` CASCADE DELETE | 1 Kandidat bisa mendapat banyak vote |
| **voters** | **event_participations** | 1:N | `voter_id` → `voters.id` CASCADE DELETE | 1 Voter bisa ikut banyak event |
| **users** | **audit_logs** | 1:N | `user_id` → `users.id` NULL ON DELETE | 1 User memiliki banyak log (nullable) |

---

## 3. Physical Data Model (PDM)

### 3.0 Diagram PDM (Physical Data Model)

```
┌─────────────────────────────────────────────┐
│              USERS                          │
├─────────────────────────────────────────────┤
│ PK  id                BIGINT UNSIGNED       │
│     name              VARCHAR(255)          │
│ UK  email             VARCHAR(255)          │
│     password          VARCHAR(255)          │
│     role              ENUM('SUPER_ADMIN',   │
│                            'PETUGAS')       │
│                       DEFAULT 'PETUGAS'     │
│     remember_token    VARCHAR(100) NULL     │
│     created_at        TIMESTAMP NULL        │
│     updated_at        TIMESTAMP NULL        │
└──────────────┬──────────────────────────────┘
               │ 1
               │ created_by
               │
               │ N
┌──────────────▼──────────────────────────────┐
│              EVENTS                         │
├─────────────────────────────────────────────┤
│ PK  id                BIGINT UNSIGNED       │
│ FK  created_by        BIGINT UNSIGNED       │
│                       → users.id CASCADE    │
│     title             VARCHAR(255)          │
│     category          ENUM('KANDIDAT',      │
│                            'OPSI')          │
│                       DEFAULT 'KANDIDAT'    │
│ UK  room_code         VARCHAR(10)           │
│     description       TEXT NULL             │
│     start_time        DATETIME NULL         │
│     end_time          DATETIME NULL         │
│     status            ENUM('DRAFT',         │
│                            'PUBLISHED',     │
│                            'CLOSED')        │
│                       DEFAULT 'DRAFT'       │
│     created_at        TIMESTAMP NULL        │
│     updated_at        TIMESTAMP NULL        │
└──────┬──────────────────────────────────────┘
       │ 1
       │ event_id
       │
       │ N
┌──────▼──────────────────────────────────────┐
│           CANDIDATES                        │
├─────────────────────────────────────────────┤
│ PK  id                BIGINT UNSIGNED       │
│ FK  event_id          BIGINT UNSIGNED       │
│                       → events.id CASCADE   │
│     candidate_number  INTEGER               │
│     name              VARCHAR(255)          │
│     vision            TEXT NULL             │
│     mission           TEXT NULL             │
│     photo             VARCHAR(255) NULL     │
│     created_at        TIMESTAMP NULL        │
│     updated_at        TIMESTAMP NULL        │
└──────┬──────────────────────────────────────┘
       │ 1
       │ candidate_id
       │
       │ N
┌──────▼──────────────────────────────────────┐
│              VOTES                          │
├─────────────────────────────────────────────┤
│ PK  id                BIGINT UNSIGNED       │
│ FK  event_id          BIGINT UNSIGNED       │
│                       → events.id CASCADE   │
│ FK  candidate_id      BIGINT UNSIGNED       │
│                       → candidates.id       │
│                         CASCADE             │
│     created_at        TIMESTAMP NULL        │
│     updated_at        TIMESTAMP NULL        │
└─────────────────────────────────────────────┘


┌─────────────────────────────────────────────┐
│              VOTERS                         │
├─────────────────────────────────────────────┤
│ PK  id                BIGINT UNSIGNED       │
│ UK  identifier        VARCHAR(50)           │
│     name              VARCHAR(255)          │
│     type              ENUM('SISWA',         │
│                            'GURU')          │
│                       DEFAULT 'SISWA'       │
│     created_at        TIMESTAMP NULL        │
│     updated_at        TIMESTAMP NULL        │
└──────────────┬──────────────────────────────┘
               │ 1
               │ voter_id
               │
               │ N
┌──────────────▼──────────────────────────────┐
│         EVENT_PARTICIPATIONS                │
├─────────────────────────────────────────────┤
│ PK  id                BIGINT UNSIGNED       │
│ FK  event_id          BIGINT UNSIGNED       │
│                       → events.id CASCADE   │
│ FK  voter_id          BIGINT UNSIGNED       │
│                       → voters.id CASCADE   │
│     voted_at          DATETIME NULL         │
│     created_at        TIMESTAMP NULL        │
│     updated_at        TIMESTAMP NULL        │
│                                             │
│ UK  UNIQUE(event_id, voter_id)              │
│     ← One Person One Vote Constraint        │
└─────────────────────────────────────────────┘


┌─────────────────────────────────────────────┐
│           AUDIT_LOGS                        │
├─────────────────────────────────────────────┤
│ PK  id                BIGINT UNSIGNED       │
│ FK  user_id           BIGINT UNSIGNED NULL  │
│                       → users.id SET NULL   │
│     user_name         VARCHAR(255)          │
│     role              VARCHAR(50)           │
│     action            VARCHAR(100)          │
│     description       TEXT                  │
│     ip_address        VARCHAR(45) NULL      │
│     created_at        TIMESTAMP NULL        │
│     updated_at        TIMESTAMP NULL        │
└─────────────────────────────────────────────┘

Legend:
PK  = Primary Key
FK  = Foreign Key
UK  = Unique Key
```

---

### 3.1 Tabel: `users`
**Deskripsi**: Menyimpan data Super Admin dan Petugas yang mengelola sistem

| Kolom | Tipe Data | Panjang | Nullable | Default | Constraint | Keterangan |
|-------|-----------|---------|----------|---------|------------|------------|
| `id` | BIGINT UNSIGNED | - | NO | AUTO_INCREMENT | PRIMARY KEY | ID unik user |
| `name` | VARCHAR | 255 | NO | - | - | Nama lengkap |
| `email` | VARCHAR | 255 | NO | - | UNIQUE | Email untuk login |
| `password` | VARCHAR | 255 | NO | - | - | Password (hashed bcrypt) |
| `role` | ENUM | - | NO | 'PETUGAS' | ('SUPER_ADMIN', 'PETUGAS') | Role akses |
| `remember_token` | VARCHAR | 100 | YES | NULL | - | Token "Remember Me" |
| `created_at` | TIMESTAMP | - | YES | NULL | - | Waktu pembuatan |
| `updated_at` | TIMESTAMP | - | YES | NULL | - | Waktu update terakhir |

**Index:**
- PRIMARY KEY: `id`
- UNIQUE KEY: `email`

---

### 3.2 Tabel: `voters`
**Deskripsi**: Menyimpan data Pemilih (Siswa dan Guru)

| Kolom | Tipe Data | Panjang | Nullable | Default | Constraint | Keterangan |
|-------|-----------|---------|----------|---------|------------|------------|
| `id` | BIGINT UNSIGNED | - | NO | AUTO_INCREMENT | PRIMARY KEY | ID unik voter |
| `identifier` | VARCHAR | 50 | NO | - | UNIQUE | NIS (Siswa) / NIP (Guru) |
| `name` | VARCHAR | 255 | NO | - | - | Nama lengkap pemilih |
| `type` | ENUM | - | NO | 'SISWA' | ('SISWA', 'GURU') | Kategori pemilih |
| `created_at` | TIMESTAMP | - | YES | NULL | - | Waktu pembuatan |
| `updated_at` | TIMESTAMP | - | YES | NULL | - | Waktu update terakhir |

**Index:**
- PRIMARY KEY: `id`
- UNIQUE KEY: `identifier`

**Validasi Bisnis:**
- `identifier` harus unik (tidak boleh duplikat)
- `type` menentukan kategori pemilih (SISWA menggunakan NIS, GURU menggunakan NIP)

---

### 3.3 Tabel: `events`
**Deskripsi**: Menyimpan data event pemilihan yang dibuat oleh Petugas

| Kolom | Tipe Data | Panjang | Nullable | Default | Constraint | Keterangan |
|-------|-----------|---------|----------|---------|------------|------------|
| `id` | BIGINT UNSIGNED | - | NO | AUTO_INCREMENT | PRIMARY KEY | ID unik event |
| `created_by` | BIGINT UNSIGNED | - | NO | - | FOREIGN KEY → `users.id` CASCADE DELETE | ID Petugas pembuat |
| `title` | VARCHAR | 255 | NO | - | - | Judul event |
| `category` | ENUM | - | NO | 'KANDIDAT' | ('KANDIDAT', 'OPSI') | Jenis pemilihan |
| `room_code` | VARCHAR | 10 | NO | - | UNIQUE | Kode room (6 karakter) |
| `description` | TEXT | - | YES | NULL | - | Deskripsi event |
| `start_time` | DATETIME | - | YES | NULL | - | Waktu mulai (opsional) |
| `end_time` | DATETIME | - | YES | NULL | - | Waktu selesai (opsional) |
| `status` | ENUM | - | NO | 'DRAFT' | ('DRAFT', 'PUBLISHED', 'CLOSED') | Status event |
| `created_at` | TIMESTAMP | - | YES | NULL | - | Waktu pembuatan |
| `updated_at` | TIMESTAMP | - | YES | NULL | - | Waktu update terakhir |

**Index:**
- PRIMARY KEY: `id`
- UNIQUE KEY: `room_code`
- INDEX: `created_by` (foreign key)

**Validasi Bisnis:**
- Event baru wajib status `DRAFT`
- `room_code` harus unik dan 6 karakter
- Minimal 2 kandidat untuk publish
- Auto-update status berdasarkan `start_time` dan `end_time`

---

### 3.4 Tabel: `candidates`
**Deskripsi**: Menyimpan data kandidat/opsi per event

| Kolom | Tipe Data | Panjang | Nullable | Default | Constraint | Keterangan |
|-------|-----------|---------|----------|---------|------------|------------|
| `id` | BIGINT UNSIGNED | - | NO | AUTO_INCREMENT | PRIMARY KEY | ID unik kandidat |
| `event_id` | BIGINT UNSIGNED | - | NO | - | FOREIGN KEY → `events.id` CASCADE DELETE | ID event |
| `candidate_number` | INTEGER | - | NO | - | - | Nomor urut kandidat |
| `name` | VARCHAR | 255 | NO | - | - | Nama kandidat/opsi |
| `vision` | TEXT | - | YES | NULL | - | Visi kandidat (opsional) |
| `mission` | TEXT | - | YES | NULL | - | Misi kandidat (opsional) |
| `photo` | VARCHAR | 255 | YES | NULL | - | Path foto kandidat |
| `created_at` | TIMESTAMP | - | YES | NULL | - | Waktu pembuatan |
| `updated_at` | TIMESTAMP | - | YES | NULL | - | Waktu update terakhir |

**Index:**
- PRIMARY KEY: `id`
- INDEX: `event_id` (foreign key)

**Validasi Bisnis:**
- `candidate_number` harus unik per event
- Foto disimpan di `storage/app/public/storage/candidates/`
- Minimal 2 kandidat per event untuk bisa publish

---

### 3.5 Tabel: `votes`
**Deskripsi**: Menyimpan data suara yang masuk (vote casting)

| Kolom | Tipe Data | Panjang | Nullable | Default | Constraint | Keterangan |
|-------|-----------|---------|----------|---------|------------|------------|
| `id` | BIGINT UNSIGNED | - | NO | AUTO_INCREMENT | PRIMARY KEY | ID unik vote |
| `event_id` | BIGINT UNSIGNED | - | NO | - | FOREIGN KEY → `events.id` CASCADE DELETE | ID event |
| `candidate_id` | BIGINT UNSIGNED | - | NO | - | FOREIGN KEY → `candidates.id` CASCADE DELETE | ID kandidat yang dipilih |
| `created_at` | TIMESTAMP | - | YES | NULL | - | Waktu vote (timestamp voting) |
| `updated_at` | TIMESTAMP | - | YES | NULL | - | Waktu update |

**Index:**
- PRIMARY KEY: `id`
- INDEX: `event_id` (foreign key)
- INDEX: `candidate_id` (foreign key)

**Validasi Bisnis:**
- **TIDAK menyimpan `voter_id`** untuk menjaga kerahasiaan pilihan
- Relasi voter ke vote hanya melalui `event_participations`
- Vote tidak bisa diubah atau dihapus setelah submit

---

### 3.6 Tabel: `event_participations`
**Deskripsi**: Menyimpan rekam partisipasi pemilih per event (tracking one-time voting)

| Kolom | Tipe Data | Panjang | Nullable | Default | Constraint | Keterangan |
|-------|-----------|---------|----------|---------|------------|------------|
| `id` | BIGINT UNSIGNED | - | NO | AUTO_INCREMENT | PRIMARY KEY | ID unik partisipasi |
| `event_id` | BIGINT UNSIGNED | - | NO | - | FOREIGN KEY → `events.id` CASCADE DELETE | ID event |
| `voter_id` | BIGINT UNSIGNED | - | NO | - | FOREIGN KEY → `voters.id` CASCADE DELETE | ID pemilih |
| `voted_at` | DATETIME | - | YES | NULL | - | Timestamp saat voting |
| `created_at` | TIMESTAMP | - | YES | NULL | - | Waktu pembuatan record |
| `updated_at` | TIMESTAMP | - | YES | NULL | - | Waktu update |

**Index:**
- PRIMARY KEY: `id`
- INDEX: `event_id` (foreign key)
- INDEX: `voter_id` (foreign key)
- **UNIQUE KEY**: `(event_id, voter_id)` ← **CONSTRAINT PENTING!**

**Validasi Bisnis:**
- UNIQUE constraint `(event_id, voter_id)` memastikan **one person one vote**
- Tidak menyimpan `candidate_id` untuk menjaga kerahasiaan
- `voted_at` diisi saat pemilih submit vote

---

### 3.7 Tabel: `audit_logs`
**Deskripsi**: Menyimpan jejak audit seluruh aktivitas sistem (security trail)

| Kolom | Tipe Data | Panjang | Nullable | Default | Constraint | Keterangan |
|-------|-----------|---------|----------|---------|------------|------------|
| `id` | BIGINT UNSIGNED | - | NO | AUTO_INCREMENT | PRIMARY KEY | ID unik log |
| `user_id` | BIGINT UNSIGNED | - | YES | NULL | FOREIGN KEY → `users.id` NULL ON DELETE | ID user (nullable) |
| `user_name` | VARCHAR | 255 | NO | - | - | Nama user (snapshot) |
| `role` | VARCHAR | 50 | NO | - | - | Role user saat aksi |
| `action` | VARCHAR | 100 | NO | - | - | Jenis aksi (CREATE_USER, dll) |
| `description` | TEXT | - | NO | - | - | Deskripsi detail aksi |
| `ip_address` | VARCHAR | 45 | YES | NULL | - | IP address user (IPv4/IPv6) |
| `created_at` | TIMESTAMP | - | YES | NULL | - | Waktu kejadian |
| `updated_at` | TIMESTAMP | - | YES | NULL | - | Waktu update |

**Index:**
- PRIMARY KEY: `id`
- INDEX: `user_id` (foreign key, nullable)
- INDEX: `created_at` (untuk sorting log)

**Jenis Action:**
- `CREATE_USER`, `UPDATE_USER`, `DELETE_USER`
- `CREATE_VOTER`, `UPDATE_VOTER`, `DELETE_VOTER`
- `CREATE_EVENT`, `UPDATE_EVENT`, `DELETE_EVENT`
- `CHANGE_EVENT_STATUS`, `CREATE_CANDIDATE`, `UPDATE_CANDIDATE`, `DELETE_CANDIDATE`
- `vote.cast` (rekam voting)

**Validasi Bisnis:**
- `user_id` nullable karena bisa anonymous action
- `user_name` disimpan sebagai snapshot (jika user dihapus, nama tetap ada)
- Constraint `NULL ON DELETE` untuk preserve log history

---

## 4. Constraint & Integrity Rules

### 4.1 Primary Keys
| Tabel | Primary Key | Tipe | Auto Increment |
|-------|-------------|------|----------------|
| `users` | `id` | BIGINT UNSIGNED | YES |
| `voters` | `id` | BIGINT UNSIGNED | YES |
| `events` | `id` | BIGINT UNSIGNED | YES |
| `candidates` | `id` | BIGINT UNSIGNED | YES |
| `votes` | `id` | BIGINT UNSIGNED | YES |
| `event_participations` | `id` | BIGINT UNSIGNED | YES |
| `audit_logs` | `id` | BIGINT UNSIGNED | YES |

### 4.2 Foreign Keys

| Child Table | Child Column | Parent Table | Parent Column | On Delete | On Update |
|-------------|--------------|--------------|---------------|-----------|-----------|
| `events` | `created_by` | `users` | `id` | CASCADE | CASCADE |
| `candidates` | `event_id` | `events` | `id` | CASCADE | CASCADE |
| `votes` | `event_id` | `events` | `id` | CASCADE | CASCADE |
| `votes` | `candidate_id` | `candidates` | `id` | CASCADE | CASCADE |
| `event_participations` | `event_id` | `events` | `id` | CASCADE | CASCADE |
| `event_participations` | `voter_id` | `voters` | `id` | CASCADE | CASCADE |
| `audit_logs` | `user_id` | `users` | `id` | SET NULL | CASCADE |

**Penjelasan Cascade:**
- **CASCADE DELETE**: Jika parent dihapus, child ikut terhapus
- **SET NULL**: Jika parent dihapus, foreign key diset NULL (untuk audit log)

### 4.3 Unique Constraints

| Tabel | Kolom | Keterangan |
|-------|-------|------------|
| `users` | `email` | Email harus unik |
| `voters` | `identifier` | NIS/NIP harus unik |
| `events` | `room_code` | Kode room harus unik |
| `event_participations` | `(event_id, voter_id)` | **Composite unique** - one person one vote per event |

### 4.4 Default Values

| Tabel | Kolom | Default Value | Alasan |
|-------|-------|---------------|--------|
| `users` | `role` | `'PETUGAS'` | Default role untuk user baru |
| `voters` | `type` | `'SISWA'` | Mayoritas pemilih adalah siswa |
| `events` | `category` | `'KANDIDAT'` | Jenis event paling umum |
| `events` | `status` | `'DRAFT'` | Event baru wajib draft (security) |

---

## 5. Keamanan & Integritas Data

### 5.1 Kerahasiaan Voting (Secret Ballot)
**Desain Database menjaga rahasia pilihan pemilih:**

1. ✅ Tabel `votes` **TIDAK menyimpan `voter_id`**
   - Tidak bisa trace siapa memilih kandidat apa
   - Hanya menyimpan: event_id, candidate_id, timestamp

2. ✅ Tabel `event_participations` **TIDAK menyimpan `candidate_id`**
   - Hanya mencatat: voter sudah ikut event atau belum
   - Tidak menyimpan pilihan kandidat

3. ✅ **Relasi terpisah** antara:
   - `event_participations` → tracking partisipasi (siapa sudah vote)
   - `votes` → counting hasil (kandidat dapat berapa suara)

**Tidak bisa query:** "Kandidat X dipilih oleh voter Y"  
**Hanya bisa query:** "Kandidat X dapat berapa suara total"

### 5.2 One Person One Vote
**Enforcement via UNIQUE constraint:**
```sql
UNIQUE KEY (event_id, voter_id) ON event_participations
```
- Database akan **reject** insert duplicate
- Aplikasi check: `event_participations->exists(event_id, voter_id)` sebelum voting
- Error: `Duplicate entry` jika coba voting 2x

### 5.3 Referential Integrity
- ✅ **CASCADE DELETE**: Jika event dihapus, semua kandidat & vote ikut terhapus
- ✅ **NULL ON DELETE**: Jika user dihapus, audit log tetap ada (user_id jadi NULL)
- ✅ **Foreign Key Constraint**: Mencegah orphan data

### 5.4 Data Type Validation
- ✅ **ENUM** untuk kolom dengan nilai terbatas (role, status, category, type)
- ✅ **VARCHAR dengan length limit** mencegah data overflow
- ✅ **UNIQUE constraint** mencegah duplikasi
- ✅ **NOT NULL** untuk kolom wajib diisi

---

## 6. Normalisasi Database

### Status Normalisasi: **3NF (Third Normal Form)**

#### 6.1 First Normal Form (1NF) ✅
- ✅ Semua kolom berisi nilai atomic (tidak ada array/json)
- ✅ Setiap tabel memiliki primary key
- ✅ Tidak ada repeating groups

#### 6.2 Second Normal Form (2NF) ✅
- ✅ Sudah 1NF
- ✅ Tidak ada partial dependency (semua non-key bergantung penuh ke primary key)
- ✅ Composite key hanya di `event_participations` (dan sudah proper)

#### 6.3 Third Normal Form (3NF) ✅
- ✅ Sudah 2NF
- ✅ Tidak ada transitive dependency
- ✅ Setiap non-key attribute bergantung langsung ke primary key

**Contoh Denormalisasi yang Diizinkan:**
- `audit_logs.user_name` → Snapshot untuk historical record (intentional denormalization)

---

## 7. Index & Performance

### 7.1 Index yang Ada

| Tabel | Kolom | Jenis Index | Alasan |
|-------|-------|-------------|--------|
| `users` | `id` | PRIMARY KEY | Pencarian utama |
| `users` | `email` | UNIQUE | Login & validasi |
| `voters` | `id` | PRIMARY KEY | Pencarian utama |
| `voters` | `identifier` | UNIQUE | Login NIS/NIP |
| `events` | `id` | PRIMARY KEY | Pencarian utama |
| `events` | `room_code` | UNIQUE | Input kode room |
| `events` | `created_by` | INDEX (FK) | Filter event per petugas |
| `candidates` | `id` | PRIMARY KEY | Pencarian utama |
| `candidates` | `event_id` | INDEX (FK) | Load kandidat per event |
| `votes` | `id` | PRIMARY KEY | Pencarian utama |
| `votes` | `event_id` | INDEX (FK) | Counting per event |
| `votes` | `candidate_id` | INDEX (FK) | Counting per kandidat |
| `event_participations` | `id` | PRIMARY KEY | Pencarian utama |
| `event_participations` | `(event_id, voter_id)` | UNIQUE | One person one vote |
| `audit_logs` | `id` | PRIMARY KEY | Pencarian utama |
| `audit_logs` | `user_id` | INDEX (FK) | Filter log per user |

### 7.2 Query yang Sering Dijalankan

| Query | Index yang Digunakan | Performance |
|-------|----------------------|-------------|
| Login admin/petugas | `users.email` (UNIQUE) | O(1) - Hash index |
| Login voter | `voters.identifier` (UNIQUE) | O(1) - Hash index |
| Cari event by room code | `events.room_code` (UNIQUE) | O(1) - Hash index |
| Load kandidat per event | `candidates.event_id` (INDEX) | O(log n) - B-tree |
| Count vote per kandidat | `votes.candidate_id` (INDEX) | O(log n) - B-tree |
| Check sudah vote? | `event_participations` (UNIQUE) | O(1) - Hash index |
| Filter event by petugas | `events.created_by` (INDEX) | O(log n) - B-tree |

---

## 8. Storage & Backup

### 8.1 Estimasi Ukuran Data

**Asumsi:**
- 1000 voters
- 50 events per tahun
- Average 5 kandidat per event
- 80% participation rate

| Tabel | Rows (Est.) | Size per Row | Total Size |
|-------|-------------|--------------|------------|
| `users` | 50 | ~500 bytes | ~25 KB |
| `voters` | 1000 | ~300 bytes | ~300 KB |
| `events` | 50 | ~600 bytes | ~30 KB |
| `candidates` | 250 (50×5) | ~1 KB (with photo path) | ~250 KB |
| `votes` | 40,000 (50×800) | ~50 bytes | ~2 MB |
| `event_participations` | 40,000 | ~100 bytes | ~4 MB |
| `audit_logs` | ~10,000/year | ~400 bytes | ~4 MB/year |
| **TOTAL** | - | - | **~10-15 MB/year** |

**File Storage (Foto Kandidat):**
- Average 250 kandidat/year × 500 KB/foto = **~125 MB/year**

### 8.2 Backup Strategy
- **Daily backup**: Full database dump
- **Weekly backup**: File storage (foto kandidat)
- **Retention**: 6 bulan audit log, permanent untuk hasil voting
- **Export CSV**: Hasil akhir tiap event untuk arsip

---

## 9. Migration & Version Control

### Urutan Migration (Dependency Order):
1. `0001_01_01_000000_create_users_table.php` ← No dependency
2. `2026_09_26_000001_create_voters_table.php` ← No dependency
3. `2026_09_26_000002_create_events_table.php` ← Depends on: users
4. `2026_09_26_000003_create_candidates_table.php` ← Depends on: events
5. `2026_09_26_000004_create_event_participations_table.php` ← Depends on: events, voters
6. `2026_09_26_000005_create_votes_table.php` ← Depends on: events, candidates
7. `2026_09_26_000006_create_audit_logs_table.php` ← Depends on: users (nullable)

**Rollback:**
- Migration harus di-rollback dalam urutan terbalik
- Laravel migration handle foreign key dependencies otomatis

---

## 10. Kesimpulan

### Kelebihan Desain Database:
1. ✅ **Kerahasiaan Terjaga**: Votes tidak tersimpan dengan voter_id
2. ✅ **Integritas Data**: Foreign key & unique constraints
3. ✅ **One Person One Vote**: UNIQUE constraint (event_id, voter_id)
4. ✅ **Audit Trail Lengkap**: Semua aktivitas tercatat
5. ✅ **Normalisasi Proper**: 3NF, minimal redundansi
6. ✅ **Scalable**: Index optimal untuk query
7. ✅ **Flexible**: Support KANDIDAT & OPSI category
8. ✅ **Authorization Ready**: created_by untuk per-petugas access control

### Potential Improvements (Future):
- Add soft deletes untuk data recovery
- Add pagination untuk audit_logs (50 per page)
- Add composite index untuk frequent multi-column queries
- Add full-text search index untuk event.title

---

**Dokumen dibuat:** 6 Oktober 2026  
**Versi:** 1.0  
**Status:** Final
