# 3. Use Case Diagram - VOTINGYUK

## 3.1 Deskripsi Umum

Use Case Diagram menggambarkan **interaksi antara aktor (pengguna) dengan sistem** untuk mencapai tujuan tertentu. Diagram ini menunjukkan **fitur-fitur apa saja yang bisa diakses** oleh setiap role dalam aplikasi VOTINGYUK.

---

## 3.2 Notasi UML Use Case Diagram

| Simbol | Keterangan |
|--------|------------|
| 👤 (Stick Figure) | **Aktor:** Mewakili peran orang, sistem yang lain, atau alat ketika berkomunikasi dengan use case |
| ○ (Oval) | **Use Case:** Abstraksi dan interaksi antara sistem dan aktor |
| ──────► | **Association:** Abstraksi dari penghubung antara aktor dengan use case |
| - - - -► | **Generalization:** Menunjukkan spesialisasi aktor untuk dapat berpartisipasi dengan use case |
| ◄───«include»──── | Menunjukkan bahwa suatu use case seluruhnya merupakan fungsionalitas dari use case lainnya |
| ◄───«extend»──── | Menunjukkan bahwa suatu use case merupakan tambahan fungsional dari use case lainnya jika suatu kondisi terpenuhi |

---

## 3.3 Aktor dalam Sistem

| No | Aktor | Deskripsi | Guard | Hak Akses |
|----|-------|-----------|-------|-----------|
| 1 | **Super Admin** | Pengelola tertinggi sistem, fokus pada manajemen user (Super Admin, Petugas, Pemilih) dan monitoring audit log sistem | `web` | Full Control untuk User Management & Audit Log (TIDAK bisa kelola event) |
| 2 | **Petugas** | Panitia event yang mengelola event voting, kandidat/opsi, dan monitoring hasil suara secara real-time | `web` | Hanya event yang dibuat sendiri |
| 3 | **Pemilih (Voter)** | Siswa atau Guru yang memiliki hak suara untuk memberikan vote pada event yang aktif | `voter` | One person one vote |

---

## 3.4 Use Case Diagram per Role

### 3.4.1 Use Case Diagram Super Admin

```
┌─────────────────────────────────────────────────────────────────────────┐
│                   SISTEM E-VOTING "VOTINGYUK"                           │
│                        SUPER ADMIN PANEL                                 │
└─────────────────────────────────────────────────────────────────────────┘

                                    👤
                              SUPER ADMIN
                                    │
                                    │
        ┌───────────────────────────┼───────────────────────────┐
        │                           │                           │
        │                           │                           │
        ▼                           ▼                           ▼
    ┌─────────────┐         ┌─────────────┐           ┌─────────────┐
    │   Lihat     │         │   Kelola    │           │   Kelola    │
    │  Dashboard  │         │    Akun     │           │  Audit Log  │
    │    Admin    │         │   Users     │           │             │
    └─────────────┘         └──────┬──────┘           └──────┬──────┘
                                   │                         │
                    ┌──────────────┼──────────────┐         │
                    │              │              │         │
                    ▼              ▼              ▼         │
            ┌──────────┐   ┌──────────┐   ┌──────────┐    │
            │  Kelola  │   │  Kelola  │   │  Kelola  │    │
            │  Super   │   │ Petugas  │   │ Pemilih  │    │
            │  Admin   │   │          │   │          │    │
            └────┬─────┘   └────┬─────┘   └────┬─────┘    │
                 │              │              │           │
                 └──────┬───────┴──────┬───────┘           │
                        │              │                   │
                        ▼              ▼                   ▼
                 ┌─────────────────────────┐     ┌─────────────┐
                 │    «include»            │     │  «extend»   │
                 │   Validasi Data         │     │ Filter Log  │
                 │   (Password, Email,     │     │ Search Log  │
                 │    NIS/NIP)             │     │             │
                 └─────────────────────────┘     └─────────────┘


CATATAN PENTING: 
- Super Admin TIDAK memiliki akses ke fitur Petugas (Kelola Event, Kandidat, Monitoring)
- Super Admin fokus pada User Management & Audit Log saja
- Tidak ada relationship generalization/inheritance antara Super Admin dan Petugas
```

---

### 3.4.2 Use Case Diagram Petugas

```
┌─────────────────────────────────────────────────────────────────────────┐
│                   SISTEM E-VOTING "VOTINGYUK"                           │
│                          PETUGAS PANEL                                   │
└─────────────────────────────────────────────────────────────────────────┘

                                    👤
                                 PETUGAS
                                    │
                                    │
        ┌───────────────────────────┼───────────────────────────┐
        │                           │                           │
        │                           │                           │
        ▼                           ▼                           ▼
    ┌─────────────┐         ┌─────────────┐           ┌─────────────┐
    │   Kelola    │         │   Kelola    │           │    Live     │
    │    Event    │         │  Kandidat/  │           │ Monitoring  │
    │             │         │    Opsi     │           │   Voting    │
    └──────┬──────┘         └──────┬──────┘           └──────┬──────┘
           │                       │                         │
           │                       │                         │
           │   ┌───────────────────┼─────────────┐          │
           │   │                   │             │          │
           ▼   ▼                   ▼             ▼          ▼
    ┌────────────────┐      ┌──────────┐  ┌──────────┐  ┌──────────┐
    │   «include»    │      │«include» │  │«extend»  │  │«extend»  │
    │   Generate     │      │Validasi  │  │ Upload   │  │  Export  │
    │   Room Code    │      │Kandidat  │  │   Foto   │  │   CSV    │
    │  (Auto/Manual) │      │(Min 2)   │  │          │  │          │
    └────────────────┘      └──────────┘  └──────────┘  └──────────┘
           │
           │
           ▼
    ┌────────────────┐
    │   «extend»     │
    │  Publish Event │
    │  Close Event   │
    │  Set Jadwal    │
    └────────────────┘


ATURAN: Petugas HANYA bisa akses event yang dibuat sendiri (created_by)
```

---

### 3.4.3 Use Case Diagram Pemilih (Voter)

```
┌─────────────────────────────────────────────────────────────────────────┐
│                   SISTEM E-VOTING "VOTINGYUK"                           │
│                          VOTER PANEL                                     │
└─────────────────────────────────────────────────────────────────────────┘

                                    👤
                                  PEMILIH
                              (Siswa / Guru)
                                    │
                                    │
                                    ▼
                            ┌─────────────┐
                            │    Login    │
                            │    Voter    │
                            │  (NIS/NIP   │
                            │   ONLY)     │
                            └──────┬──────┘
                                   │
                                   │ «include»
                                   ▼
                            ┌─────────────┐
                            │  Validasi   │
                            │   NIS/NIP   │
                            │ (No Password)│
                            └──────┬──────┘
                                   │
                                   ▼
                            ┌─────────────┐
                            │    Lihat    │
                            │  Dashboard  │
                            │    Voter    │
                            └──────┬──────┘
                                   │
                                   ▼
                            ┌─────────────┐
                            │    Join     │
                            │    Room     │
                            │    Event    │
                            └──────┬──────┘
                                   │
                       ┌───────────┴───────────┐
                       │       «include»       │
                       ▼                       ▼
                ┌─────────────┐         ┌─────────────┐
                │  Validasi   │         │    Cek      │
                │  Room Code  │         │ Eligibility │
                │             │         │ (Belum Vote?)│
                └─────────────┘         └─────────────┘
                       │
                       ▼
                ┌─────────────┐
                │    Masuk    │
                │   Bilik     │
                │    Suara    │
                └──────┬──────┘
                       │
                       │ «include»
                       ▼
                ┌─────────────┐
                │    Load     │
                │ Data Event  │
                │  & Kandidat │
                └──────┬──────┘
                       │
                       ▼
                ┌─────────────┐
                │ Memberikan  │
                │    Suara    │
                │  (Vote)     │
                └──────┬──────┘
                       │
           ┌───────────┴───────────┐
           │       «include»       │
           ▼                       ▼
    ┌─────────────┐         ┌─────────────┐
    │ Konfirmasi  │         │   Submit    │
    │  Pilihan    │         │    Vote     │
    │             │         │             │
    └─────────────┘         └──────┬──────┘
                                   │
                                   │ «extend»
                                   ▼
                            ┌─────────────┐
                            │    Lihat    │
                            │ Konfirmasi  │
                            │   Sukses    │
                            └─────────────┘

ATURAN: One Person One Vote (tidak bisa vote lagi di event yang sama)
```

---

## 3.5 Deskripsi Detail Use Case Berdasarkan Analisis Fitur

### 3.5.1 Use Case Super Admin

**Sumber: Analisis Fitur Section 3.1**

| ID | Nama Use Case | Deskripsi Lengkap | Route/Akses | Controller |
|----|---------------|-------------------|-------------|------------|
| **UC-SA-01** | **Lihat Dashboard Admin** | Tampilan ringkasan sistem dengan metrics:<br>- Total Super Admin<br>- Total Petugas<br>- Total Pemilih (Siswa & Guru)<br>- Informasi sistem<br>- Recent audit logs | `/admin/dashboard` | `Admin\DashboardController` |
| **UC-SA-02** | **Kelola Akun Super Admin** | CRUD akun Super Admin lainnya:<br>- Tambah Super Admin baru<br>- Edit data Super Admin<br>- Hapus akun Super Admin<br>- Password management<br>**Include:** Validasi Data (email, password) | `/admin/users?tab=super_admin` | `Admin\UserController` |
| **UC-SA-03** | **Kelola Akun Petugas** | CRUD akun Petugas Pemilihan:<br>- Tambah Petugas baru<br>- Edit data Petugas<br>- Hapus akun Petugas<br>- Password management<br>**Include:** Validasi Data (email, password) | `/admin/users?tab=petugas` | `Admin\UserController` |
| **UC-SA-04** | **Kelola Akun Pemilih** | CRUD akun Pemilih (Siswa & Guru):<br>- Tambah Pemilih (Siswa/Guru)<br>- Edit data Pemilih<br>- Hapus akun Pemilih<br>- Filter berdasarkan tipe (Siswa/Guru)<br>**Include:** Validasi Data (NIS/NIP, nama, tipe) | `/admin/users?tab=pemilih` | `Admin\UserController` |
| **UC-SA-05** | **Kelola Audit Log** | Monitoring jejak aktivitas sistem:<br>- View semua audit logs<br>- Filter berdasarkan role<br>- Search berdasarkan Pengguna/IP/deskripsi<br>- Timestamp aktivitas<br>**Extend:** Filter by Action, Filter by User, Search Logs | `/admin/audit-logs` | `Admin\AuditLogController` |
| **UC-SA-06** | **Kelola Event (Inheritance)** | Mewarisi semua fitur Petugas UC-PT-01<br>Dapat akses dan kelola semua event milik Petugas manapun<br>**Generalization dari Petugas** | `/petugas/events/*` | `Petugas\EventController` |
| **UC-SA-07** | **Live Monitoring (Inheritance)** | Mewarisi semua fitur Petugas UC-PT-04<br>Monitoring real-time semua event<br>**Generalization dari Petugas** | `/petugas/events/{id}/monitor` | `Petugas\MonitoringController` |

---

### 3.5.2 Use Case Petugas

**Sumber: Analisis Fitur Section 3.2**

| ID | Nama Use Case | Deskripsi Lengkap | Route/Akses | Controller |
|----|---------------|-------------------|-------------|------------|
| **UC-PT-01** | **Kelola Event Pemilihan** | CRUD event voting:<br>- Tambah event baru (wajib DRAFT)<br>- Edit event (judul, kategori, kode room, status, waktu, deskripsi)<br>- Hapus event<br>- Set jadwal mulai & selesai (opsional)<br>- Filter: Status, Kategori, Search<br>**Include:** Generate Room Code (auto/manual)<br>**Extend:** Publish Event, Close Event, Set Jadwal | `/petugas/events` | `Petugas\EventController` |
| **UC-PT-02** | **Generate Room Code** | Sistem auto-generate kode room unik 6 karakter untuk akses voter<br>**Included by:** UC-PT-01 (Kelola Event)<br>Format: Alphanumeric 6 digit | Otomatis saat create event | `Petugas\EventController` |
| **UC-PT-03** | **Kelola Kandidat/Opsi** | CRUD kandidat per event:<br>- Tambah kandidat dengan nomor urut<br>- Edit nomor urut, nama, foto, visi-misi/deskripsi<br>- Hapus kandidat<br>- Upload foto kandidat (max 2MB)<br>**Include:** Validasi Kandidat (min 2 untuk publish)<br>**Extend:** Upload Foto, Set Nomor Urut<br>Storage: `storage/app/public/candidates/` | `/petugas/events/{id}/candidates` | `Petugas\CandidateController` |
| **UC-PT-04** | **Live Monitoring Voting** | Real-time monitoring hasil:<br>- Total suara per kandidat<br>- Persentase suara<br>- Grafik batang horizontal<br>- Deteksi hasil sementara, seri, dan pemenang<br>- Log aktivitas partisipasi<br>**Extend:** Export CSV, Auto Refresh, Deteksi Tie | `/petugas/events/{id}/monitor` | `Petugas\MonitoringController` |
| **UC-PT-05** | **Export Hasil Voting** | Export data hasil voting ke format CSV untuk laporan<br>**Extended by:** UC-PT-04 (Live Monitoring) | `/petugas/events/{id}/export` | `Petugas\MonitoringController` |

**Authorization Rule:** Petugas HANYA bisa akses event yang dibuat sendiri (`created_by` = user ID)

---

**Sumber: Analisis Fitur Section 3.3**

| ID | Nama Use Case | Deskripsi Lengkap | Route/Akses | Controller |
|----|---------------|-------------------|-------------|------------|
| **UC-VM-01** | **Login Voter** | Login menggunakan **HANYA NIS (Siswa) atau NIP (Guru)** TANPA password<br>**Include:** Validasi NIS/NIP (cek apakah terdaftar)<br>Guard: `voter` | `/voter/login` | `Auth\VoterAuthController` |
| **UC-VM-02** | **Lihat Dashboard Voter** | Setelah login, voter diarahkan ke halaman input Room Code:<br>- Data diri (Nama, NIS/NIP)<br>- Kategori (Siswa/Guru)<br>- Badge warna kategori<br>- Form input kode room | `/voter/join` | `Voter\VoterDashboardController` |
| **UC-VM-03** | **Join Room Event** | Input Room Code untuk join event tertentu:<br>- Input 6 karakter kode room<br>**Include:** Validasi Room Code (event aktif?), Cek Eligibility (sudah vote?)<br>- Redirect ke voting booth jika valid | `/voter/join` (POST) | `Voter\VoterDashboardController` |
| **UC-VM-04** | **Masuk Bilik Suara** | Bilik suara digital:<br>- Tampilan kandidat/opsi<br>- Foto & visi-misi/deskripsi kandidat/opsi<br>- Detail lengkap event<br>**Include:** Load Data Event, Load Kandidat | `/voter/events/{room_code}/vote` | `Voter\VotingBoothController` |
| **UC-VM-05** | **Memberikan Suara** | Proses voting:<br>- Pilih 1 kandidat/opsi<br>**Include:** Konfirmasi Pilihan (modal), Submit Vote (one-time)<br>**Extend:** Lihat Konfirmasi Sukses<br>Record ke `event_participations` & `votes` | `/voter/events/{room_code}/vote` (POST) | `Voter\VotingBoothController` |
| **UC-VM-06** | **Lihat Konfirmasi Sukses** | Konfirmasi voting berhasil:<br>- Pesan terima kasih<br>- Timestamp voting<br>- Tidak bisa voting lagi di event yang sama<br>**Extended by:** UC-VM-05 (Memberikan Suara) | `/voter/events/{room_code}/success` | `Voter\VotingBoothController` |

**Business Rule:** One Person One Vote (tidak bisa vote lagi atau ganti pilihan)

---

## 3.6 Relasi Antar Use Case

### 3.6.1 Include Relationship (Mandatory)

Use case wajib memanggil use case lain:

| Use Case Parent | «include» | Use Case Child |
|-----------------|-----------|----------------|
| Kelola Akun Super Admin | ──«include»──► | Validasi Data (Email, Password) |
| Kelola Akun Petugas | ──«include»──► | Validasi Data (Email, Password) |
| Kelola Akun Pemilih | ──«include»──► | Validasi Data (NIS/NIP, Nama, Tipe) |
| Kelola Event | ──«include»──► | Generate Room Code |
| Kelola Kandidat/Opsi | ──«include»──► | Validasi Kandidat (Min 2 untuk publish) |
| Login Voter | ──«include»──► | Validasi NIS/NIP |
| Join Room Event | ──«include»──► | Validasi Room Code |
| Join Room Event | ──«include»──► | Cek Eligibility (Belum vote? Event PUBLISHED?) |
| Masuk Bilik Suara | ──«include»──► | Load Data Event & Kandidat |
| Memberikan Suara | ──«include»──► | Konfirmasi Pilihan |
| Memberikan Suara | ──«include»──► | Submit Vote |

---

### 3.6.2 Extend Relationship (Optional)

Use case opsional, hanya terjadi dalam kondisi tertentu:

| Use Case Base | «extend» | Use Case Extension | Kondisi |
|---------------|----------|-------------------|---------|
| Kelola Audit Log | ◄──«extend»── | Filter by Action | Jika user ingin filter |
| Kelola Audit Log | ◄──«extend»── | Filter by User | Jika user ingin filter |
| Kelola Audit Log | ◄──«extend»── | Search Logs | Jika user search |
| Kelola Event | ◄──«extend»── | Publish Event | Jika min 2 kandidat terpenuhi |
| Kelola Event | ◄──«extend»── | Close Event | Jika event selesai |
| Kelola Event | ◄──«extend»── | Set Jadwal Event | Jika dijadwalkan otomatis |
| Kelola Kandidat/Opsi | ◄──«extend»── | Upload Foto Kandidat | Jika foto diupload |
| Kelola Kandidat/Opsi | ◄──«extend»── | Set Nomor Urut | Jika nomor urut diubah |
| Live Monitoring | ◄──«extend»── | Export CSV | Jika user ingin export |
| Live Monitoring | ◄──«extend»── | Auto Refresh 5s | Background process |
| Live Monitoring | ◄──«extend»── | Deteksi Tie | Jika hasil seri |
| Memberikan Suara | ◄──«extend»── | Lihat Konfirmasi Sukses | Setelah vote berhasil |

---

### 3.6.3 Generalization Relationship (Inheritance)

Super Admin mewarisi semua fitur Petugas:

**TIDAK ADA INHERITANCE** antara Super Admin dan Petugas.

```
        👤                          👤
   SUPER ADMIN                  PETUGAS
        │                          │
        │                          │
   (User Mgmt)              (Event Mgmt)
   (Audit Log)              (Kandidat)
                            (Monitoring)

CATATAN: 
- Super Admin dan Petugas adalah role TERPISAH
- Super Admin TIDAK bisa akses fitur Petugas
- Petugas TIDAK bisa akses fitur Super Admin
- Tidak ada relasi generalization
---

## 3.7 Business Rules & Validation

### 3.7.1 Authorization Rules

| Role | Batasan Akses |
|------|---------------|
| **Super Admin** | - Akses penuh ke semua fitur<br>- Dapat mengelola 3 jenis akun: Super Admin lain, Petugas, dan Pemilih (Siswa+Guru)<br>- Dapat melihat dan mengelola semua event (milik Petugas manapun)<br>- Dapat melihat semua audit log<br>- Interface dengan 3 tab management di `/admin/users` |
| **Petugas** | - Hanya bisa kelola event yang dibuat sendiri (`created_by` = user ID)<br>- Tidak bisa akses fitur admin (user management, audit log global)<br>- Tidak bisa edit/delete event milik Petugas lain<br>- Authorization check di Candidate & Monitoring controller<br>- Return 403 jika akses unauthorized |
| **Pemilih** | - Hanya bisa voting pUser Management & Audit Log<br>- Dapat mengelola 3 jenis akun: Super Admin lain, Petugas, dan Pemilih (Siswa+Guru)<br>- **TIDAK bisa akses fitur event, kandidat, atau monitoring** (hanya Petugas yang bisa)<br>- Dapat melihat semua audit log sistem<br>- Interface dengan 3 tab management di `/admin/users`<br>- Menu: Beranda, Kelola Akun, Audit Log

---

### 3.7.2 Business Rules

| Rule ID | Aturan Bisnis | Related Use Case |
|---------|---------------|------------------|
| **BR-01** | Setiap event harus memiliki minimal 2 kandidat/opsi sebelum bisa di-PUBLISH | UC-PT-01, UC-PT-03 |
| **BR-02** | Room Code bersifat unik per event dan auto-generated (6 karakter alphanumeric) | UC-PT-02 |
| **BR-03** | Voter login dengan NIS/NIP TANPA password, lalu join event dengan input Room Code di dashboard | UC-VM-01, UC-VM-02, UC-VM-03 |
| **BR-04** | Voter hanya bisa memberikan suara 1 kali per event (tidak ada edit/hapus vote) | UC-VM-05 |
| **BR-05** | Voter tidak bisa melihat hasil voting (hanya Petugas/Admin yang bisa monitoring) | UC-PT-04 |
| **BR-06** | Super Admin dan Petugas adalah role TERPISAH tanpa inheritance (Super Admin fokus user management, Petugas fokus event management) | UC-SA-01 sampai UC-SA-05, UC-PT-01 sampai UC-PT-05 |
| **BR-07** | Setiap aksi CRUD (Create, Update, Delete) dicatat dalam audit log dengan timestamp dan user info | UC-SA-05 |
| **BR-08** | Event dengan status DRAFT tidak bisa diakses oleh Voter | UC-VM-03 |
| **BR-09** | Kandidat/Opsi harus memiliki nomor urut unik dalam 1 event | UC-PT-03 |
| **BR-10** | Foto kandidat bersifat opsional, tapi jika diupload maksimal 2MB (format: JPG, PNG, WEBP) | UC-PT-03 |
| **BR-11** | Petugas hanya bisa melihat event yang dibuat sendiri (`created_by`) | UC-PT-01, UC-PT-03, UC-PT-04 |
| **BR-12** | Event baru wajib status DRAFT (default) | UC-PT-01 |

---

## 3.8 Traceability Matrix: Use Case ↔ Analisis Fitur

| Use Case ID | Nama Use Case | Sumber Analisis Fitur | Controller | Model |
|-------------|---------------|----------------------|------------|-------|
| UC-SA-01 | Lihat Dashboard Admin | Section 3.1 - Fitur 1 | `Admin\DashboardController` | User, Voter |
| UC-SA-02 | Kelola Akun Super Admin | Section 3.1 - Fitur 2 | `Admin\UserController` | User |
| UC-SA-03 | Kelola Akun Petugas | Section 3.1 - Fitur 3 | `Admin\UserController` | User |
| UC-SA-04 | Kelola Akun Pemilih | Section 3.1 - Fitur 4 | `Admin\UserController` | Voter |
| UC-SA-05 | Kelola Audit Log | Section 3.1 - Fitur 5 | `Admin\AuditLogController` | AuditLog |
| **--- PEMISAHAN: Super Admin TIDAK bisa akses fitur di bawah ini ---** | | | | |
| UC-PT-01 | Kelola Event Pemilihan | Section 3.2 - Fitur 2 | `Petugas\EventController` | Event |
| UC-PT-02 | Generate Room Code | Section 3.2 - Fitur 2 | `Petugas\EventController` | Event |
| UC-PT-03 | Kelola Kandidat/Opsi | Section 3.2 - Fitur 5 | `Petugas\CandidateController` | Candidate |
| UC-PT-04 | Live Monitoring Voting | Section 3.2 - Fitur 6 | `Petugas\MonitoringController` | Vote, Event |
| UC-PT-05 | Export Hasil Voting | Section 3.2 - Fitur 6 | `Petugas\MonitoringController` | Vote |
| UC-VM-01 | Login Voter | Section 3.3 - Fitur 1 | `Auth\VoterAuthController` | Voter |
| UC-VM-02 | Lihat Dashboard Voter | Section 3.3 - Fitur 1 | `Voter\VoterDashboardController` | Voter |
| UC-VM-03 | Join Room Event | Section 3.3 - Fitur 2 | `Voter\VoterDashboardController` | Event |
| UC-VM-04 | Masuk Bilik Suara | Section 3.3 - Fitur 3 | `Voter\VotingBoothController` | Event, Candidate |
| UC-VM-05 | Memberikan Suara | Section 3.3 - Fitur 3 | `Voter\VotingBoothController` | Vote, EventParticipation |
| UC-VM-06 | Lihat Konfirmasi Sukses | Section 3.3 - Fitur 4 | `Voter\VotingBoothController` | - |

---

## 3.9 Kesimpulan

### Total Use Case per Role:
- **Super Admin**: 5 use case (User Management & Audit Log)
- **Petugas**: 5 use case (Event & Kandidat Management, Monitoring)
- **Pemilih**: 6 use case (Login, Join Room, Voting)

### Kompleksitas Relasi:
- **Include Relationship**: 11 relasi (mandatory)
- **Extend Relationship**: 11 relasi (optional)
- **Generalization**: TIDAK ADA (Super Admin dan Petugas terpisah)

### Keunggulan Diagram:
1. ✅ Sesuai dengan notasi UML standar
2. ✅ Traceability jelas dengan Analisis Fitur
3. ✅ Business rules terdefinisi dengan baik
4. ✅ Authorization rules eksplisit
5. ✅ Dokumentasi lengkap dengan controller & route

---

**Dokumen dibuat:** 12 Oktober 2026  
**Versi:** 2.0 (Revised)  
**Status:** Final - Disesuaikan dengan Analisis Fitur & Implementasi Aktual
