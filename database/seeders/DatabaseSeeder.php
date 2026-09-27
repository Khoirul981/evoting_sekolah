<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\Candidate;
use App\Models\Event;
use App\Models\User;
use App\Models\Voter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Super Admins
        $superAdmin1 = User::create([
            'name' => 'Super Admin Utama',
            'email' => 'admin@sekolah.sch.id',
            'password' => Hash::make('password'),
            'role' => 'SUPER_ADMIN',
        ]);

        $superAdmin2 = User::create([
            'name' => 'SYARIF KHOIRUL RIFA',
            'email' => 'khoirul@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'SUPER_ADMIN',
        ]);

        // 2. Petugas
        $petugas1 = User::create([
            'name' => 'Petugas OSIS 1',
            'email' => 'petugas1@sekolah.sch.id',
            'password' => Hash::make('password'),
            'role' => 'PETUGAS',
        ]);

        $petugas2 = User::create([
            'name' => 'Petugas OSIS 2',
            'email' => 'petugas2@sekolah.sch.id',
            'password' => Hash::make('password'),
            'role' => 'PETUGAS',
        ]);

        // 3. Voters (Siswa)
        $voters = [
            ['identifier' => '1001', 'name' => 'Ahmad Rizky Pratama', 'type' => 'SISWA', 'is_active' => true],
            ['identifier' => '1002', 'name' => 'Budi Santoso', 'type' => 'SISWA', 'is_active' => true],
            ['identifier' => '1003', 'name' => 'Citra Lestari', 'type' => 'SISWA', 'is_active' => true],
            ['identifier' => '1004', 'name' => 'Dewi Sartika', 'type' => 'SISWA', 'is_active' => true],
            ['identifier' => '1005', 'name' => 'Eko Prasetyo', 'type' => 'SISWA', 'is_active' => true],
            ['identifier' => '010109', 'name' => 'SYARIF KHOIRUL RIFA', 'type' => 'SISWA', 'is_active' => true],
            ['identifier' => '2001', 'name' => 'Drs. Bambang Wijaya, M.Pd', 'type' => 'GURU', 'is_active' => true],
            ['identifier' => '2002', 'name' => 'Siti Aminah, S.Pd', 'type' => 'GURU', 'is_active' => true],
            ['identifier' => '2003', 'name' => 'Hendra Gunawan, S.Kom', 'type' => 'GURU', 'is_active' => true],
            ['identifier' => '2004', 'name' => 'Sri Wahyuni, M.Si', 'type' => 'GURU', 'is_active' => true],
        ];

        foreach ($voters as $voterData) {
            Voter::create($voterData);
        }

        // 4. Sample Event 1 (Kategori: KANDIDAT)
        $event1 = Event::create([
            'created_by' => $petugas1->id,
            'title' => 'Pemilihan Ketua OSIS Periode 2026/2027',
            'category' => 'KANDIDAT',
            'room_code' => 'OSIS26',
            'description' => 'Pemilihan Ketua dan Wakil Ketua OSIS SMA Negeri untuk masa bakti 2026/2027 secara jujur dan transparan.',
            'status' => 'PUBLISHED',
        ]);

        Candidate::create([
            'event_id' => $event1->id,
            'candidate_number' => 1,
            'name' => 'Muhammad Fatih & Nadia Putri',
            'vision' => 'Mewujudkan OSIS yang aspiratif, berkarakter unggul, dan berdaya saing di era digital.',
            'mission' => "1. Mengembangkan bakat siswa melalui kegiatan ekstrakurikuler modern.\n2. Meningkatkan transparansi program kerja OSIS berbasis teknologi digital.\n3. Menumbuhkan budaya peduli lingkungan dan empati sosial.",
        ]);

        Candidate::create([
            'event_id' => $event1->id,
            'candidate_number' => 2,
            'name' => 'Rehan Pratama & Zahra Aulia',
            'vision' => 'Menjadikan sekolah sebagai ruang kreasi, prestasi, dan kolaborasi yang inklusif.',
            'mission' => "1. Menyelenggarakan festival seni dan sains tahunan berskala regional.\n2. Membangun platform aspirasi terbuka bagi seluruh siswa dan guru.\n3. Mengadakan program mentoring kepemimpinan berkala.",
        ]);

        // 5. Sample Event 2 (Kategori: OPSI)
        $event2 = Event::create([
            'created_by' => $petugas1->id,
            'title' => 'Penentuan Destinasi Studi Tour Kelas XII',
            'category' => 'OPSI',
            'room_code' => 'TOUR26',
            'description' => 'Musyawarah digital penentuan lokasi kegiatan studi tour siswa kelas XII tahun pelajaran 2026.',
            'status' => 'PUBLISHED',
        ]);

        Candidate::create([
            'event_id' => $event2->id,
            'candidate_number' => 1,
            'name' => 'Opsi A: Yogyakarta (Budaya & Edukasi)',
            'description' => 'Kunjungan edukasi sejarah ke Candi Prambanan, Candi Borobudur, Keraton Yogyakarta, dan sentra kerajinan perak Kotagede.',
        ]);

        Candidate::create([
            'event_id' => $event2->id,
            'candidate_number' => 2,
            'name' => 'Opsi B: Malang & Batu (Agrowisata & Science)',
            'description' => 'Kunjungan sains ke Museum Angkut, Jatim Park 3, dan wisata edukasi perkebunan apel.',
        ]);

        Candidate::create([
            'event_id' => $event2->id,
            'candidate_number' => 3,
            'name' => 'Opsi C: Bandung (Teknologi & Industri Kreatif)',
            'description' => 'Kunjungan ke Museum Geologi, Saung Angklung Udjo, dan pusat industri kreatif tekstil Bandung.',
        ]);

        // 6. Audit Logs Awal (Hanya Aksi Terkait Event)
        AuditLog::create([
            'user_id' => $petugas1->id,
            'user_name' => $petugas1->name,
            'role' => $petugas1->role,
            'action' => 'CREATE_EVENT',
            'description' => "Membuat event baru '{$event1->title}' (Kode Room: {$event1->room_code})",
            'ip_address' => '127.0.0.1',
            'created_at' => now()->subMinutes(30),
        ]);

        AuditLog::create([
            'user_id' => $petugas1->id,
            'user_name' => $petugas1->name,
            'role' => $petugas1->role,
            'action' => 'CREATE_EVENT',
            'description' => "Membuat event baru '{$event2->title}' (Kode Room: {$event2->room_code})",
            'ip_address' => '127.0.0.1',
            'created_at' => now()->subMinutes(20),
        ]);

        AuditLog::create([
            'user_id' => $petugas1->id,
            'user_name' => $petugas1->name,
            'role' => $petugas1->role,
            'action' => 'CHANGE_EVENT_STATUS',
            'description' => "Mengubah status event '{$event1->title}' dari DRAFT menjadi PUBLISHED",
            'ip_address' => '127.0.0.1',
            'created_at' => now()->subMinutes(10),
        ]);

        AuditLog::create([
            'user_id' => $petugas1->id,
            'user_name' => $petugas1->name,
            'role' => $petugas1->role,
            'action' => 'UPDATE_EVENT',
            'description' => "Memperbarui deskripsi dan rincian opsi pada event '{$event2->title}'",
            'ip_address' => '127.0.0.1',
            'created_at' => now()->subMinutes(5),
        ]);
    }
}

