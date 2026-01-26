<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StatusMaster extends Model
{
    //
    use HasFactory;

    protected $table = 'status_masters';
    protected $fillable = [
        'nama_status',
        'kode'
    ];

    public function barangs()
    {
        return $this->hasMany(Barang::class, 'status_id');
    }
}
