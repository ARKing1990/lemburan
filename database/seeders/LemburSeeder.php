<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LemburSeeder extends Seeder
{
    public function run(): void
    {
        // ==========================================
        // 1. AKUN ADMIN (MASTER)
        // ==========================================
        User::firstOrCreate(
            ['email' => 'admin@netzap.com'],
            [
                'name' => 'Master Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin'
            ]
        );

        // ==========================================
        // 2. USER: HUSEIN SYAHFIKRI
        // ==========================================
        $userHusein = User::firstOrCreate(
            ['email' => 'husein@netzap.com'],
            [
                'name' => 'Husein Syahfikri',
                'password' => Hash::make('password'),
                'role' => 'user'
            ]
        );

        $dataHusein = [
            ['2026-01-05', '18:00', '01:00', 7, 'Standby & Dismantle Event Natal', 'Bu Nina'],
            ['2026-01-06', '18:00', '23:00', 5, 'Instalasi Event RUPS BTN', 'Bu Nina'],
            ['2026-01-07', '18:00', '20:00', 2, 'Standby & Dismantle Event RUPS BTN', 'Bu Nina'],
            ['2026-01-10', '09:00', '00:00', 15, 'Instalasi & Standby Event Pocky Holo Live', 'Bu Nina'],
            ['2026-01-11', '09:00', '18:00', 9, 'Standby & Dismantle Event Pocky Holo Live', 'Bu Nina'],
            ['2026-01-14', '18:00', '00:00', 6, 'Instalasi Event Netflix', 'Bu Nina'],
            ['2026-01-15', '18:00', '00:00', 6, 'Instalasi Event Netflix', 'Bu Nina'],
            ['2026-01-16', '08:00', '01:00', 17, 'Standby & Dismantle Event Netflix', 'Bu Nina'],
            ['2026-01-17', '09:00', '20:00', 11, 'Standby & Dismantle Event Partai Gema Bangsa', 'Bu Nina'],
            ['2026-01-19', '18:00', '00:00', 6, 'Instalasi & Standby Event Paragon', 'Bu Nina'],
            ['2026-01-20', '18:00', '21:00', 3, 'Standby & Dismantle Event Paragon', 'Bu Nina'],
            ['2026-01-22', '18:00', '00:00', 6, 'Standby & Dismantle Event Herbalife', 'Bu Nina'],
            ['2026-01-24', '09:00', '18:00', 9, 'Instalasi Event 1000 Hari Cinta', 'Bu Nina'],
            ['2026-01-25', '08:00', '20:00', 12, 'Standby & Dismantle Event 1000 Hari Cinta', 'Bu Nina'],
        ];

        foreach ($dataHusein as $row) {
            DB::table('lemburs')->insert([
                'user_id' => $userHusein->id,
                'nama_pegawai' => $userHusein->name,
                'tanggal' => $row[0],
                'jam_mulai' => $row[1],
                'jam_selesai' => $row[2],
                'jumlah_jam' => $row[3],
                'keterangan' => $row[4],
                'ditugaskan_oleh' => $row[5],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ==========================================
        // 3. USER: FIRZHA RAMADHAN
        // ==========================================
        $userFirzha = User::firstOrCreate(
            ['email' => 'firzha@netzap.com'],
            [
                'name' => 'Firzha Ramadhan',
                'password' => Hash::make('password'),
                'role' => 'user'
            ]
        );

        $dataFirzha = [
            ['2026-01-04', '09:00', '20:00', 11, 'Instalasi event Natal', 'Bu Nina'],
            ['2026-01-07', '18:00', '20:00', 2, 'Dismantle event RUPS BTN', 'Bu Nina'],
            ['2026-01-10', '09:00', '00:00', 15, 'Instalasi event Pocky', 'Bu Nina'],
            ['2026-01-14', '18:00', '00:00', 6, 'Instalasi event Netflix', 'Bu Nina'],
            ['2026-01-15', '18:00', '00:00', 6, 'Instalasi event Netflix', 'Bu Nina'],
            ['2026-01-19', '18:00', '00:00', 6, 'Instalasi event Paragon', 'Bu Nina'],
            ['2026-01-20', '18:00', '21:00', 3, 'Dismantle event Paragon', 'Bu Nina'],
            ['2026-01-22', '18:00', '00:00', 6, 'Dismantle event Herbalife', 'Bu Nina'],
            ['2026-01-24', '09:00', '18:00', 9, 'Instalasi event 1000 Hari Cinta', 'Bu Nina'],
            ['2026-01-25', '08:00', '20:00', 12, 'Dismantle event 100 Hari Cinta', 'Bu Nina'],
        ];

        foreach ($dataFirzha as $row) {
            DB::table('lemburs')->insert([
                'user_id' => $userFirzha->id,
                'nama_pegawai' => $userFirzha->name,
                'tanggal' => $row[0],
                'jam_mulai' => $row[1],
                'jam_selesai' => $row[2],
                'jumlah_jam' => $row[3],
                'keterangan' => $row[4],
                'ditugaskan_oleh' => $row[5],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ==========================================
        // 4. USER: SOLEH
        // ==========================================
        $userSoleh = User::firstOrCreate(
            ['email' => 'soleh@netzap.com'],
            [
                'name' => 'Soleh',
                'password' => Hash::make('password'),
                'role' => 'user'
            ]
        );

        $dataSoleh = [
            ['2026-01-05', '18:00', '01:00', 7, 'Standby Event Natal', 'Bu Nina'],
            ['2026-01-06', '18:00', '23:00', 5, 'Instalasi Event BTN', 'Bu Nina'],
            ['2026-01-14', '18:00', '00:00', 6, 'Instalasi Event Netflix', 'Bu Nina'],
            ['2026-01-15', '18:00', '00:00', 6, 'Standby Event Netflix', 'Bu Nina'],
            ['2026-01-21', '18:00', '00:00', 6, 'Instalasi Event Herbalife', 'Bu Nina'],
            ['2026-01-24', '07:00', '00:00', 17, 'Standby Event Raw Festival', 'Bu Nina'],
        ];

        foreach ($dataSoleh as $row) {
            DB::table('lemburs')->insert([
                'user_id' => $userSoleh->id,
                'nama_pegawai' => $userSoleh->name,
                'tanggal' => $row[0],
                'jam_mulai' => $row[1],
                'jam_selesai' => $row[2],
                'jumlah_jam' => $row[3],
                'keterangan' => $row[4],
                'ditugaskan_oleh' => $row[5],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ==========================================
        // 5. USER: MUHAMMAD BAYU FAQIH (DATA BARU)
        // ==========================================
        $userBayu = User::firstOrCreate(
            ['email' => 'bayu@netzap.com'],
            [
                'name' => 'Muhammad Bayu Faqih',
                'password' => Hash::make('password'),
                'role' => 'user'
            ]
        );

        $dataBayu = [
            ['2026-01-04', '09:00', '20:00', 11, 'Instalasi Event Natal Tenis Indoor @GBK', 'Bu Nina'],
            ['2026-01-11', '09:00', '18:00', 9, 'Standby & Dismantle Event Hololive @CGV GI', 'Bu Nina'],
            ['2026-01-14', '18:00', '00:00', 6, 'Instalasi Event Netflix @Kokas', 'Bu Nina'],
            ['2026-01-15', '18:00', '00:00', 6, 'Instalasi Event Partai Gema Bangsa @JICC', 'Bu Nina'],
            ['2026-01-16', '09:00', '22:00', 13, 'Standby Event Partai Gema Bangsa @JIC', 'Bu Nina'],
            ['2026-01-17', '09:00', '20:00', 11, 'Standby & Dismantle Event Partai Gema Bangsa @JIC', 'Bu Nina'],
            ['2026-01-21', '18:00', '00:00', 6, 'Instalasi Event Herbalife @Sheraton Gancit', 'Bu Nina'],
            ['2026-01-24', '07:00', '00:00', 17, 'Standby & Dismantle Event Raw Festival @Basketball Hall', 'Bu Nina'],
        ];

        foreach ($dataBayu as $row) {
            DB::table('lemburs')->insert([
                'user_id' => $userBayu->id,
                'nama_pegawai' => $userBayu->name,
                'tanggal' => $row[0],
                'jam_mulai' => $row[1],
                'jam_selesai' => $row[2],
                'jumlah_jam' => $row[3],
                'keterangan' => $row[4],
                'ditugaskan_oleh' => $row[5],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
