<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lembur extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_pegawai',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'jumlah_jam',
        'keterangan',
        'ditugaskan_oleh',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
