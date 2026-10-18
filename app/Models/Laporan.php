<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(LaporanItem::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
