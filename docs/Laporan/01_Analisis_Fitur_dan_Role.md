# Laporan Analisis Fitur & Role
## Sistem E-Voting Sekolah "VOTINGYUK"

---

## 1. Gambaran Umum Sistem

**VOTINGYUK** adalah aplikasi pemilihan elektronik berbasis web yang dirancang khusus untuk lingkungan sekolah (SMK Negeri 1). Sistem ini memfasilitasi pemilihan kandidat atau voting opsi secara digital dengan prinsip LUBER dan JURDIL (Langsung, Umum, Bebas, Rahasia, Jujur, dan Adil).

### Tujuan Sistem
- Digitalisasi proses pemilihan di sekolah
- Meningkatkan transparansi dan akuntabilitas
- Mempercepat proses perhitungan suara
- Menyediakan monitoring real-time hasil pemilihan
- Mencatat jejak audit untuk keamanan

---

## 2. Struktur Role & Hak Akses

Sistem menggunakan **Multi-Guard Authentication** dengan 3 role utama:

| No | Role | Guard | Deskripsi |
|----|------|-------|-----------|
| 1 | **Super Admin** | `web` | Administrator tertinggi dengan kontrol penuh sistem |
| 2 | **Petugas** | `web` | Penyelenggara pemilihan yang mengelola event dan monitoring |
| 3 | **Pemilih (Voter)** | `voter` | Siswa atau Guru yang memberikan suara |

> **Catatan Matriks Hak Akses:**  
> Untuk tabel matriks hak akses berformat checklist standar laporan asesmen LSP / UKK (`NO`, `FITUR`, `SUPER ADMIN`, `PETUGAS`, `PEMILIH (SISWA)`, `PEMILIH (GURU)`), silakan buka dokumen:  
> 📄 [**01_Tabel_Matriks_Fitur_dan_Role.md**](./01_Tabel_Matriks_Fitur_dan_Role.md).

---

## 3. Analisis Fitur per Role

### 3.1 SUPER ADMIN

**Akses:** Full Control - Mengelola seluruh sistem

#### Fitur Utama:

| No | Modul/Fitur | Deskripsi | Controller |
|----|-------------|-----------|------------|
| 1 | **Dashboard Admin** | Tampilan ringkasan sistem dengan metrics pengguna aktif | `Admin\DashboardController` |
| | | - Total Super Admin | |
| | | - Total Petugas | |
| | | - Total Pemilih (Siswa & Guru) | |
| | | - Informasi sistem | |
| | | - Recent audit logs | |
| 2 | **Kelola Akun Super Admin** | CRUD akun Super Admin | `Admin\UserController` |
| | | - Tambah Super Admin baru | |
| | | - Edit data Super Admin | |
| | | - Hapus akun Super Admin | |
| | | - Password management | |
| 3 | **Kelola Akun Petugas** | CRUD akun Petugas Pemilihan | `Admin\UserController` |
| | | - Tambah Petugas baru | |
| | | - Edit data Petugas | |
| | | - Hapus akun Petugas | |
| | | - Password management | |
| 4 | **Kelola Akun Pemilih** | CRUD akun Pemilih (Siswa & Guru) | `Admin\UserController` |
| | | - Tambah Pemilih (Siswa/Guru) | |
| | | - Edit data Pemilih | |
| | | - Hapus akun Pemilih | |
| | | - Filter berdasarkan tipe (Siswa/Guru) | |
| 5 | **Audit Log & Security** | Monitoring jejak aktivitas sistem | `Admin\AuditLogController` |
| | | - View semua audit logs | |
| | | - Filter berdasarkan role | |
| | | - Search berdasarkan Pengguna/IP/deskripsi | |
| | | - Timestamp aktivitas | |

#### Detail Audit Log Actions:
- `CREATE_USER` - Pembuatan akun Super Admin/Petugas
- `UPDATE_USER` - Update akun Super Admin/Petugas
- `DELETE_USER` - Hapus akun Super Admin/Petugas
- `CREATE_VOTER` - Pembuatan akun Pemilih
- `UPDATE_VOTER` - Update akun Pemilih
- `DELETE_VOTER` - Hapus akun Pemilih
- `CREATE_EVENT` - Pembuatan event pemilihan
- `UPDATE_EVENT` - Update event pemilihan
- `DELETE_EVENT` - Hapus event pemilihan
- `CHANGE_EVENT_STATUS` - Mengubah status event (DRAFT - PUBLISHED - CLOSED)
- `CREATE_CANDIDATE` - Tambah kandidat/opsi
- `UPDATE_CANDIDATE` - Update kandidat/opsi
- `DELETE_CANDIDATE` - Hapus kandidat/opsi
- `vote.cast` - Data pemilih yang telah sah memilih/memvoting


---

### 3.2 PETUGAS

**Akses:** Mengelola event pemilihan yang dibuat sendiri

#### Fitur Utama:

| No | Modul/Fitur | Deskripsi | Controller |
|----|-------------|-----------|------------|
| 1 | **Dashboard Petugas** | Ringkasan event dan aktivitas | - |
| 2 | **Kelola Event Pemilihan** | CRUD event voting | `Petugas\EventController` |
| | | - Tambah event baru (wajib Draft) | |
| | | - Edit event (judul, kategori, kode room, status event, waktu mulai dan selesai, deskripsi) | |
| | | - Hapus event | |
| | | - Generate kode room otomatis/manual | |
| | | - Set jadwal mulai & selesai (opsional) | |
| | | - Filter: Status, Kategori, Search | |
| 3 | **Status Event** | Kontrol status event | `Petugas\EventController` |
| | | - **DRAFT**: Event baru (default) | |
| | | - **PUBLISHED**: Event aktif untuk voting | |
| | | - **CLOSED**: Event selesai | |
| | | - Auto-update berdasarkan jadwal | |
| | | - Manual publish/close (jika tanpa jadwal) | |
| 4 | **Kategori Event** | Dua jenis pemilihan | |
| | | - **KANDIDAT**: Pemilihan kandidat (foto, visi-misi) | |
| | | - **OPSI**: Voting pilihan sederhana | |
| 5 | **Kelola Kandidat/Opsi** | CRUD kandidat per event | `Petugas\CandidateController` |
| | | - Tambah kandidat dengan nomor urut | |
| | | - Upload foto kandidat | |
| | | - Edit nomor urut, nama, foto, dan visi misi/deskripsi| |
| | | - Hapus kandidat | |
| | | - Validasi: min 2 kandidat untuk publish | |
| | | - Storage: `storage/app/public/storage/candidates/` | |
| 6 | **Live Monitoring** | Real-time monitoring hasil | `Petugas\MonitoringController` |
| | | - Total suara per kandidat | |
| | | - Persentase suara | |
| | | - Grafik batang horizontal | |
| | | - Deteksi hasil sementara, seri, dan pemenang | |
| | | - Log aktivitas partisipasi | |

#### Aturan Akses Petugas:
- ✅ Hanya bisa melihat event yang **dibuat sendiri** (`created_by`)
- ✅ Tidak bisa akses event petugas lain
- ✅ Authorization check di Candidate & Monitoring controller
- ✅ Return 403 jika akses unauthorized

---

### 3.3 PEMILIH (VOTER)

**Akses:** Memberikan suara pada event aktif

#### Fitur Utama:

| No | Modul/Fitur | Deskripsi | Controller |
|----|-------------|-----------|------------|
| 1 | **Dashboard Pemilih** | Tampilan informasi pemilih | `Voter\VoterDashboardController` |
| | | - Data diri (Nama, NIS/NIP) | |
| | | - Kategori (Siswa/Guru) | |
| | | - Badge warna kategori | |
| | | - Tombol "Masuk Bilik Suara" | |
| 2 | **Input Kode Room** | Akses event dengan kode | `Voter\VoterDashboardController` |
| | | - Input 6 karakter kode room | |
| | | - Validasi event aktif (PUBLISHED) | |
| | | - Cek apakah sudah voting | |
| | | - Redirect ke voting booth | |
| 3 | **Voting Booth** | Bilik suara digital | `Voter\VotingBoothController` |
| | | - Tampilan kandidat/opsi | |
| | | - Foto & visi-misi/deskripsi kandidat/opsi | |
| | | - Pilih 1 kandidat | |
| | | - Konfirmasi pilihan | |
| | | - Submit vote (one-time) | |
| 4 | **Halaman Sukses** | Konfirmasi voting berhasil | `Voter\VotingBoothController` |
| | | - Pesan terima kasih | |
| | | - Timestamp voting | |
| | | - Tidak bisa voting lagi | |
| 5 | **Keamanan Voting** | Validasi & proteksi | |
| | | - One person one vote | |
| | | - Rahasia pilihan dijaga | |
| | | - Hanya event PUBLISHED | |
| | | - Record ke `event_participations` | |

#### Kategori Pemilih:
- **SISWA**: Siswa dengan NIS
- **GURU**: Guru & Tenaga Pendidik dengan NIP

---

## 4. Fitur Umum Sistem

### 4.1 Autentikasi

| Fitur | Deskripsi | Controller |
|-------|-----------|------------|
| **Login Admin/Petugas** | Login dengan email & password (guard `web`) | `Auth\AdminAuthController` |
| **Login Voter** | Login dengan identifier (NIS/NIP) | `Auth\VoterAuthController` |
| **Logout** | Logout dan redirect ke login | `Auth\AdminAuthController`, `Auth\VoterAuthController` |
| **Session Management** | Session berbeda per guard | Laravel Session |

### 4.2 Middleware & Role Protection

| Middleware | Fungsi |
|------------|--------|
| `RoleMiddleware` | Validasi role user (SUPER_ADMIN, PETUGAS, VOTER) |
| `auth:web` | Protect route admin/petugas |
| `auth:voter` | Protect route voter |

### 4.3 Real-time Features

| Fitur | Teknologi | Deskripsi |
|-------|-----------|-----------|
| **Tie Detection** | PHP Logic | Deteksi hasil seri otomatis |
| **Status Auto-update** | Carbon Datetime | Update status event berdasarkan jadwal |

---

## 5. Model Database & Relasi

| Model | Deskripsi | Relasi |
|-------|-----------|--------|
| **User** | Super Admin & Petugas | - |
| **Voter** | Pemilih (Siswa/Guru) | `hasMany` Event Participations, Votes |
| **Event** | Event pemilihan | `belongsTo` User (creator), `hasMany` Candidates, Votes |
| **Candidate** | Kandidat/Opsi | `belongsTo` Event, `hasMany` Votes |
| **Vote** | Suara pemilih | `belongsTo` Event, Candidate, Voter |
| **EventParticipation** | Rekam partisipasi | `belongsTo` Event, Voter |
| **AuditLog** | Jejak aktivitas | - |

---

## 6. Keamanan & Validasi

### 6.1 Proteksi Data
- ✅ Password di-hash dengan bcrypt
- ✅ CSRF protection
- ✅ SQL Injection prevention (Eloquent ORM)
- ✅ XSS protection (Blade escaping)

### 6.2 Validasi Bisnis Logic
- ✅ Event baru wajib status DRAFT
- ✅ Minimum 2 kandidat untuk publish
- ✅ One person one vote per event
- ✅ Hanya event PUBLISHED bisa di-vote
- ✅ Petugas hanya akses event sendiri

### 6.3 Audit Trail
- ✅ Semua CRUD operation tercatat
- ✅ Timestamp & IP address
- ✅ User identity & role
- ✅ Action description

---

## 7. UI/UX Features

### 7.1 Design System
- **Color Palette**: Cyan & Red (sesuai logo SMK Negeri 1)
- **Typography**: Plus Jakarta Sans
- **CSS Framework**: Tailwind CSS v4

### 7.2 Warna Badge per Role
| Role | Background | Text | Border |
|------|------------|------|--------|
| Super Admin | `#800020` (maroon) | Rose 200 | Rose 600 |
| Petugas | Cyan 950 | Cyan 300 | Cyan 700 |
| Pemilih | White 10% | White | White 30% |
| Siswa | Sky 950 | Sky 300 | Sky 700 |
| Guru | Amber 950 | Amber 300 | Amber 700 |

### 7.3 Interaktivitas
- ✅ Password toggle (eye icon)
- ✅ Modal dialogs
- ✅ Konfirmasi hapus
- ✅ Real-time search & filter
- ✅ Responsive design

---

## 9. Kesimpulan Analisis Fitur

### Total Fitur Berdasarkan Role:
- **Super Admin**: 5 modul utama
- **Petugas**: 6 modul utama  
- **Pemilih**: 5 modul utama

### Kompleksitas:
- **High**: Live monitoring, audit log, authorization
- **Medium**: Event management, candidate CRUD
- **Low**: Dashboard, authentication

### Keunggulan Sistem:
1. ✅ Pemisahan role yang jelas
2. ✅ Real-time monitoring
3. ✅ Audit trail lengkap
4. ✅ One-time voting enforcement
5. ✅ User-friendly interface
6. ✅ Mobile responsive

---

**Dokumen dibuat:** 6 Oktober 2026  
**Versi:** 1.0  
**Status:** Final
