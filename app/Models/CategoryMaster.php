<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryMaster extends Model
{
    //
    use HasFactory;

    protected $table = 'category_masters';
    protected $fillable = [
        'nama_kategori',
        'kode'
    ];

    public function barangs()
    {
        return $this->hasMany(Barang::class, 'kategori_id');
    }
}
