# Matriks Analisis Fitur dan Hak Akses Role
## Sistem E-Voting Sekolah "VOTINGYUK"
### Standar Dokumentasi Uji Kompetensi Keahlian (UKK) / LSP Rekayasa Perangkat Lunak

---

## 1. Definisi Role Pengguna

Berdasarkan arsitektur **Multi-Guard Authentication** pada sistem **VOTINGYUK**, hak akses dibagi ke dalam 4 entitas pengguna:

| No | Role Pengguna | Guard Auth | Basis Kredensial | Deskripsi Otoritas |
|:--:|---------------|:----------:|------------------|--------------------|
| 1 | **SUPER ADMIN** | `web` | Email & Password | Administrator utama dengan kontrol penuh atas manajemen akun (Super Admin, Petugas, Pemilih), audit log sistem, dan keamanan. |
| 2 | **PETUGAS** | `web` | Email & Password | Panitia pemilihan yang berwenang membuat dan mengelola event pemilihan miliknya, mengelola kandidat/opsi, serta memantau live monitoring. |
| 3 | **PEMILIH (SISWA)** | `voter` | NIS (Nomor Induk Siswa) | Peserta pemilihan dari kalangan siswa yang memberikan 1 hak suara sah di bilik suara digital melalui validasi kode room. |
| 4 | **PEMILIH (GURU)** | `voter` | NIP (Nomor Induk Pegawai) | Peserta pemilihan dari kalangan guru/tenaga pendidik yang memberikan 1 hak suara sah di bilik suara digital melalui validasi kode room. |

---

## 2. Tabel Matriks Analisis Fitur & Role (Format 4 Kolom Role)

*Format tabel di bawah ini disusun persis sesuai acuan standar instrumen asesmen uji kompetensi, di mana setiap modul dikelompokkan berdasarkan kategori fitur dengan tanda centang (✓) sebagai penanda hak akses.*9router9

| NO | FITUR SISTEM | SUPER ADMIN | PETUGAS | PEMILIH (SISWA) | PEMILIH (GURU) |
|:--:|:-------------|:-----------:|:-------:|:---------------:|:--------------:|
| **A** | **Autentikasi & Sesi Pengguna** | | | | |
| 1 | Login Pengelola (Email & Password via Guard Web) | ✓ | ✓ | | |
| 2 | Login Pemilih (Identifikasi NIS via Guard Voter) | | | ✓ | |
| 3 | Login Pemilih (Identifikasi NIP via Guard Voter) | | | | ✓ |
| 4 | Logout Sesi Pengguna & Invalidate Session Token | ✓ | ✓ | ✓ | ✓ |
| **B** | **Dashboard & Ringkasan Informasi** | | | | |
| 5 | Melihat Dashboard Admin (Statistik Total Akun & Server Info) | ✓ | | | |
| 6 | Melihat Dashboard Pemilih (Profil, Status Memilih, & Form Room) | | | ✓ | ✓ |
| **C** | **Kelola Data Akun Pengelola (Admin & Petugas)** | | | | |
| 7 | List Data Akun Pengelola (Tab Super Admin & Tab Petugas) | ✓ | | | |
| 8 | Pencarian Data Akun Pengelola (Nama & Email) | ✓ | | | |
| 9 | Tambah Akun Super Admin Baru (Dengan Kredensial Password) | ✓ | | | |
| 10 | Tambah Akun Petugas Pemilihan Baru (Dengan Kredensial Password) | ✓ | | | |
| 11 | Ubah / Edit Data Akun Pengelola (Nama Lengkap & Email) | ✓ | | | |
| 12 | Ubah / Reset Password Akun Pengelola | ✓ | | | |
| 13 | Hapus Akun Pengelola (Dilengkapi Proteksi Anti-Self-Delete) | ✓ | | | |
| **D** | **Kelola Data Pemilih Tetap (DPT Siswa & Guru)** | | | | |
| 14 | List Data Pemilih Tetap (DPT) pada Tab Pemilih | ✓ | | | |
| 15 | Pencarian Data Pemilih (Berdasarkan Nama & NIS/NIP) | ✓ | | | |
| 16 | Filter Data Pemilih Berdasarkan Tipe (Semua / Siswa / Guru) | ✓ | | | |
| 17 | Tambah Data Pemilih Baru (Nama, NIS/NIP, Tipe, & Kelas Tanpa Password) | ✓ | | | |
| 18 | Ubah / Edit Data Pemilih (Nama, NIS/NIP, Tipe, & Kelas) | ✓ | | | |
| 19 | Hapus Data Akun Pemilih dari Daftar DPT | ✓ | | | |
| **E** | **Audit Log & Jejak Digital Keamanan** | | | | |
| 20 | Melihat Riwayat Jejak Digital Sistem (Audit Log Trail) | ✓ | | | |
| 21 | Pencarian Log Berdasarkan Aksi, User Pelaku, atau IP Address | ✓ | | | |
| 22 | Filter Riwayat Log Berdasarkan Role Pengguna | ✓ | | | |
| **F** | **Kelola Data Event Pemilihan (E-Voting)** | | | | |
| 23 | List Data Event Pemilihan (Event yang Dibuat Sendiri) | | ✓ | | |
| 24 | Pencarian Data Event Pemilihan Berdasarkan Judul | | ✓ | | |
| 25 | Filter Data Event Berdasarkan Status (Draft, Published, Closed) | | ✓ | | |
| 26 | Filter Data Event Berdasarkan Kategori (Kandidat / Opsi) | | ✓ | | |
| 27 | Tambah Event Pemilihan Baru (Otomatis Berstatus DRAFT) | | ✓ | | |
| 28 | Generate Kode Ruangan (Room Code) Otomatis / Manual (6 Karakter) | | ✓ | | |
| 29 | Pengaturan Jadwal Pemilihan (Waktu Mulai & Waktu Selesai Otomatis) | | ✓ | | |
| 30 | Ubah / Edit Data Event (Judul, Kategori, Kode Room, Deskripsi) | | ✓ | | |
| 31 | Kontrol Status Siklus Event (Publikasikan Event / Tutup Event) | | ✓ | | |
| 32 | Hapus Event Pemilihan Beserta Seluruh Data Terkait | | ✓ | | |
| **G** | **Kelola Data Kandidat & Opsi Pemilihan** | | | | |
| 33 | List Data Kandidat / Opsi Pemilihan per Event | | ✓ | | |
| 34 | Tambah Kandidat / Opsi Baru (Nomor Urut & Nama Paslon/Opsi) | | ✓ | | |
| 35 | Upload & Ganti Foto Kandidat / Gambar Ilustrasi Opsi (Max 2MB) | | ✓ | | |
| 36 | Kelola Visi & Misi Kandidat (Form Teks Terstruktur) | | ✓ | | |
| 37 | Preview Modal Visi & Misi Kandidat | | ✓ | | |
| 38 | Ubah / Edit Data Kandidat & Opsi Pilihan | | ✓ | | |
| 39 | Hapus Data Kandidat & Opsi Pilihan | | ✓ | | |
| 40 | Validasi Syarat Minimal 2 Pilihan Sebelum Event Dipublikasikan | | ✓ | | |
| **H** | **Live Monitoring & Rekapitulasi Suara** | | | | |
| 41 | Live Monitoring Perolehan Suara Real-time (Auto Polling AJAX) | | ✓ | | |
| 42 | Visualisasi Grafik Batang (Progress Bar) Persentase Suara | | ✓ | | |
| 43 | Deteksi Otomatis Pilihan yang Sedang Unggul (Hasil Sementara) | | ✓ | | |
| 44 | Tampilan Pemenang Resmi / Hasil Akhir (Saat Event CLOSED) | | ✓ | | |
| 45 | Deteksi Otomatis Kondisi Hasil Suara Seri (Tie Detection) | | ✓ | | |
| 46 | Live Stream Log Partisipasi Pemilih (Audit Prinsip LUBER & JURDIL) | | ✓ | | |
| 47 | Tombol Muat Ulang Manual (Manual Refresh) Data Suara Masuk | | ✓ | | |
| 48 | Salin Kode Room Pemilihan ke Clipboard (Copy Room Code) | | ✓ | | |
| **I** | **Pelaksanaan Pemilihan (Bilik Suara Digital)** | | | | |
| 49 | Input & Validasi Kode Room Pemilihan (6 Karakter Unik) | | | ✓ | ✓ |
| 50 | Masuk Ruang Bilik Suara Digital (Voting Booth) | | | ✓ | ✓ |
| 51 | Melihat Profil, Nomor Urut, Foto, & Visi-Misi di Bilik Suara | | | ✓ | ✓ |
| 52 | Memberikan 1 Suara Pilihan (Voting Kandidat / Opsi) | | | ✓ | ✓ |
| 53 | Konfirmasi Pilihan Suara (Modal Dialog Konfirmasi LUBER & JURDIL) | | | ✓ | ✓ |
| 54 | Submit Suara Sah & Penguncian Hak Suara (One Person One Vote) | | | ✓ | ✓ |
| 55 | Melihat Halaman Tanda Terima Sukses Memilih Lengkap dengan Waktu | | | ✓ | ✓ |

---

## 3. Tabel Matriks Analisis Fitur & Role (Format Konsolidasi 3 Role)

*Format alternatif jika peran Siswa dan Guru digabungkan ke dalam 1 entitas umum **PEMILIH (VOTER)**:*

| NO | FITUR SISTEM | SUPER ADMIN | PETUGAS | PEMILIH (VOTER) |
|:--:|:-------------|:-----------:|:-------:|:---------------:|
| **A** | **Autentikasi & Sesi Pengguna** | | | |
| 1 | Login Pengelola (Email & Password via Guard Web) | ✓ | ✓ | |
| 2 | Login Pemilih (NIS / NIP via Guard Voter) | | | ✓ |
| 3 | Logout Sesi Pengguna & Invalidate Session Token | ✓ | ✓ | ✓ |
| **B** | **Dashboard & Ringkasan Informasi** | | | |
| 4 | Melihat Dashboard Admin (Statistik Total Akun & Server Info) | ✓ | | |
| 5 | Melihat Dashboard Pemilih (Profil, Status Memilih, & Form Room) | | | ✓ |
| **C** | **Kelola Data Akun Pengguna & Pemilih** | | | |
| 6 | List Data Seluruh Akun Pengguna (Super Admin, Petugas, Pemilih) | ✓ | | |
| 7 | Pencarian Data Akun Pengguna & Pemilih | ✓ | | |
| 8 | Filter Tab Akun (Super Admin, Petugas, Pemilih) | ✓ | | |
| 9 | Tambah Akun Pengelola (Super Admin & Petugas) | ✓ | | |
| 10 | Tambah Akun Pemilih (Siswa & Guru Tanpa Password) | ✓ | | |
| 11 | Ubah / Edit Data Akun Pengguna & Pemilih | ✓ | | |
| 12 | Ubah / Reset Password Akun Pengelola | ✓ | | |
| 13 | Hapus Akun Pengguna / Pemilih (Dilengkapi Proteksi Diri Sendiri) | ✓ | | |
| **D** | **Audit Log & Jejak Digital Keamanan** | | | |
| 14 | Melihat Riwayat Jejak Digital Sistem (Audit Log Trail) | ✓ | | |
| 15 | Pencarian & Filter Log (User, Aksi, Role, & IP Address) | ✓ | | |
| **E** | **Kelola Data Event Pemilihan (E-Voting)** | | | |
| 16 | List Data Event Pemilihan (Milik Sendiri) | | ✓ | |
| 17 | Pencarian & Filter Data Event (Status & Kategori) | | ✓ | |
| 18 | Tambah Event Pemilihan Baru (Status Awal Draft) | | ✓ | |
| 19 | Generate Kode Ruangan Otomatis / Manual | | ✓ | |
| 20 | Pengaturan Jadwal Pemilihan (Mulai & Selesai) | | ✓ | |
| 21 | Ubah / Edit Data Event Pemilihan | | ✓ | |
| 22 | Kontrol Perubahan Status Event (Draft, Published, Closed) | | ✓ | |
| 23 | Hapus Event Pemilihan | | ✓ | |
| **F** | **Kelola Data Kandidat & Opsi Pemilihan** | | | |
| 24 | List Data Kandidat / Opsi Pemilihan per Event | | ✓ | |
| 25 | Tambah Kandidat / Opsi Baru (Nomor Urut & Nama) | | ✓ | |
| 26 | Upload & Ganti Foto Kandidat / Gambar Ilustrasi Opsi | | ✓ | |
| 27 | Kelola Visi & Misi Kandidat serta Preview Modal | | ✓ | |
| 28 | Ubah / Edit Data Kandidat & Opsi Pilihan | | ✓ | |
| 29 | Hapus Data Kandidat & Opsi Pilihan | | ✓ | |
| 30 | Validasi Minimal 2 Pilihan Sebelum Publikasi | | ✓ | |
| **G** | **Live Monitoring & Rekapitulasi Suara** | | | |
| 31 | Live Monitoring Perolehan Suara Real-time (AJAX Polling) | | ✓ | |
| 32 | Visualisasi Grafik Batang Persentase Suara | | ✓ | |
| 33 | Deteksi Pilihan Unggul / Pemenang (Hasil Sementara & Akhir) | | ✓ | |
| 34 | Deteksi Otomatis Kondisi Hasil Seri (Tie Detection) | | ✓ | |
| 35 | Live Stream Log Partisipasi Pemilih (LUBER & JURDIL) | | ✓ | |
| 36 | Muat Ulang Manual & Salin Kode Room ke Clipboard | | ✓ | |
| **H** | **Pelaksanaan Pemilihan (Bilik Suara Digital)** | | | |
| 37 | Input & Validasi Kode Room Pemilihan (6 Karakter) | | | ✓ |
| 38 | Masuk Ruang Bilik Suara Digital (Voting Booth) | | | ✓ |
| 39 | Melihat Profil, Foto, & Visi-Misi di Bilik Suara | | | ✓ |
| 40 | Memberikan Suara / Memilih 1 Kandidat atau Opsi | | | ✓ |
| 41 | Konfirmasi Pilihan Suara (Modal Dialog LUBER & JURDIL) | | | ✓ |
| 42 | Submit Suara Sah & Enforce One Person One Vote | | | ✓ |
| 43 | Melihat Halaman Tanda Terima Sukses Memilih | | | ✓ |

---

## 4. Format Tabel HTML Siap Salin ke Microsoft Word / Google Docs

*Gunakan kode tabel HTML di bawah ini jika Anda ingin meng-copy-paste langsung ke Microsoft Word atau Google Docs agar format border, garis pemisah kategori, dan perataan centang tetap rapi dan tidak berantakan:*

```html
<table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse; width: 100%; font-family: 'Times New Roman', Times, serif; font-size: 11pt;">
  <thead>
    <tr style="background-color: #f2f2f2; text-align: center; font-weight: bold;">
      <th style="width: 6%;">NO</th>
      <th style="width: 46%;">FITUR SISTEM</th>
      <th style="width: 12%;">SUPER ADMIN</th>
      <th style="width: 12%;">PETUGAS</th>
      <th style="width: 12%;">PEMILIH (SISWA)</th>
      <th style="width: 12%;">PEMILIH (GURU)</th>
    </tr>
  </thead>
  <tbody>
    <!-- Kategori A -->
    <tr style="background-color: #fafafa; font-weight: bold;">
      <td colspan="6">Autentikasi & Sesi Pengguna</td>
    </tr>
    <tr>
      <td align="center">1</td>
      <td>Login Pengelola (Email & Password via Guard Web)</td>
      <td align="center">&#10003;</td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">2</td>
      <td>Login Pemilih (Identifikasi NIS via Guard Voter)</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">3</td>
      <td>Login Pemilih (Identifikasi NIP via Guard Voter)</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
    </tr>
    <tr>
      <td align="center">4</td>
      <td>Logout Sesi Pengguna & Invalidate Session Token</td>
      <td align="center">&#10003;</td>
      <td align="center">&#10003;</td>
      <td align="center">&#10003;</td>
      <td align="center">&#10003;</td>
    </tr>

    <!-- Kategori B -->
    <tr style="background-color: #fafafa; font-weight: bold;">
      <td colspan="6">Dashboard & Ringkasan Informasi</td>
    </tr>
    <tr>
      <td align="center">5</td>
      <td>Melihat Dashboard Admin (Statistik Total Akun & Server Info)</td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">6</td>
      <td>Melihat Dashboard Pemilih (Profil, Status Memilih, & Form Room)</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center">&#10003;</td>
    </tr>

    <!-- Kategori C -->
    <tr style="background-color: #fafafa; font-weight: bold;">
      <td colspan="6">Kelola Data Akun Pengelola</td>
    </tr>
    <tr>
      <td align="center">7</td>
      <td>List Data Akun Pengelola (Tab Super Admin & Tab Petugas)</td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">8</td>
      <td>Pencarian Data Akun Pengelola (Nama & Email)</td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">9</td>
      <td>Tambah Akun Super Admin Baru</td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">10</td>
      <td>Tambah Akun Petugas Pemilihan Baru</td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">11</td>
      <td>Ubah / Edit Data Akun Pengelola (Nama Lengkap & Email)</td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">12</td>
      <td>Ubah / Reset Password Akun Pengelola</td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">13</td>
      <td>Hapus Akun Pengelola (Dilengkapi Proteksi Diri Sendiri)</td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>

    <!-- Kategori D -->
    <tr style="background-color: #fafafa; font-weight: bold;">
      <td colspan="6">Kelola Data Pemilih Tetap (DPT)</td>
    </tr>
    <tr>
      <td align="center">14</td>
      <td>List Data Pemilih Tetap (DPT) pada Tab Pemilih</td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">15</td>
      <td>Pencarian Data Pemilih (Berdasarkan Nama & NIS/NIP)</td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">16</td>
      <td>Filter Data Pemilih Berdasarkan Tipe (Semua / Siswa / Guru)</td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">17</td>
      <td>Tambah Data Pemilih Baru (Nama, NIS/NIP, Tipe, & Kelas)</td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">18</td>
      <td>Ubah / Edit Data Pemilih (Nama, NIS/NIP, Tipe, & Kelas)</td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">19</td>
      <td>Hapus Data Akun Pemilih dari Daftar DPT</td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>

    <!-- Kategori E -->
    <tr style="background-color: #fafafa; font-weight: bold;">
      <td colspan="6">Audit Log & Jejak Digital Keamanan</td>
    </tr>
    <tr>
      <td align="center">20</td>
      <td>Melihat Riwayat Jejak Digital Sistem (Audit Log Trail)</td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">21</td>
      <td>Pencarian Log Berdasarkan Aksi, User Pelaku, atau IP Address</td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">22</td>
      <td>Filter Riwayat Log Berdasarkan Role Pengguna</td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>

    <!-- Kategori F -->
    <tr style="background-color: #fafafa; font-weight: bold;">
      <td colspan="6">Kelola Data Event Pemilihan (E-Voting)</td>
    </tr>
    <tr>
      <td align="center">23</td>
      <td>List Data Event Pemilihan (Event Milik Sendiri)</td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">24</td>
      <td>Pencarian Data Event Pemilihan Berdasarkan Judul</td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">25</td>
      <td>Filter Data Event (Status Draft, Published, Closed & Kategori)</td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">26</td>
      <td>Tambah Event Pemilihan Baru (Otomatis Status DRAFT)</td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">27</td>
      <td>Generate Kode Ruangan (Room Code) Otomatis / Manual</td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">28</td>
      <td>Pengaturan Jadwal Pemilihan (Waktu Mulai & Selesai Otomatis)</td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">29</td>
      <td>Ubah / Edit Data Event Pemilihan</td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">30</td>
      <td>Kontrol Status Siklus Event (Publikasikan / Tutup Event)</td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">31</td>
      <td>Hapus Event Pemilihan Beserta Seluruh Data Terkait</td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>

    <!-- Kategori G -->
    <tr style="background-color: #fafafa; font-weight: bold;">
      <td colspan="6">Kelola Data Kandidat & Opsi Pemilihan</td>
    </tr>
    <tr>
      <td align="center">32</td>
      <td>List Data Kandidat / Opsi Pemilihan per Event</td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">33</td>
      <td>Tambah Kandidat / Opsi Baru (Nomor Urut & Nama)</td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">34</td>
      <td>Upload & Ganti Foto Kandidat / Gambar Ilustrasi Opsi</td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">35</td>
      <td>Kelola Visi & Misi Kandidat serta Preview Modal</td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">36</td>
      <td>Ubah / Edit Data Kandidat & Opsi Pilihan</td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">37</td>
      <td>Hapus Data Kandidat & Opsi Pilihan</td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">38</td>
      <td>Validasi Syarat Minimal 2 Pilihan Sebelum Publikasi Event</td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>

    <!-- Kategori H -->
    <tr style="background-color: #fafafa; font-weight: bold;">
      <td colspan="6">Live Monitoring & Rekapitulasi Suara</td>
    </tr>
    <tr>
      <td align="center">39</td>
      <td>Live Monitoring Perolehan Suara Real-time (Auto Polling AJAX)</td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">40</td>
      <td>Visualisasi Grafik Batang (Progress Bar) Persentase Suara</td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">41</td>
      <td>Deteksi Pilihan Unggul / Pemenang (Hasil Sementara & Akhir)</td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">42</td>
      <td>Deteksi Otomatis Kondisi Hasil Suara Seri (Tie Detection)</td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">43</td>
      <td>Live Stream Log Partisipasi Pemilih (Audit LUBER & JURDIL)</td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>
    <tr>
      <td align="center">44</td>
      <td>Muat Ulang Manual & Salin Kode Room ke Clipboard</td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center"></td>
      <td align="center"></td>
    </tr>

    <!-- Kategori I -->
    <tr style="background-color: #fafafa; font-weight: bold;">
      <td colspan="6">Pelaksanaan Pemilihan (Bilik Suara Digital)</td>
    </tr>
    <tr>
      <td align="center">45</td>
      <td>Input & Validasi Kode Room Pemilihan (6 Karakter)</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center">&#10003;</td>
    </tr>
    <tr>
      <td align="center">46</td>
      <td>Masuk Ruang Bilik Suara Digital (Voting Booth)</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center">&#10003;</td>
    </tr>
    <tr>
      <td align="center">47</td>
      <td>Melihat Profil, Foto, & Visi-Misi Pilihan di Bilik Suara</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center">&#10003;</td>
    </tr>
    <tr>
      <td align="center">48</td>
      <td>Memberikan 1 Suara Pilihan (Voting Kandidat / Opsi)</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center">&#10003;</td>
    </tr>
    <tr>
      <td align="center">49</td>
      <td>Konfirmasi Pilihan Suara (Modal Dialog LUBER & JURDIL)</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center">&#10003;</td>
    </tr>
    <tr>
      <td align="center">50</td>
      <td>Submit Suara Sah & Penguncian Hak Suara (One Person One Vote)</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center">&#10003;</td>
    </tr>
    <tr>
      <td align="center">51</td>
      <td>Melihat Halaman Tanda Terima Sukses Memilih Lengkap dengan Waktu</td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center">&#10003;</td>
      <td align="center">&#10003;</td>
    </tr>
  </tbody>
</table>
```
