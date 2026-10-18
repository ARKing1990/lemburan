<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;

class BarangSeeder extends Seeder
{
    // database/seeders/BarangSeeder.php
    public function run(): void
    {
        // 1. BUAT MASTER BARANG (Grouping)
        $mikrotik = Barang::create(['nama_barang' => 'Router Mikrotik 450', 'satuan' => 'Unit', 'stok' => 2]);
        $switch = Barang::create(['nama_barang' => 'D-Link DGS-1100-08V2', 'satuan' => 'Unit', 'stok' => 1]);
        $kabel = Barang::create(['nama_barang' => 'Kabel LAN Belden CAT6', 'satuan' => 'Roll', 'stok' => 5]);

        // 2. ISI DETAILNYA (Serial Number)
        // Mikrotik ada 2 unit dengan SN beda
        \App\Models\DetailBarang::create(['barang_id' => $mikrotik->id, 'serial_number' => 'nE308KQKC2D', 'status' => 'Tersedia']);
        \App\Models\DetailBarang::create(['barang_id' => $mikrotik->id, 'serial_number' => 'j0kow1', 'status' => 'Tersedia']); // Contoh SN kedua

        // D-Link 1 unit
        \App\Models\DetailBarang::create(['barang_id' => $switch->id, 'serial_number' => 'TM5A156000436', 'status' => 'Tersedia']);

        // Kabel tidak punya detail SN (Biarkan kosong detailnya, stok diatur di master)
    }
}
