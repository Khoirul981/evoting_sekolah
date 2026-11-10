# 🎤 PANDUAN LENGKAP PRESENTASI & NASKAH DIALOG ASESMEN LSP P3 / UKOM
**Sistem E-Voting Pemilihan Ketua OSIS & MPK Berbasis Web**  
**Framework: Laravel 13 (v13.33.0) | PHP: 8.3.33 | Database: MySQL | Styling: Tailwind CSS v4**

---

## 📌 DAFTAR ISI
1. [Persiapan Sebelum Tampil (Pre-Flight Checklist)](#1-persiapan-sebelum-tampil-pre-flight-checklist)
2. [Rundown Waktu & Alur Presentasi](#2-rundown-waktu--alur-presentasi)
3. [Naskah Dialog & Panduan Aksi Demo (Word-by-Word Script)](#3-naskah-dialog--panduan-aksi-demo-word-by-word-script)
   - [Bab 1: Pembukaan & Latar Belakang](#bab-1-pembukaan--latar-belakang-durasi-1-2-menit)
   - [Bab 2: Demo Role Super Admin](#bab-2-demo-role-super-admin-durasi-2-3-menit)
   - [Bab 3: Demo Role Petugas / Panitia](#bab-3-demo-role-petugas--panitia-durasi-3-menit)
   - [Bab 4: Demo Bilik Suara Pemilih (Voter)](#bab-4-demo-bilik-suara-pemilih-voter-durasi-3-menit)
   - [Bab 5: Demo Live Monitoring & Quick Count](#bab-5-demo-live-monitoring--quick-count-durasi-2-menit)
   - [Bab 6: Pembuktian Teknis & Keamanan (Bedah Kode)](#bab-6-pembuktian-teknis--keamanan-bedah-kode-durasi-2-menit)
   - [Bab 7: Penutup](#bab-7-penutup-durasi-30-detik)
4. [Antisipasi Pertanyaan Kritis Asesor & Jawaban Skakmat](#4-antisipasi-pertanyaan-kritis-asesor--jawaban-skakmat)
5. [Tips & Trik Menghadapi Asesor](#5-tips--trik-menghadapi-asesor)

---

## 1. Persiapan Sebelum Tampil (Pre-Flight Checklist)

Sebelum dipanggil masuk oleh asesor, pastikan lingkungan pengujian sudah siap 100%:

### A. Terminal & Server Lokal
Pastikan development server sudah running di port 8000:
```powershell
# Buka PowerShell di folder project
cd c:\ProjectLaravel\evoting_sekolah
php artisan serve
```
Pastikan database sudah terisi data seeder awal:
```powershell
php artisan migrate:fresh --seed
```

### B. Siapkan 3 Jendela / Tab Browser
Untuk kelancaran demo tanpa bolak-balik logout:
- **Tab 1 (Browser Utama / Chrome):** Halaman Login Panitia (`http://127.0.0.1:8000/login`)  
  *Gunakan untuk login Super Admin: `admin@sekolah.sch.id` / `password`*
- **Tab 2 (Jendela Incognito / Edge / Firefox):** Halaman Login Panitia (`http://127.0.0.1:8000/login`)  
  *Gunakan untuk login Petugas: `petugas@sekolah.sch.id` / `password` (buka layar Live Monitoring)*
- **Tab 3 (Browser Lain / Jendela Private):** Halaman Login Pemilih (`http://127.0.0.1:8000/voter/login`)  
  *Gunakan untuk simulasi mencoblos siswa: NIS `2024001`*

### C. Editor VS Code
- Buka file penting yang siap ditunjukkan jika asesor minta melihat kode:
  1. `app/Http/Controllers/Voter/VotingBoothController.php` (Logika transaksi coblos & anti double-voting)
  2. `database/migrations/2024_01_01_000004_create_votes_table.php` (Bukti struktur LUBER tanpa voter_id)
  3. Terminal siap jalankan `php artisan test`

---

## 2. Rundown Waktu & Alur Presentasi

| No | Segmen | Durasi | Fokus Demonstrasi |
|:---:|---|:---:|---|
| **1** | Pembukaan & Latar Belakang | 1.5 Menit | Salam, perkenalan, urgensi masalah, solusi sistem |
| **2** | Demo Role: Super Admin | 2.5 Menit | Kelola Petugas, kelola DPT (Siswa/Guru), Audit Log |
| **3** | Demo Role: Petugas | 3 Menit | Buat event, Room Code, siklus status DRAFT→OPEN, input kandidat |
| **4** | Demo Role: Pemilih (Voter) | 3 Menit | Login NIS tanpa password, input kode ruang, bilik suara, anti-double voting |
| **5** | Demo Live Quick Count | 2 Menit | Realtime AJAX polling perolehan suara, LUBER recent voters |
| **6** | Bedah Kode & Keamanan | 2 Menit | Transaksi database ACID, struktur tabel rahasia, PHPUnit test 28 passed |
| **7** | Penutup & Sesi Q&A | 1 Menit | Kesimpulan & penyerahan ke asesor |
| **TOTAL** | | **~15 Menit** | |

---

## 3. Naskah Dialog & Panduan Aksi Demo (Word-by-Word Script)

---

### Bab 1: Pembukaan & Latar Belakang (Durasi: 1 - 2 Menit)

> **[Aksi Layar]:** Tampilkan halaman browser di URL `http://127.0.0.1:8000/` atau landing page aplikasi. Posisi berdiri tegak, kontak mata sopan ke asesor.

**Naskah yang Anda Ucapkan:**
> *"Selamat pagi / siang kepada Bapak dan Ibu Asesor yang saya hormati.*
>
> *Perkenalkan, nama saya **[Sebutkan Nama Lengkap Anda]**. Pada kesempatan asesmen uji kompetensi keahlian Rekayasa Perangkat Lunak kali ini, saya akan mempresentasikan proyek sistem yang telah saya rancang dan bangun, yaitu: **Sistem E-Voting Sekolah Berbasis Web**.*
>
> *Latar belakang pembuatan aplikasi ini bermula dari permasalahan proses pemilihan konvensional di sekolah—seperti pemilihan Ketua OSIS, MPK, maupun perwakilan guru—yang selama ini menggunakan surat suara kertas. Proses manual tersebut memerlukan biaya pencetakan yang besar, memakan waktu rekapitulasi hitung suara berjam-jam, dan memiliki risiko tinggi terhadap surat suara rusak serta potensi human error saat penghitungan suara.*
>
> *Untuk menjawab masalah tersebut, saya mengembangkan sistem E-Voting modern berbasis web menggunakan framework **Laravel 13 (v13.33.0)**, database **MySQL**, dan antarmuka responsif berbasis **Tailwind CSS v4**. Sistem ini menerapkan prinsip pemilu **LUBER JURDIL** (Langsung, Umum, Bebas, Rahasia, Jujur, dan Adil) yang dijamin langsung secara arsitektur database dan logika sistem.*
>
> *Sistem ini memiliki 3 level pengguna utama: **Super Admin**, **Petugas Pemilihan**, dan **Pemilih (Voter)**. Izinkan saya mendemonstrasikan alur kerja sistem ini dari sudut pandang masing-masing peran."*

---

### Bab 2: Demo Role Super Admin (Durasi: 2 - 3 Menit)

> **[Aksi Layar]:** Buka Tab 1 (`http://127.0.0.1:8000/login`). Masukkan email `admin@sekolah.sch.id` dan password `password`. Klik **Masuk**. Masuk ke dashboard Admin.

**Naskah yang Anda Ucapkan:**
> *"Pertama, saya masuk ke dalam sistem sebagai **Super Admin**.*
>
> *Super Admin memegang peran tata kelola administratif tertinggi. Tugas utamanya mencakup manajemen data master pengguna dan pengawasan integritas sistem.*
>
> *(Tunjukkan halaman Beranda Super Admin)*
>
> *Setelah berhasil login, Super Admin akan disambut dengan **Dashboard Beranda** yang menampilkan ringkasan statistik sistem secara komprehensif. Di sini terdapat:*
> 1. ***Widget Metrik Utama:*** *Jumlah total Super Admin terdaftar, total Petugas aktif, total Pemilih Siswa (NIS), dan total Pemilih Guru (NIP).*
> 2. ***Keterangan Wewenang Role Sistem:*** *Penjelasan singkat fungsi setiap role—Super Admin mengelola master data dan Petugas, Petugas mengelola event dan kandidat, serta Pemilih yang menggunakan hak suara.*
> 3. ***Informasi Lingkungan Teknis:*** *Sistem secara otomatis menampilkan versi Database Engine (MySQL), versi PHP yang digunakan, versi Framework Laravel, **metode enkripsi AES-256-CBC** yang digunakan Laravel untuk melindungi session dan data sensitif, dan zona waktu server (WIB).*
> 4. ***Log Keamanan & Aktivitas Terkini:*** *Bagian bawah dashboard menampilkan 6 aktivitas terakhir yang dicatat oleh sistem Audit Log—mencakup waktu kejadian, nama pengguna yang melakukan aksi, jenis aksi (contoh: CREATE_EVENT, DELETE_VOTER, vote_cast), dan deskripsi lengkap aktivitas.*
>
> *Dashboard ini memberikan pandangan menyeluruh (bird's eye view) terhadap kesehatan dan status operasional sistem secara real-time.*
>
> *(Klik menu **Kelola Akun**)*
>
> *Di menu Kelola Akun Pengguna, terdapat sistem **tab navigasi yang terorganisir rapi** untuk memudahkan pengelolaan berbagai jenis pengguna.*
>
> *(Tunjukkan Tab 'Super Admin')*  
> *Tab pertama adalah **Super Admin**. Di sini Super Admin dapat mengelola sesama akun Super Admin lainnya—termasuk menambah, mengubah data, atau menghapus akun Super Admin.*
>
> *(Klik Tab 'Petugas')*  
> *Tab kedua adalah **Petugas**. Super Admin mengelola akun-akun Petugas Pemilihan atau Panitia yang bertanggung jawab menjalankan event pemilihan di lapangan. Super Admin dapat membuat akun petugas baru, mengedit profil petugas, atau menonaktifkan akun jika diperlukan.*
>
> *(Klik Tab 'Pemilih')*  
> *Tab ketiga adalah **Pemilih**, yaitu manajemen **Daftar Pemilih Tetap (DPT)**.*  
> *Pada sistem ini, pemilih diklasifikasikan menjadi dua entitas berdasarkan badge tipe:*
> 1. *Pemilih bertipe **SISWA** (ditandai badge biru), diidentifikasi menggunakan **Nomor Induk Siswa (NIS)**.*
> 2. *Pemilih bertipe **GURU** (ditandai badge oranye), diidentifikasi menggunakan **Nomor Induk Pegawai (NIP)**.*
>
> *Super Admin dapat memfilter pemilih berdasarkan tipe menggunakan dropdown 'Semua Tipe', 'Siswa', atau 'Guru'. Sistem juga dilengkapi fitur pencarian cepat berdasarkan nama atau nomor NIS/NIP untuk mempermudah validasi data DPT.*
>
> *Hal ini menjamin validitas bahwa hanya pemilih terdaftar resmi yang berhak memberikan suara di lingkungan sekolah.*
>
> *(Klik menu **Audit Log**)*
>
> *Super Admin juga memiliki akses ke modul **Audit Log**. Setiap aksi krusial yang terjadi di dalam aplikasi—seperti saat petugas membuat event pemilihan baru, mengubah status bilik suara, atau menghapus kandidat—akan dicatat secara otomatis oleh sistem lengkap dengan nama pelaku, jenis aksi, deskripsi, alamat IP, dan stempel waktu (timestamp). Fitur ini memenuhi aspek transparansi dan akuntabilitas sistem."*

---

### Bab 3: Demo Role Petugas / Panitia (Durasi: 3 Menit)

> **[Aksi Layar]:** Buka Tab 2 (`http://127.0.0.1:8000/login`). Masukkan email `petugas@sekolah.sch.id` dan password `password`. Klik **Masuk**. Tampilkan halaman Dashboard Petugas (`/petugas/events`).

**Naskah yang Anda Ucapkan:**
> *"Sekarang kita beralih ke peran kedua, yaitu **Petugas Pemilihan**.*
>
> *Petugas bertugas mengorganisir teknis jalannya acara pemilihan di lapangan.*
>
> *(Tunjukkan halaman Panel Manajemen Pemilihan)*
>
> *Setelah login, Petugas disambut dengan **Panel Manajemen Pemilihan** yang menampilkan greeting personal 'Selamat Datang, Petugas OSIS 1!' beserta penjelasan singkat tugas petugas: kelola agenda pemilihan sekolah, daftarkan kandidat & opsi voting, publikasikan ruang pemilihan, dan pantau rekapitulasi suara secara real-time.*
>
> *Di halaman ini, Petugas dapat melihat **daftar kartu event pemilihan** yang telah dibuat, lengkap dengan informasi lengkap:*
> - **Badge Status**: Draft (kuning), OPSI (oranye), KANDIDAT (biru), atau Selesai/Ditutup (abu-abu)
> - **Kode Ruang Voting**: Contohnya `JAMWG0`, `TOUR45`, `KETOS26` yang akan digunakan pemilih untuk masuk bilik suara
> - **Judul Event**: Misalnya 'Pemilihan Presiden & Wakil Presiden Indonesia Periode 2024-2029', 'Pemilihan Liburan Study Tour 1945', atau 'Pemilihan Ketua OSIS Perioade 2026-2027'
> - **Statistik Real-time**: Jumlah kandidat/opsi terdaftar dan perolehan suara terkini
> - **Jadwal**: Waktu mulai dan selesai pemilihan, status berjalan (Manual/Langsung atau Dijadwalkan Otomatis)
> - **Action Button**: Tombol Salin kode ruang, Kandidat/Opsi, Live Monitor, Edit, dan Hapus
>
> *Sistem juga dilengkapi **filter dan pencarian canggih** untuk memudahkan petugas mengelola banyak event sekaligus—bisa filter berdasarkan status event, kategori (Kandidat vs Opsi), atau pencarian berdasarkan judul, kode ruang, dan deskripsi.*
>
> 1. ***Membuat Event Pemilihan Baru:***  
>    *(Klik tombol **Buat Event Baru** di kanan atas)*  
>    *Petugas menginput judul event, misalnya 'Pemilihan Ketua OSIS 2024/2025', deskripsi kegiatan, kategori pemilihan (Kandidat atau Opsi), waktu mulai dan selesai, serta menentukan **Room Code (Kode Ruang)** yang unik, contohnya `OSIS2024`.*
>
> 2. ***Memahami Siklus Status Event:***  
>    *Event pemilihan dirancang memiliki 3 fase siklus hidup yang ketat:*
>    - **Fase DRAFT**: *Fase persiapan. Petugas mendaftarkan kandidat dan memeriksa kesiapan. Pada fase ini pemilih belum dapat masuk mencoblos.*
>    - **Fase OPEN** (badge merah 'KANDIDAT' atau 'OPSI'): *Bilik suara resmi dibuka. Pemilih dapat memasukkan kode ruang dan memberikan suaranya.*
>    - **Fase CLOSED** (badge abu-abu 'EVENT DITUTUP'): *Pemilihan telah berakhir. Bilik suara dikunci permanen, tidak ada suara baru yang bisa masuk, dan hasil akhir dikunci.*
>
> 3. ***Manajemen Data Pasangan Calon (Kandidat):***  
>    *(Klik tombol 'Kandidat (2)' atau 'Opsi (2)' pada salah satu kartu event)*  
>    *Petugas mendaftarkan kandidat dengan nomor urut, nama pasangan calon (Ketua dan Wakil), upload file foto resmi, serta rincian visi dan misi.*
>
> *(Tunjukkan tombol status 'Otomatis Sesuai Jadwal' atau 'TUTUP EVENT')*  
> *Setelah kandidat siap, Petugas dapat mengubah pengaturan jadwal otomatis atau menutup event secara manual. Dengan demikian, bilik suara secara resmi telah dibuka untuk para pemilih."*

---

### Bab 4: Demo Bilik Suara Pemilih (Voter) (Durasi: 3 Menit)

> **[Aksi Layar]:** Buka Tab 3 (Private Window) di URL `http://127.0.0.1:8000/voter/login`.

**Naskah yang Anda Ucapkan:**
> *"Sekarang mari kita masuk ke fitur inti dari aplikasi ini, yaitu **Bilik Suara Pemilih (Voter Voting Booth)**.*
>
> *Di sisi autentikasi, aplikasi ini menggunakan arsitektur **Multi-Guard Authentication** bawaan Laravel. Untuk panitia menggunakan guard `web`, sedangkan untuk pemilih menggunakan guard tersendiri bernama `voter`.*
>
> 1. ***Login Cepat & Ramah Siswa (Tanpa Password):***  
>    *(Ketik NIS: `2024001` di kolom identifier, lalu klik Masuk)*  
>    *Dapat Bapak/Ibu Asesor perhatikan, form login pemilih **hanya meminta nomor NIS atau NIP**, tanpa memerlukan password.*
>    *Keputusan desain ini diambil berdasarkan pertimbangan operasional riil di bilik suara sekolah: saat hari pemilihan, ratusan siswa bergantian mencoblos dalam waktu terbatas. Jika menggunakan password, antrean akan terhambat oleh siswa yang lupa password. Validitas pemilih di bilik suara diverifikasi secara fisik oleh kartu pelajar atau daftar hadir panitia.*
>
> 2. ***Dashboard Pemilih & Daftar Event Sedang Berlangsung:***  
>    *(Tampil halaman Dashboard Pemilih dengan sambutan personal, kartu identitas NIS/NIP, badge DPT Terverifikasi, serta widget status partisipasi)*  
>    *Di halaman dashboard ini, pemilih dapat melihat seluruh **Daftar Event Pemilihan yang Sedang Berlangsung** yang diselenggarakan oleh seluruh petugas sekolah—misalnya Pemilihan Ketua OSIS dan Pemilihan Ketua MPK.*  
>    *Setiap kartu event menampilkan informasi transparan: nama petugas penyelenggara, jumlah kandidat/opsi, batas waktu, dan status pemilihan.*  
>    *Namun, sesuai regulasi keamanan bilik suara, pemilih **tidak bisa langsung mencoblos sembarangan**. Setiap event mewajibkan pemilih untuk **memasukkan Kode Room resmi** yang diberikan oleh petugas TPS event tersebut.*  
>    *(Ketik kode room `OSIS26` pada kartu event Pemilihan Ketua OSIS, lalu klik 'Ikut Vote')*  
>    *Sistem memvalidasi kesesuaian kode room dengan event tersebut. Jika valid, bilik suara langsung terbuka.*  
>
> 3. ***Tampilan Bilik Suara Digital:***  
>    *(Tampil kartu kandidat interaktif)*  
>    *Sistem menampilkan kartu kandidat nomor urut 1 dan nomor urut 2, lengkap dengan foto, nama calon, visi-misi, dan tombol Coblos.*
>
> 4. ***Aksi Pencoblosan (Voting):***  
>    *(Klik tombol Coblos pada Kandidat 1 -> Muncul konfirmasi modal -> Klik 'Ya, Yakin')*  
>    *Setelah mengonfirmasi pilihan, request diproses dan sistem menampilkan layar sukses: 'Terima Kasih Atas Partisipasi Anda!'.*
>
> 5. ***Pembuktian Anti Double-Voting & Riwayat Partisipasi:***  
>    *(Kembali ke Dashboard Pemilih)*  
>    *Dapat dilihat, pada kartu event yang telah dicoblos, statusnya otomatis berganti menjadi badge hijau **'Sudah Memilih / Hak Suara Telah Digunakan'**, form input kode room dikunci, dan muncul dua tombol aksi: **'Live Perolehan Suara'** serta **'Bukti Suara'**. Riwayat partisipasi juga tercatat di bagian bawah dashboard.*  
>    *Menariknya, pemilih yang telah berpartisipasi kini dapat mengklik tombol **'Live Perolehan Suara'** untuk memantau pergerakan rekapitulasi suara (quick count) secara realtime sama seperti di layar monitoring petugas—lengkap dengan persentase kandidat, pilihan unggul/seri, dan audit stream partisipasi.*  
>    *Namun, jika pemilih belum berpartisipasi pada event tertentu, akses ke perolehan suara akan dibatasi demi integritas pemilihan.*  
>    *Jika pemilih mencoba mengakses kembali bilik suara atau mencoblos ulang, sistem secara otomatis menolak dengan notifikasi: **'Anda sudah menggunakan hak suara pada event ini'**. Ini membuktikan prinsip 1 pemilih hanya memiliki 1 hak suara."*

---

### Bab 5: Demo Live Monitoring & Quick Count (Durasi: 2 Menit)

> **[Aksi Layar]:** Beralih kembali ke Tab 2 (Layar Petugas), klik tombol **Live Monitor / Quick Count** pada event `OSIS2024`.

**Naskah yang Anda Ucapkan:**
> *"Sembari proses pemilihan berlangsung di bilik suara, panitia dan saksi dapat memantau jalannya penghitungan suara secara langsung melalui fitur **Live Monitoring & Quick Count**.*
>
> *(Tunjukkan grafik / bar perolehan suara yang baru saja bertambah)*
>
> *Fitur ini bekerja secara asinkron menggunakan teknik **Periodic AJAX Polling** ke endpoint internal JSON (`/petugas/events/{id}/monitor/data`) setiap beberapa detik tanpa perlu me-refresh seluruh halaman browser.*
>
> *Informasi yang tersaji secara komprehensif mencakup:*
> 1. *Total DPT pemilih yang terdaftar di sistem.*
> 2. *Total suara sah yang sudah masuk.*
> 3. *Tingkat partisipasi pemilih dalam bentuk persentase.*
> 4. *Perolehan suara dan grafik persentase setiap pasangan calon.*
> 5. *Indikator hasil perolehan suara (apakah ada calon unggul atau kondisi seri).*
> 6. *Daftar **Aktivitas Pemilih Terbaru (Recent Voters)**: menampilkan nama siswa dan jam pencoblosan.*
>
> *(Tekankan poin ini)*  
> *Perlu digarisbawahi bahwa pada daftar pemilih terbaru, sistem **hanya mencatat jam kehadiran pemilih**, dan **sama sekali tidak menampilkan kandidat mana yang dipilih**. Ini menjaga privasi pemilih di hadapan saksi dan panitia."*

---

### Bab 6: Pembuktian Teknis & Keamanan (Bedah Kode) (Durasi: 2 Menit)

> **[Aksi Layar]:** Buka editor VS Code, tunjukkan kode dan jalankan pengujian di terminal.

**Naskah yang Anda Ucapkan:**
> *"Bapak dan Ibu Asesor, selain fungsionalitas antarmuka, sistem ini dibangun dengan standar rekayasa perangkat lunak dan keamanan data yang matang:*
>
> 1. ***Penerapan Asas Rahasia (LUBER) pada Skema Database:***  
>    *(Buka file migration `create_votes_table.php`)*  
>    *Pada implementasi konvensional pemula, seringkali kolom `voter_id` ditaruh di dalam tabel `votes`. Hal tersebut merusak asas rahasia karena siapa pun yang memiliki akses ke database MySQL dapat melacak pilihan siswa.*  
>    *Pada sistem ini, tabel `votes` **hanya menyimpan `event_id` dan `candidate_id`**. Suara bersifat sepenuhnya anonim.*  
>    *Pencegahan suara ganda ditangani oleh tabel terpisah yaitu `event_participations` yang hanya mencatat bahwa pemilih tersebut sudah hadir mencoblos tanpa menyimpan kandidat pilihannya.*
>
> 2. ***Penanganan Masalah Konkurensi & Race Condition:***  
>    *(Buka `VotingBoothController.php`)*  
>    *Proses perekaman suara dibungkus di dalam **Database Transaction** (`DB::transaction`) dan menggunakan teknik **Pessimistic Locking** (`lockForUpdate`). Jika ada serangan script otomatis yang mencoba submit berulang kali pada milidetik yang sama, transaksi diproses secara atomic (ACID) sehingga duplikasi suara mustahil terjadi.*
>
> 3. ***Jaminan Kualitas Kode Melalui Automated Testing:***  
>    *(Jalankan perintah di terminal VS Code: `php artisan test`)*  
>    *Seluruh alur bisnis, autentikasi multi-guard, hak akses otorisasi role, dan skenario kecurangan pemilihan telah diuji menggunakan automated feature test.*  
>    *(Tunjukkan hasil terminal hijau: **28 passed, 121 assertions**)*  
>    *Seluruh 28 unit pengujian lulus 100% tanpa ada kegagalan."*

---

### Bab 7: Penutup (Durasi: 30 Detik)

> **[Aksi Layar]:** Kembali ke halaman utama aplikasi atau slide kesimpulan.

**Naskah yang Anda Ucapkan:**
> *"Demikian pemaparan demonstrasi Sistem E-Voting Sekolah Berbasis Web yang telah saya bangun.*
>
> *Sistem ini telah memenuhi seluruh unit kompetensi dalam skema sertifikasi, mencakup perancangan arsitektur perangkat lunak, perancangan database relasional yang aman, implementasi antarmuka web responsif, hingga pengujian fungsionalitas otomatis.*
>
> *Terima kasih banyak atas waktu dan perhatian Bapak/Ibu Asesor. Saya siap menerima pertanyaan, masukan, maupun evaluasi teknis lebih lanjut. Terima kasih."*

---

## 4. Antisipasi Pertanyaan Kritis Asesor & Jawaban Skakmat

Berikut adalah pertanyaan yang **paling sering ditanyakan asesor pada skema RPL/Web Developer** beserta jawaban taktis:

---

### ❓ Pertanyaan 1: "Mengapa login pemilih (siswa/guru) tidak menggunakan password?"
> **💡 Jawaban Anda:**
> *"Keputusan ini didasarkan pada analisis kebutuhan operasional nyata di sekolah:*
> 1. *Pada hari pemilihan, ratusan siswa bergantian di bilik suara dalam waktu sempit. Jika menggunakan password, akan terjadi antrean panjang akibat siswa lupa password atau salah mengetikkan karakter.*
> 2. *Keamanan tetap terjamin karena pemilihan dilakukan di area fisik terkontrol di hadapan panitia KPPS, dan sistem secara ketat membatasi: 1 NIS hanya bisa mengakses event dengan Room Code yang valid dan hanya bisa mencoblos tepat 1 kali.*
> 3. *Sementara untuk akses manajerial sistem seperti Super Admin dan Petugas, sistem tetap menerapkan email dan password dengan enkripsi Bcrypt berstandar industri."*

---

### ❓ Pertanyaan 2: "Bagaimana Anda membuktikan bahwa suara pemilih benar-benar rahasia jika admin membuka phpMyAdmin?"
> **💡 Jawaban Anda:**
> *(Tunjukkan diagram relasi atau file migration)*
> *"Kerahasiaan dijamin oleh pemisahan dua tabel yang tidak memiliki foreign key saling mengikat:*
> - *Tabel `votes` hanya menyimpan: `id`, `event_id`, `candidate_id`, dan `created_at`.*
> - *Tabel `event_participations` hanya menyimpan: `id`, `voter_id`, `event_id`, dan `voted_at`.*
>
> *Sekalipun Super Admin melakukan query langsung di MySQL, tidak ada foreign key atau kolom yang menghubungkan baris pilihan kandidat dengan identitas siswa. Dengan demikian, asas **Rahasia** terpenuhi 100% secara arsitektur data."*

---

### ❓ Pertanyaan 3: "Jelaskan penerapan konsep MVC pada aplikasi ini!"
> **💡 Jawaban Anda:**
> *"Aplikasi ini menerapkan pola arsitektur **Model-View-Controller (MVC)**:*
> - ***Model:*** *Mewakili struktur data, relasi Eloquent, dan aturan bisnis. Contohnya model `Event.php` yang memiliki relasi `hasMany(Candidate::class)` dan method `isVotingOpen()`.*
> - ***View:*** *Menangani lapisan presentasi menggunakan Blade Templating Engine dan Tailwind CSS, seperti `resources/views/voter/booth.blade.php`.*
> - ***Controller:*** *Sebagai pengendali alur aplikasi yang menerima HTTP Request, melakukan validasi data form, memanggil Model untuk manipulasi database, dan mengembalikan Response atau View.*
>
> *Saya juga menerapkan prinsip **Thin Controller, Rich Model**, di mana controller tetap ringkas dan logika kalkulasi perolehan suara didelegasikan ke Model."*

---

### ❓ Pertanyaan 4: "Mengapa untuk Quick Count menggunakan polling AJAX, bukan WebSocket (Pusher / Soketi)?"
> **💡 Jawaban Anda:**
> *"Ada pertimbangan efisiensi infrastruktur dan reliabilitas:*
> 1. *Untuk skala pemilihan sekolah di jaringan lokal (LAN/WiFi sekolah), metode **Periodic AJAX Polling** ke endpoint JSON `/petugas/events/{id}/monitor/data` setiap 4 detik sangat ringan dan minim konsumsi bandwidth karena payload JSON yang dikirim berukuran kurang dari 2 KB.*
> 2. *Pendekatan ini tidak membutuhkan dependensi server Node.js terpisah (seperti Laravel Reverb atau Pusher) yang dapat meningkatkan kompleksitas deployment dan potensi kegagalan koneksi di lingkungan server sekolah."*

---

### ❓ Pertanyaan 5: "Bagaimana sistem menangani jika ada 2 pemilih yang mencoblos pada detik yang persis sama?"
> **💡 Jawaban Anda:**
> *"Pada method `storeVote` di `VotingBoothController`, kami membungkus seluruh operasi di dalam **`DB::transaction()`** dan menerapkan **Pessimistic Locking** (`lockForUpdate()`):*
> ```php
> DB::transaction(function () use ($event, $voter, $candidateId) {
>     // Cek partisipasi dengan locking baris data
>     $alreadyVoted = EventParticipation::where('event_id', $event->id)
>         ->where('voter_id', $voter->id)
>         ->lockForUpdate()
>         ->exists();
>
>     if ($alreadyVoted) {
>         throw new \Exception('Sudah memilih');
>     }
>
>     // Simpan suara & catat partisipasi secara atomic
> });
> ```
> *Database engine MySQL InnoDB akan memproses transaksi secara antrean atomic (ACID). Request kedua akan menunggu lock dilepaskan dan langsung dibatalkan oleh validasi duplikasi."*

---

### ❓ Pertanyaan 6: "Apa fungsi fitur CSRF Protection dan mengapa tadi sempat di-exclude saat testing Postman?"
> **💡 Jawaban Anda:**
> *"CSRF (Cross-Site Request Forgery) melindungi pengguna dari serangan web berbahaya yang memalsukan aksi atas nama user yang sah. Di Laravel, form HTML menyertakan token `@csrf` terenkripsi.*
>
> *Pada pengujian API menggunakan tools seperti Postman, client eksternal tidak merender form Blade sehingga token CSRF web tidak terbentuk secara alami. Oleh karena itu, kita mendaftarkan URI testing ke dalam method `$middleware->validateCsrfTokens(except: [...])` di `bootstrap/app.php` khusus untuk keperluan testing API, sementara pengamanan utama tetap ditangani oleh session guard dan hak otorisasi role."*

---

## 5. Tips & Trik Menghadapi Asesor

1. **Gunakan Bahasa Teknis yang Tepat:**
   - Gunakan istilah *"Multi-Guard Authentication"*, bukan sekadar *"Login dua tabel"*.
   - Gunakan istilah *"Pessimistic Locking & Database Transaction"*, bukan sekadar *"Dikunci databasenya"*.
   - Gunakan istilah *"Asynchronous AJAX Polling"*, bukan sekadar *"Otomatis ganti sendiri"*.

2. **Jangan Panik Saat Ditanya Masalah / Bug:**
   - Jika asesor bertanya *"Bagaimana kalau X gagal?"*, jawablah dari sudut pandang penanganan exception (*try-catch block*), pesan validasi form (*Form Request Validation*), dan pesan flash message yang informatif.

3. **Demonstrasikan Kode Langsung (Show, Don't Just Tell):**
   - Saat asesor bertanya tentang keamanan, jangan hanya menjawab lisan. Buka langsung file terkait di VS Code (misalnya migration tabel votes atau controller voting booth). Ini membuktikan bahwa Anda yang menulis kode tersebut sendiri.

4. **Tunjukkan Bukti Unit Testing:**
   - Membuka terminal dan menjalankan `php artisan test` dengan **28 passed tests** adalah nilai plus tertinggi yang membedakan Anda dari peserta lain karena membuktikan penerapan kaidah Software Quality Assurance.

---
*Semoga sukses dalam asesmen uji kompetensi LSP P3! Anda menguasai sistem ini sepenuhnya.*
