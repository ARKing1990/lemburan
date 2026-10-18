<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransaksiBarang extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'barang_id',
        'detail_barang_id',
        'jenis',
        'jumlah',
        'kondisi',
        'keterangan',
        'tanggal_transaksi'
    ];

    public function detail_barang()
    {
        return $this->belongsTo(DetailBarang::class);
    }

    // Relasi: Transaksi milik satu Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    // Relasi: Transaksi dilakukan oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
