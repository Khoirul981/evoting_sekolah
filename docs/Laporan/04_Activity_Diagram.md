# 4. Activity Diagram - SISTEM E-VOTING "VOTINGYUK"

> **PENTING / DOKUMEN RESMI TERBARU:**  
> Versi Activity Diagram terbaru dengan format **2-Kolom Swimlane UML (`[Aktor]` dan `[System]`)**, notasi standar UML (Status Awal, Aktivitas, Percabangan, Penggabungan, Status Akhir), dan sinkronisasi 100% dengan 12 Halaman UI serta Use Case Diagram telah dipindahkan ke file resmi:  
> **👉 [`docs/Laporan/04_Activity_Diagram_NEW.md`](./04_Activity_Diagram_NEW.md)**

---

## 4.1 Deskripsi Umum

Activity Diagram menggambarkan **alur proses bisnis secara detail** dalam sistem VOTINGYUK. Diagram ini menunjukkan **urutan aktivitas, decision point (percabangan), dan flow control** dari awal hingga akhir untuk setiap proses utama.

Activity diagram berbeda dengan Use Case diagram:
- **Use Case Diagram** → Menampilkan **APA** yang bisa dilakukan user
- **Activity Diagram** → Menampilkan **BAGAIMANA** proses tersebut berjalan step-by-step

---

## 4.2 Activity Diagram Utama

### 4.2.1 Proses Login Multi-Guard

```
                    ┌─────────────────┐
                    │  User Buka      │
                    │  Aplikasi       │
                    └────────┬────────┘
                             │
                             ▼
                    ┌─────────────────┐
                    │  Pilih Jenis    │
                    │  Login          │
                    └────────┬────────┘
                             │
                ┌────────────┴────────────┐
                │                         │
                ▼                         ▼
    ┌────────────────────┐    ┌────────────────────┐
    │  Login Admin/      │    │  Login Pemilih     │
    │  Petugas           │    │  (Voter)           │
    └─────────┬──────────┘    └─────────┬──────────┘
              │                          │
              ▼                          ▼
    ┌────────────────────┐    ┌────────────────────┐
    │  Input:            │    │  Input:            │
    │  - Email           │    │  - Room Code       │
    │  - Password        │    │  - NIS/NIP         │
    │  - Remember Me?    │    │  - Password        │
    └─────────┬──────────┘    └─────────┬──────────┘
              │                          │
              ▼                          ▼
    ┌────────────────────┐    ┌────────────────────┐
    │  Validate:         │    │  Validate:         │
    │  - Email exist?    │    │  - Room Code valid?│
    │  - Password match? │    │  - NIS/NIP exist?  │
    │  - User active?    │    │  - Password match? │
    └─────────┬──────────┘    └─────────┬──────────┘
              │                          │
         ◇────┴────◇              ◇─────┴─────◇
         │ Valid?  │              │  Valid?   │
         └─────────┘              └───────────┘
         │         │              │           │
      [YES]      [NO]          [YES]        [NO]
         │         │              │           │
         │         └──────┐       │      ┌────┘
         │                │       │      │
         ▼                ▼       ▼      ▼
    ┌─────────┐   ┌──────────┐  ┌─────────┐  ┌──────────┐
    │ Set     │   │  Show    │  │ Set     │  │  Show    │
    │ Session │   │  Error   │  │ Session │  │  Error   │
    │ (Guard  │   │  Message │  │ (Guard  │  │  Message │
    │  web)   │   └────┬─────┘  │  voter) │  └────┬─────┘
    └────┬────┘        │        └────┬────┘       │
         │             │             │            │
         ▼             │             ▼            │
    ┌─────────┐        │        ┌─────────┐      │
    │Remember │        │        │ Redirect│      │
    │Me = Yes?│        │        │ to      │      │
    └────┬────┘        │        │ Voter   │      │
         │             │        │Dashboard│      │
    ◇────┴────◇        │        └─────────┘      │
    │ Checked?│        │                         │
    └─────────┘        │                         │
    │         │        │                         │
  [YES]     [NO]       │                         │
    │         │        │                         │
    ▼         ▼        │                         │
┌────────┐ ┌────────┐ │                         │
│Set     │ │Continue│ │                         │
│Cookie  │ │        │ │                         │
│Token   │ │        │ │                         │
└───┬────┘ └───┬────┘ │                         │
    │          │      │                         │
    └────┬─────┘      │                         │
         ▼            │                         │
    ┌─────────┐       │                         │
    │Check    │       │                         │
    │Role     │       │                         │
    └────┬────┘       │                         │
         │            │                         │
    ◇────┴────◇       │                         │
    │  Role?  │       │                         │
    └─────────┘       │                         │
    │         │       │                         │
[SuperAdmin][Petugas] │                         │
    │         │       │                         │
    ▼         ▼       │                         │
┌─────────┐┌─────────┐│                         │
│Admin    ││Petugas  ││                         │
│Dashboard││Events   ││                         │
│         ││List Page││                         │
└─────────┘└─────────┘│                         │
                      │                         │
                      └─────────────────────────┘
                                  │
                                  ▼
                          ┌───────────────┐
                          │  Back to      │
                          │  Login Form   │
                          └───────────────┘
```

---

### 4.2.2 Proses Petugas Membuat Event & Kandidat

```
                    ┌─────────────────┐
                    │  Petugas Login  │
                    │  Success        │
                    └────────┬────────┘
                             │
                             ▼
                    ┌─────────────────┐
                    │  Dashboard      │
                    │  Petugas        │
                    └────────┬────────┘
                             │
                             ▼
                    ┌─────────────────┐
                    │  Klik "Buat     │
                    │  Event Baru"    │
                    └────────┬────────┘
                             │
                             ▼
        ┌────────────────────────────────────────────┐
        │  Form Input Event:                         │
        │  - Title (required)                        │
        │  - Description (optional)                  │
        │  - Category (KANDIDAT / OPSI)              │
        │  - Start Time (optional, default=manual)   │
        │  - End Time (optional, default=manual)     │
        └────────────────┬───────────────────────────┘
                         │
                         ▼
        ┌────────────────────────────────────────────┐
        │  Backend Process:                          │
        │  1. Validate Input                         │
        │  2. Check Authorization (created_by)       │
        │  3. Auto-generate Room Code (6 char)       │
        │  4. Set Status = DRAFT                     │
        │  5. Save to Database                       │
        │  6. Create Audit Log Entry                 │
        └────────────────┬───────────────────────────┘
                         │
                         ▼
                ┌────────────────┐
                │  Event Created │
                │  (DRAFT)       │
                └────────┬───────┘
                         │
                         ▼
        ┌────────────────────────────────────────────┐
        │  Redirect ke Halaman                       │
        │  Kelola Kandidat/Opsi                      │
        └────────────────┬───────────────────────────┘
                         │
                         ▼
        ┌────────────────────────────────────────────┐
        │  Klik "Tambah Kandidat/Opsi"               │
        └────────────────┬───────────────────────────┘
                         │
                         ▼
        ┌────────────────────────────────────────────┐
        │  Form Input Kandidat/Opsi:                 │
        │  - Nomor Urut (required, unique per event) │
        │  - Nama (required)                         │
        │  - Visi (optional, for KANDIDAT)           │
        │  - Misi (optional, for KANDIDAT)           │
        │  - Deskripsi (optional, for OPSI)          │
        │  - Foto (optional, max 2MB)                │
        └────────────────┬───────────────────────────┘
                         │
                         ▼
                ◇────────────────◇
                │  Foto Upload?  │
                └────────────────┘
                │              │
              [YES]          [NO]
                │              │
                ▼              │
        ┌──────────────┐       │
        │  Validate:   │       │
        │  - Size≤2MB  │       │
        │  - Format OK │       │
        │  - Store to  │       │
        │    storage/  │       │
        │    candidates│       │
        └──────┬───────┘       │
               │               │
               └───────┬───────┘
                       │
                       ▼
        ┌────────────────────────────────────────────┐
        │  Backend Process:                          │
        │  1. Validate all fields                    │
        │  2. Check nomor urut unique                │
        │  3. Save candidate to DB                   │
        │  4. Create Audit Log                       │
        └────────────────┬───────────────────────────┘
                         │
                         ▼
            ◇────────────────────────◇
            │  Tambah Kandidat Lagi? │
            └────────────────────────┘
            │                        │
          [YES]                    [NO]
            │                        │
            │                        ▼
            │            ┌───────────────────┐
            │            │  Total Kandidat   │
            │            │  Minimal 1?       │
            │            └─────────┬─────────┘
            │                      │
            │                 ◇────┴────◇
            │                 │ >= 1?   │
            │                 └─────────┘
            │                 │         │
            │               [YES]     [NO]
            │                 │         │
            │                 ▼         ▼
            │            ┌─────────┐┌────────────┐
            │            │Bisa     ││Show Warning│
            │            │Publish  ││Can't Publish│
            │            └────┬────┘└─────┬──────┘
            │                 │           │
            │                 ▼           │
            │            ┌─────────┐      │
            │            │Klik     │      │
            │            │Publish  │      │
            │            └────┬────┘      │
            │                 │           │
            │                 ▼           │
            │      ┌────────────────────┐ │
            │      │ Update Status =   │ │
            │      │ PUBLISHED         │ │
            │      │ Create Audit Log  │ │
            │      └─────────┬──────────┘ │
            │                │            │
            │                ▼            │
            │      ┌────────────────────┐ │
            │      │ Event PUBLISHED   │ │
            │      │ Siap untuk Voting │ │
            │      └─────────┬──────────┘ │
            │                │            │
            └────────────────┤            │
                             │            │
                             ▼            │
                    ┌─────────────────┐   │
                    │  Bagikan Room   │   │
                    │  Code ke Voter  │   │
                    └─────────────────┘   │
                                          │
                                          ▼
                                  ┌───────────────┐
                                  │  Kembali ke   │
                                  │  Form Tambah  │
                                  └───────────────┘
```

---

### 4.2.3 Proses Voter Melakukan Voting

```
                    ┌─────────────────┐
                    │  Voter Login    │
                    │  (HANYA NIS/NIP)│
                    │  NO PASSWORD    │
                    └────────┬────────┘
                             │
                             ▼
                    ┌─────────────────┐
                    │  Halaman Input  │
                    │  Room Code      │
                    └────────┬────────┘
                             │
                             ▼
        ┌───────────────────┐
        │  Voter Input      │
        │  Room Code        │
        └─────────┬─────────┘
                  │
                  ▼
        ┌───────────────────────────────────┐
        │  Backend Check:                   │
        │  1. Room Code valid?              │
        │  2. Event status = PUBLISHED?     │
        │  3. Voter already voted?          │
        └─────────┬─────────────────────────┘
                  │
             ◇────┴────◇
             │  Valid? │
             └─────────┘
             │         │
           [YES]     [NO]
             │         │
             │         ▼
             │    ┌──────────────┐
             │    │  Show Error: │
             │    │  - Invalid   │
             │    │    room code │
             │    │  - Already   │
             │    │    voted     │
             │    │  - Event not │
             │    │    active    │
             │    └──────┬───────┘
             │           │
             │           ▼
             │    ┌──────────────┐
             │    │  Back to     │
             │    │  Room Input  │
             │    └──────────────┘
             │
             ▼
    ┌────────────────────────┐
    │  Masuk Bilik Suara     │
    │  (Voting Booth)        │
    └────────┬───────────────┘
             │
             ▼
    ┌────────────────────────────────────┐
    │  Tampilkan:                        │
    │  - Detail Event                    │
    │  - Daftar Kandidat/Opsi            │
    │  - Foto, Visi, Misi, Deskripsi     │
    │  - Nomor Urut                      │
    └────────┬───────────────────────────┘
             │
             ▼
    ┌────────────────────────┐
    │  Voter Lihat Semua     │
    │  Pilihan               │
    └────────┬───────────────┘
             │
             ▼
        ◇────────────◇
        │  Sudah     │
        │  Yakin?    │
        └────────────┘
        │          │
      [NO]       [YES]
        │          │
        │          ▼
        │  ┌───────────────┐
        │  │  Klik Tombol  │
        │  │  "Coblos"     │
        │  └───────┬───────┘
        │          │
        │          ▼
        │  ┌───────────────────────┐
        │  │  Modal Konfirmasi:    │
        │  │  "Yakin pilih [Nama]?"│
        │  └───────┬───────────────┘
        │          │
        │     ◇────┴────◇
        │     │ Confirm?│
        │     └─────────┘
        │     │         │
        │   [NO]      [YES]
        │     │         │
        └─────┘         ▼
                ┌───────────────────────────┐
                │  Backend Process:         │
                │  1. Validate voter        │
                │  2. Check not already voted│
                │  3. Insert to votes table │
                │  4. Set voted_at = now()  │
                │  5. Create audit log      │
                │  6. Record participation  │
                └───────────┬───────────────┘
                            │
                            ▼
                   ◇────────────────◇
                   │  Insert        │
                   │  Success?      │
                   └────────────────┘
                   │              │
                 [YES]          [NO]
                   │              │
                   ▼              ▼
          ┌────────────────┐  ┌──────────────┐
          │  Redirect to   │  │  Show Error  │
          │  Success Page  │  │  Message     │
          └────────┬───────┘  └──────┬───────┘
                   │                 │
                   ▼                 │
          ┌────────────────┐         │
          │  Show Success  │         │
          │  Confirmation  │         │
          │  - Timestamp   │         │
          │  - Event Name  │         │
          │  - Candidate   │         │
          └────────┬───────┘         │
                   │                 │
                   ▼                 │
          ┌────────────────┐         │
          │  Link Back to  │         │
          │  Dashboard     │         │
          └────────┬───────┘         │
                   │                 │
                   └─────────┬───────┘
                             │
                             ▼
                    ┌─────────────────┐
                    │  Voting Complete│
                    └─────────────────┘
```

---

### 4.2.4 Proses Live Monitoring Voting (Petugas)

```
                    ┌─────────────────┐
                    │  Petugas Login  │
                    └────────┬────────┘
                             │
                             ▼
                    ┌─────────────────┐
                    │  Dashboard atau │
                    │  Daftar Event   │
                    └────────┬────────┘
                             │
                             ▼
                    ┌─────────────────┐
                    │  Klik "Live     │
                    │  Monitoring"    │
                    └────────┬────────┘
                             │
                             ▼
        ┌────────────────────────────────────────┐
        │  Backend Check Authorization:          │
        │  - Petugas = creator (created_by)?     │
        │  - OR Super Admin?                     │
        └────────────┬───────────────────────────┘
                     │
                ◇────┴────◇
                │Authorized?│
                └──────────┘
                │         │
              [YES]     [NO]
                │         │
                │         ▼
                │    ┌────────────┐
                │    │  403 Error │
                │    │  Forbidden │
                │    └────────────┘
                │
                ▼
    ┌──────────────────────────────────────┐
    │  Load Monitoring Page dengan Data:   │
    │  - Event Detail                      │
    │  - Total Partisipan                  │
    │  - Total Voters (yang sudah vote)    │
    │  - Persentase Partisipasi            │
    │  - Vote Count per Kandidat           │
    │  - Chart Visualisasi (Bar Chart)     │
    └──────────────┬───────────────────────┘
                   │
                   ▼
    ┌──────────────────────────────────────┐
    │  JavaScript Auto-Refresh:            │
    │  - setInterval(fetchData, 5000)      │
    │  - Fetch JSON endpoint setiap 5 detik│
    └──────────────┬───────────────────────┘
                   │
                   ▼
         ┌─────────────────┐
         │  Loop Forever   │
         │  (While Page    │
         │   is Open)      │
         └────────┬────────┘
                  │
                  ▼
         ┌─────────────────┐
         │  Wait 5 seconds │
         └────────┬────────┘
                  │
                  ▼
    ┌──────────────────────────────────────┐
    │  AJAX Request to:                    │
    │  /petugas/monitoring/{id}/data       │
    └──────────────┬───────────────────────┘
                   │
                   ▼
    ┌──────────────────────────────────────┐
    │  Backend Query Database:             │
    │  SELECT candidate_id, COUNT(*)       │
    │  FROM votes                          │
    │  WHERE event_id = ?                  │
    │  GROUP BY candidate_id               │
    └──────────────┬───────────────────────┘
                   │
                   ▼
    ┌──────────────────────────────────────┐
    │  Return JSON:                        │
    │  {                                   │
    │    total_votes: 120,                 │
    │    total_participants: 200,          │
    │    percentage: 60,                   │
    │    candidates: [                     │
    │      {id:1, name:"A", votes:80},     │
    │      {id:2, name:"B", votes:40}      │
    │    ]                                 │
    │  }                                   │
    └──────────────┬───────────────────────┘
                   │
                   ▼
    ┌──────────────────────────────────────┐
    │  JavaScript Update DOM:              │
    │  - Update vote count numbers         │
    │  - Update chart bars                 │
    │  - Update percentages                │
    │  - Smooth animation transition       │
    └──────────────┬───────────────────────┘
                   │
                   ▼
            ◇──────────────◇
            │  Page Still  │
            │  Open?       │
            └──────────────┘
            │            │
          [YES]        [NO]
            │            │
            │            ▼
            │     ┌──────────────┐
            │     │  Stop Loop   │
            │     │  Clear Timer │
            │     └──────────────┘
            │
            └──────┐
                   │
                   ▼
         ┌─────────────────┐
         │  Back to Wait   │
         │  5 seconds      │
         └─────────────────┘
```

---

### 4.2.5 Proses Super Admin Kelola Audit Log

```
                    ┌─────────────────┐
                    │  Super Admin    │
                    │  Login          │
                    └────────┬────────┘
                             │
                             ▼
                    ┌─────────────────┐
                    │  Dashboard      │
                    │  Admin          │
                    └────────┬────────┘
                             │
                             ▼
                    ┌─────────────────┐
                    │  Klik Menu      │
                    │  "Audit Logs"   │
                    └────────┬────────┘
                             │
                             ▼
        ┌────────────────────────────────────────┐
        │  Check Role:                           │
        │  - Middleware: SuperAdminMiddleware    │
        └────────────┬───────────────────────────┘
                     │
                ◇────┴────◇
                │  Super  │
                │  Admin? │
                └─────────┘
                │         │
              [YES]     [NO]
                │         │
                │         ▼
                │    ┌────────────┐
                │    │  403 Error │
                │    │  Forbidden │
                │    └────────────┘
                │
                ▼
    ┌──────────────────────────────────────┐
    │  Load Audit Logs Page                │
    │  Default: Show All (Paginated)       │
    └──────────────┬───────────────────────┘
                   │
                   ▼
            ◇──────────────◇
            │  Apply       │
            │  Filter?     │
            └──────────────┘
            │            │
          [NO]         [YES]
            │            │
            │            ▼
            │   ┌─────────────────────┐
            │   │  Select Filter:     │
            │   │  - Action Type      │
            │   │  - User (dropdown)  │
            │   │  - Date Range       │
            │   │  - Search Keyword   │
            │   └──────────┬──────────┘
            │              │
            │              ▼
            │   ┌─────────────────────────────┐
            │   │  Submit Filter Form         │
            │   └──────────┬──────────────────┘
            │              │
            │              ▼
            │   ┌─────────────────────────────┐
            │   │  Backend Build Query:       │
            │   │  SELECT * FROM audit_logs   │
            │   │  WHERE action = ?           │
            │   │  AND user_id = ?            │
            │   │  AND created_at BETWEEN ?   │
            │   │  ORDER BY created_at DESC   │
            │   │  LIMIT 20 OFFSET ?          │
            │   └──────────┬──────────────────┘
            │              │
            └──────┬───────┘
                   │
                   ▼
    ┌──────────────────────────────────────┐
    │  Display Results:                    │
    │  - Table with columns:               │
    │    * Timestamp                       │
    │    * User (name + role)              │
    │    * Action Type                     │
    │    * Description                     │
    │    * IP Address                      │
    │  - Pagination Links                  │
    └──────────────┬───────────────────────┘
                   │
                   ▼
            ◇──────────────◇
            │  Export      │
            │  Laporan?    │
            └──────────────┘
            │            │
          [NO]         [YES]
            │            │
            │            ▼
            │   ┌─────────────────────┐
            │   │  Pilih Format:      │
            │   │  - PDF              │
            │   │  - Excel (CSV)      │
            │   └──────────┬──────────┘
            │              │
            │              ▼
            │   ┌─────────────────────────────┐
            │   │  Backend Generate File:     │
            │   │  - Collect filtered data    │
            │   │  - Format to PDF/Excel      │
            │   │  - Download file            │
            │   └──────────┬──────────────────┘
            │              │
            │              ▼
            │      ┌───────────────┐
            │      │  File         │
            │      │  Downloaded   │
            │      └───────────────┘
            │
            ▼
    ┌──────────────────┐
    │  Continue Browse │
    │  or Logout       │
    └──────────────────┘
```

---

## 4.3 Swimlane Diagram - Proses Voting End-to-End

Swimlane diagram menunjukkan **tanggung jawab setiap aktor** dalam proses voting lengkap dari awal hingga akhir:

```
┌───────────────────────────────────────────────────────────────────────────────────┐
│                    PROSES VOTING END-TO-END (Swimlane)                            │
└───────────────────────────────────────────────────────────────────────────────────┘

PETUGAS           │   SISTEM             │   VOTER              │   DATABASE
──────────────────┼──────────────────────┼──────────────────────┼───────────────────
                  │                      │                      │
[Buat Event] ────>│                      │                      │
                  │                      │                      │
                  │ [Validate Input] ───>│                      │
                  │                      │                      │
                  │ [Generate Room]      │                      │
                  │  Code (6 char)       │                      │
                  │                      │                      │
                  │─────────────────────>│                      │ [INSERT events]
                  │                      │                      │
                  │<─────────────────────┤                      │ [Event Created]
                  │                      │                      │
[Tambah          ]│                      │                      │
[Kandidat]  ─────>│                      │                      │
                  │                      │                      │
                  │ [Validate Data] ────>│                      │
                  │                      │                      │
                  │ [Upload Foto]        │                      │
                  │  to storage/         │                      │
                  │  candidates/         │                      │
                  │                      │                      │
                  │─────────────────────>│                      │ [INSERT candidates]
                  │                      │                      │
[Publish Event]──>│                      │                      │
                  │                      │                      │
                  │ [Check Min 1        ]│                      │
                  │  Kandidat]           │                      │
                  │                      │                      │
                  │─────────────────────>│                      │ [UPDATE events
                  │                      │                      │  SET status=
                  │                      │                      │  'PUBLISHED']
                  │                      │                      │
[Bagikan Room     │                      │                      │
 Code ke Voter]──>│                      │                      │
                  │                      │                      │
                  │                      │ [Terima Room Code]   │
                  │                      │  dari Petugas        │
                  │                      │                      │
                  │                      │ [Login: NIS/NIP +    │
                  │                      │  Password only]      │
                  │                      │                      │
                  │                      │ [Redirect to        ]│
                  │                      │  Dashboard] ────────>│ [SELECT events
                  │                      │                      │  WHERE status=
                  │                      │                      │  'PUBLISHED']
                  │                      │                      │
                  │                      │ [Lihat Daftar Event] │
                  │                      │                      │
                  │                      │ [Input Room Code     │
                  │                      │  di Form Join]       │
                  │                      │                      │
                  │                      │ [Submit Room Code]──>│ [SELECT events
                  │                      │                      │  WHERE room_code=?]
                  │                      │                      │
                  │                      │                      │ [Check already
                  │                      │                      │  voted?]
                  │                      │                      │
                  │                      │<─────────────────────│ [Eligible to vote]
                  │                      │                      │
                  │                      │ [Lihat Semua         │
                  │                      │  Kandidat]           │
                  │                      │                      │
                  │                      │ [Pilih Kandidat]     │
                  │                      │                      │
                  │                      │ [Konfirmasi Pilihan] │
                  │                      │                      │
                  │                      │ [Submit Vote] ──────>│ [INSERT votes]
                  │                      │                      │
                  │                      │                      │ [INSERT
                  │                      │                      │  event_participations
                  │                      │                      │  SET voted_at=now()]
                  │                      │                      │
                  │                      │                      │ [INSERT audit_logs]
                  │                      │                      │
                  │                      │<─────────────────────│ [Vote Recorded]
                  │                      │                      │
                  │                      │ [Redirect to        ]│
                  │                      │  Success Page]       │
                  │                      │                      │
                  │                      │ [Show Konfirmasi:    │
                  │                      │  - Timestamp         │
                  │                      │  - Event Name        │
                  │                      │  - Candidate Name]   │
                  │                      │                      │
[Buka Live       ]│                      │                      │
[Monitoring] ────>│                      │                      │
                  │                      │                      │
                  │ [Check              ]│                      │
                  │  Authorization]      │                      │
                  │                      │                      │
                  │─────────────────────>│                      │ [SELECT COUNT(*)
                  │                      │                      │  FROM votes
                  │                      │                      │  GROUP BY
                  │                      │                      │  candidate_id]
                  │                      │                      │
                  │<─────────────────────│                      │ [Vote Counts]
                  │                      │                      │
[Lihat Real-time ]│                      │                      │
[Results with    ]│                      │                      │
[Auto-refresh    ]│                      │                      │
[every 5s]        │                      │                      │
                  │                      │                      │
[Close Event]────>│                      │                      │
                  │                      │                      │
                  │─────────────────────>│                      │ [UPDATE events
                  │                      │                      │  SET status=
                  │                      │                      │  'CLOSED']
                  │                      │                      │
                  │                      │                      │ [INSERT audit_logs]
                  │                      │                      │
                  │                      │ [Voter can now      ]│
                  │                      │  view final results] │
                  │                      │                      │
                  │                      │                      │
```

---

## 4.4 Decision Points & Conditions

### 4.4.1 Tabel Decision Points dalam Activity Diagram

| No | Decision Point | Condition | Action if TRUE | Action if FALSE |
|----|----------------|-----------|----------------|-----------------|
| 1 | **Login Validation Admin/Petugas** | Email + Password benar? | Set Session → Redirect berdasarkan role | Show error message → Back to login form |
| 1b | **Login Validation Voter** | NIS/NIP terdaftar? (TANPA password check) | Set Session → Redirect ke halaman input Room Code | Show error "NIS/NIP tidak terdaftar" |
| 2 | **Remember Me** | Checkbox "Remember Me" dicentang? | Set cookie token (5 tahun) | Continue tanpa cookie |
| 3 | **Role Check** | User = Super Admin? | Redirect Admin Dashboard | Redirect Petugas Events List (`/petugas/events`) |
| 4 | **Event Authorization** | created_by = current_user OR Super Admin? | Allow access | 403 Forbidden Error |
| 5 | **Kandidat Minimum** | Total kandidat >= 1? | Enable Publish button | Show warning "Min 1 kandidat" |
| 6 | **Voter Eligibility** | Room Code valid dan voter belum vote? | Allow voting | Show error "Invalid room code / Already voted" |
| 7 | **Already Voted** | Vote record exist for voter+event? | Block voting (show "Sudah memilih") | Allow voting |
| 8 | **Event Status** | Event status = PUBLISHED? | Voter can access | Show error "Event not active" |
| 9 | **Photo Upload** | File uploaded in form? | Validate → Store to storage/candidates/ | Continue without photo |
| 10 | **Filter Applied** | User click filter button? | Build query with WHERE clauses | Show all records (default) |

---

## 4.5 Parallel Activities

Beberapa proses berjalan **secara paralel (concurrent)** dalam sistem:

### 4.5.1 Parallel: Auto-Refresh Monitoring

```
┌─────────────────────────────────────────────────────┐
│  Petugas membuka halaman Live Monitoring            │
└──────────────────────┬──────────────────────────────┘
                       │
         ┌─────────────┴─────────────┐
         │                           │
         ▼                           ▼
┌──────────────────┐      ┌──────────────────┐
│  Main Thread:    │      │  JavaScript Timer│
│  - Render UI     │      │  - setInterval() │
│  - Handle clicks │      │  - Fetch API     │
│  - User interact │      │  - Update DOM    │
└──────────────────┘      └──────────────────┘
         │                           │
         └─────────────┬─────────────┘
                       │
                       ▼
            Both run simultaneously
```

### 4.5.2 Parallel: Multiple Voters Voting

```
┌─────────────────────────────────────────────────────┐
│  Event PUBLISHED, Multiple Voters Login             │
└──────────────────────┬──────────────────────────────┘
                       │
         ┌─────────────┼─────────────┬─────────────┐
         │             │             │             │
         ▼             ▼             ▼             ▼
    ┌────────┐    ┌────────┐    ┌────────┐    ┌────────┐
    │ Voter1 │    │ Voter2 │    │ Voter3 │    │ VoterN │
    │ Vote   │    │ Vote   │    │ Vote   │    │ Vote   │
    │ Async  │    │ Async  │    │ Async  │    │ Async  │
    └───┬────┘    └───┬────┘    └───┬────┘    └───┬────┘
        │             │             │             │
        └─────────────┴─────────────┴─────────────┘
                       │
                       ▼
            All votes saved to database
            (no race condition due to DB transactions)
```

---

## 4.6 Exception Handling

### 4.6.1 Error Scenarios & Recovery

| Scenario | Error | Recovery Action |
|----------|-------|-----------------|
| **Login Failed** | Credentials invalid | Show error message → Stay on login form → Allow retry |
| **Unauthorized Access** | User tidak punya akses | HTTP 403 Forbidden → Redirect to appropriate dashboard |
| **Already Voted** | Vote record exists | Show message "Anda sudah memberikan suara" → Redirect to dashboard |
| **Event Not Published** | Status != PUBLISHED | Show error "Event belum aktif" → Back to event list |
| **Upload Foto Gagal** | File > 2MB or invalid format | Show validation error → Stay on form → Allow re-upload |
| **Nomor Urut Duplicate** | candidate_number already used | Show error "Nomor urut sudah digunakan" → Clear field |
| **Network Timeout** | AJAX request failed | Show notification → Retry automatically (3x) → Then show error |
| **Database Down** | Connection refused | HTTP 500 Error → Show maintenance page |

---

## 4.7 State Transitions

### 4.7.1 Event Status Lifecycle

```
        ┌─────────┐
        │ DRAFT   │  (Initial state, created by Petugas)
        └────┬────┘
             │
             │ [Action: Publish Event]
             │ [Condition: Min 1 Kandidat]
             ▼
        ┌──────────┐
        │PUBLISHED │  (Voter can vote)
        └────┬─────┘
             │
             │ [Action: Close Event]
             │ [By: Manual or Auto at end_time]
             ▼
        ┌─────────┐
        │ CLOSED  │  (Final state, show results)
        └─────────┘
```

---

### 4.7.2 Voter Participation Status

```
        ┌──────────────┐
        │ NOT_STARTED  │  (Event exists, voter belum vote)
        └──────┬───────┘
               │
               │ [Action: Submit Vote]
               ▼
        ┌──────────────┐
        │   VOTED      │  (Vote recorded, can't change)
        └──────┬───────┘
               │
               │ [Wait for Event Close]
               ▼
        ┌──────────────┐
        │ VIEW_RESULTS │  (Can see final results)
        └──────────────┘
```

---

## 4.8 Catatan Implementasi

### 4.8.1 Performance Considerations

- **Database Indexing:** Kolom `event_id`, `candidate_id`, `voter_id` di-index untuk query cepat
- **Caching:** Room code validation di-cache untuk mengurangi database hits
- **Lazy Loading:** Foto kandidat di-load on-demand (lazy load) untuk mempercepat page load
- **Query Optimization:** Gunakan `COUNT(*)` dengan `GROUP BY` untuk aggregation di monitoring

### 4.8.2 Security Measures

- **CSRF Protection:** Semua form POST menggunakan `@csrf` token Laravel
- **SQL Injection Prevention:** Gunakan Laravel Eloquent ORM atau prepared statements
- **XSS Prevention:** Output di-escape dengan `{{ }}` atau `{!! nl2br(e()) !!}`
- **Authorization Check:** Setiap route dilindungi middleware (`auth:web`, `auth:voter`, `super_admin`)
- **Rate Limiting:** Login endpoint dibatasi 5 attempts per menit untuk mencegah brute force

---

**Activity Diagram ini menunjukkan alur proses bisnis lengkap sistem VOTINGYUK. Section selanjutnya akan membahas Mockup (screenshot UI Mobile & Desktop).**
