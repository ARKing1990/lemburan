<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_barangs', function (Blueprint $table) {
            $table->id();

            // Relasi: Siapa yang input (User) & Barang apa (Barang)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('barang_id')->constrained()->onDelete('cascade');
            // Tambahkan ini (Nullable karena barang seperti Kabel tidak punya SN)
            $table->foreignId('detail_barang_id')->nullable()->constrained('detail_barangs')->onDelete('set null');

            // Jenis Transaksi:
            // - Masuk: Pembelian/Retur
            // - Keluar: Pemakaian Event/Rusak/Hilang
            $table->enum('jenis', ['Masuk', 'Keluar']);

            $table->integer('jumlah'); // Jumlah barang yg dimutasi

            // Status Barang (Opsional tapi berguna untuk laporan)
            // Contoh: 'Baru', 'Bekas', 'Rusak'
            // Jika tidak diisi, default 'Baik'
            $table->string('kondisi')->default('Baik');

            // Keterangan PENTING:
            // Jika Masuk: "Pembelian dari Toko A"
            // Jika Keluar: "Instalasi Event Netflix", "Barang Rusak/Pecah"
            $table->text('keterangan')->nullable();

            // Tanggal transaksi (bisa beda dengan created_at jika input susulan)
            $table->date('tanggal_transaksi');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_barangs');
    }
};
