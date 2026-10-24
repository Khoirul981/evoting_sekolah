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
        // 1. Super Admin (1 akun)
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@sekolah.sch.id',
            'password' => Hash::make('password'),
            'role' => 'SUPER_ADMIN',
        ]);

        // 2. Petugas (2 akun)
        User::create([
            'name' => 'Petugas OSIS 1',
            'email' => 'petugas1@sekolah.sch.id',
            'password' => Hash::make('password'),
            'role' => 'PETUGAS',
        ]);

        User::create([
            'name' => 'Petugas OSIS 2',
            'email' => 'petugas2@sekolah.sch.id',
            'password' => Hash::make('password'),
            'role' => 'PETUGAS',
        ]);

        // 3. Voters - 30 Akun (20 Siswa + 10 Guru)
        $voters = [
            // SISWA (20 orang)
            ['identifier' => '1001', 'name' => 'Ahmad Rizky Pratama', 'type' => 'SISWA'],
            ['identifier' => '1002', 'name' => 'Budi Santoso', 'type' => 'SISWA'],
            ['identifier' => '1003', 'name' => 'Citra Lestari', 'type' => 'SISWA'],
            ['identifier' => '1004', 'name' => 'Dewi Sartika', 'type' => 'SISWA'],
            ['identifier' => '1005', 'name' => 'Eko Prasetyo', 'type' => 'SISWA'],
            ['identifier' => '1006', 'name' => 'Fitri Handayani', 'type' => 'SISWA'],
            ['identifier' => '1007', 'name' => 'Galih Ramadhan', 'type' => 'SISWA'],
            ['identifier' => '1008', 'name' => 'Hana Pertiwi', 'type' => 'SISWA'],
            ['identifier' => '1009', 'name' => 'Irfan Maulana', 'type' => 'SISWA'],
            ['identifier' => '1010', 'name' => 'Jasmine Putri', 'type' => 'SISWA'],
            ['identifier' => '1011', 'name' => 'Kevin Adiputra', 'type' => 'SISWA'],
            ['identifier' => '1012', 'name' => 'Lina Marlina', 'type' => 'SISWA'],
            ['identifier' => '1013', 'name' => 'Muhammad Fauzi', 'type' => 'SISWA'],
            ['identifier' => '1014', 'name' => 'Nabila Azzahra', 'type' => 'SISWA'],
            ['identifier' => '1015', 'name' => 'Oscar Wijaya', 'type' => 'SISWA'],
            ['identifier' => '1016', 'name' => 'Putri Wulandari', 'type' => 'SISWA'],
            ['identifier' => '1017', 'name' => 'Qomar Hidayat', 'type' => 'SISWA'],
            ['identifier' => '1018', 'name' => 'Rina Novita', 'type' => 'SISWA'],
            ['identifier' => '1019', 'name' => 'Satria Nugraha', 'type' => 'SISWA'],
            ['identifier' => '1020', 'name' => 'Tiara Kusuma', 'type' => 'SISWA'],

            // GURU (10 orang)
            ['identifier' => '2001', 'name' => 'Drs. Bambang Wijaya, M.Pd', 'type' => 'GURU'],
            ['identifier' => '2002', 'name' => 'Siti Aminah, S.Pd', 'type' => 'GURU'],
            ['identifier' => '2003', 'name' => 'Hendra Gunawan, S.Kom', 'type' => 'GURU'],
            ['identifier' => '2004', 'name' => 'Sri Wahyuni, M.Si', 'type' => 'GURU'],
            ['identifier' => '2005', 'name' => 'Dr. Agus Salim, M.Pd', 'type' => 'GURU'],
            ['identifier' => '2006', 'name' => 'Nur Hidayah, S.Pd', 'type' => 'GURU'],
            ['identifier' => '2007', 'name' => 'Bambang Sutrisno, S.S', 'type' => 'GURU'],
            ['identifier' => '2008', 'name' => 'Rina Puspitasari, M.Pd', 'type' => 'GURU'],
            ['identifier' => '2009', 'name' => 'Dedi Supriadi, S.Pd', 'type' => 'GURU'],
            ['identifier' => '2010', 'name' => 'Yuni Kartika, S.Pd.I', 'type' => 'GURU'],
        ];

        foreach ($voters as $voterData) {
            Voter::create($voterData);
        }

        echo "\n✅ Seeder berhasil!\n";
        echo "📊 Data yang dibuat:\n";
        echo "   - 1 Super Admin (admin@sekolah.sch.id / password)\n";
        echo "   - 2 Petugas (petugas1@sekolah.sch.id, petugas2@sekolah.sch.id / password)\n";
        echo "   - 30 Voters (20 Siswa + 10 Guru)\n\n";
    }
}

