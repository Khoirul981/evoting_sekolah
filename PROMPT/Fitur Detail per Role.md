Fitur Detail per Role (BISA KAMU BEDAKAN DAN KEMBANGKAN KALAU MENURUT KAMU PERLU DI BEDAKAN, KALAU ADA YANG YANG KAMU BEDAKAN YANG BERBEDA DARI FILE TOLONG BERI TAHU)

DISCLAIMER!!! (INI ADALAH TAMPILAN ATAU FITUR YANG TELAH SAYA COBA IMPLEMENTASIKAN, DAN SEKARANG SAYA INGIN MEMBUATNYA LAGI DENGAN YANG LEBIH MUDAH DAN SIMPLE AGAR SAYA MUDAH MENGERTI CARA IMPLEMENTASI ATAU FUNGSI FUNGSI NYA NANTI UNTUK APA)

UNTUK KAYAK PENULISAN DESKRIPSI ATAU PENJELASNYA GITU GAUSAH DIIKUTIN BANGET ITU CUMA SEBAGAI CONTOH (MISAL Subtitle**: "Visualisasi statistik real-time partisipasi pemilih, distribusi DPT, status event pemilihan sekolah, dan kontrol terpusat" INI GAUSAH DISAMAIN BANGET SUBTITLENYA HARUS DIISI SEPERTI ITU, KAMU BISA BEDAKAN SESUAI PEMIKIRAN KAMU AJA BAGUSNYA GIMANA), BERLAKU JUGA DENGAN UI/UX NYA GAUSAH DISAMAIN BANGET KAYAK SEPANJANG FILE INI, SOALNYA INI SAYA INGIN MEMBERIKAN GAMBARANNYA SAJA BIAR KAMU GA BINGUNG. INTINYA ADA PADA YANG SUDAH SAYA DISCLAIMER SEBELUMNYA.

### **A. SUPER ADMIN - Fitur Lengkap**

Super Admin memiliki akses penuh ke **7 menu utama** yang diakses melalui **sidebar navigasi**. Interface menggunakan **dark mode** dengan desain modern dan responsif.

---

#### **1. Dashboard Ringkasan (Halaman Utama)**

**Header Section:**
- **Badge "EXECUTIVE OVERVIEW"** (ungu)
- **Badge "Live Cluster Metrics"** (abu-abu)
- **Badge "Auto Refresh (3s)"** (hijau) - indikator refresh otomatis
- **Judul**: "Ringkasan Sistem E-Voting"
- **Subtitle**: "Visualisasi statistik real-time partisipasi pemilih, distribusi DPT, status event pemilihan sekolah, dan kontrol terpusat"
- **Tombol**: "Auto 3s (Aktif)" dengan icon refresh

**4 Metric Cards (Statistik Real-time):**

| Card | Konten | Keterangan |
|------|--------|------------|
| **Admin & Petugas** | **7** total<br>3 Super Admin • 4 Petugas | Jumlah pengelola sistem |
| **Total DPT (Voters)** | **16** total<br>16 Aktif • 0 Nonaktif | Data Pemilih Tetap |

**Chart Section:**
- **Heading**: "Distribusi Master DPT (Siswa vs Guru)"
- **Pie Chart**: 
  - Siswa: 69% (11 Orang) - warna dominan
  - Guru & Staf: 31% (5 Orang)

**User Profile Section (Sidebar):**
- Nama: "Super Admin Utama"
- Email: "admin@sekolah.sch.id"
- Tombol: "Logout"

**API Endpoint:**
- `GET /api/v1/admin/dashboard/stats` - Fetch real-time metrics

---

#### **2. Manajemen Super Admin**

**Header:**
- **Badge**: "Highest Privilege"
- **Judul**: "Manajemen Super Admin"
- **Deskripsi**: "Kelola akun administrator level tertinggi yang memiliki akses penuh ke seluruh master data dan audit security."
- **Tombol**: "Tambah Super Admin" (hijau, pojok kanan)

**Tabel Data Super Admin:**

| Kolom | Keterangan |
|-------|------------|
| **Nama Super Admin** | Nama lengkap admin |
| **Email Login** | Email untuk login |
| **Role System** | Badge "SUPER_ADMIN" (ungu) |
| **Aksi** | • Icon Edit (pensil)<br>• Icon Hapus (trash) |

**Contoh Data:**
- RIFA | khoirul@gmail.com | SUPER_ADMIN | [Edit] [Hapus]
- SYARIF KHOIRUL RIFA | arip123@gmail.com | SUPER_ADMIN | [Edit] [Hapus]
- Super Admin Utama | admin@sekolah.sch.id | SUPER_ADMIN | [Edit] [Hapus Disabled]

**Proteksi:**
- Akun yang sedang login menampilkan badge **"Akun Anda"** dan tombol hapus disabled dengan tooltip "Tidak dapat menghapus akun Anda sendiri"

**API Endpoints:**
- `GET /api/v1/admin/super-admins` - List semua super admin
- `POST /api/v1/admin/super-admins` - Tambah super admin baru
- `PUT /api/v1/admin/super-admins/{id}` - Edit super admin
- `DELETE /api/v1/admin/super-admins/{id}` - Hapus super admin

---

#### **3. Manajemen Petugas**

**Header:**
- **Judul**: "Akun Pengelola Petugas"
- **Deskripsi**: "Super Admin berwenang membuat, mereset password, dan mengelola hak akses Petugas Pemilihan."
- **Tombol**: "Tambah Akun Petugas" (hijau)

**Tabel Data Petugas:**

| Kolom | Keterangan |
|-------|------------|
| **Nama Petugas** | Nama lengkap petugas |
| **Email Login** | Email untuk login |
| **Role System** | Badge "PETUGAS" (biru) |
| **Aksi** | • Icon Edit<br>• Icon Hapus |

**Contoh Data:**
- SYARIF KHOIRUL RIFA | arip1234@gmail.com | PETUGAS
- Petugas kucing | petugas50@gmail.com | PETUGAS
- Petugas OSIS 2 | petugas2@sekolah.sch.id | PETUGAS
- Petugas OSIS 1 | petugas1@sekolah.sch.id | PETUGAS

**Fitur:**
- Edit: Update nama, email, reset password
- Hapus: Konfirmasi sebelum menghapus (validasi jika petugas masih mengelola event aktif)

**API Endpoints:**
- `GET /api/v1/admin/petugas` - List semua petugas
- `POST /api/v1/admin/petugas` - Tambah petugas baru
- `PUT /api/v1/admin/petugas/{id}` - Edit petugas
- `DELETE /api/v1/admin/petugas/{id}` - Hapus petugas

---

#### **4. Master Pemilih (DPT)**

**Header:**
- **Judul**: "Master Data Pemilih (Siswa & Guru)"
- **Deskripsi**: "Kelola DPT NIS/NIP, & impor data masal CSV."
- **Tombol Action Group**:
  - "Import CSV" (biru) - Upload file CSV untuk bulk import
  - "Export CSV DPT" (hijau) - Download semua data pemilih
  - "Tambah Pemilih" (hijau) - Tambah manual satu per satu

**Filter & Search:**
- **Search Box**: "Cari NIS / NIP / Nama Pemilih..." dengan icon search
- **Dropdown Filter**: "Semua Tipe" | "Siswa" | "Guru"

**Tabel Data Pemilih:**

| Kolom | Keterangan |
|-------|------------|
| **NIS / NIP** | Identifier unik (biru bold) |
| **Nama Pemilih** | Nama lengkap |
| **Tipe** | Badge "SISWA" (cyan) atau "GURU" (ungu) |
| **Status Hak Akses** | Tombol toggle "Aktif" (hijau) / "Nonaktif" (merah) |
| **Aksi** | • Icon Edit<br>• Icon Hapus |

**Contoh Data:**
- **010109** | SYARIF KHOIRUL RIFA | SISWA | [Aktif ✓] | [Edit] [Hapus]
- **2005** | Guru 2005 | GURU | [Aktif ✓] | [Edit] [Hapus]
- **2004** | Guru 2004 | GURU | [Aktif ✓] | [Edit] [Hapus]
- **2003** | Guru 2003 | GURU | [Aktif ✓] | [Edit] [Hapus]

**Fitur Status Toggle:**
- Klik tombol "Aktif" → Ubah jadi "Nonaktif" (voter tidak bisa login)
- Klik tombol "Nonaktif" → Ubah jadi "Aktif" (voter bisa login kembali)

**API Endpoints:**
- `GET /api/v1/admin/voters` - List semua voter dengan search & filter
- `POST /api/v1/admin/voters` - Tambah voter manual
- `POST /api/v1/admin/voters/import` - Import bulk CSV
- `GET /api/v1/admin/system/voters/export` - Export CSV (link direct download)
- `PUT /api/v1/admin/voters/{id}` - Edit voter
- `DELETE /api/v1/admin/voters/{id}` - Hapus voter
- `PATCH /api/v1/admin/voters/{id}/toggle-status` - Toggle aktif/nonaktif


---

#### **6. Audit Log & Security (Jejak Digital)**

**Header:**
- **Judul**: "Audit Log & Security (Jejak Digital)"
- **Deskripsi**: "Log sistem otomatis mencatat seluruh aktivitas sensitif yang dilakukan oleh Petugas dan Super Admin demi keamanan & transparansi."

**Search Bar:**
- Placeholder: "Cari aktivitas / nama pengguna / aksi..."
- Icon search

**Tabel Audit Log:**

| Kolom | Keterangan |
|-------|------------|
| **Waktu** | Timestamp dengan icon clock (contoh: "24/9/2026, 19.48.40") |
| **Pengguna** | Nama user yang melakukan aksi |
| **Role** | Badge role (PETUGAS / SUPER_ADMIN) |
| **Aksi (Action)** | Type aksi (contoh: "CHANGE_EVENT_STATUS", "CREATE_EVENT") |
| **Deskripsi Aktivitas** | Detail lengkap aksi yang dilakukan |
| **IP Address** | IP address user (contoh: "127.0.0.1") |

**Contoh Log Entries:**
- 24/9/2026, 19.48.40 | Petugas OSIS 1 | PETUGAS | **CHANGE_EVENT_STATUS** | "Mengubah status event 'Pemilihan Ketua Estrakurikuler Basket' menjadi PUBLISHED" | 127.0.0.1
- 24/9/2026, 18.34.51 | Petugas OSIS 1 | PETUGAS | **CHANGE_EVENT_STATUS** | "Mengubah status event 'Pemilihan Ketua Estrakurikuler Voli' menjadi CLOSED" | 127.0.0.1
- 21/9/2026, 21.16.42 | Petugas OSIS 1 | PETUGAS | **CREATE_EVENT** | "Membuat event baru 'Pemilihan Ketua Estrakurikuler Futsal' (Kode Room: FIXL7H)" | 127.0.0.1
- 21/9/2026, 20.36.49 | Petugas OSIS 1 | PETUGAS | **DELETE_EVENT** | "Menghapus event 'Pemilihan Ketua Estrakurikuler Silat' beserta data terkait" | 127.0.0.1
- 21/9/2026, 20.31.59 | Petugas OSIS 1 | PETUGAS | **UPDATE_EVENT** | "Memperbarui data event 'Pemilihan Ketua Estrakurikuler Silat'" | 127.0.0.1

**Action Types yang Tercatat:**
- `CHANGE_EVENT_STATUS` - Perubahan status event
- `CREATE_EVENT` - Pembuatan event baru
- `DELETE_EVENT` - Penghapusan event
- `UPDATE_EVENT` - Update data event

**API Endpoints:**
- `GET /api/v1/admin/audit-logs` - List audit logs dengan search & pagination
- `GET /api/v1/admin/audit-logs/export` - Export logs ke CSV

---


#### **Navigasi Sidebar (7 Menu Utama)**

| No | Menu | Icon | Route |
|----|------|------|-------|
| 1 | **Dashboard Ringkasan** | 📊 Grid | `/admin/dashboard` |
| 2 | **Manajemen Super Admin** | 🛡️ Shield | `/admin/super-admins` |
| 3 | **Manajemen Petugas** | 👥 Users | `/admin/petugas` |
| 4 | **Master Pemilih (DPT)** | 👤 User | `/admin/voters` |
| 6 | **Audit Log & Security** | 📋 Clipboard | `/admin/audit-logs` |


**Layout:**
- Sidebar kiri dengan logo "E-Voting Admin" dan badge "SUPER ADMIN"
- Content area utama di kanan
- User profile di bawah sidebar (nama, email, logout)
- Semua menu menggunakan **single-page interface** dengan konten yang dimuat dinamis

---

#### **Keamanan & Proteksi Super Admin**

1. **Self-Deletion Protection**: Admin tidak bisa menghapus akun sendiri
2. **Role Guard Isolated**: Session admin dan voter terpisah total
3. **Audit Trail**: Semua aksi admin tercatat otomatis
4. **IP Tracking**: IP address tercatat di setiap log
5. **Token-Based Auth**: Laravel Sanctum dengan bearer token

------------------------------------------------------------------------------------------------------------------------------------------------------------

### **B. PETUGAS - Fitur Lengkap**

#### **1. Halaman Daftar Event (Main Dashboard)**

Halaman utama petugas menampilkan **semua event dalam bentuk card list**. Setiap event card berisi informasi lengkap dan tombol aksi.

**Header Section:**
- Judul: "Kelola Event Pemilihan"
- Deskripsi: "Buat Event Voting, Atur Waktu & Kategori Manual, Tambah/Edit KANDIDAT Kandidat beserta Foto/Visi-Misi, dan Pantau Real-time"
- Tombol: **"Buat Event & KANDIDAT Baru"** (di kanan atas)

**Filter & Search Section:**
- **Search Bar**: Cari judul, kode room
- **Filter Status**: Semua | DRAFT | PUBLISHED | CLOSED

**Event Cards Section:**

Setiap **Event Card** menampilkan:

| Komponen | Deskripsi |
|----------|-----------|
| **Badge Status** | DRAFT / PUBLISHED / CLOSED (warna berbeda) |
| **Badge Kategori** | KANDIDAT / OPSI |
| **Room Code** | Contoh: "KODE: FIXL7H" dengan icon 🔑 |
| **Tombol Hapus** | Icon 🗑️ di pojok kanan atas card |
| **Judul Event** | Heading besar, contoh: "Pemilihan Ketua OSIS" |
| **Deskripsi** | Ringkasan deskripsi event |
| **Info Waktu** | • Mulai: [tanggal] atau "-"<br>• Selesai: [tanggal] atau "-" |
| **Info Kandidat/Opsi** | "Terdaftar: X KANDIDAT/Opsi" |

**Tombol Aksi di setiap Event Card:**

| Tombol | Fungsi | API Endpoint |
|--------|--------|--------------|
| **"Edit"** | Buka modal edit event (judul, deskripsi, waktu) | `PUT /api/v1/petugas/events/{id}` |
| **"Publish Event"** | Publikasikan event (muncul jika status DRAFT) | `PATCH /api/v1/petugas/events/{id}/status` |
| **"+ KANDIDAT"** | Buka modal tambah kandidat baru | `POST /api/v1/petugas/events/{eventId}/candidates` |
| **"Live Monitor & Rekapitulasi Suara"** | Redirect ke halaman monitoring real-time | Navigate ke `/petugas/events/{id}/monitor` |
| **Icon Hapus (🗑️)** | Hapus event dengan konfirmasi | `DELETE /api/v1/petugas/events/{id}` |

#### **2. API Endpoints untuk Halaman Daftar Event**

| Endpoint | Method | Fungsi |
|----------|--------|--------|
| `/api/v1/petugas/events` | GET | Fetch semua event (created + assigned) dengan filter & search |
| `/api/v1/petugas/events` | POST | Buat event baru (auto-generate room_code) |
| `/api/v1/petugas/events/{id}` | GET | Detail event (untuk modal edit) |
| `/api/v1/petugas/events/{id}` | PUT | Update event (judul, deskripsi, waktu) |
| `/api/v1/petugas/events/{id}` | DELETE | Hapus event |
| `/api/v1/petugas/events/{id}/status` | PATCH | Update status (DRAFT → PUBLISHED → CLOSED) |

#### **3. Modal: Buat Event Baru**

Form input yang muncul saat klik **"Buat Event & KANDIDAT Baru"**:

| Field | Type | Keterangan |
|-------|------|------------|
| **Judul Event** | text (required) | Nama event, contoh: "Pemilihan Ketua OSIS 2026" |
| **Kategori** | select (required) | Pilihan: KANDIDAT / OPSI |
| KODE ROOM *OPSIONAL (JIKA DIISI BERARTI MENGIKUTI KODE YANG DIISINYA, JIKA TIDAK MAKA KODENYA AKAN ACAK )|
| **Deskripsi** | textarea (optional) | Deskripsi lengkap event |
| **Waktu Mulai** | datetime (optional) | Jika di set Waktu mulai dan selesai maka gamuncul tombol publish dan juga close karena harus mengikuti Waktu mulai dan selesai (JIKA ADA SALAH DALAM MENGINPUT SET WAKTU MULAI DAN SELESAI HANYA BISA DILAKUKAN DI TOMBOL/FITUR EDIT) DAN Jika tidak di set Waktu mulai dan selesai maka muncul tombol publish (HARUS MANUAL JADINYA KARENA GA DI SET WAKTU MULAI DAN SELESAI)  |
| **Waktu Selesai** | datetime (optional) | Jika di set Waktu mulai dan selesai maka gamuncul tombol publish dan juga close karena harus mengikuti Waktu mulai dan selesai (JIKA ADA SALAH DALAM MENGINPUT SET WAKTU MULAI DAN SELESAI HANYA BISA DILAKUKAN DI TOMBOL/FITUR EDIT) DAN Jika tidak di set Waktu mulai dan selesai maka muncul tombol publish (HARUS MANUAL JADINYA KARENA GA DI SET WAKTU MULAI DAN SELESAI) |

**Auto-generated saat submit:**
- **Room Code**: 6 karakter unique (contoh: "FIXL7H") (JIKA DIISI BERARTI MENGIKUTI KODE YANG DIISINYA, JIKA TIDAK MAKA KODENYA AKAN ACAK )


#### **4. Modal: Edit Event**

Form edit yang muncul saat klik tombol **"Edit"** di card:

| Field | Type | Keterangan |
|-------|------|------------|
| **Judul Event** | text | Update judul |
| **Deskripsi** | textarea | Update deskripsi |
| **Waktu Mulai** | datetime | Update waktu mulai |
| **Waktu Selesai** | datetime | Update waktu selesai |

**Tombol:**
- **"Simpan Perubahan"** → PUT request
- **"Batal"** → Close modal

#### **5. Modal: Tambah Kandidat DAN EDIT DAN JUGA HAPUS**

Form yang muncul saat klik **" KELOLA KANDIDAT"** di card:

**Tombol:**
- **"Tambah Kandidat"** → POST request 

| Field | Type | Keterangan |
|-------|------|------------|
| **Nama Kandidat** | text (required) | Nama lengkap kandidat |
| **No Urut** | number (required) | Auto-increment, bisa diubah |
| **Visi** | textarea (optional) | Untuk kategori KANDIDAT |
| **Misi** | textarea (optional) | Untuk kategori KANDIDAT |
| **Deskripsi** | textarea (optional) | Untuk kategori OSI |
| **Foto** | file upload (optional) | Max 2MB, format: jpg, png, webp |

**Edit & Hapus Kandidat:**
- Edit: Klik kandidat → Update via `PUT /api/v1/petugas/candidates/{id}`

| Field | Type | Keterangan |
|-------|------|------------|
| **Nama Kandidat** | text (required) | Nama lengkap kandidat |
| **No Urut** | number (required) | Auto-increment, bisa diubah |
| **Visi** | textarea (optional) | Untuk kategori KANDIDAT |
| **Misi** | textarea (optional) | Untuk kategori KANDIDAT |
| **Deskripsi** | textarea (optional) | Untuk kategori OSI |
| **Foto** | file upload (optional) | Max 2MB, format: jpg, png, webp |

- Hapus: Icon hapus → Konfirmasi → `DELETE /api/v1/petugas/candidates/{id}`

SEMUA KANDIDAT/OPSI SEMUANYA TERLIHAT


#### **7. Kontrol Status Event**

**Status Flow:**
```
┌──────────┐ Publish/Auto   ┌───────────┐ Close/Auto   ┌────────┐
│  DRAFT   │───────────────>│ PUBLISHED │────────────>│ CLOSED │
└──────────┘                └───────────┘              └────────┘

```

**Tombol yang muncul di card berdasarkan status:** (BERLAKU JIKA TIDAK MENGINPUT WAKTU MULAI DAN SELESAI)

| Status Event | Tombol yang Tampil | Aksi |
|--------------|-------------------|------|
| **DRAFT** | "Publish Event" | Ubah status ke PUBLISHED |
| **PUBLISHED** | "Tutup Event" (manual) | Ubah status ke CLOSED |

**Auto-Update Status:**
- DRAFT → PUBLISHED: Otomatis saat `start_time` tercapai
- PUBLISHED → CLOSED: Otomatis saat `end_time` tercapai

#### **8. Halaman Monitoring Real-time** (Halaman Terpisah)

Saat klik **"Live Monitor & Rekapitulasi Suara"** di card, redirect ke halaman monitoring:

**URL**: `/petugas/events/{eventId}/monitor`

**API**: `GET /api/v1/petugas/events/{eventId}/monitor`

**Tampilan:**
- **Stats Section**:
  - Total Voter Sudah Vote: X 
- **Chart Section**:
  - Bar chart perolehan suara per kandidat
  - Persentase setiap kandidat
- **Live Vote List**:
  - Tabel voter yang sudah vote (nama, waktu vote)
  - Auto-refresh setiap 3-5 detik
- **Export Button**:
  - "Export ke Excel" → `GET /api/v1/petugas/events/{eventId}/export` (OPSIONAL, KATANYA PAKE PACKAGE)

#### **9. Room Code Management**

**Di setiap Event Card**, room code ditampilkan dengan format:
```
🔑 KODE: FIXL7H
```

**Fitur:**
- **Copy Room Code**: Klik untuk copy ke clipboard

Room code **tidak bisa diubah** setelah event dibuat (untuk menjaga konsistensi).

------------------------------------------------------------------------------------------------------------------------------------------------------------

### **C. VOTER - Fitur Lengkap** 

#### **1. Halaman Login Voter**

**URL**: `/voter/login`

**Tampilan:**
- **Header Section**:
  - Logo aplikasi
  - Heading: "Login Pemilih (Voter)"
  - Subtitle: "Bilik Suara Digital Siswa & Guru"
- **Form Login**:
  - Label: "Nomor NIS / NIP"
  - Input field dengan placeholder: "Contoh: 1001 (Siswa) / 2001 (Guru)"
  - Tombol: **"Masuk Bilik Suara Digital"** dengan icon 🗳️
- **Footer Link**: "Login Petugas Panitia atau Administrator? Masuk Di Sini"

**API**: `POST /api/v1/auth/voter/login`

**Validasi:**
- Identifier exist? ✓
- Password benar? ✓
- Voter status aktif? ✓

#### **2. Halaman Join Event (Input Room Code)**

**URL**: `/join`

Setelah login berhasil, voter diarahkan ke halaman ini untuk memasukkan kode room event.

**Header Section:**
- **Logo & Title**: "E-VOTING SYSTEM"
- **Subtitle**: "Bilik Suara Pemilih"
- **Tombol**: Logout

**Profile Card:**
- **Icon**: 👤
- **Label**: "Pemilih Terverifikasi"
- **Nama**: Nama lengkap voter (contoh: "Siswa 1001")
- **Identifier**: Nomor NIS/NIP (contoh: "1001")

**Main Section:**
- **Icon**: 🗳️
- **Heading**: "Masuk Bilik Suara"
- **Instruksi**: "Silakan masukkan **Kode Room 6 digit** yang diberikan oleh panitia / petugas untuk mulai memilih."
- **Form Input**:
  - Label: "KODE ROOM PEMILIHAN"
  - Input field dengan placeholder "CONTOH"
  - Auto-uppercase format
  - Helper text: "Huruf kapital otomatis terformat. Pastikan koneksi internet stabil."
  - Tombol: **"Buka Bilik Suara"** (disabled jika input kosong)



**API**: `POST /api/v1/voter/events/join`

**Validasi:**
- Event exist dengan room_code? ✓
- Event status PUBLISHED? ✓
- Waktu mulai sudah lewat? ✓
- Waktu selesai belum lewat? ✓
- Voter belum join event ini? ✓

**Success**: Insert ke `event_participations` → Redirect ke halaman voting

**Error Messages:**
- "Event tidak ditemukan"
- "Event belum dimulai"
- "Event sudah selesai"
- "Anda sudah join event ini"

#### **3. Halaman Voting (Bilik Suara Digital)**

**URL**: `/vote/{eventId}`

**API**: `GET /api/v1/voter/events/{eventId}`

**Header Section:**
- **Logo & Title**: "BILIK SUARA DIGITAL"
- **Badge Room Code**: Kode room (contoh: "GVOBTY")
- **Info Pemilih**: "Pemilih: **Siswa 1001** (1001)"
- **Tombol**: Ganti Room, Logout

**Event Info Section:**
- **Badge Kategori**: "Kategori: Pemilihan KANDIDAT" (atau "OPSI")
- **Badge Status**: 
  - "SIAP MENCOBLOS" (jika belum vote, warna hijau)
  - "SUDAH MEMILIH" (jika sudah vote, warna biru)
- **Judul Event**: Heading besar (contoh: "Pemilihan Ketua Estrakurikuler Basket")
- **Deskripsi**: Text deskripsi event
- **Verifikasi Box**:
  - "KODE ROOM": Room code
  - Icon ✓ "Terverifikasi"

**Kandidat Section:**

**Header:**
- Heading: "Pilih KANDIDAT Kandidat Terbaik Anda"
- Instruksi: "Klik tombol **Coblos** pada kartu di bawah untuk mengirimkan hak suara"
- Info: "Total X KANDIDAT"

**Kandidat Cards** (Grid layout):

Setiap card menampilkan:

| Komponen | Detail |
|----------|--------|
| **Badge Header** | • No Urut: "#1", "#2", "#3" (badge hijau)<br>• Label: "KANDIDAT 1", "KANDIDAT 2", dll |
| **Badge Dipilih** | Muncul icon ✓ "Dipilih" saat kandidat di-highlight |
| **Foto Kandidat** | • Foto kandidat (jika ada)<br>• Placeholder: "Tanpa Foto Lampiran" dengan icon 📷 |
| **Nama Kandidat** | Heading besar nama kandidat |
| **Visi & Misi** | (untuk kategori KANDIDAT)<br>• "Visi KANDIDAT:" + text visi<br>• "Misi KANDIDAT:" + text misi |
| **Deskripsi** | (untuk kategori OPSI)<br>• Text deskripsi opsi |
| **Tombol Coblos** | • "COBLOS KANDIDAT #X" dengan icon 🗳️<br>• Warna hijau, full width<br>• Disabled jika sudah vote |

**Jika Sudah Vote:**
- Semua tombol "COBLOS" disabled
- Badge status berubah "SUDAH MEMILIH"
- Kandidat yang dipilih di-highlight
- Pesan: "Anda sudah memberikan suara"

#### **4. Modal Konfirmasi Voting**

Muncul saat voter klik tombol "COBLOS KANDIDAT #X"

**Tampilan Modal:**
- **Icon**: ☑️ (ballot box)
- **Heading**: "Konfirmasi Pilihan Suara"
- **Subtitle**: "Pastikan pilihan Anda sudah benar sebelum mengirimkan ke sistem."
- **Kandidat Dipilih**:
  - Badge: "KANDIDAT #X"
  - Nama kandidat (heading besar)
- **Warning Box** (dengan icon ⚠️):
  - "Pilihan suara bersifat **permanen**, otomatis dienkripsi, dan tidak dapat diubah setelah dicoblos."
- **Tombol Aksi**:
  - "Batal" (abu-abu, tutup modal)
  - "Ya, Coblos Sekarang!" (hijau, dengan icon ✓)

**API**: `POST /api/v1/voter/events/{eventId}/vote`

**Payload**: `{ candidate_id: X }`

**Backend Validation:**
- Event masih PUBLISHED? ✓
- Waktu voting valid? ✓
- Voter belum vote? ✓
- Candidate valid & belongs to event? ✓

**Success Response:**
- Insert ke table `votes`
- Update `voted_at` di `event_participations`
- Audit log: `vote.cast`
- Redirect ke halaman success

**Error Response (422):**
- "Event sudah ditutup"
- "Anda sudah memberikan suara"
- "Kandidat tidak valid"

#### **5. Halaman Success (Setelah Voting)**

**URL**: `/vote/{eventId}` (same page, different state)

**Tampilan:**

**Icon Section:**
- Icon success besar: ✅ (checkmark in circle, warna hijau)

**Message Section:**
- **Badge**: "HAK SUARA BERHASIL DIGUNAKAN" (warna hijau)
- **Heading**: "Terima Kasih Atas Partisipasi Anda!"
- **Subtitle**: "Pilihan Anda telah terenkripsi secara aman ke dalam sistem e-voting dan tidak dapat diakses atau diubah oleh pihak manapun."

**Info Card** (2 kolom):

| Kolom 1 | Kolom 2 |
|---------|---------|
| **Pemilih** | **Status Suara** |
| Nama: "Siswa 1001" | Icon: 🔒 "Terenkripsi" |
| Identifier: "1001" | Status: "Sah & Tercatat" |


**Tidak ada tombol aksi** - Voter tidak bisa mengubah pilihan atau vote lagi.
