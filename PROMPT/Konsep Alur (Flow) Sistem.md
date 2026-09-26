Konsep Alur (Flow) Sistem

### **Flow 1: Alur Pembuatan Event (Petugas)**

```
┌─────────────────────────────────────────────────────────────────┐
│ START: Petugas Login                                            │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│ Dashboard Petugas → Klik "Buat Event Baru"                      │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│ Form Input Event:                                               │
│  - Judul Event                                                  │
│  - Kategori (KANDIDAT / OPSI)                                  │
│  - Deskripsi                                                     │
│  - Waktu Mulai & Selesai (Opsional, KALAU DI GA DIISI BERARTI BEBAS MENENTUKAN KAPAN MULAI DAN SELESAI TINGGAL PENCET PUBLISH EVENTNYA AJA KALAU MULAI 	DAN CLOSE UNTUK SELESAI EVENT)                            │
│  - Auto-generate Room Code (setelah form semuanya Sudah diisi dan di confirm)                        │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│ Submit → Event Created (Status: DRAFT)                          │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│ Tambah Kandidat/Opsi:                                           │
│  - Nama Kandidat (UNTUK KATEGORI KANDIDAT)
    - Nama Opsi (UNTUK KATEGORI OPSI)                                        │
│  - Visi & Misi (untuk KANDIDAT)                          │
│  - Deskripsi (untuk OPSI)                                    │
│  - No Urut                                                      │
│  - Foto (opsional)                                              │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│ Ulangi Tambah Kandidat hingga selesai (TIDAK MENERIMA HANYA 1 KANDIDAT/HARUS LEBIH DARI 1)                           │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│ Publish Event:                                                  │
   - Jika di set Waktu mulai dan selesai maka gamuncul tombol publish dan juga close karena harus mengikuti Waktu mulai dan selesai (JIKA ADA SALAH DALAM MENGINPUT SET WAKTU MULAI DAN SELESAI HANYA BISA DILAKUKAN DI TOMBOL/FITUR EDIT)
   - Jika tidak di set Waktu mulai dan selesai maka muncul tombol publish (HARUS MANUAL JADINYA KARENA GA DI SET WAKTU MULAI DAN SELESAI)           │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│ Event Status: PUBLISHED                                         │
│ Voter bisa mulai join dan voting                                │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│ Monitor Real-time Voting:                                       │
│  - Total Suara Masuk                                            │
│  - Perolehan per Kandidat                                       │
│  - Daftar Voter yang sudah voting                               │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│ Close Event:                                                    │
│  - Manual: Klik "Tutup Event" (JIKA TIDAK DI SET WAKTU MULAI DAN SELESAI)                                   │
│  - Auto: Waktu selesai tercapai (DI SET WAKTU MULAI DAN SELESAINYA)                                 │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│ Event Status: CLOSED                                            │
│ Export Laporan (Excel/PDF) (KATANYA HARUS PAKE PACKAGE)                                      │
└────────────┬────────────────────────────────────────────────────┘


```

### **Flow 2: Alur Voting (Voter)**

```
┌─────────────────────────────────────────────────────────────────┐
│ START: Voter Login                                              │
│ (NIS/NIP)                                       │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│ Dashboard Voter → Input Room Code Event (YANG SUDAH DIBERI OLEH PETUGAS)                         │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│ Validasi Room Code:                                             │
│  - Event ada? ✓                                                 │
│  - Event status PUBLISHED? ✓                                    │
│  - Waktu mulai sudah lewat? ✓                                   │
│  - Waktu selesai belum lewat? ✓                                 │
│  - Voter sudah vote? ✗ (Belum)                                  │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│ Join Event Success → Record di event_participations             │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│ Tampilkan Halaman Voting:                                       │
│  - Info Event (Judul, Deskripsi, Kategori)                      │
│  - Daftar Kandidat/Opsi dengan Foto (FOTO JIKA DIINPUT)
   - Deskripsi masing-masing opsi (JIKA KATEGORI OPSI)
   - Visi-misi masing-masing kandidat (JIKA KATEGORI KANDIDAT)                                  │
│  - Button "PILIH" untuk setiap kandidat                         │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│ Voter Klik "PILIH" pada salah satu kandidat                     │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│ Konfirmasi Modal: "Apakah Anda yakin memilih [Kandidat(Beserta siapa kandidatnya)/Opsi (Beserta apa opsinya)]?"          │
│  → Klik "Ya, Saya Yakin"                                        │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│ Backend Validasi Ulang:                                         │
│  - Event masih PUBLISHED? ✓                                     │
│  - Voter belum vote? ✓                                          │
│  - Candidate/Opsi valid? ✓                                           │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│ Record Vote ke Database:                                        │
│  - Insert ke table `votes`                                      │
│  - Update `voted_at` di `event_participations`                  │
│  - Audit Log: "Voter [NIS] voted in Event [ID]" (masih bimbang mau pake apa engga)                │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│ Success Response: "Terima kasih, suara Anda berhasil dicatat!" │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│ Redirect ke Halaman "Suara Anda Sudah Tercatat"                │
│ (Tidak bisa vote lagi)                                          │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
```

### **Flow 3: Auto-Update Status Event (Background Process)**

```
┌─────────────────────────────────────────────────────────────────┐
│ Trigger: User (Petugas/Voter) akses sistem                     │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│ Check semua event dengan status DRAFT atau PUBLISHED            │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│ Untuk setiap event:                                             │
│  ┌────────────────────────────────────────────────────┐         │
│  │ IF status = DRAFT && start_time <= NOW:            │         │
│  │    → Update status to PUBLISHED                    │         │
│  └────────────────────────────────────────────────────┘         │
│  ┌────────────────────────────────────────────────────┐         │
│  │ IF status = PUBLISHED && end_time <= NOW:          │         │
│  │    → Update status to CLOSED                       │         │
│  └────────────────────────────────────────────────────┘         │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│ Return Response dengan status event terbaru                     │
└─────────────────────────────────────────────────────────────────┘
```

