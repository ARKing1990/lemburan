<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanItem extends Model
{
    protected $guarded = []; // Wajib ada

    public function images()
    {
        return $this->hasMany(LaporanImage::class);
    }
    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }
}
