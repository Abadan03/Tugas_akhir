<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    //
    protected $table = 'pinjamans';
    use HasFactory;

    protected $fillable = [
        // 'user_id',
        'barang_id',
    ];

  

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function barangRusak()
    {
        return $this->hasOne(BarangRusak::class);
    }
}
