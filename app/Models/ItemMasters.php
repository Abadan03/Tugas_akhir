<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemMasters extends Model
{
    //
    use HasFactory;

    protected $table = 'items_masters';

    protected $fillable = [
        'nama_barang',
        'kode',
        'deskripsi',
    ];

    public function barangs()
    {
        return $this->hasMany(Barang::class, 'items_id');
    }
}
