<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class DummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Insert user
    $userId = DB::table('users')->insertGetId([
        'name' => 'Budi Santoso',
        'email' => 'budi@example.com',
        'password' => bcrypt('password'), 
        'remember_token' => Str::random(10),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Insert barang
    $barangId = DB::table('barangs')->insertGetId([
        'nama_barang' => 'Laptop Dell XPS 13',
        'harga_awal' => 15000000.00,
        'kategori' => 'Elektronik',
        'tipe' => 'Laptop',
        'status' => 'Tersedia',
        'kodeQR' => 'asdmas123@mk21(example unique code)',
        'bukti' => '/images/example.jpg',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Insert pinjaman
    $pinjamanId = DB::table('pinjamans')->insertGetId([
        // 'user_id' => $userId,
        'barang_id' => $barangId,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Insert barang rusak
    DB::table('barang_rusaks')->insert([
        'barang_id' => $barangId,
        'pinjaman_id' => $pinjamanId,
        'biaya_perbaikan' => 500000.00,
        'keterangan' => 'Layar retak akibat terjatuh',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    }
}
