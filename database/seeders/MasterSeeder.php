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
            ['nama_kategori' => 'Milik Sekolah', 'deskripsi' => 'Barang milik sekolah'],
            ['nama_kategori' => 'Dipinjam oleh siswa', 'deskripsi' => 'Barang pinjaman untuk santri'],
        ]);

        \App\Models\StatusMaster::insert([
            ['nama_status' => 'Baru', 'warna' => 'green'],
            ['nama_status' => 'Hilang', 'warna' => 'yellow'],
            ['nama_status' => 'Rusak Ringan', 'warna' => 'red'],
            ['nama_status' => 'Rusak Berat', 'warna' => 'red'],
            ['nama_status' => 'Diperbarui', 'warna' => 'blue'],
        ]);

        \App\Models\TypeMaster::insert([
            ['nama_tipe' => 'Barang Berpindah'],
            ['nama_tipe' => 'Barang Tetap'],
        ]);

        \App\Models\ItemMasters::insert([
            ['nama_barang' => 'Laptop'],
            ['nama_barang' => 'Selimut'],
        ]);
    }
}
