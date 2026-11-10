# 🎯 PANDUAN LENGKAP 11 UNIT KOMPETENSI SKKNI & PREDIKSI PERTANYAAN ASESOR
**Sistem E-Voting Sekolah (VOTINGYUK) | LSP P3 / UKOM Rekayasa Perangkat Lunak**

---

> ### 💡 CATATAN PENTING MENGENAI SKEMA:
> Dokumen Asesmen Mandiri mencantumkan beberapa unit bertuliskan *"Mobile"*. Berdasarkan hasil pembekalan RPS:
> - **Peserta bebas menggunakan Web, Desktop, atau Mobile**.
> - Sistem kita adalah **Aplikasi Web Modern dengan Mobile-First Responsive Design**. Siswa mencoblos langsung melalui smartphone (HP) di bilik suara dengan antarmuka yang sangat responsif dan ramah layar sentuh. Hal ini 100% sah dan memenuhi unit antarmuka mobile/web.

---

## 📑 DAFTAR 11 UNIT KOMPETENSI & PEMETAANNYA KE SISTEM

---

### UNIT 1: Menunjukkan Platform Operating System dan Bahasa Pemrograman
* **Kode Unit:** `J.612000.001`
* **Penerapan di Sistem Kita:**
  - **Bahasa Pemrograman:** PHP 8.3 (Backend) dan JavaScript Modern + HTML5/CSS3 (Frontend).
  - **Platform OS:** Cross-platform (berjalan di Windows saat development, dan Linux Ubuntu Server saat production).
  - **Runtime Web:** Apache/Nginx web server dengan database MySQL.
* **Prediksi Pertanyaan Asesor:**
  > *"Bahasa pemrograman dan platform apa yang Anda gunakan untuk membangun sistem ini, dan bagaimana spesifikasinya?"*
* **Jawaban Mudah & Tepat:**
  > *"Sistem ini dibangun menggunakan bahasa pemrograman **PHP versi 8.3** dengan framework **Laravel 13**. Di sisi tampilan (frontend), kami menggunakan **Blade Template Engine** bawaan Laravel yang dikombinasikan dengan **HTML5, Tailwind CSS v4 untuk styling responsif, serta JavaScript Native (Vanilla JS)** untuk interaksi realtime.*  
  > *Platform sistem ini bersifat cross-platform: saat ini dijalankan pada sistem operasi **Windows** dengan web server lokal dan database engine **MySQL**, serta siap di-deploy ke server berbasis **Linux** tanpa perubahan kode karena arsitektur Laravel yang terstandarisasi."*
* **File Bukti:** File `.env` dan `composer.json` (baris `"php": "^8.3"`).

---

### UNIT 2: Merancang Mobile Interface (Antarmuka Responsif Layar HP)
* **Kode Unit:** `J.612000.007`
* **Penerapan di Sistem Kita:**
  - Tampilan **Bilik Suara Siswa** dirancang menggunakan prinsip **Mobile-First Responsive** dengan Tailwind CSS.
  - Kartu kandidat berukuran besar, tombol coblos mudah ditekan jari layar sentuh, dan form login NIS tanpa scroll berlebih.
* **Prediksi Pertanyaan Asesor:**
  > *"Bagaimana Anda merancang antarmuka agar nyaman diakses oleh siswa melalui perangkat mobile (smartphone) di bilik suara?"*
* **Jawaban Mudah & Tepat:**
  > *"Saya menggunakan framework **Tailwind CSS v4** dengan pendekatan **Mobile-First Responsive**.*  
  > *Khusus di bilik suara (`voting-booth.blade.php`), elemen UI dibuat ramah sentuhan (touch-friendly): kartu kandidat memiliki padding yang pas, ukuran tombol 'Coblos' berukuran besar, dan tipografi jelas terbaca tanpa perlu zoom. Sistem juga dilengkapi meta tag viewport agar otomatis menyesuaikan skala layar HP siswa."*
* **File Bukti:** `resources/views/voter/voting-booth.blade.php` dan `resources/views/layouts/voter.blade.php` (ada `<meta name="viewport" content="width=device-width, initial-scale=1.0">`).

---

### UNIT 3: Menganalisis Tools Pengembangan
* **Kode Unit:** `J.620100.001.01`
* **Penerapan di Sistem Kita:**
  - **Local Development Environment:** **Laragon** (mengelola web server Apache/Nginx, PHP 8.3, dan MySQL 8.0).
  - **Code Editor (IDE):** Visual Studio Code (dengan ekstensi PHP Intelephense & Tailwind CSS IntelliSense).
  - **Version Control System (VCS):** Git & GitHub untuk pelacakan versi kode dan backup aman.
  - **Database Management Tool:** HeidiSQL / phpMyAdmin (bawaan Laragon) untuk inspeksi tabel langsung.
  - **API & HTTP Request Tester:** Postman (untuk uji coba endpoint dan simulasi payload).
  - **Browser Developer Tools:** Chrome/Edge DevTools untuk pengujian responsive viewport mobile & debugging console.
* **Prediksi Pertanyaan Asesor:**
  > *"Tools apa saja yang Anda gunakan selama proses pembuatan perangkat lunak ini dan apa alasan pemilihannya?"*
* **Jawaban Mudah & Tepat:**
  > *"Saya menganalisis dan memilih beberapa tools yang efisien untuk mendukung pengembangan sistem ini:*
  > 1. ***Laragon:*** *Sebagai lingkungan server lokal (WAMP stack). Alasan memilih Laragon dibanding XAMPP karena Laragon jauh lebih ringan, manajemen PHP 8.3 dan MySQL terisolasi dengan rapi, serta langsung terintegrasi dengan database client (HeidiSQL).*
  > 2. ***Visual Studio Code:*** *Sebagai code editor utama yang dilengkapi linter untuk mendeteksi error penulisan kode lebih cepat.*
  > 3. ***Git & GitHub:*** *Untuk version control, mencatat riwayat commit, dan menjamin keamanan kode sumber.*
  > 4. ***Postman:*** *Untuk menguji endpoint API, autentikasi sesi, dan validasi response sistem.*
  > 5. ***Chrome DevTools:*** *Untuk menguji tampilan responsif pada resolusi layar smartphone (mobile-view)."*
* **File Bukti:** File `.git/`, file `.env`, file `composer.json`, dan dokumentasi Postman di folder `docs/`.

---

### UNIT 4: Mengidentifikasi Library, Komponen, atau Framework
* **Kode Unit:** `J.620100.003.01`
* **Penerapan di Sistem Kita:**
  - **Framework Utama:** `laravel/framework` (v13.33.0).
  - **Library Waktu:** `nesbot/carbon` (bawaan Laravel untuk jadwal voting).
  - **Testing Framework:** `phpunit/phpunit` v12.
  - **CSS Framework:** `tailwindcss` v4.
  - **Asset Bundler:** `vite` v8.
* **Prediksi Pertanyaan Asesor:**
  > *"Jelaskan pustaka (library/framework) eksternal apa saja yang Anda pakai dan apa keuntungannya dibandingkan membuat sendiri dari nol?"*
* **Jawaban Mudah & Tepat:**
  > *"Kami menerapkan prinsip **lean dependency**—hanya menggunakan library esensial:*
  > 1. ***Laravel Framework:*** *Memberikan keamanan bawaan seperti proteksi CSRF, SQL Injection, dan struktur MVC.*
  > 2. ***Tailwind CSS & Vite:*** *Membuat antarmuka modern yang sangat ringan dan cepat di-compile.*
  > 3. ***PHPUnit:*** *Untuk pengujian otomatis tanpa harus mengetes manual berulang kali.*  
  > *Keuntungannya: menghemat waktu pengembangan, kode berstandar industri, dan keamanan sistem jauh lebih terjamin dibanding membuat dari nol."*
* **File Bukti:** `composer.json` dan `package.json`.

---

### UNIT 5: Menggunakan Struktur Data
* **Kode Unit:** `J.620100.004.02`
* **Penerapan di Sistem Kita:**
  - **Array Asosiatif:** Data validation rules, flash message session.
  - **Collection & Eloquent Object:** Pengurutan kandidat berdasarkan nomor urut (`orderBy('candidate_number', 'asc')`).
  - **JSON Object:** Response data real-time polling pada monitoring Quick Count.
* **Prediksi Pertanyaan Asesor:**
  > *"Di mana Anda menerapkan struktur data di kode program dan bagaimana cara mengaksesnya?"*
* **Jawaban Mudah & Tepat:**
  > *"Saya menerapkan struktur data di beberapa bagian:*
  > 1. ***Array Asosiatif:*** *Pada aturan validasi input di Controller (misal validasi login NIS dan input kandidat).*
  > 2. ***Eloquent Collection (Objek Terurut):*** *Saat mengambil data kandidat terurut dengan `$event->candidates()->orderBy('candidate_number')->get()`.*
  > 3. ***Format JSON:*** *Pada `MonitoringController.php` method `data()`, di mana data perolehan suara dikemas ke dalam struktur JSON terstruktur untuk dikonsumsi JavaScript di frontend."*
* **File Bukti:** `app/Http/Controllers/Petugas/MonitoringController.php` (method `data()`).

---

### UNIT 6: Mengimplementasikan User Interface (UI/UX)
* **Kode Unit:** `J.620100.005.02`
* **Penerapan di Sistem Kita:**
  - Sistem tampilan berbasis template engine **Blade**.
  - Modal pop-up konfirmasi sebelum mencoblos (mencegah salah klik).
  - Tab navigasi Kelola Akun (Super Admin, Petugas, Pemilih).
  - Filter dropdown dan pencarian data DPT.
* **Prediksi Pertanyaan Asesor:**
  > *"Tunjukkan bagaimana Anda mengimplementasikan alur antarmuka yang mencegah kesalahan aksi pengguna (human error)!"*
* **Jawaban Mudah & Tepat:**
  > *"Pada halaman bilik suara (`voting-booth.blade.php`), saat siswa mengklik tombol 'Coblos', sistem tidak langsung mengeksekusi suara, melainkan memunculkan **modal dialog konfirmasi** terlebih dahulu yang menanyakan: 'Apakah Anda yakin memilih Paslon X?'. Suara baru dikirim jika siswa menekan tombol 'Ya, Yakin'. Hal ini meminimalisir kesalahan salah klik pada layar sentuh."*
* **File Bukti:** `resources/views/voter/voting-booth.blade.php` (bagian dialog modal konfirmasi).

---

### UNIT 7: Mengimplementasikan Rancangan Entitas dan Keterkaitan (Database & Relasi)
* **Kode Unit:** `J.620100.007.01`
* **Penerapan di Sistem Kita:**
  - **Tabel Utama:** `users`, `voters`, `events`, `candidates`, `votes`, `event_participations`, `audit_logs`.
  - **Relasi:** One-to-Many (`users` $\rightarrow$ `events`, `events` $\rightarrow$ `candidates`), Pivot (`voters` $\leftrightarrow$ `events` via `event_participations`).
  - **Asas LUBER:** Tabel `votes` berdiri independen tanpa foreign key `voter_id`.
* **Prediksi Pertanyaan Asesor:**
  > *"Tunjukkan relasi antar tabel (Foreign Key) di database Anda dan jelaskan fungsinya!"*
* **Jawaban Mudah & Tepat:**
  > *"Saya menggunakan relasi Foreign Key di beberapa tabel:*
  > 1. *Tabel `candidates` memiliki foreign key `event_id` yang mengarah ke `events.id` (Relasi One-to-Many: 1 event pemilihan memiliki banyak kandidat).*
  > 2. *Tabel `events` memiliki foreign key `created_by` mengarah ke `users.id` (Mencatat petugas pembuat event).*
  > 3. *Tabel `event_participations` menghubungkan `voter_id` dan `event_id` sebagai bukti siswa sudah hadir memilih.*  
  > *Yang paling unik, tabel `votes` sengaja **tidak memiliki relasi ke tabel `voters`** demi menjamin asas kerahasiaan pilihan pemilih (LUBER)."*
* **File Bukti:** `database/migrations/2026_09_26_000003_create_candidates_table.php` (ada `$table->foreignId('event_id')->constrained()`).

---

### UNIT 8: Menerapkan Perintah Eksekusi Bahasa Berbasis Teks, Grafik, dan Multimedia
* **Kode Unit:** `J.620100.010.01`
* **Penerapan di Sistem Kita:**
  - Eksekusi skrip via CLI Artisan (`php artisan migrate`, `php artisan serve`).
  - Penanganan berkas **Multimedia** (Upload foto kandidat via `Storage::disk('public')`).
  - Penampilan gambar dinamis dengan pengecekan ketersediaan file fisik.
* **Prediksi Pertanyaan Asesor:**
  > *"Bagaimana sistem menangani input multimedia seperti foto kandidat dan bagaimana eksekusinya?"*
* **Jawaban Mudah & Tepat:**
  > *"Di Controller `CandidateController`, upload foto divalidasi tipe filenya (`image|mimes:jpeg,png,jpg|max:2048`). Berkas disimpan ke folder penyimpanan publik menggunakan `Storage::disk('public')->putFile()`.*  
  > *Lalu pada Model `Candidate`, ada method accessor `getPhotoUrlAttribute()` yang otomatis mengecek apakah file fisiknya ada di storage sebelum menghasilkan URL publik, sehingga mencegah broken image di browser."*
* **File Bukti:** `app/Http/Controllers/Petugas/CandidateController.php` (method `store()`) dan `app/Models/Candidate.php` (method `getPhotoUrlAttribute()`).

---

### UNIT 9: Melakukan Instalasi Software Tools Pemrograman
* **Kode Unit:** `J.620100.011.01`
* **Penerapan di Sistem Kita:**
  - Prosedur instalasi PHP 8.3, Composer, Node.js, Git, dan MySQL.
* **Prediksi Pertanyaan Asesor:**
  > *"Bagaimana langkah awal jika aplikasi ini ingin di-install dan dijalankan di komputer yang baru?"*
* **Jawaban Mudah & Tepat:**
  > *"Langkahnya sangat terstruktur:*
  > 1. *Pastikan terinstall **PHP 8.3, Composer, Node.js, dan MySQL**.*
  > 2. *Clone repositori Git, lalu masuk ke folder project.*
  > 3. *Jalankan `composer install` untuk mengunduh library PHP.*
  > 4. *Jalankan `npm install` dan `npm run build` untuk meng-compile asset CSS/JS.*
  > 5. *Salin file `.env.example` menjadi `.env`, lalu jalankan `php artisan key:generate`.*
  > 6. *Konfigurasi database di `.env`, lalu jalankan migrasi: `php artisan migrate --seed`.*
  > 7. *Jalankan server lokal dengan `php artisan serve`."*

---

### UNIT 10: Melakukan Pengaturan Software Tools Pemrograman
* **Kode Unit:** `J.620100.012.01`
* **Penerapan di Sistem Kita:**
  - Konfigurasi environment di file `.env`.
  - Konfigurasi autentikasi multi-guard di `config/auth.php`.
  - Konfigurasi bundler asset di `vite.config.js`.
* **Prediksi Pertanyaan Asesor:**
  > *"Di mana Anda mengatur konfigurasi database dan environment sistem?"*
* **Jawaban Mudah & Tepat:**
  > *"Semua pengaturan sensitif dan environment aplikasi diatur terpusat di file **`.env`**, mencakup nama database (`DB_DATABASE`), username, password, dan port server. Laravel membaca file ini secara otomatis saat boot.*  
  > *Selain itu, konfigurasi otentikasi dipisahkan di file `config/auth.php` untuk membedakan guard `web` (panitia) dan guard `voter` (siswa)."*
* **File Bukti:** File `.env` dan `config/auth.php`.

---

### UNIT 11: Menerapkan Metode Penggunaan Kembali (Reusable Code)
* **Kode Unit:** `J.620100.014.01`
* **Penerapan di Sistem Kita:**
  - Static method `AuditLog::record()`.
  - Helper method di Model Event (`isPublished()`, `isDraft()`).
  - Layout Template Blade (`layouts/admin.blade.php`, `layouts/petugas.blade.php`).
* **Prediksi Pertanyaan Asesor:**
  > *"Tunjukkan fungsi (subrutin) buatan Anda sendiri yang bersifat reusable (dipakai berulang kali)!"*
* **Jawaban Mudah & Tepat:**
  > *"Contoh terbaik adalah fungsi `AuditLog::record($action, $description)` di file `app/Models/AuditLog.php`.*  
  > *Fungsi ini saya buat satu kali, tetapi dipanggil berulang kali di berbagai tempat: dipanggil saat Petugas membuat event, dipanggil saat menghapus data, dan dipanggil saat siswa berhasil menyalurkan suara di bilik suara.*  
  > *Dengan demikian, saya tidak perlu menulis ulang logika penyimpanan log aktivitas di setiap controller."*
* **File Bukti:** `app/Models/AuditLog.php` (baris 32) dan pemanggilannya di `VotingBoothController.php` (baris 172).

---

## 🔥 5 PERTANYAAN PALING KRUSIAL (WAJIB DIHAFAL / DIKUASAI MATI-MATIAN)

Ini adalah 5 pertanyaan yang **hampir pasti ditanyakan oleh asesor** dan menjadi tolok ukur utama kelulusan Anda:

---

### 1. Pertanyaan Kerahasiaan Pemilu (LUBER):
> **Asesor:** *"Apakah Admin atau Petugas bisa melihat siapa memilih siapa di database?"*
* **Jawaban Wajib:**  
  > *"Tidak bisa sama sekali. Karena tabel `votes` **sama sekali tidak memiliki kolom `voter_id`**. Tabel suara hanya mencatat `event_id` dan `candidate_id`. Sedangkan bukti siswa sudah memilih dicatat terpisah di tabel `event_participations`. Keduanya tidak berelasi, sehingga pilihan pemilih 100% anonim dan rahasia."*
* **Tunjukkan:** `database/migrations/2026_09_26_000005_create_votes_table.php`.

---

### 2. Pertanyaan Anti-Curang (Double Voting):
> **Asesor:** *"Bagaimana jika ada siswa nakal membuka 2 tab browser sekaligus lalu menekan tombol coblos bersamaan?"*
* **Jawaban Wajib:**  
  > *"Proses pencoblosan dibungkus dalam **`DB::transaction()`** dengan **`lockForUpdate()`** pada tabel `event_participations`. Request pertama akan mengunci data pemilih sementara. Request kedua yang masuk di detik yang sama dipaksa mengantre. Begitu request pertama selesai dan statusnya jadi 'sudah memilih', request kedua otomatis ditolak oleh sistem dengan exception."*
* **Tunjukkan:** `app/Http/Controllers/Voter/VotingBoothController.php` baris 136–160.

---

### 3. Pertanyaan Foreign Key & Relasi:
> **Asesor:** *"Tunjukkan di mana letak relasi antar tabel di database Anda!"*
* **Jawaban Wajib:**  
  > *"Salah satu contohnya di tabel `candidates`. Terdapat foreign key `event_id` yang terhubung ke `id` pada tabel `events` dengan opsi `onDelete('cascade')`. Artinya satu event memiliki banyak kandidat, dan jika event dihapus maka kandidat di dalamnya otomatis ikut terhapus."*
* **Tunjukkan:** `database/migrations/2026_09_26_000003_create_candidates_table.php`.

---

### 4. Pertanyaan Multi-Guard Login:
> **Asesor:** *"Kenapa login siswa tidak pakai password dan bagaimana Laravel memisahkan sesinya dengan Admin?"*
* **Jawaban Wajib:**  
  > *"Kami menggunakan fitur **Multi-Guard Authentication** di `config/auth.php`. Guard `web` untuk Admin/Petugas berbasis email dan password, sedangkan guard `voter` untuk Siswa berbasis NIS tanpa password.*  
  > *Siswa tidak memakai password demi efisiensi waktu antrean saat hari pemilihan di bilik suara, di mana verifikasi fisik dilakukan langsung oleh panitia di TPS."*
* **Tunjukkan:** `config/auth.php` (bagian `guards` dan `providers`).

---

### 5. Pertanyaan Reusable Function:
> **Asesor:** *"Mana fungsi buatan Anda yang dipakai berulang-ulang di sistem ini?"*
* **Jawaban Wajib:**  
  > *"Fungsi `AuditLog::record()` di file `app/Models/AuditLog.php`. Fungsi ini dipakai berulang kali di seluruh controller untuk mencatat jejak audit (siapa melakukan apa, kapan, dan dari IP mana) secara otomatis."*
* **Tunjukkan:** `app/Models/AuditLog.php`.
