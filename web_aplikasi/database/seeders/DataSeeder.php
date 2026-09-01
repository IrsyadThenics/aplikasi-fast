<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dummyData = [
            [
                'dtl' => 'Ada',
                'ulp' => 'ULP LAMONGAN',
                'transaksi' => 'Pasang Baru',
                'status' => 'Bayar',
                'no_agenda' => '518039912601284909',
                'alamat' => 'JL TAMAN PRIJEG TAMANPRIJEG, LAREN, KAB. LAMONGAN, JAWA TIMUR',
                'tarif_lama' => null,
                'daya_lama' => 0,
                'tarif_baru' => 'B2T',
                'daya_baru' => 13200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dtl' => 'Tidak',
                'ulp' => 'ULP BOJONEGORO',
                'transaksi' => 'Perubahan Daya',
                'status' => 'Mohon',
                'no_agenda' => '518030522602037603',
                'alamat' => 'BENGKEL BONANZA MOTOR, JL JEND SUDIRMAN No.0',
                'tarif_lama' => 'R2T',
                'daya_lama' => 5500,
                'tarif_baru' => 'B2T',
                'daya_baru' => 16500,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dtl' => 'Ada',
                'ulp' => 'ULP TUBAN',
                'transaksi' => 'Pasang Baru',
                'status' => 'Bayar',
                'no_agenda' => '518039912603255573',
                'alamat' => 'DN KANDANAGAN DS TUMAPEL KEC DUDUKSAMPEYAN',
                'tarif_lama' => null,
                'daya_lama' => 0,
                'tarif_baru' => 'B2T',
                'daya_baru' => 16500,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        \App\Models\data::insert($dummyData);
    }
}
