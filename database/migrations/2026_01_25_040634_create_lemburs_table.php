<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lemburs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pegawai'); // Husein Syahfikri
            $table->date('tanggal'); // Tanggal lembur
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('jumlah_jam'); // Durasi
            $table->string('keterangan'); // Kegiatan/Event
            $table->string('ditugaskan_oleh')->nullable(); // Bu Nina
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lemburs');
    }
};
