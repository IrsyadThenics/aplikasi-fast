<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ULPUsersSeeder extends Seeder
{
    public function run(): void
    {
        $ulps = [
            ['51801', 'managerULP_bjn',        'Bojonegoro'],
            ['51802', 'managerULP_tuban',      'Tuban'],
            ['51803', 'managerULP',            'Lamongan'],
            ['51804', 'managerULP_babat',      'Babat'],
            ['51805', 'managerULP_padangan',   'Padangan'],
            ['51806', 'managerULP_brondong',   'Brondong'],
            ['51807', 'managerULP_jatirogo',   'Jatirogo'],
            ['51808', 'managerULP_sumberejo',  'Sumberrejo'],
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
