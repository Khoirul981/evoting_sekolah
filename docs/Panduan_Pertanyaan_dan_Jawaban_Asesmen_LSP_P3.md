# PANDUAN LENGKAP TANYA JAWAB ASESMEN LSP P3 & UJI KOMPETENSI (UKOM)
## Sistem E-Voting Sekolah Digital (VOTINGYUK)

---

| Parameter | Keterangan |
| :--- | :--- |
| **Nama Aplikasi** | VOTINGYUK - Sistem E-Voting Pemilihan Ketua OSIS & MPK Digital |
| **Skema Sertifikasi** | Pemrogram Web / Rekayasa Perangkat Lunak (LSP P3 / BNSP) |
| **Dasar Standar** | Standar Kompetensi Kerja Nasional Indonesia (SKKNI) |
| **Framework & Versi**| Laravel 13 (v13.33.0), PHP 8.3.33, MySQL 8.0.30, Tailwind CSS v4.0.0 |
| **Target Dokumen** | Panduan persiapan wawancara lisan, verifikasi portofolio, dan demonstrasi/live-coding |

---

## DAFTAR ISI

1. [Strategi Utama Menghadapi Asesor](#1-strategi-utama-menghadapi-asesor)
2. [Pertanyaan Kritis Inti Sistem E-Voting (Aspek Keamanan & LUBER)](#2-pertanyaan-kritis-inti-sistem-e-voting-aspek-keamanan--luber)
3. [Tanya Jawab Berdasarkan 11 Unit Kompetensi SKKNI](#3-tanya-jawab-berdasarkan-11-unit-kompetensi-skkni)
   - [Unit 1 & 9: Sistem Operasi, Environment, dan Perangkat Lunak Tools](#unit-1--9-sistem-operasi-environment-dan-perangkat-lunak-tools)
   - [Unit 2 & 6: Desain Antarmuka (UI/UX) & Implementasi User Interface](#unit-2--6-desain-antarmuka-uiux--implementasi-user-interface)
   - [Unit 3: Analisis Tools Pengembangan Sistem](#unit-3-analisis-tools-pengembangan-sistem)
   - [Unit 4: Library, Komponen, dan Framework](#unit-4-library-komponen-dan-framework)
   - [Unit 5 & 7: Struktur Data, Perancangan Entitas, Database, & Query](#unit-5--7-struktur-data-perancangan-entitas-database--query)
   - [Unit 8 & 10: Eksekusi Kode Sumber, Konfigurasi, & Pengujian (Testing)](#unit-8--10-eksekusi-kode-sumber-konfigurasi--pengujian-testing)
   - [Unit 11: Reusable Code (Subrutin, Trait, Helper, & Modularitas)](#unit-11-reusable-code-subrutin-trait-helper--modularitas)
4. [Simulasi Kasus Uji Praktik / Live Coding Spontan](#4-simulasi-kasus-uji-praktik--live-coding-spontan)
5. [Daftar Istilah Teknis Kunci (Cheat Sheet)](#5-daftar-istilah-teknis-kunci-cheat-sheet)

---

## 1. STRATEGI UTAMA MENGHADAPI ASESOR

Asesor LSP P3 bertugas memvalidasi **apakah Anda benar-benar kompeten (K)** atau **belum kompeten (BK)** berdasarkan bukti portofolio dan pemahaman langsung Anda.

### Tiga Aturan Emas Saat Menjawab:
1. **Jawab dengan Konsep + Bukti Kode (Code-Referenced):**
   Jangan hanya berteori. Selalu sebutkan nama file, fungsi, atau lokasi tabel.
   *Contoh:* *"Untuk menjaga kerahasiaan, saya memisahkan pencatatan pada `app/Http/Controllers/Voter/VotingBoothController.php` di baris 150..."*
2. **Kuasai Alur Data (Data Flow):**
   Ketahui perjalanan request dari **Route $\rightarrow$ Middleware $\rightarrow$ Controller $\rightarrow$ Model/DB $\rightarrow$ View**.
3. **Pamerkan Fitur Unggulan Sistem:**
   Bila ditanya hal umum, arahkan ke kelebihan sistem Anda:
   - **Multi-Guard Authentication** (memisahkan pengelola vs pemilih).
   - **Prinsip LUBER Database** (suara tidak memiliki foreign key ke pemilih).
   - **Automated Testing** (28 test case PHPUnit lulus 100%).
   - **Pessimistic Locking & Database Transaction** (anti double voting).

---

## 2. PERTANYAAN KRITIS INTI SISTEM E-VOTING (ASPEK KEAMANAN & LUBER)

### Q1: *"Bagaimana sistem menjamin asas RAHASIA (LUBER)? Apakah Super Admin atau Database Administrator bisa melacak pilihan seorang siswa/guru?"*
* **Lokasi Kode:**
  - `database/migrations/2026_09_26_000004_create_votes_table.php`
  - `app/Models/Vote.php`
  - `app/Http/Controllers/Voter/VotingBoothController.php` (method `castVote()`)
* **Jawaban Mantap:**
  > *"Sistem kami menjamin asas kerahasiaan secara struktural pada level skema database. Pada tabel `votes`, kami **hanya menyimpan kolom `event_id` dan `candidate_id`**, tanpa ada kolom `voter_id` sama sekali.*
  >
  > *Untuk mencegah pemilih mencoblos dua kali, kami membuat tabel terpisah bernama `event_participations` yang hanya mencatat `voter_id`, `event_id`, dan `voted_at` (waktu mencoblos). Kedua tabel ini tidak memiliki foreign key yang saling menghubungkan. Dengan demikian, bahkan jika seseorang membuka database MySQL langsung melalui phpMyAdmin, tidak ada relasi atau jejak matematis untuk mengetahui kandidat mana yang dipilih oleh pemilih tertentu."*

---

### Q2: *"Bagaimana cara sistem mencegah Double Voting (mencoblos 2 kali) dan Race Condition jika pemilih membuka 2 tab browser bersamaan?"*
* **Lokasi Kode:**
  - `app/Http/Controllers/Voter/VotingBoothController.php` baris 136–165.
* **Jawaban Mantap:**
  > *"Kami menerapkan dua lapisan perlindungan:*
  > 1. *Validasi Logis: Di awal bilik suara, sistem memeriksa relasi `$participation->hasVoted()`. Jika sudah pernah memilih, akses bilik suara langsung di-redirect ke halaman tanda terima sukses.*
  > 2. *Proteksi Konkurensi (Race Condition) dengan Database Transaction & Pessimistic Lock:*
  >    *Proses simpan dibungkus dalam `DB::transaction()` menggunakan query `$participation->lockForUpdate()`. Baris data partisipasi pemilih akan dikunci di level database hingga transaksi commit. Jika ada request kedua yang masuk secara bersamaan, transaksi kedua akan membaca status terkini dan melemparkan `DomainException`, sehingga suara ganda mustahil tercatat."*

```php
// Potongan bukti kode di VotingBoothController.php
DB::transaction(function () use ($event, $voter, $candidate) {
    $participation = EventParticipation::where('event_id', $event->id)
        ->where('voter_id', $voter->id)
        ->lockForUpdate()
        ->first();

    if ($participation && $participation->hasVoted()) {
        throw new \DomainException('Anda sudah memberikan suara pada event ini.');
    }

    // 1. Simpan suara murni (Anonim)
    Vote::create([
        'event_id' => $event->id,
        'candidate_id' => $candidate->id,
    ]);

    // 2. Tandai voter telah menggunakan hak suara
    EventParticipation::updateOrCreate(
        ['event_id' => $event->id, 'voter_id' => $voter->id],
        ['voted_at' => Carbon::now()]
    );
});
```

---

### Q3: *"Mengapa arsitektur login menggunakan Multi-Guard Authentication? Mengapa siswa/guru tidak disatukan di tabel `users`?"*
* **Lokasi Kode:**
  - `config/auth.php`
  - `app/Models/User.php` vs `app/Models/Voter.php`
  - `routes/web.php`
* **Jawaban Mantap:**
  > *"Sistem kami membedakan entitas Pengelola dan Pemilih demi keamanan dan efisiensi UX:*
  > - *Guard `web` (Tabel `users`): Digunakan untuk Super Admin dan Petugas. Menggunakan kredensial `email` dan `password` terenkripsi Bcrypt dengan session durasi kerja panitia.*
  > - *Guard `voter` (Tabel `voters`): Digunakan untuk Siswa dan Guru. Menggunakan kredensial identitas sekolah unik (`identifier` berupa NIS atau NIP) tanpa mewajibkan email pribadi siswa.*
  >
  > *Dengan Multi-Guard, credential leakage pada bilik suara tidak akan pernah bisa mengeksploitasi hak akses panel admin, dan session pemilih bisa diisolasi sepenuhnya."*

---

### Q4: *"Bagaimana sistem mengontrol waktu buka-tutup pemilihan secara otomatis?"*
* **Lokasi Kode:**
  - `app/Models/Event.php` (method `checkAndUpdateAutoStatus()`)
  - `app/Http/Controllers/Voter/VotingBoothController.php` (method `show()`)
* **Jawaban Mantap:**
  > *"Di model `Event`, terdapat fungsi pembantu `checkAndUpdateAutoStatus()`. Setiap kali ada request ke bilik suara atau panel monitoring, sistem mengecek waktu saat ini (`Carbon::now()`) terhadap kolom `start_time` dan `end_time`. Jika waktu telah melewati `end_time` dan status masih `PUBLISHED`, event secara otomatis berganti status menjadi `CLOSED` tanpa perlu cron job tambahan."*

---

### Q5: *"Apa fungsi tabel `audit_logs` dan data apa saja yang dicatat di dalamnya?"*
* **Lokasi Kode:**
  - `app/Models/AuditLog.php`
  - `database/migrations/2026_09_26_000007_create_audit_logs_table.php`
* **Jawaban Mantap:**
  > *"Tabel `audit_logs` bertindak sebagai jejak forensik digital (Digital Forensics Trail). Setiap aksi kritis—seperti petugas mempublikasikan event, menghapus kandidat, atau mengekspor hasil suara—dicatat bersama user ID, IP address, user agent, dan waktu kejadian.*
  >
  > *Khusus untuk pemilih, audit log mencatat aksi 'vote.cast' yang berbunyi 'Pemilih Ahmad (NIS: 100123) telah memberikan suara', **tanpa pernah mencatat nama kandidat yang dicoblos** demi menjaga asas LUBER."*

---

## 3. TANYA JAWAB BERDASARKAN 11 UNIT KOMPETENSI SKKNI

### Unit 1 & 9: Sistem Operasi, Environment, dan Perangkat Lunak Tools
*(Kode Unit: J.612000.001 & J.620100.011.01)*

#### Q: *"Sebutkan sistem operasi dan tools utama yang Anda gunakan dalam membangun aplikasi ini!"*
* **Jawaban:**
  - **Sistem Operasi:** Windows 11 Pro 64-bit.
  - **Web & DB Server:** Laragon v6.0 Full (Apache 2.4.68, MySQL 8.0.30, PHP 8.3.33).
  - **Node.js Environment:** Node.js v22.23.1 dan NPM 10.9.8 untuk build tools aset front-end.
  - **Code Editor:** Visual Studio Code dengan ekstensi spesifik: *PHP Intelephense, Laravel Blade Snippets, Tailwind CSS IntelliSense, GitLens, dan Prettier*.
  - **VCS & Repository:** Git CLI dan GitHub.

#### Q: *"Mengapa memilih PHP 8.3 dan fitur modern apa dari PHP 8.3 yang diterapkan?"*
* **Jawaban:**
  > *"PHP 8.3 memiliki engine JIT yang lebih optimal dan penanganan type-safety yang ketat. Di projek ini, kami memanfaatkan:*
  > 1. *Typed Properties & Return Types pada Controller dan Model.*
  > 2. *Union Return Types seperti `View|RedirectResponse`.*
  > 3. *Readonly Properties dan Nullsafe Operator `?->`.*
  > 4. *Fungsi `Hash::make()` yang otomatis menggunakan algoritma Bcrypt yang aman."*

---

### Unit 2 & 6: Desain Antarmuka (UI/UX) & Implementasi User Interface
*(Kode Unit: J.612000.007 & J.620100.005.02)*

#### Q: *"Teknologi apa yang digunakan pada antarmuka (frontend) dan bagaimana menjamin responsivitas di smartphone?"*
* **Jawaban:**
  > *"Kami menggunakan **Tailwind CSS v4.0.0** dengan pendekatan Mobile-First. Desain tampilan menggunakan utility classes seperti `grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3` agar layout bilik suara beradaptasi mulus dari layar HP siswa (360px) hingga layar monitor proyektor (1920px).*
  >
  > *Font utama yang digunakan adalah **Plus Jakarta Sans** untuk readability yang bersih dan modern, serta font monospace pada field NIS/NIP untuk meminimalkan typo angka."*

#### Q: *"Bagaimana mencegah kesalahan klik pemilih saat mencoblos di bilik suara?"*
* **Jawaban:**
  > *"Kami mengimplementasikan **Modal Dialog Konfirmasi 2 Langkah**. Saat pemilih menekan tombol 'COBLOS', sistem tidak langsung mengirim request POST, melainkan memunculkan dialog modal JavaScript yang menampilkan nomor kandidat, foto, dan nama paslon yang dipilih. Pemilih harus menekan tombol konfirmasi 'Ya, Saya Yakin' sebelum form disubmit."*

---

### Unit 3: Analisis Tools Pengembangan Sistem
*(Kode Unit: J.620100.001.01)*

#### Q: *"Mengapa memilih framework Laravel dibandingkan membangun sistem dengan Native PHP?"*
* **Jawaban:**
  > *"1. **Keamanan Bawaan:** Laravel sudah menyertakan proteksi CSRF (`@csrf`) untuk mencegah tindakan tidak sah yang dilakukan atas nama pengguna yang sudah terautentikasi., proteksi SQL Injection melalui Eloquent ORM parameter binding, XSS sanitization pada Blade `{{ }}`, dan Session Hijacking protection.*
  > *2. **Standar Arsitektur:** Menerapkan MVC (Model-View-Controller) sehingga kode modular, mudah dirawat, dan terorganisir.*
  > *3. **Testing Suite:** Memiliki integrasi PHPUnit bawaan untuk Automated Testing."*

---

### Unit 4: Library, Komponen, dan Framework
*(Kode Unit: J.620100.003.01)*

#### Q: *"Jelaskan pustaka (library/dependencies) eksternal apa saja yang Anda pasang melalui Composer dan NPM!"*
* **Jawaban:**
  > *"Kami berprinsip lean & clean dependency—hanya memasang paket yang benar-benar esensial:*
  > - **PHP/Composer:**
  >   - `laravel/framework`: Fondasi aplikasi MVC.
  >   - `nesbot/carbon`: Manipulasi tanggal/waktu pemilihan.
  >   - `phpunit/phpunit`: Pengujian otomatis unit & feature.
  > - **JavaScript/NPM:**
  >   - `tailwindcss` & `@tailwindcss/vite`: Utility CSS compiler generasi terbaru.
  >   - `vite`: Fast asset bundler & Hot Module Replacement (HMR).*
  >
  > *(Catatan: library manipulasi gambar eksternal tidak dipasang karena pengelolaan upload foto kandidat ditangani native melalui `Illuminate\Support\Facades\Storage` Laravel)."*

---

### Unit 5 & 7: Struktur Data, Perancangan Entitas, Database, & Query
*(Kode Unit: J.620100.004.02 & J.620100.007.01)*

#### Q: *"Sebutkan entitas tabel utama pada database aplikasi ini dan jelaskan relasinya!"*
* **Jawaban:**
  - **`users`:** Menyimpan akun pengelola (`role`: SUPER_ADMIN, PETUGAS).
  - **`voters`:** Menyimpan data induk pemilih siswa dan guru (`identifier`, `name`, `type`).
  - **`events`:** Menyimpan sesi pemilihan (`created_by`, `title`, `room_code`, `status`, `start_time`, `end_time`).
  - **`candidates`:** Menyimpan calon kandidat (`event_id`, `candidate_number`, `name`, `photo`, `vision`, `mission`).
  - **`votes`:** Menyimpan suara sah yang masuk (`event_id`, `candidate_id`) - **Tanpa `voter_id`**.
  - **`event_participations`:** Tabel pivot pemilih (`event_id`, `voter_id`, `voted_at`).
  - **`audit_logs`:** Menyimpan rekaman jejak sistem (`user_id`, `action`, `description`, `ip_address`).

#### Q: *"Bagaimana query untuk menampilkan live count suara kandidat beserta persentasenya?"*
* **Lokasi Kode:** `app/Http/Controllers/Petugas/MonitoringController.php` method `show()`
* **Jawaban:**
  > *"Kami memanfaatkan fitur agregasi Eloquent `withCount('votes')`:*

```php
$totalVotes = $event->votes()->count();

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

---

### Unit 8 & 10: Eksekusi Kode Sumber, Konfigurasi, & Pengujian (Testing)
*(Kode Unit: J.620100.010.01 & J.620100.012.01)*

#### Q: *"Bagaimana Anda menguji keandalan sistem ini secara otomatis?"*
* **Jawaban:**
  > *"Kami menguji aplikasi menggunakan **Automated Feature Testing** dengan PHPUnit bawaan Laravel. Kami memiliki 28 skenario pengujian dengan 121 assertions yang mencakup:*
  > 1. *`PetugasEventTest`: Pengujian validasi event, slug unik, proteksi publish minimal 2 kandidat, dan filter otorisasi.*
  > 2. *`PetugasCandidateTest`: Pengujian upload foto kandidat, validasi nomor urut unik per event, dan penghapusan foto otomatis dari storage.*
  > 3. *`VotingBoothTest`: Pengujian proteksi voting tertutup, anti double-voting, verifikasi LUBER di database, dan redirect status.*
  >
  > *Semua test dijalankan menggunakan perintah `php artisan test` dan menghasilkan status **100% PASSED** dalam durasi di bawah 2 detik menggunakan SQLite in-memory database."*

#### Q: *"Coba sebutkan apa arti perintah `php artisan optimize:clear` atau `php artisan migrate:fresh --seed`!"*
* **Jawaban:**
  > - `php artisan optimize:clear`: Membersihkan seluruh cache sistem (route cache, config cache, view/blade cache, dan compiled services) ketika ada perubahan konfigurasi `.env`.
  > - `php artisan migrate:fresh --seed`: Men-drop seluruh tabel lama, menjalankan ulang semua file migrasi dari awal, dan mengeksekusi seeder untuk membuat akun bawaan Super Admin, Petugas contoh, dan data simulasi pemilih.

---

### Unit 11: Reusable Code (Subrutin, Trait, Helper, & Modularitas)
*(Kode Unit: J.620100.014.01)*

#### Q: *"Di bagian mana dalam kode Anda yang menunjukkan penerapan Reusable Code (fungsi yang dipakai berulang)?"*
* **Jawaban:**
  > *"1. **Model Helper Methods:** Pada model `Event`, method `isPublished()`, `isClosed()`, dan `checkAndUpdateAutoStatus()` dipanggil berulang kali di berbagai controller (EventController, VotingBoothController, dan MonitoringController).*
  > *2. **Static Logging Helper:** Pada model `AuditLog`, terdapat method static `AuditLog::record($action, $description)` yang dapat dipanggil dari controller mana saja dalam satu baris kode.*
  > *3. **Blade Layout Inheritance:** Tiga master layout modular (`layouts/admin.blade.php`, `layouts/petugas.blade.php`, `layouts/voter.blade.php`) menggunakan direktif `@yield('content')` dan `@include('components.navbar')` sehingga struktur HTML tidak ditulis berulang-ulang."*

---

## 4. SIMULASI KASUS UJI PRAKTIK / LIVE CODING SPONTAN

Saat asesmen, asesor sering memberikan tugas kecil untuk dikerjakan langsung di depan mereka dalam waktu 5–10 menit. Berikut adalah skenario yang paling sering diminta:

---

### Kasus Praktik 1: "Coba ubah aturan validasi input nama kandidat agar minimal 3 karakter!"
* **Langkah Penyelesaian:**
  1. Buka file: `app/Http/Controllers/Petugas/CandidateController.php`.
  2. Cari method `store()` dan `update()`.
  3. Ubah aturan validasi pada `'name'`:
     ```php
     // SEBELUM:
     'name' => ['required', 'string', 'max:255'],
     
     // SESUDAH:
     'name' => ['required', 'string', 'min:3', 'max:255'],
     ```
  4. Simpan file dan tunjukkan pesan error di browser jika nama diisi kurang dari 3 huruf.

---

### Kasus Praktik 2: "Coba ubah warna tombol Coblos dari Merah menjadi Biru!"
* **Langkah Penyelesaian:**
  1. Buka file: `resources/views/voter/voting-booth.blade.php`.
  2. Cari tombol modal coblos kandidat (`<button type="button" onclick="openVoteModal(...)">`).
  3. Ganti class Tailwind dari `bg-red-600 hover:bg-red-700` menjadi `bg-blue-600 hover:bg-blue-700`:
     ```html
     <!-- Ganti bg-red-600 menjadi bg-blue-600 -->
     <button type="button" class="... bg-blue-600 hover:bg-blue-700 ...">
         <span>COBLOS KANDIDAT</span>
     </button>
     ```
  4. Refresh browser di bilik suara untuk menunjukkan perubahan langsung.

---

### Kasus Praktik 3: "Coba jalankan automated test dan jelaskan output terminalnya!"
* **Langkah Penyelesaian:**
  1. Buka terminal di VS Code (`Ctrl + ~`).
  2. Ketik perintah:
     ```bash
     php artisan test
     ```
  3. Jelaskan kepada asesor saat hasil muncul hijau:
     > *"Pak Asesor, ini adalah hasil eksekusi pengujian otomatis PHPUnit. Terlihat 28 test berhasil dieksekusi dengan 121 assertion dalam waktu 1.9 detik. Ini membuktikan semua skenario hak akses, validasi coblos, dan proteksi kerahasiaan suara terverifikasi berjalan tanpa bug."*

---

### Kasus Praktik 4: "Coba buat satu data pemilih baru langsung lewat fitur Super Admin!"
* **Langkah Penyelesaian:**
  1. Login ke `/login` menggunakan akun Super Admin: `superadmin@evoting.test` / `password`.
  2. Buka menu **Manajemen Akun** $\rightarrow$ Tab **Daftar Pemilih (Siswa & Guru)**.
  3. Klik tombol **Tambah Pemilih Baru**.
  4. Masukkan Identitas: `20269999`, Nama: `Budi Asesor Testing`, Tipe: `SISWA`.
  5. Klik **Simpan**. Tunjukkan notifikasi sukses dan data baru muncul di tabel data pemilih.

---

## 5. DAFTAR ISTILAH TEKNIS KUNCI (CHEAT SHEET)

Gunakan istilah-istilah berikut dalam jawaban Anda untuk memberikan kesan profesional dan menguasai standar industri:

| Istilah | Konteks Penggunaan pada Projek VOTINGYUK |
| :--- | :--- |
| **ACID Transactions** | Memastikan simpan suara dan update status pemilih tereksekusi utuh (*Atomicity*). |
| **Pessimistic Locking (`lockForUpdate`)** | Mengunci baris data di MySQL agar tidak bisa dicoblos ganda secara bersamaan. |
| **Multi-Guard Authentication** | Memisahkan sistem login pengelola (`users`) dan pemilih siswa/guru (`voters`). |
| **LUBER Principle** | Menjamin kerahasiaan pemilih dengan meniadakan foreign key `voter_id` pada tabel `votes`. |
| **Eloquent ORM** | Object-Relational Mapper Laravel untuk interaksi database bebas celah SQL Injection. |
| **Bcrypt Hashing** | Algoritma hashing satu arah untuk melindungi kata sandi admin di database. |
| **CSRF Protection** | Token acak pada setiap form Blade (`@csrf`) untuk mencegah serangan *Cross-Site Request Forgery*. |
| **Automated Testing** | Pengujian fungsionalitas aplikasi dengan script PHPUnit (28 Test Cases). |
| **Mobile-First Responsive** | Desain UI Tailwind CSS yang diprioritaskan untuk layar smartphone pemilih. |
| **Digital Forensic Trail** | Pencatatan setiap aksi penting sistem pada tabel `audit_logs` untuk audit independen. |

---

*Dokumen ini disusun sebagai materi penguasaan kompetensi mandiri untuk menghadapi Asesmen Uji Sertifikasi Profesi (LSP P3) & Uji Kompetensi Keahlian (UKOM).*
