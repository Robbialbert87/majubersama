<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EggSize;

class EggSizeSeeder extends Seeder
{
    public function run(): void
    {
        EggSize::create(['kode' => 'B', 'nama' => 'Besar', 'urutan' => 1, 'status' => 'Active']);
        EggSize::create(['kode' => 'S', 'nama' => 'Sedang', 'urutan' => 2, 'status' => 'Active']);
        EggSize::create(['kode' => 'T', 'nama' => 'Tanggung', 'urutan' => 3, 'status' => 'Active']);
        EggSize::create(['kode' => 'P', 'nama' => 'Putih', 'urutan' => 4, 'status' => 'Active']);
    }
}
