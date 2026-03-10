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
        'kategori_id',
        'nama_siswa',
        'tipe_id',
        'status_id',
        'items_id',
        'kodeQR',
        'bukti',
        'keterangan',
    ];

    public function pinjamans()
    {
        return $this->hasMany(Pinjaman::class);
    }

    public function barangRusak()
    {
        return $this->hasOne(BarangRusak::class);
    }

    public function barangRusaks()
    {
        return $this->hasMany(BarangRusak::class, 'barang_id');
    }

    public function itemStatusLogs()
        {
            return $this->hasMany(ItemStatusLog::class, 'barang_id');
        }

        public function kategori()
        {
            return $this->belongsTo(CategoryMaster::class, 'kategori_id');
        }

    public function status()
        {
            return $this->belongsTo(StatusMaster::class, 'status_id');
        }

    public function tipe()
        {
            return $this->belongsTo(TypeMaster::class, 'tipe_id');
        }
        
        public function itemMaster()
        {
            return $this->belongsTo(ItemMasters::class, 'items_id');
        }

}
