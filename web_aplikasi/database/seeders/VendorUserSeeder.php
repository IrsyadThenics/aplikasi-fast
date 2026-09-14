<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class VendorUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun User Vendor (PT. Alpha & PT. Bravo)
        $vendors = [
            [
                'name' => 'PT. Alpha Electrical',
                'user_id' => 'VENDOR_ALPHA',
                'password' => Hash::make('password'),
                'role' => 'vendor',
                'lokasi_UP3' => 'UP3 Bojonegoro',
                'lokasi_ULP' => 'Bojonegoro',
            ],
            [
                'name' => 'PT. Bravo Kencana',
                'user_id' => 'VENDOR_BRAVO',
                'password' => Hash::make('password'),
                'role' => 'vendor',
                'lokasi_UP3' => 'UP3 Bojonegoro',
                'lokasi_ULP' => 'Lamongan',
            ],
            [
                'name' => 'Vendor Konstruksi',
                'user_id' => 'VENDOR_KONSTRUKSI',
                'password' => Hash::make('password'),
                'role' => 'vendor_konstruksi',
                'lokasi_UP3' => 'UP3 Bojonegoro',
                'lokasi_ULP' => 'Bojonegoro',
            ],
        ];

        foreach ($vendors as $v) {
            User::updateOrCreate(
                ['user_id' => $v['user_id']],
                $v
            );
        }

    }
}
