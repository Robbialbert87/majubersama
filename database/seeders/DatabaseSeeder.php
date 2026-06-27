<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(EggSizeSeeder::class);
        $this->call(KandangSeeder::class);

        $roles = ['Admin', 'Manager', 'Editor', 'Staff', 'User'];
        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role' => 'Admin',
            'status' => 'Active',
        ]);

        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'User',
            'status' => 'Active',
        ]);
    }
}
