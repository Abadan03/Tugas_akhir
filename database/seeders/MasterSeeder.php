<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        \App\Models\CategoryMaster::insert([
            ['nama_kategori' => 'Milik Sekolah', 'deskripsi' => 'Barang milik sekolah', 'defaultTrigger' => true, 'is_default' => true],
            ['nama_kategori' => 'Dipinjam oleh siswa', 'deskripsi' => 'Barang pinjaman untuk santri', 'defaultTrigger' => false, 'is_default' => true],
        ]);

        \App\Models\StatusMaster::insert([
            ['nama_status' => 'Baru', 'warna' => 'green', 'is_default' => true],
            ['nama_status' => 'Hilang', 'warna' => 'yellow', 'is_default' => true],
            ['nama_status' => 'Rusak Ringan', 'warna' => 'red', 'is_default' => true],
            ['nama_status' => 'Rusak Berat', 'warna' => 'red', 'is_default' => true],
            ['nama_status' => 'Diperbarui', 'warna' => 'blue', 'is_default' => true],
        ]);

        \App\Models\TypeMaster::insert([
            ['nama_tipe' => 'Barang Berpindah', 'is_default' => true],
            ['nama_tipe' => 'Barang Tetap', 'is_default' => true],
        ]);

        \App\Models\ItemMasters::insert([
            ['nama_barang' => 'Laptop'],
            ['nama_barang' => 'Selimut'],
        ]);
    }
}
