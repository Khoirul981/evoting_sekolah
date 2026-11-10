# 🏛️ PANDUAN LENGKAP ARSITEKTUR MVC & TANYA JAWAB ASESOR (LSP P3 / UKOM)
**Aplikasi E-Voting Sekolah Berbasis Web (Laravel 13 & MySQL)**

---

## 📑 DAFTAR ISI
1. [Pengantar & Filosofi MVC](#1-pengantar--filosofi-mvc)
2. [Siklus Hidup Permintaan (Request Lifecycle MVC di Laravel)](#2-siklus-hidup-permintaan-request-lifecycle-mvc-di-laravel)
3. [Bedah Komponen MVC Proyek E-Voting](#3-bedah-komponen-mvc-proyek-e-voting)
   - [Model (Eloquent ORM & Business Logic)](#a-model-eloquent-orm--logika-bisnis)
   - [View (Blade Template Engine & Polling UI)](#b-view-blade-engine--antarmuka-pengguna)
   - [Controller (Traffic Conductor & Orchestrator)](#c-controller-orkestrator-lalu-lintas)
4. [Studi Kasus Alur Nyata MVC pada 3 Fitur Kunci](#4-studi-kasus-alur-nyata-mvc-pada-3-fitur-kunci)
   - [Kasus 1: Bilik Suara & Pencoblosan Rahasia (Asas LUBER)](#kasus-1-pencoblosan-di-bilik-suara-voter-voting-booth)
   - [Kasus 2: Live Monitoring & Quick Count Real-time (AJAX Polling)](#kasus-2-live-monitoring-dan-quick-count-polling)
   - [Kasus 3: Otentikasi Multi-Guard (Admin vs Pemilih)](#kasus-3-multi-guard-authentication)
5. [Daftar Prediksi Pertanyaan Asesor & Jawaban Mantap](#5-daftar-prediksi-pertanyaan-asesor--jawaban-mantap)
6. [Cheat Sheet Singkat Saat Wawancara di Depan Asesor](#6-cheat-sheet-singkat-saat-wawancara)

---

## 1. Pengantar & Filosofi MVC

### Apa itu MVC?
**MVC (Model-View-Controller)** adalah pola arsitektur perangkat lunak (*software architectural pattern*) yang membagi aplikasi menjadi tiga komponen terpisah berdasarkan prinsip **Separation of Concerns (Pemisahan Tanggung Jawab)**:

```
        ┌────────────────────────────────────────────────────────┐
        │                     HTTP REQUEST                       │
        └──────────────────────────┬─────────────────────────────┘
                                   │
                                   ▼
        ┌────────────────────────────────────────────────────────┐
        │                 ROUTE (routes/web.php)                 │
        │               + MIDDLEWARE (Otorisasi)                 │
        └──────────────────────────┬─────────────────────────────┘
                                   │
                                   ▼
        ┌────────────────────────────────────────────────────────┐
        │                       CONTROLLER                       │
        │   - Memvalidasi data input                             │
        │   - Mengatur alur logika                               │
        │   - Mengorkestrasi Model                               │
        └──────────────┬──────────────────────────▲──────────────┘
                       │                          │
         Panggil Query │            Return Data / │
         atau Transaksi│            Objek Model   │
                       ▼                          │
        ┌─────────────────────────┐               │
        │          MODEL          │               │
        │  - Relasi Entitas       │               │
        │  - Aturan Bisnis        │               │
        │  - Eloquent ORM         │               │
        └──────────────┬──────────┘               │
                       │                          │
             SQL Query │ Hasil DB                 │
                       ▼                          │
        ┌─────────────────────────┐               │
        │     DATABASE MYSQL      │───────────────┘
        └─────────────────────────┘
                       │
       Kirim Data yang │
       Sudah Diproses  │
                       ▼
        ┌────────────────────────────────────────────────────────┐
        │                          VIEW                          │
        │   - Template Blade HTML/CSS Tailwind                   │
        │   - Komponen UI / Modal Interaktif                     │
        │   - Escape output (XSS Protection)                     │
        └──────────────────────────┬─────────────────────────────┘
                                   │
                                   ▼
        ┌────────────────────────────────────────────────────────┐
        │                HTTP RESPONSE (HTML/JSON)               │
        │                     Browser Klien                      │
        └────────────────────────────────────────────────────────┘
```

### Mengapa Proyek E-Voting Menggunakan MVC?
1. **Keamanan Maksimal (*Security*):** Query SQL tidak pernah ditulis langsung di file antarmuka (View), mencegah celah fatal **SQL Injection** dan kebocoran logika bilik suara.
2. **Kerapian & Kemudahan Perawatan (*Maintainability*):** Jika tampilan halaman monitoring diubah (misal ganti warna tombol), backend tidak terganggu. Jika skema database dioptimasi, tampilan Blade tidak rusak.
3. **Kemudahan Pengujian Otomatis (*Automated Testing*):** Controller dan Model dapat diuji menggunakan PHPUnit (`php artisan test`) tanpa memerlukan browser grafis.
4. **Kolaborasi Tim:** Memungkinkan pembagian tugas antara Frontend Developer (fokus di `resources/views`) dan Backend Developer (fokus di `app/Http/Controllers` dan `app/Models`).

---

## 2. Siklus Hidup Permintaan (Request Lifecycle MVC di Laravel)

Setiap aksi pengguna di sistem E-Voting melewati 7 tahapan berikut:

1. **Permintaan Pengguna (User Action):** Pemilih mengklik tombol *"Coblos Kandidat No. 1"* di browser. Browser mengirimkan HTTP Request `POST /voter/events/KODE123/vote`.
2. **Routing & Middleware:** Laravel membaca `routes/web.php`. Request disaring oleh middleware `auth:voter` untuk memastikan pemilih sudah login dengan NIS/NIP yang sah.
3. **Controller Handling:** Request diteruskan ke `VotingBoothController@store`. Controller memvalidasi keberadaan `candidate_id`.
4. **Model Interaction & Transaction:** Controller memanggil Model `EventParticipation` dan Model `Vote` di dalam `DB::transaction()`.
5. **Database Execution:** Eloquent ORM mengubah method Model menjadi query SQL aman (`SELECT FOR UPDATE`, `INSERT INTO votes`, `UPDATE event_participations`) ke database MySQL.
6. **View Compilation / Response Preparation:** Controller menyiapkan redirect ke route `voter.events.success`, memicu pemanggilan View `resources/views/voter/success.blade.php`.
7. **HTTP Response:** Template Blade dikompilasi menjadi dokumen HTML murni dan dikirimkan kembali ke browser pemilih.

---

## 3. Bedah Komponen MVC Proyek E-Voting

### A. MODEL (Eloquent ORM & Logika Bisnis)
Model terletak di direktori `app/Models/`. Di aplikasi ini, Model bukan sekadar tabel kosong, melainkan merepresentasikan entitas bisnis dengan method pintar (*Domain Methods*):

| Model | Lokasi File | Peran & Logika Bisnis Kunci |
| :--- | :--- | :--- |
| `User` | `app/Models/User.php` | Entitas panitia (Super Admin & Petugas). Menyimpan relasi ke event yang dibuat dan mengelola password terenkripsi Bcrypt. |
| `Voter` | `app/Models/Voter.php` | Entitas pemilih (Siswa/Guru). Menggunakan kolom unik `identifier` (NIS/NIP) dan mengimplementasikan `Authenticatable` untuk guard `voter`. |
| `Event` | `app/Models/Event.php` | Entitas pemilihan. Memiliki logika otomatis `checkAndUpdateAutoStatus()` yang membandingkan waktu sekarang (`now()`) dengan `start_time` dan `end_time`. |
| `Candidate` | `app/Models/Candidate.php` | Entitas paslon/opsi. Menyimpan nomor urut, visi-misi, dan path foto kandidat di storage publik. |
| `Vote` | `app/Models/Vote.php` | **Kunci Kerahasiaan (LUBER):** Hanya berisi kolom `event_id` dan `candidate_id`. Sama sekali tidak ada `voter_id`! |
| `EventParticipation` | `app/Models/EventParticipation.php` | Mencatat daftar hadir pemilihan (`voter_id`, `event_id`, `voted_at`). Memiliki method bisnis `hasVoted()` untuk enkapsulasi cek pencoblosan. |
| `AuditLog` | `app/Models/AuditLog.php` | Black box audit sistem. Mencatat seluruh aktivitas mutasi data penting beserta user dan IP address. |

#### Contoh Kode Model Berlogika Bisnis (`Event.php`):
```php
// Enkapsulasi logika otomatis perubahan status event di level Model
public function checkAndUpdateAutoStatus(): void
{
    $now = Carbon::now();

    if ($this->status === 'DRAFT' && $this->start_time && $now->gte($this->start_time)) {
        if ($this->end_time && $now->gte($this->end_time)) {
            $this->update(['status' => 'CLOSED']);
        } else {
            $this->update(['status' => 'OPEN']);
        }
    } elseif ($this->status === 'OPEN' && $this->end_time && $now->gte($this->end_time)) {
        $this->update(['status' => 'CLOSED']);
    }
}
```

---

### B. VIEW (Blade Engine & Antarmuka Pengguna)
View terletak di direktori `resources/views/`. View bertugas menyajikan data secara elegan, responsif, dan aman:

1. **Struktur Berbasis Layout & Komponen Modular:**
   - `resources/views/layouts/app.blade.php`: Kerangka utama admin & petugas.
   - `resources/views/layouts/voter.blade.php`: Kerangka khusus bilik suara yang bersih dari distraksi navigasi umum.
2. **Fitur Keamanan Bawaan View:**
   - **XSS Prevention:** Penulisan variabel menggunakan kurung kurawal ganda `{{ $candidate->name }}` secara otomatis menjalankan fungsi PHP `htmlspecialchars()`.
   - **CSRF Token:** Setiap formulir POST menyertakan `@csrf` yang menghasilkan input tersembunyi `_token` untuk mencegah serangan pemalsuan request lintas situs.
3. **Pola Hybrid (Blade View + AJAX Polling JSON):**
   - Halaman `resources/views/petugas/monitoring/show.blade.php` di-render awal oleh Blade.
   - Di dalamnya terdapat kode JavaScript murni yang melakukan polling setiap beberapa detik ke endpoint JSON Controller untuk memperbarui grafik perolehan suara tanpa reload halaman.

---

### C. CONTROLLER (Orkestrator Lalu Lintas)
Controller terletak di `app/Http/Controllers/` dan dikelompokkan berdasarkan peran (*namespace*):

```
app/Http/Controllers/
├── Admin/
│   ├── DashboardController.php   (Statistik ringkasan sistem)
│   ├── UserController.php        (Kelola akun panitia & master data DPT)
│   └── AuditLogController.php    (Monitoring jejak aktivitas sistem)
├── Petugas/
│   ├── EventController.php       (CRUD event & aktivasi ruang pemilihan)
│   ├── CandidateController.php   (Kelola paslon, visi misi, foto)
│   └── MonitoringController.php  (Pusat kendali live monitoring & data polling)
├── Voter/
│   ├── VoterDashboardController.php (Lobi pemilih & input kode ruang)
│   └── VotingBoothController.php    (Bilik suara & eksekusi pencoblosan)
└── Auth/
    ├── AdminAuthController.php   (Login/logout panitia via email/password)
    └── VoterAuthController.php   (Login/logout siswa/guru via NIS/NIP)
```

**Karakteristik Controller di Aplikasi Ini:**
- **Thin Controller:** Controller tidak menulis query SQL mentah (*raw query*), melainkan memanfaatkan Eloquent Model.
- **Strict Authorization:** Setiap aksi di Controller diverifikasi otorisasi kepemilikannya (contoh: Petugas hanya dapat mengedit event miliknya sendiri: `if ($event->created_by !== Auth::id()) abort(403);`).
- **Atomic Execution:** Operasi yang melibatkan lebih dari satu tabel selalu dibungkus `DB::transaction()`.

---

## 4. Studi Kasus Alur Nyata MVC pada 3 Fitur Kunci

### Kasus 1: Pencoblosan di Bilik Suara (Voter Voting Booth)
Merupakan pembuktian implementasi asas **LUBER (Langsung, Umum, Bebas, Rahasia)** dan proteksi anti-kecurangan:

```
[Browser Pemilih]
       │
       │ 1. Kirim candidate_id yang dipilih
       ▼
[Route: routes/web.php] ──▶ Middleware auth:voter
       │
       │ 2. Diteruskan jika sesi login voter valid
       ▼
[Controller: VotingBoothController@store]
       │
       │ 3. Panggil Event::where('room_code')->firstOrFail()
       │ 4. Mulai DB::transaction()
       │ 5. Lock baris partisipasi: lockForUpdate()
       │
       ├─────────────────────────────────────────────┐
       │ 6a. Simpan Suara Anonim                     │ 6b. Catat Kehadiran
       ▼                                             ▼
[Model Vote]                                  [Model EventParticipation]
INSERT INTO votes                             UPDATE event_participations
(event_id, candidate_id)                      SET voted_at = NOW()
                                              WHERE voter_id = X AND event_id = Y
       │                                             │
       └──────────────────────┬──────────────────────┘
                              │
                              ▼
[Controller: Commit DB Transaksi]
Redirect ke rute 'voter.events.success'
                              │
                              ▼
[View: voter/success.blade.php]
Render kartu konfirmasi sukses dan tombol keluar
```

---

### Kasus 2: Live Monitoring dan Quick Count (Polling)
Menjelaskan bagaimana sistem menyajikan data real-time tanpa membebani server:

1. **Model:** Model `Candidate` dihitung jumlah suaranya menggunakan method agregasi optimal:
   ```php
   $candidates = $event->candidates()->withCount('votes')->get();
   ```
2. **Controller (`MonitoringController@data`):**
   Menerima HTTP Request via AJAX, menghitung persentase suara, mendeteksi jika terjadi suara seri (*tie condition*), dan mengembalikan data dalam format `JsonResponse`:
   ```php
   return response()->json([
       'total_votes' => $totalVotes,
       'participation_rate' => $participationRate,
       'candidates' => $candidates,
       'is_tied' => $isTied,
   ]);
   ```
3. **View (`monitoring/show.blade.php`):**
   Fungsi `updateMonitoringData()` di JavaScript memperbarui lebar bar persentase CSS Tailwind dan teks angka tanpa me-refresh halaman browser.

---

### Kasus 3: Multi-Guard Authentication
Memisahkan otentikasi antara Panitia dan Pemilih:

1. **Model:** `User` (tabel `users`) dan `Voter` (tabel `voters`) keduanya mengimplementasikan antarmuka `Illuminate\Contracts\Auth\Authenticatable`.
2. **Konfigurasi (`config/auth.php`):**
   - Guard `web` $\rightarrow$ Provider: `users`
   - Guard `voter` $\rightarrow$ Provider: `voters`
3. **Controller:**
   - `AdminAuthController` memanggil `Auth::guard('web')->attempt(...)`
   - `VoterAuthController` memvalidasi identifier unik sekolah dan memanggil `Auth::guard('voter')->login($voter)`
4. **View:**
   Blade menggunakan directif `@auth('web')` untuk menampilkan menu panel admin dan `@auth('voter')` untuk menampilkan profil pemilih di pojok bilik suara.

---

## 5. Daftar Prediksi Pertanyaan Asesor & Jawaban Mantap

### ❓ Q1: "Coba jelaskan apa itu MVC dan tunjukkan di mana letak masing-masing komponennya di kodingan Anda!"
* **Lokasi Kode:** 
  - Model: `app/Models/`
  - View: `resources/views/`
  - Controller: `app/Http/Controllers/`
  - Route: `routes/web.php`
* **Jawaban Mantap:**
  > *"MVC adalah pola arsitektur yang memisahkan tanggung jawab aplikasi menjadi 3 bagian:*
  > 1. *Model (`app/Models/`): Berisi representasi entitas data dan aturan bisnis menggunakan Eloquent ORM. Contohnya `app/Models/Vote.php` dan `Event.php`.*
  > 2. *View (`resources/views/`): Mengatur antarmuka pengguna berbasis template Blade. Contohnya `resources/views/voter/booth.blade.php` untuk bilik suara.*
  > 3. *Controller (`app/Http/Controllers/`): Pengatur lalu lintas logika yang menjembatani input request dari user menuju Model, lalu meresponsnya ke View. Contohnya `VotingBoothController.php`.*
  > 
  > *Semuanya dihubungkan secara terpusat oleh rute di `routes/web.php`."*

---

### ❓ Q2: "Mengapa Anda menggunakan MVC daripada membuat aplikasi PHP native satu file procedural?"
* **Jawaban Mantap:**
  > *"Ada tiga alasan teknis fundamental:*
  > 1. *Keamanan (Security): Pada PHP procedural satu file, developer rawan lupa sanitasi input sehingga rentan SQL Injection dan XSS. Di Laravel MVC, Model otomatis menggunakan PDO Parameter Binding, dan View Blade otomatis melakukan escaping karakter berbahaya.*
  > 2. *Keterawatan (Maintainability): Logika database tidak bercampur dengan tag HTML. Jika kami ingin mengubah desain UI menggunakan Tailwind CSS, kami cukup memodifikasi file View tanpa resiko merusak query database.*
  > 3. *Pengujian Otomatis (Testability): Dengan MVC, kami dapat menguji fungsionalitas bilik suara melalui PHPUnit test (`tests/Feature/VotingBoothTest.php`) secara otomatis dan terisolasi."*

---

### ❓ Q3: "Apa itu Fat Controller vs Thin Controller? Pendekatan mana yang Anda gunakan di proyek ini?"
* **Jawaban Mantap:**
  > *"Fat Controller adalah anti-pattern di mana semua baris kode (validasi, kalkulasi bisnis, query rumit, pengiriman email) ditumpuk di Controller sehingga file menjadi ribuan baris dan sulit dirawat.*
  > 
  > *Di proyek ini, kami menerapkan prinsip **Thin Controller & Rich Model**:*
  > - *Controller hanya bertugas mengorkestrasi validasi request dan menentukan respon.*
  > - *Logika bisnis entitas diekapsulasi di dalam Model terkait. Contohnya logika buka-tutup otomatis waktu pemilihan dibuat dalam method `$event->checkAndUpdateAutoStatus()` di `Event.php`, dan pengecekan apakah pemilih sudah mencoblos dibuat dalam `$participation->hasVoted()` di `EventParticipation.php`."*

---

### ❓ Q4: "Bagaimana cara Controller mengirimkan data hasil query ke View pada Laravel?"
* **Lokasi Kode:** `app/Http/Controllers/Petugas/MonitoringController.php` baris 48–59.
* **Jawaban Mantap:**
  > *"Controller memanggil method view() dengan menyertakan helper compact() atau array asosiatif.*
  > 
  > *Sebagai contoh di `MonitoringController@show`:*
  > ```php
  > return view('petugas.monitoring.show', compact(
  >     'event', 'totalVoters', 'totalVotes', 'candidates'
  > ));
  > ```
  > *Variabel-variabel tersebut kemudian otomatis dapat diakses di file Blade menggunakan sintaks `{{ $totalVotes }}` atau diulang dengan `@foreach($candidates as $candidate)`."*

---

### ❓ Q5: "Di mana Anda meletakkan proteksi hak akses pengguna? Di Controller atau di Route?"
* **Lokasi Kode:** `routes/web.php` dan `app/Http/Controllers/Petugas/EventController.php`.
* **Jawaban Mantap:**
  > *"Kami menerapkan prinsip **Defense in Depth (Perlindungan Berlapis)**:*
  > 1. *Lapisan Pertama (Route Middleware): Di `routes/web.php`, seluruh rute dikelompokkan dengan middleware otentikasi dan peran (contoh: `middleware(['auth:web', 'role:PETUGAS'])`).*
  > 2. *Lapisan Kedua (Object-Level Authorization di Controller): Di dalam method Controller, kami memeriksa apakah entitas data benar milik pengguna yang sedang aktif. Contohnya di `CandidateController`:*
  >    ```php
  >    if ($event->created_by !== Auth::id()) {
  >        abort(403, 'Anda tidak memiliki akses ke event ini.');
  >    }
  >    ```
  > *Dengan dua lapisan ini, manipulasi URL atau parameter ID dari pengguna nakal akan langsung diblokir dengan HTTP 403 Forbidden."*

---

### ❓ Q6: "Bagaimana cara kerja Eloquent ORM di Model dibandingkan query SQL mentah (Raw Query)?"
* **Jawaban Mantap:**
  > *"Eloquent ORM adalah implementasi pola **Active Record**. Setiap instance Model merepresentasikan satu baris data pada tabel database.*
  > 
  > *Keuntungan menggunakan Eloquent dibanding Raw SQL:*
  > 1. *Keamanan: Otomatis menggunakan PDO Prepared Statements untuk menangkal SQL Injection.*
  > 2. *Kemudahan Relasi: Mendefinisikan relasi antar-tabel secara ekspresif seperti `$event->candidates()` atau `$event->participations()`.*
  > 3. *Data Casting & Mutator: Kolom timestamp otomatis dikonversi menjadi objek Carbon sehingga manipulasi format tanggal Indonesia menjadi sangat mudah.*
  > 4. *Agregasi Efisien: Eloquent menyediakan method seperti `withCount('votes')` yang menghasilkan query SQL `COUNT(*)` teroptimasi tanpa perlu mengambil seluruh record ke memori PHP."*

---

### ❓ Q7: "Jika ada form input dari View ke Controller, bagaimana sistem mencegah serangan CSRF (Cross-Site Request Forgery)?"
* **Jawaban Mantap:**
  > *"Setiap form HTML di file View Blade diwajibkan menyertakan direktif `@csrf`. Direktif ini otomatis menghasilkan elemen input tersembunyi berisikan token rahasia sesi.*
  > 
  > *Ketika form dikirimkan, middleware bawaan Laravel (`VerifyCsrfToken`) akan membandingkan token dari request dengan token yang tersimpan di sesi server. Jika tidak cocok (misalnya request palsu dari website eksternal), Laravel akan langsung membatalkan request dan mengembalikan error HTTP 419 Page Expired."*

---

## 6. Cheat Sheet Singkat Saat Wawancara

Hafalkan 5 poin kunci ini sebelum masuk ruang asesmen:

1. **M = Model (`app/Models`)** $\rightarrow$ Tempat data, relasi Eloquent, dan logika aturan bisnis.
2. **V = View (`resources/views`)** $\rightarrow$ Tempat UI Blade, layout, kartu kandidat, modal, dan proteksi XSS.
3. **C = Controller (`app/Http/Controllers`)** $\rightarrow$ Tempat menerima request, validasi input, otorisasi ID, dan memanggil Model.
4. **Jalur Eksekusi Data:** `Browser` $\rightarrow$ `Route + Middleware` $\rightarrow$ `Controller` $\rightarrow$ `Model` $\rightarrow$ `MySQL` $\rightarrow$ `View (Blade)` $\rightarrow$ `Response ke Browser`.
5. **Keunggulan Nyata di E-Voting:** Menjamin **Asas LUBER** karena Model `Vote` (tanpa voter_id) dan Model `EventParticipation` (tanpa candidate_id) diproses terpisah secara aman di dalam `DB::transaction()` pada Controller.

