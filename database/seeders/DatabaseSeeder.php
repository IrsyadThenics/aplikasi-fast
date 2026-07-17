<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        // manager UP3
        User::factory()->create([
            'user_id' => '5180MAN',
            'password' => bcrypt('password'),
            'role' => 'managerUP3',
        ]);

        // manager ULP
        User::factory()->create([
            'user_id' => '51803',
            'password' => bcrypt('password'),
            'role' => 'managerULP',
        ]);

        // KONSTRUKSI
        User::factory()->create([
            'user_id' => '5180KON',
            'password' => bcrypt('password'),
            'role' => 'konstruksi',
        ]);

        // PELAYANAN dan ADMINISTRASI
        User::factory()->create([
            'user_id' => '5180PA',
            'password' => bcrypt('password'),
            'role' => 'pelayanan',
        ]);

        // PERENCANAAN
        User::factory()->create([
            'user_id' => '5180REN',
            'password' => bcrypt('password'),
            'role' => 'perencanaan',
        ]);

        // JARINGAN
        User::factory()->create([
            'user_id' => '5180JAR',
            'password' => bcrypt('password'),
            'role' => 'jaringan',
        ]);

        // TRANSAKSI ENERGI LISTRIK
        User::factory()->create([
            'user_id' => '5180TEL',
            'password' => bcrypt('password'),
            'role' => 'transaksi',
        ]);

        // ADMINISTRATOR
        User::factory()->create([
            'user_id' => '5180',
            'password' => bcrypt('password'),
            'role' => 'administrator',
        ]);
    }
}
