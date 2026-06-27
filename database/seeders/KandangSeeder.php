<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kandang;

class KandangSeeder extends Seeder
{
    public function run(): void
    {
        Kandang::create(['kode' => 'KDG001', 'nama' => 'Kandang Bayu', 'lokasi' => ' Desa Bayu, Kec. Ngantang, Kab. Malang']);
        Kandang::create(['kode' => 'KDG002', 'nama' => 'Kandang Opung', 'lokasi' => ' Ds. Opung, Kec. Ngantang, Kab. Malang']);
    }
}
