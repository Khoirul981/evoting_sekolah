# 🧪 CONTOH LENGKAP REQUEST POSTMAN - E-VOTING API
**Aplikasi E-Voting Sekolah - Laravel 13**

---

## 📋 Daftar Lengkap Request yang Bisa Diuji

Berikut adalah **contoh lengkap semua endpoint** yang bisa diuji di Postman, termasuk CRUD untuk Super Admin dan Petugas.

---

## 1️⃣ AUTENTIKASI

### A. Login Super Admin
```
Method: POST
URL: http://127.0.0.1:8000/login
Headers:
  Accept: application/json
Body (x-www-form-urlencoded):
  email: admin@sekolah.sch.id
  password: password
```

### B. Login Petugas
```
Method: POST
URL: http://127.0.0.1:8000/login
Headers:
  Accept: application/json
Body (x-www-form-urlencoded):
  email: petugas@sekolah.sch.id
  password: password
```

### C. Login Pemilih (Siswa)
```
Method: POST
URL: http://127.0.0.1:8000/voter/login
Headers:
  Accept: application/json
Body (x-www-form-urlencoded):
  identifier: 2024001
```

**⚠️ CATATAN:** Login Voter **TIDAK menggunakan password**, hanya identifier (NIS/NIP) saja!

### D. Login Pemilih (Guru)
```
Method: POST
URL: http://127.0.0.1:8000/voter/login
Headers:
  Accept: application/json
Body (x-www-form-urlencoded):
  identifier: 196501011990031001
```

---

## 2️⃣ SUPER ADMIN - MANAJEMEN AKUN PANITIA

### A. Lihat Daftar Users & Voters
```
Method: GET
URL: http://127.0.0.1:8000/admin/users
Headers:
  Accept: application/json
Auth: Harus login sebagai Super Admin
```

### B. Buat Akun Petugas Baru
```
Method: POST
URL: http://127.0.0.1:8000/admin/users
Headers:
  Accept: application/json
  X-CSRF-TOKEN: <token_dari_cookie>
Body (x-www-form-urlencoded):
  name: Petugas Baru
  email: petugas.baru@sekolah.sch.id
  password: password123
  password_confirmation: password123
  role: PETUGAS
Auth: Harus login sebagai Super Admin
```

**Response Sukses:**
```json
{
    "message": "Akun Petugas berhasil dibuat!",
    "user": {
        "id": 5,
        "name": "Petugas Baru",
        "email": "petugas.baru@sekolah.sch.id",
        "role": "PETUGAS"
    }
}
```

### C. Edit Akun Panitia
```
Method: PUT
URL: http://127.0.0.1:8000/admin/users/5
Headers:
  Accept: application/json
  X-CSRF-TOKEN: <token>
Body (x-www-form-urlencoded):
  name: Petugas Updated
  email: petugas.updated@sekolah.sch.id
Auth: Harus login sebagai Super Admin
```

### D. Hapus Akun Panitia
```
Method: DELETE
URL: http://127.0.0.1:8000/admin/users/5
Headers:
  Accept: application/json
  X-CSRF-TOKEN: <token>
Auth: Harus login sebagai Super Admin
```

---

## 3️⃣ SUPER ADMIN - MANAJEMEN DPT (Data Pemilih Tetap)

### A. Tambah Siswa ke DPT
```
Method: POST
URL: http://127.0.0.1:8000/admin/voters
Headers:
  Accept: application/json
  X-CSRF-TOKEN: <token>
Body (x-www-form-urlencoded):
  identifier: 2024100
  name: Andi Pratama
  type: SISWA
  password: password
  password_confirmation: password
Auth: Harus login sebagai Super Admin
```

**Response Sukses:**
```json
{
    "message": "Data pemilih SISWA berhasil ditambahkan ke dalam DPT!",
    "voter": {
        "id": 51,
        "identifier": "2024100",
        "name": "Andi Pratama",
        "type": "SISWA"
    }
}
```

### B. Tambah Guru ke DPT
```
Method: POST
URL: http://127.0.0.1:8000/admin/voters
Headers:
  Accept: application/json
  X-CSRF-TOKEN: <token>
Body (x-www-form-urlencoded):
  identifier: 197801051999031002
  name: Ibu Dewi Sartika, S.Pd
  type: GURU
  password: password
  password_confirmation: password
Auth: Harus login sebagai Super Admin
```

### C. Edit Data Pemilih
```
Method: PUT
URL: http://127.0.0.1:8000/admin/voters/51
Headers:
  Accept: application/json
  X-CSRF-TOKEN: <token>
Body (x-www-form-urlencoded):
  identifier: 2024100
  name: Andi Pratama (Updated)
  type: SISWA
Auth: Harus login sebagai Super Admin
```

### D. Hapus Pemilih dari DPT
```
Method: DELETE
URL: http://127.0.0.1:8000/admin/voters/51
Headers:
  Accept: application/json
  X-CSRF-TOKEN: <token>
Auth: Harus login sebagai Super Admin
```

---

## 4️⃣ SUPER ADMIN - AUDIT LOG

### A. Lihat Jejak Audit Sistem
```
Method: GET
URL: http://127.0.0.1:8000/admin/audit-logs
Headers:
  Accept: application/json
Auth: Harus login sebagai Super Admin
```

**Response Sukses:**
```json
{
    "logs": [
        {
            "id": 1,
            "action": "CREATE_EVENT",
            "description": "Membuat event pemilihan 'Pilketos 2024'",
            "user_name": "Petugas Pilketos",
            "ip_address": "127.0.0.1",
            "created_at": "06 Nov 2026, 10:30:15 WIB"
        }
    ]
}
```

---

## 5️⃣ PETUGAS - MANAJEMEN EVENT PEMILIHAN

### A. Lihat Daftar Event Milik Petugas
```
Method: GET
URL: http://127.0.0.1:8000/petugas/events
Headers:
  Accept: application/json
Auth: Harus login sebagai Petugas
```

### B. Buat Event Pemilihan Baru
```
Method: POST
URL: http://127.0.0.1:8000/petugas/events
Headers:
  Accept: application/json
  X-CSRF-TOKEN: <token>
Body (x-www-form-urlencoded):
  title: Pemilihan Ketua OSIS 2024
  description: Pemilihan Ketua OSIS periode 2024/2025
  category: KANDIDAT
  room_code: OSIS2024
  start_time: 2026-11-10 08:00
  end_time: 2026-11-10 15:00
  status: DRAFT
Auth: Harus login sebagai Petugas
```

**Response Sukses:**
```json
{
    "message": "Event pemilihan 'Pemilihan Ketua OSIS 2024' berhasil dibuat!",
    "event": {
        "id": 1,
        "title": "Pemilihan Ketua OSIS 2024",
        "room_code": "OSIS2024",
        "category": "KANDIDAT",
        "status": "DRAFT"
    }
}
```

### C. Edit Event
```
Method: PUT
URL: http://127.0.0.1:8000/petugas/events/1
Headers:
  Accept: application/json
  X-CSRF-TOKEN: <token>
Body (x-www-form-urlencoded):
  title: Pemilihan Ketua OSIS 2024 (Updated)
  description: Deskripsi baru
  category: KANDIDAT
  room_code: OSIS2024
  start_time: 2026-11-12 08:00
  end_time: 2026-11-12 15:00
Auth: Harus login sebagai Petugas (hanya bisa edit event milik sendiri)
```

### D. Ubah Status Event (DRAFT → OPEN → CLOSED)
```
Method: PATCH
URL: http://127.0.0.1:8000/petugas/events/1/status
Headers:
  Accept: application/json
  X-CSRF-TOKEN: <token>
Body (x-www-form-urlencoded):
  status: OPEN
Auth: Harus login sebagai Petugas
```

**Response Sukses:**
```json
{
    "message": "Status event berhasil diubah menjadi OPEN.",
    "event": {
        "id": 1,
        "status": "OPEN"
    }
}
```

### E. Hapus Event
```
Method: DELETE
URL: http://127.0.0.1:8000/petugas/events/1
Headers:
  Accept: application/json
  X-CSRF-TOKEN: <token>
Auth: Harus login sebagai Petugas
```

---

## 6️⃣ PETUGAS - MANAJEMEN KANDIDAT

### A. Lihat Daftar Kandidat dalam Event
```
Method: GET
URL: http://127.0.0.1:8000/petugas/events/1/candidates
Headers:
  Accept: application/json
Auth: Harus login sebagai Petugas
```

### B. Tambah Kandidat Baru
```
Method: POST
URL: http://127.0.0.1:8000/petugas/events/1/candidates
Headers:
  Accept: application/json
  X-CSRF-TOKEN: <token>
Body (form-data):
  candidate_number: 1
  name: Ahmad & Siti
  vision: Membangun OSIS yang Inklusif dan Inovatif
  mission: 1. Transparansi anggaran
           2. Program beasiswa siswa
  photo: <file upload .jpg/.png/.webp>
Auth: Harus login sebagai Petugas
```

**Response Sukses:**
```json
{
    "message": "Kandidat No. 1 'Ahmad & Siti' berhasil ditambahkan!",
    "candidate": {
        "id": 1,
        "candidate_number": 1,
        "name": "Ahmad & Siti",
        "vision": "Membangun OSIS yang Inklusif dan Inovatif",
        "photo": "candidates/abc123.jpg"
    }
}
```

### C. Edit Kandidat
```
Method: PUT
URL: http://127.0.0.1:8000/petugas/events/1/candidates/1
Headers:
  Accept: application/json
  X-CSRF-TOKEN: <token>
Body (form-data):
  candidate_number: 1
  name: Ahmad & Siti (Updated)
  vision: Visi baru...
  mission: Misi baru...
  photo: <file baru atau kosongkan jika tidak ganti>
Auth: Harus login sebagai Petugas
```

### D. Hapus Kandidat
```
Method: DELETE
URL: http://127.0.0.1:8000/petugas/events/1/candidates/1
Headers:
  Accept: application/json
  X-CSRF-TOKEN: <token>
Auth: Harus login sebagai Petugas
```

---

## 7️⃣ PETUGAS - LIVE MONITORING & QUICK COUNT (API JSON)

### A. Ambil Data Quick Count Real-time (JSON API) 🔥
```
Method: GET
URL: http://127.0.0.1:8000/petugas/events/1/monitor/data
Headers:
  Accept: application/json
Auth: Harus login sebagai Petugas
```

**Response Sukses:**
```json
{
    "status": "success",
    "event_status": "OPEN",
    "total_voters": 50,
    "total_votes": 23,
    "participation_rate": 46.0,
    "candidates": [
        {
            "id": 1,
            "candidate_number": 1,
            "name": "Ahmad & Siti",
            "photo_url": "http://127.0.0.1:8000/storage/candidates/...",
            "votes_count": 15,
            "percentage": 65.2
        },
        {
            "id": 2,
            "candidate_number": 2,
            "name": "Budi & Rina",
            "photo_url": null,
            "votes_count": 8,
            "percentage": 34.8
        }
    ],
    "is_tied": false,
    "leading_candidates": [
        {
            "id": 1,
            "candidate_number": 1,
            "name": "Ahmad & Siti",
            "votes_count": 15,
            "percentage": 65.2
        }
    ],
    "recent_voters": [
        {
            "voter_name": "Ahmad Fauzi",
            "identifier": "2024001",
            "type": "SISWA",
            "voted_at": "06 Nov 2026, 22:15:30 WIB",
            "voted_at_human": "5 menit yang lalu"
        }
    ],
    "server_time": "22:20:45 WIB"
}
```

**Catatan:** Endpoint ini adalah **satu-satunya API JSON murni** di aplikasi yang digunakan untuk polling real-time. JavaScript di frontend memanggil endpoint ini setiap 4 detik untuk memperbarui grafik perolehan suara tanpa reload halaman.

---

## 8️⃣ PEMILIH (VOTER) - BILIK SUARA

### A. Masuk ke Lobi Pemilih (Dashboard)
```
Method: GET
URL: http://127.0.0.1:8000/voter/dashboard
Headers:
  Accept: application/json
Auth: Harus login sebagai Voter
```

### B. Submit Kode Ruang untuk Join Event
```
Method: POST
URL: http://127.0.0.1:8000/voter/join
Headers:
  Accept: application/json
  X-CSRF-TOKEN: <token>
Body (x-www-form-urlencoded):
  room_code: OSIS2024
Auth: Harus login sebagai Voter
```

**Response Sukses (Redirect ke Bilik Suara):**
```json
{
    "message": "Berhasil masuk ke ruang pemilihan!",
    "redirect": "/voter/events/OSIS2024/vote"
}
```

### C. Lihat Bilik Suara (Daftar Kandidat)
```
Method: GET
URL: http://127.0.0.1:8000/voter/events/OSIS2024/vote
Headers:
  Accept: application/json
Auth: Harus login sebagai Voter
```

### D. Submit Suara (Coblos Kandidat)
```
Method: POST
URL: http://127.0.0.1:8000/voter/events/OSIS2024/vote
Headers:
  Accept: application/json
  X-CSRF-TOKEN: <token>
Body (x-www-form-urlencoded):
  candidate_id: 1
Auth: Harus login sebagai Voter
```

**Response Sukses:**
```json
{
    "message": "Terima kasih! Suara Anda telah berhasil tercatat.",
    "redirect": "/voter/events/OSIS2024/success"
}
```

**Response Error (Sudah Pernah Memilih):**
```json
{
    "message": "Anda sudah memberikan suara pada event ini.",
    "errors": {
        "vote": ["Anda sudah memberikan suara pada event ini."]
    }
}
```

---

## 💡 Tips Penting Testing di Postman

### 1. Solusi Error 419 Page Expired ⚠️

Error **419 Page Expired** muncul karena CSRF token tidak valid/kadaluarsa. Ada 2 solusi:

**Solusi A: Nonaktifkan CSRF untuk Testing (RECOMMENDED untuk Lokal)**

1. Buka file `app/Http/Middleware/VerifyCsrfToken.php`
2. Tambahkan route yang ingin dikecualikan dari CSRF:

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'petugas/*',
        'admin/*',
        'voter/*',
        'login',
        'voter/login',
    ];
}
```

3. Sekarang semua endpoint bisa ditest tanpa header `X-CSRF-TOKEN`!

**Solusi B: Dapatkan CSRF Token via Browser (Untuk Testing dengan CSRF)**

1. Buka browser: `http://127.0.0.1:8000/login`
2. Klik kanan → **Inspect** → Tab **Application** → **Cookies**
3. Cari cookie `XSRF-TOKEN`
4. Copy value-nya dan decode di: https://www.urldecoder.org/
5. Paste hasil decode ke header `X-CSRF-TOKEN` di Postman
6. **PENTING:** Token ini akan kadaluarsa setelah beberapa waktu, harus diambil ulang

### 2. Pastikan Cookie Session Tersimpan
- Di Postman, aktifkan **Settings** → **General** → **Automatically follow redirects**
- Cookie `laravel_session` akan otomatis tersimpan setelah login berhasil
- Cookie ini otomatis dikirim di request berikutnya ke domain yang sama

### 3. Testing dengan Data Real
Jalankan seeder dulu untuk isi database:
```powershell
php artisan migrate:fresh --seed
```

---

## 🎯 Kesimpulan

Aplikasi E-Voting ini memiliki **25+ endpoint** yang bisa diuji di Postman, meliputi:
- ✅ Autentikasi Multi-Guard (Admin, Petugas, Voter)
- ✅ CRUD User & Voter (Super Admin)
- ✅ CRUD Event & Kandidat (Petugas)
- ✅ API JSON Quick Count Real-time (Petugas)
- ✅ Flow Pemilihan Lengkap (Voter)
- ✅ Audit Log System (Super Admin)

Semua endpoint menggunakan **session-based authentication** dengan proteksi CSRF Token untuk keamanan maksimal.
