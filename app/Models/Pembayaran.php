<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{

    use HasFactory;
    //
    protected $table = 'pembayaran';
    protected $fillable = [
        'barang_rusaks_id',
        'biaya_perbaikan',
    ];

    
     public function barang()
    {
        return $this->belongsTo(Barang::class);
    }


    public function barangRusak()
    {
        return $this->belongsTo(BarangRusak::class, 'barang_rusaks_id');
    }
}
