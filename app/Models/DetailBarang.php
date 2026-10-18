<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailBarang extends Model
{
    protected $fillable = ['barang_id', 'serial_number', 'status'];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
