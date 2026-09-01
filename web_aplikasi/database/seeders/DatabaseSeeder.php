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
            'lokasi_UP3' => 'UP3 Bojonegoro',
            'lokasi_ULP' => 'Bojonegoro',
        ]);

        // manager ULP - Lamongan
        User::factory()->create([
            'user_id' => '51803',
            'password' => bcrypt('password'),
            'role' => 'managerULP',
            'lokasi_UP3' => 'UP3 Bojonegoro',
            'lokasi_ULP' => 'Lamongan',
        ]);

        // manager ULP - Babat
        User::factory()->create([
            'user_id' => '51803BAB',
            'password' => bcrypt('password'),
            'role' => 'managerULP_babat',
            'lokasi_UP3' => 'UP3 Bojonegoro',
            'lokasi_ULP' => 'Babat',
        ]);

        // manager ULP - Brondong
        User::factory()->create([
            'user_id' => '51803BRO',
            'password' => bcrypt('password'),
            'role' => 'managerULP_brondong',
            'lokasi_UP3' => 'UP3 Bojonegoro',
            'lokasi_ULP' => 'Brondong',
        ]);

        // manager ULP - Padangan
        User::factory()->create([
            'user_id' => '51803PAD',
            'password' => bcrypt('password'),
            'role' => 'managerULP_padangan',
            'lokasi_UP3' => 'UP3 Bojonegoro',
            'lokasi_ULP' => 'Padangan',
        ]);

        // manager ULP - Bojonegoro
        User::factory()->create([
            'user_id' => '51803BJN',
            'password' => bcrypt('password'),
            'role' => 'managerULP_bjn',
            'lokasi_UP3' => 'UP3 Bojonegoro',
            'lokasi_ULP' => 'Bojonegoro',
        ]);

        // manager ULP - Sumberejo
        User::factory()->create([
            'user_id' => '51803SUM',
            'password' => bcrypt('password'),
            'role' => 'managerULP_sumberejo',
            'lokasi_UP3' => 'UP3 Bojonegoro',
            'lokasi_ULP' => 'Sumberejo',
        ]);

        // manager ULP - Tuban
        User::factory()->create([
            'user_id' => '51803TBN',
            'password' => bcrypt('password'),
            'role' => 'managerULP_tuban',
            'lokasi_UP3' => 'UP3 Bojonegoro',
            'lokasi_ULP' => 'Tuban',
        ]);

        // manager ULP - Jatirogo
        User::factory()->create([
            'user_id' => '51803JAT',
            'password' => bcrypt('password'),
            'role' => 'managerULP_jatirogo',
            'lokasi_UP3' => 'UP3 Bojonegoro',
            'lokasi_ULP' => 'Jatirogo',
        ]);

        // KONSTRUKSI
        User::factory()->create([
            'user_id' => '5180KON',
            'password' => bcrypt('password'),
            'role' => 'konstruksi',
            'lokasi_UP3' => 'UP3 Bojonegoro',
            'lokasi_ULP' => 'Bojonegoro',
        ]);

        // PELAYANAN dan ADMINISTRASI
        User::factory()->create([
            'user_id' => '5180PA',
            'password' => bcrypt('password'),
            'role' => 'pelayanan',
            'lokasi_UP3' => 'UP3 Bojonegoro',
            'lokasi_ULP' => 'Bojonegoro',
        ]);

        // PERENCANAAN
        User::factory()->create([
            'user_id' => '5180REN',
            'password' => bcrypt('password'),
            'role' => 'perencanaan',
            'lokasi_UP3' => 'UP3 Bojonegoro',
            'lokasi_ULP' => 'Bojonegoro',
        ]);

        // JARINGAN
        User::factory()->create([
            'user_id' => '5180JAR',
            'password' => bcrypt('password'),
            'role' => 'jaringan',
            'lokasi_UP3' => 'UP3 Bojonegoro',
            'lokasi_ULP' => 'Bojonegoro',
        ]);

        // TRANSAKSI ENERGI LISTRIK
        User::factory()->create([
            'user_id' => '5180TEL',
            'password' => bcrypt('password'),
            'role' => 'transaksi',
            'lokasi_UP3' => 'UP3 Bojonegoro',
            'lokasi_ULP' => 'Bojonegoro',
        ]);

        // ADMINISTRATOR
        User::factory()->create([
            'user_id' => '5180',
            'password' => bcrypt('password'),
            'role' => 'administrator',
            'lokasi_UP3' => 'UP3 Bojonegoro',
            'lokasi_ULP' => 'Bojonegoro',
        ]);

        $this->call([
            Data_pb_pdSeeder::class,
            JTMSeeder::class,
            TanpaPerluasanSeeder::class,
            PerluasanJtrSeeder::class,
            PengoperasianSeeder::class,
            ProsesPerluasanSeeder::class,
            RestitusiSeeder::class,
            LaporanSeeder::class,
            NotifikasiSeeder::class,
            BaOperasiSeeder::class,
            SurveySeeder::class,
            ChecklistSeeder::class,
        ]);
    }
}
