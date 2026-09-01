<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ULPUsersSeeder extends Seeder
{
    public function run(): void
    {
        $ulps = [
            ['51803BAB', 'managerULP_babat',     'Babat'],
            ['51803BRO', 'managerULP_brondong',  'Brondong'],
            ['51803PAD', 'managerULP_padangan',  'Padangan'],
            ['51803BJN', 'managerULP_bjn',       'Bojonegoro'],
            ['51803SUM', 'managerULP_sumberejo', 'Sumberejo'],
            ['51803TBN', 'managerULP_tuban',     'Tuban'],
            ['51803JAT', 'managerULP_jatirogo',  'Jatirogo'],
        ];

        foreach ($ulps as [$uid, $role, $ulp]) {
            User::updateOrCreate(
                ['user_id' => $uid],
                [
                    'password'   => bcrypt('password'),
                    'role'       => $role,
                    'lokasi_UP3' => 'UP3 Bojonegoro',
                    'lokasi_ULP' => $ulp,
                ]
            );
            $this->command->info("Created/Updated: $uid ($ulp)");
        }
    }
}
