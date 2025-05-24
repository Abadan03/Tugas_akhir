<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangRusak extends Model
{
    use HasFactory;

    protected $fillable = [
        'barang_id',
        'pinjaman_id',
        // 'biaya_perbaikan',
        // 'keterangan',
        'surat',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class);
    }
}
