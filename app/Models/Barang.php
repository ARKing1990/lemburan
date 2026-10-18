<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'satuan',
        'stok',
    ];

    // Relasi: Satu barang punya banyak riwayat transaksi
    public function transaksi()
    {
        return $this->hasMany(TransaksiBarang::class);
    }
    public function details()
    {
        // Satu Barang punya banyak Unit/SN
        return $this->hasMany(DetailBarang::class);
    }

    // Helper untuk mengambil SN yang tersedia saja
    public function stokTersedia()
    {
        return $this->details()->where('status', 'Tersedia');
    }
}
