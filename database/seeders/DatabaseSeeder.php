<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\EggCategory;
use App\Models\Barn;
use App\Models\SystemSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $roles = ['Admin', 'Manager', 'Editor', 'Staff', 'User'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        User::firstOrCreate(['email' => 'admin@admin.com'], [
            'name' => 'Admin',
            'password' => bcrypt('password'),
            'role' => 'Admin',
            'status' => 'Active',
        ]);

        User::firstOrCreate(['email' => 'test@example.com'], [
            'name' => 'Test User',
            'password' => bcrypt('password'),
            'role' => 'User',
            'status' => 'Active',
        ]);

        // Egg categories per business rules
        foreach ([
            ['kode' => 'J', 'nama' => 'Jumbo', 'unit_penjualan' => 'papan', 'urutan' => 1],
            ['kode' => 'B', 'nama' => 'Besar', 'unit_penjualan' => 'ikat', 'urutan' => 2],
            ['kode' => 'S', 'nama' => 'Sedang', 'unit_penjualan' => 'ikat', 'urutan' => 3],
            ['kode' => 'K', 'nama' => 'Kecil', 'unit_penjualan' => 'ikat', 'urutan' => 4],
            ['kode' => 'P', 'nama' => 'Putih', 'unit_penjualan' => 'papan', 'urutan' => 5],
            ['kode' => 'R', 'nama' => 'Pecah', 'unit_penjualan' => 'tidak', 'urutan' => 6],
        ] as $cat) {
            EggCategory::firstOrCreate(['kode' => $cat['kode']], [...$cat, 'status' => 'Active']);
        }

        // Barns (kandang)
        foreach ([
            ['kode' => 'KDG001', 'nama' => 'Kandang Bayu', 'lokasi' => 'Desa Bayu, Kec. Ngantang, Kab. Malang', 'kapasitas_ayam' => 5000],
            ['kode' => 'KDG002', 'nama' => 'Kandang Opung', 'lokasi' => 'Ds. Opung, Kec. Ngantang, Kab. Malang', 'kapasitas_ayam' => 3000],
        ] as $barn) {
            Barn::firstOrCreate(['kode' => $barn['kode']], [...$barn, 'status' => 'Active']);
        }

        // System settings (1 papan = 30 butir, 1 ikat = 5 papan)
        SystemSetting::firstOrCreate(['id' => 1], ['butir_per_papan' => 30, 'papan_per_ikat' => 5]);
    }
}
