<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\PengirimanData;
use App\Models\BerkasDokumen;
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
        ];

        foreach ($vendors as $v) {
            User::updateOrCreate(
                ['user_id' => $v['user_id']],
                $v
            );
        }

        // 2. Data Pengiriman dari Role Perencanaan beserta Berkas Dokumen
        $pengirimanList = [
            [
                'agendaKey'   => '518039912601284909',
                'dest'        => 'tanpa_perluasan',
                'no_agenda'   => '518039912601284909',
                'nama'        => 'PT. Alpha Electrical (Project Laren)',
                'alamat'      => 'JL TAMAN PRIJEG TAMANPRIJEG, LAREN, KAB. LAMONGAN',
                'transaksi'   => 'Pasang Baru',
                'status'      => 'Bayar',
                'tarif_lama'  => null,
                'daya_lama'   => 0,
                'tarif_baru'  => 'B2T',
                'daya_baru'   => 13200,
                'total_biaya' => 15000000,
                'ulp'         => 'Lamongan',
                'ktpCount'    => 1,
                'ittCount'    => 1,
                'sentAt'      => now(),
                'berkas'      => [
                    [
                        'jenis_berkas' => 'wo',
                        'nama_file'    => 'WO_518039912601284909.pdf',
                        'path_file'    => 'uploads/wo/WO_518039912601284909.pdf',
                    ],
                    [
                        'jenis_berkas' => 'ktp',
                        'nama_file'    => 'KTP_Pelanggan_Alpha.pdf',
                        'path_file'    => 'uploads/ktp/KTP_Pelanggan_Alpha.pdf',
                    ],
                ],
            ],
            [
                'agendaKey'   => '518030522602037603',
                'dest'        => 'perluasan_jtr',
                'no_agenda'   => '518030522602037603',
                'nama'        => 'PT. Bravo Kencana (Project Sundu)',
                'alamat'      => 'BENGKEL BONANZA MOTOR, JL JEND SUDIRMAN No.12',
                'transaksi'   => 'Perubahan Daya',
                'status'      => 'Mohon',
                'tarif_lama'  => 'R2T',
                'daya_lama'   => 5500,
                'tarif_baru'  => 'B2T',
                'daya_baru'   => 16500,
                'total_biaya' => 22000000,
                'ulp'         => 'Bojonegoro',
                'ktpCount'    => 1,
                'ittCount'    => 1,
                'sentAt'      => now(),
                'berkas'      => [
                    [
                        'jenis_berkas' => 'wo',
                        'nama_file'    => 'WO_518030522602037603.pdf',
                        'path_file'    => 'uploads/wo/WO_518030522602037603.pdf',
                    ],
                    [
                        'jenis_berkas' => 'itt',
                        'nama_file'    => 'ITT_Bravo.pdf',
                        'path_file'    => 'uploads/itt/ITT_Bravo.pdf',
                    ],
                ],
            ],
        ];

        foreach ($pengirimanList as $pData) {
            $berkas = $pData['berkas'];
            unset($pData['berkas']);

            $pengiriman = PengirimanData::updateOrCreate(
                ['no_agenda' => $pData['no_agenda']],
                $pData
            );

            foreach ($berkas as $b) {
                BerkasDokumen::updateOrCreate(
                    [
                        'pengiriman_id' => $pengiriman->id,
                        'nama_file'     => $b['nama_file'],
                    ],
                    $b
                );
            }
        }
    }
}
