# 🧪 PANDUAN LENGKAP TESTING API DENGAN POSTMAN
**Aplikasi E-Voting Sekolah - Laravel 13**

---

## 📑 DAFTAR ISI
1. [Persiapan Awal](#1-persiapan-awal)
2. [Endpoint API yang Tersedia](#2-endpoint-api-yang-tersedia)
3. [Langkah-Langkah Testing di Postman](#3-langkah-langkah-testing-di-postman)
   - [Test 1: Login Admin/Petugas](#test-1-login-adminpetugas-web-guard)
   - [Test 2: Login Pemilih (Voter)](#test-2-login-pemilih-voter-guard)
   - [Test 3: Ambil Data Quick Count (Live Monitoring JSON)](#test-3-ambil-data-quick-count-live-monitoring-json)
4. [Penjelasan Cookie Session & CSRF Token](#4-penjelasan-cookie-session--csrf-token)
5. [Troubleshooting Error Umum](#5-troubleshooting-error-umum)

---

## 1. Persiapan Awal

### A. Pastikan Server Laravel Sudah Berjalan
Buka terminal PowerShell di folder proyek dan jalankan:
```powershell
php artisan serve
```
Output akan muncul:
```
INFO Server running on [http://127.0.0.1:8000].
```
✅ **Jangan tutup terminal ini selama testing Postman!**

---

### B. Install Postman
1. Download Postman di: https://www.postman.com/downloads/
2. Install dan buka aplikasi Postman.
3. Buat **Collection Baru** dengan nama `E-Voting API Testing`.

---

### C. Isi Database dengan Data Dummy (Opsional)
Jika database masih kosong, jalankan seeder:
```powershell
php artisan migrate:fresh --seed
```
Ini akan membuat:
- **Super Admin:** `admin@sekolah.sch.id` / `password`
- **Petugas:** `petugas@sekolah.sch.id` / `password`
- **Pemilih (Siswa):** NIS `2024001` / Password `password`
- **Pemilih (Guru):** NIP `196501011990031001` / Password `password`
- **Event & Kandidat:** Data contoh pemilihan Ketua OSIS.

---

## 2. Endpoint API yang Tersedia

Aplikasi ini **bukan REST API murni**, melainkan aplikasi **berbasis Web Session (Cookie-based Authentication)**. Semua endpoint bisa diuji di Postman dengan mengikuti pola session-based auth.

### A. Endpoint Autentikasi (Publik)

| Metode | Endpoint | Kegunaan | Auth |
| :--- | :--- | :--- | :--- |
| **POST** | `/login` | Login panitia (Admin/Petugas) | ❌ Publik |
| **POST** | `/logout` | Logout panitia | ✅ Web Guard |
| **POST** | `/voter/login` | Login pemilih (Siswa/Guru) | ❌ Publik |
| **POST** | `/voter/logout` | Logout pemilih | ✅ Voter Guard |

---

### B. Endpoint Super Admin (Role: SUPER_ADMIN)

| Metode | Endpoint | Kegunaan | Auth |
| :--- | :--- | :--- | :--- |
| **GET** | `/admin/dashboard` | Dashboard statistik sistem | ✅ Super Admin |
| **GET** | `/admin/users` | Lihat daftar User & Voter | ✅ Super Admin |
| **POST** | `/admin/users` | Buat akun Super Admin / Petugas baru | ✅ Super Admin |
| **PUT** | `/admin/users/{user}` | Edit akun panitia | ✅ Super Admin |
| **DELETE** | `/admin/users/{user}` | Hapus akun panitia | ✅ Super Admin |
| **POST** | `/admin/voters` | Tambah pemilih (Siswa/Guru) ke DPT | ✅ Super Admin |
| **PUT** | `/admin/voters/{voter}` | Edit data pemilih | ✅ Super Admin |
| **DELETE** | `/admin/voters/{voter}` | Hapus pemilih dari DPT | ✅ Super Admin |
| **GET** | `/admin/audit-logs` | Lihat jejak audit sistem | ✅ Super Admin |

---

### C. Endpoint Petugas (Role: PETUGAS)

| Metode | Endpoint | Kegunaan | Auth |
| :--- | :--- | :--- | :--- |
| **GET** | `/petugas/events` | Lihat daftar event milik petugas | ✅ Petugas |
| **POST** | `/petugas/events` | Buat event pemilihan baru | ✅ Petugas |
| **PUT** | `/petugas/events/{event}` | Edit detail event | ✅ Petugas |
| **PATCH** | `/petugas/events/{event}/status` | Ubah status event (DRAFT/OPEN/CLOSED) | ✅ Petugas |
| **DELETE** | `/petugas/events/{event}` | Hapus event | ✅ Petugas |
| **GET** | `/petugas/events/{event}/candidates` | Lihat kandidat dalam event | ✅ Petugas |
| **POST** | `/petugas/events/{event}/candidates` | Tambah kandidat/opsi baru | ✅ Petugas |
| **PUT** | `/petugas/events/{event}/candidates/{candidate}` | Edit kandidat | ✅ Petugas |
| **DELETE** | `/petugas/events/{event}/candidates/{candidate}` | Hapus kandidat | ✅ Petugas |
| **GET** | `/petugas/events/{event}/monitor` | Halaman monitoring (HTML View) | ✅ Petugas |
| **GET** | `/petugas/events/{event}/monitor/data` | **API JSON Quick Count Real-time** 🔥 | ✅ Petugas |

---

### D. Endpoint Pemilih (Role: VOTER)

| Metode | Endpoint | Kegunaan | Auth |
| :--- | :--- | :--- | :--- |
| **GET** | `/voter/dashboard` | Dashboard lobi pemilih | ✅ Voter |
| **POST** | `/voter/join` | Submit kode ruang untuk masuk event | ✅ Voter |
| **GET** | `/voter/events/{room_code}/vote` | Bilik suara (halaman coblos) | ✅ Voter |
| **POST** | `/voter/events/{room_code}/vote` | Submit suara ke kandidat | ✅ Voter |
| **GET** | `/voter/events/{room_code}/success` | Halaman konfirmasi sukses memilih | ✅ Voter |

---

## 3. Langkah-Langkah Testing di Postman

---

### Test 1: Login Admin/Petugas (Web Guard)

#### **Request Detail:**
- **Method:** `POST`
- **URL:** `http://127.0.0.1:8000/login`
- **Headers:**
  ```
  Content-Type: application/x-www-form-urlencoded
  Accept: application/json
  ```
- **Body (x-www-form-urlencoded):**
  ```
  email: petugas@sekolah.sch.id
  password: password
  ```

#### **Langkah di Postman:**
1. Buat **New Request** dengan nama `Login Petugas`.
2. Pilih method **POST**.
3. Masukkan URL: `http://127.0.0.1:8000/login`
4. Di tab **Headers**, tambahkan:
   - Key: `Accept` | Value: `application/json`
5. Di tab **Body**, pilih **x-www-form-urlencoded**, lalu tambahkan:
   - Key: `email` | Value: `petugas@sekolah.sch.id`
   - Key: `password` | Value: `password`
6. Klik **Send**.

#### **Response Sukses (HTTP 302 Redirect):**
```json
{
    "message": "Login berhasil! Selamat datang, Petugas Pilketos."
}
```
Atau jika berupa redirect, di tab **Headers** response akan ada:
```
Location: http://127.0.0.1:8000/petugas/events
Set-Cookie: laravel_session=...
```

#### **Cara Menyimpan Session Cookie di Postman:**
- Postman otomatis menyimpan cookie `laravel_session` di tab **Cookies**.
- Cookie ini akan otomatis dikirim di request berikutnya pada domain yang sama.

---

### Test 2: Login Pemilih (Voter Guard)

#### **Request Detail:**
- **Method:** `POST`
- **URL:** `http://127.0.0.1:8000/voter/login`
- **Headers:**
  ```
  Content-Type: application/x-www-form-urlencoded
  Accept: application/json
  ```
- **Body (x-www-form-urlencoded):**
  ```
  identifier: 2024001
  ```

**⚠️ PENTING:** Login Voter TIDAK menggunakan password! Hanya identifier (NIS/NIP) saja.

#### **Langkah di Postman:**
1. Buat **New Request** dengan nama `Login Voter (Siswa)`.
2. Method **POST**, URL: `http://127.0.0.1:8000/voter/login`
3. Tab **Headers**: Tambahkan `Accept: application/json`
4. Tab **Body** (x-www-form-urlencoded):
   - Key: `identifier` | Value: `2024001`
   - Key: `password` | Value: `password`
5. Klik **Send**.

#### **Response Sukses:**
```json
{
    "message": "Login berhasil! Silakan masukkan kode ruang pemilihan.",
    "voter": {
        "name": "Ahmad Fauzi",
        "identifier": "2024001",
        "type": "SISWA"
    }
}
```

---

### Test 3: Buat Event Pemilihan Baru (Super Admin / Petugas)

#### **Request Detail:**
- **Method:** `POST`
- **URL:** `http://127.0.0.1:8000/petugas/events`
- **Headers:**
  ```
  Content-Type: application/x-www-form-urlencoded
  Accept: application/json
  X-CSRF-TOKEN: <token_dari_cookie>
  ```
- **Body (x-www-form-urlencoded):**
  ```
  title: Pemilihan Ketua OSIS 2024
  description: Pemilihan Ketua OSIS periode 2024/2025
  category: KANDIDAT
  room_code: OSIS2024
  start_time: 2026-11-10 08:00
  end_time: 2026-11-10 15:00
  status: DRAFT
  ```
- **Authentication:** Harus login sebagai Petugas (gunakan session dari Test 1).

#### **Langkah di Postman:**
1. Login sebagai Petugas terlebih dahulu (Test 1).
2. Dapatkan CSRF Token (lihat bagian [Penjelasan CSRF Token](#4-penjelasan-cookie-session--csrf-token)).
3. Buat **New Request** dengan nama `Create Event`.
4. Method **POST**, URL: `http://127.0.0.1:8000/petugas/events`
5. Tab **Headers**:
   - `Accept: application/json`
   - `X-CSRF-TOKEN: <token>`
6. Tab **Body** (x-www-form-urlencoded): Isi sesuai field di atas.
7. Klik **Send**.

#### **Response Sukses:**
```json
{
    "message": "Event pemilihan berhasil dibuat!",
    "event": {
        "id": 1,
        "title": "Pemilihan Ketua OSIS 2024",
        "room_code": "OSIS2024",
        "status": "DRAFT"
    }
}
```

---

### Test 4: Tambah Akun Pemilih (Super Admin)

#### **Request Detail:**
- **Method:** `POST`
- **URL:** `http://127.0.0.1:8000/admin/voters`
- **Headers:**
  ```
  Content-Type: application/x-www-form-urlencoded
  Accept: application/json
  X-CSRF-TOKEN: <token>
  ```
- **Body (x-www-form-urlencoded):**
  ```
  identifier: 2024050
  name: Budi Santoso
  type: SISWA
  password: password
  ```
- **Authentication:** Harus login sebagai Super Admin.

#### **Response Sukses:**
```json
{
    "message": "Data pemilih SISWA berhasil ditambahkan ke dalam DPT!",
    "voter": {
        "id": 51,
        "identifier": "2024050",
        "name": "Budi Santoso",
        "type": "SISWA"
    }
}
```

---

### Test 5: Tambah Kandidat ke Event (Petugas)

#### **Request Detail:**
- **Method:** `POST`
- **URL:** `http://127.0.0.1:8000/petugas/events/1/candidates`
  - *(Ganti `1` dengan ID event yang sudah dibuat)*
- **Headers:**
  ```
  Content-Type: multipart/form-data
  Accept: application/json
  X-CSRF-TOKEN: <token>
  ```
- **Body (form-data):**
  ```
  candidate_number: 1
  name: Ahmad & Siti
  vision: Membangun OSIS yang Inklusif dan Inovatif
  photo: <file upload .jpg/.png>
  ```

#### **Response Sukses:**
```json
{
    "message": "Kandidat No. 1 berhasil ditambahkan!",
    "candidate": {
        "id": 1,
        "candidate_number": 1,
        "name": "Ahmad & Siti",
        "photo": "candidates/xyz.jpg"
    }
}
```

---

### Test 6: Ambil Data Quick Count (Live Monitoring JSON)

Ini adalah **satu-satunya endpoint API JSON murni** di aplikasi ini yang digunakan untuk polling real-time perolehan suara.

#### **Request Detail:**
- **Method:** `GET`
- **URL:** `http://127.0.0.1:8000/petugas/events/1/monitor/data`
  - *(Ganti angka `1` dengan ID event yang ada di database Anda)*
- **Headers:**
  ```
  Accept: application/json
  ```
- **Authentication:** Harus sudah login sebagai Petugas terlebih dahulu (gunakan cookie session dari Test 1).

#### **Langkah di Postman:**
1. **WAJIB:** Jalankan request **Login Petugas** terlebih dahulu agar mendapat session cookie.
2. Buat **New Request** dengan nama `Get Live Monitoring Data`.
3. Method **GET**, URL: `http://127.0.0.1:8000/petugas/events/1/monitor/data`
4. Tab **Headers**: Tambahkan `Accept: application/json`
5. Klik **Send**.

#### **Response Sukses (JSON):**
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

#### **Penjelasan Field Response:**
- `total_voters`: Jumlah DPT yang terdaftar aktif di database.
- `total_votes`: Jumlah suara yang sudah masuk.
- `participation_rate`: Tingkat partisipasi pemilih (persen).
- `candidates`: Array berisi perolehan suara setiap kandidat beserta persentase.
- `is_tied`: Boolean, `true` jika ada kandidat dengan perolehan suara tertinggi yang sama (seri).
- `recent_voters`: 15 pemilih terakhir yang baru saja mencoblos (LUBER: tanpa menyebutkan kandidat yang dipilih).

---

### Test 4: Submit Suara Pemilih (POST Vote)

#### **Request Detail:**
- **Method:** `POST`
- **URL:** `http://127.0.0.1:8000/voter/events/OSIS2024/vote`
  - *(Ganti `OSIS2024` dengan `room_code` event yang aktif)*
- **Headers:**
  ```
  Content-Type: application/x-www-form-urlencoded
  Accept: application/json
  X-CSRF-TOKEN: <token_dari_cookie>
  ```
- **Body (x-www-form-urlencoded):**
  ```
  candidate_id: 1
  ```
- **Authentication:** Harus sudah login sebagai Voter (gunakan session dari Test 2).

#### **Langkah di Postman:**
1. Login sebagai Voter terlebih dahulu (Test 2).
2. **Dapatkan CSRF Token:**
   - Buka browser, buka halaman login voter `http://127.0.0.1:8000/voter/login`.
   - Klik kanan → **Inspect** → Tab **Application** → **Cookies** → Cari cookie bernama `XSRF-TOKEN`.
   - Copy value cookie tersebut (sudah URL-encoded, decode dulu di https://www.urldecoder.org/).
3. Buat **New Request** di Postman dengan nama `Submit Vote`.
4. Method **POST**, URL: `http://127.0.0.1:8000/voter/events/OSIS2024/vote`
5. Tab **Headers**:
   - Key: `Accept` | Value: `application/json`
   - Key: `X-CSRF-TOKEN` | Value: *(paste token yang sudah didecode)*
6. Tab **Body** (x-www-form-urlencoded):
   - Key: `candidate_id` | Value: `1`
7. Klik **Send**.

#### **Response Sukses:**
```json
{
    "message": "Suara Anda berhasil tercatat! Terima kasih atas partisipasi Anda.",
    "redirect": "/voter/events/OSIS2024/success"
}
```

#### **Response Error (Sudah Pernah Memilih):**
```json
{
    "message": "Anda sudah memberikan suara pada event ini.",
    "errors": {
        "vote": ["Anda sudah memberikan suara pada event ini."]
    }
}
```

---

## 4. Penjelasan Cookie Session & CSRF Token

### A. Mengapa Perlu Cookie Session?
Aplikasi Laravel menggunakan **session-based authentication**. Setelah login, Laravel membuat cookie `laravel_session` yang berisi ID sesi pengguna. Cookie ini otomatis dikirim browser ke server pada setiap request berikutnya.

Di Postman:
- Cookie otomatis disimpan di **Cookies Manager**.
- Aktifkan fitur **Automatically follow redirects** di Settings → General agar Postman mengikuti redirect HTTP 302.

---

### B. Apa Itu CSRF Token?
**CSRF (Cross-Site Request Forgery)** adalah serangan di mana website jahat membuat user yang sedang login mengirim request palsu ke aplikasi kita tanpa sepengetahuan mereka.

Laravel melindungi form POST dengan **CSRF Token** yang harus disertakan di setiap request mutasi data (POST/PUT/DELETE).

**Cara Kerja:**
1. Saat user membuka form, Laravel menyisipkan token rahasia (`@csrf` di Blade).
2. Saat form di-submit, token dikirim bersama data.
3. Laravel memverifikasi token cocok dengan sesi aktif. Jika tidak cocok → HTTP 419 Page Expired.

**Di Postman:**
- Token bisa diambil dari cookie `XSRF-TOKEN` (perlu decode URL).
- Atau bisa dinonaktifkan sementara (HANYA untuk testing lokal):
  - Buka `app/Http/Middleware/VerifyCsrfToken.php`
  - Tambahkan route ke array `$except`:
    ```php
    protected $except = [
        'voter/events/*/vote',
    ];
    ```

---

## 5. Troubleshooting Error Umum

### ❌ Error: `419 Page Expired` (CSRF Token Mismatch)
**Penyebab:** CSRF token tidak dikirim atau salah.

**Solusi:**
1. Pastikan header `X-CSRF-TOKEN` sudah benar.
2. Atau tambahkan route ke `$except` di `VerifyCsrfToken.php` (hanya untuk testing).

---

### ❌ Error: `401 Unauthenticated`
**Penyebab:** Session cookie tidak dikirim atau user belum login.

**Solusi:**
1. Pastikan request **Login** berhasil terlebih dahulu.
2. Di Postman, cek tab **Cookies** apakah cookie `laravel_session` ada.
3. Pastikan **domain** URL sama persis (`127.0.0.1:8000`, bukan `localhost:8000`).

---

### ❌ Error: `403 Forbidden`
**Penyebab:** User login dengan role yang salah (misal: login sebagai Voter, tapi akses endpoint Petugas).

**Solusi:**
- Pastikan login dengan akun yang memiliki hak akses sesuai endpoint:
  - `/petugas/*` → Harus login sebagai **Petugas**.
  - `/voter/*` → Harus login sebagai **Voter**.
  - `/admin/*` → Harus login sebagai **Super Admin**.

---

### ❌ Error: `302 Redirect Loop`
**Penyebab:** Postman tidak mengikuti redirect otomatis.

**Solusi:**
- Aktifkan **Settings** → **General** → **Automatically follow redirects**.

---

## 6. Collection Postman Siap Pakai (Import)

Anda bisa membuat file JSON collection untuk import ke Postman:

```json
{
  "info": {
    "name": "E-Voting API Testing",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "Login Petugas",
      "request": {
        "method": "POST",
        "header": [
          {
            "key": "Accept",
            "value": "application/json"
          }
        ],
        "body": {
          "mode": "urlencoded",
          "urlencoded": [
            {
              "key": "email",
              "value": "petugas@sekolah.sch.id"
            },
            {
              "key": "password",
              "value": "password"
            }
          ]
        },
        "url": {
          "raw": "http://127.0.0.1:8000/login",
          "protocol": "http",
          "host": ["127", "0", "0", "1"],
          "port": "8000",
          "path": ["login"]
        }
      }
    },
    {
      "name": "Get Live Monitoring Data",
      "request": {
        "method": "GET",
        "header": [
          {
            "key": "Accept",
            "value": "application/json"
          }
        ],
        "url": {
          "raw": "http://127.0.0.1:8000/petugas/events/1/monitor/data",
          "protocol": "http",
          "host": ["127", "0", "0", "1"],
          "port": "8000",
          "path": ["petugas", "events", "1", "monitor", "data"]
        }
      }
    }
  ]
}
```

Simpan sebagai `E-Voting_Postman_Collection.json` dan import di Postman via **Import** → **Upload Files**.

---

## 🎯 Kesimpulan

Aplikasi E-Voting ini **bukan REST API murni**, melainkan aplikasi web berbasis session. Namun endpoint `/petugas/events/{event}/monitor/data` adalah API JSON yang bisa diuji di Postman untuk membuktikan kemampuan sistem menyajikan data Quick Count secara real-time.

Untuk pengujian penuh, disarankan menggunakan **Automated Testing (PHPUnit Feature Test)** yang sudah tersedia di `tests/Feature/`.
