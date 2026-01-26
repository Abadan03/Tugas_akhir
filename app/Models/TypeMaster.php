<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypeMaster extends Model
{
    //
    use HasFactory;

    protected $table = 'type_masters';
    protected $fillable = [
        'nama_tipe',
        'kode'
    ];

    public function barangs()
    {
        return $this->hasMany(Barang::class, 'tipe_id');
    }
}
