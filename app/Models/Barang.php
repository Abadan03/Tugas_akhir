<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    //  
    protected $table = 'barangs';
    use HasFactory;

    protected $fillable = [
        'nama_barang',
        'harga_awal',
        'kategori',
        'nama_siswa',
        'tipe',
        'status',
        'kodeQR',
        'bukti'
    ];

    public function pinjamans()
    {
        return $this->hasMany(Pinjaman::class);
    }

    public function barangRusak()
    {
        return $this->hasOne(BarangRusak::class);
    }
}
