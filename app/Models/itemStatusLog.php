<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class itemStatusLog extends Model
{
    //
    use HasFactory;

    protected $table = 'item_status_logs';
    protected $fillable = ['barang_id', 'status', 'keterangan'];

    public function barang() {
        return $this->belongsTo(Barang::class);
    }
}
