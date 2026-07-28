<?php

namespace Database\Seeders;

use App\Models\data_pb_pd_UP3;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Data_pb_pdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Database\Factories\Data_pb_pdFactory::new()->count(25)->create();
    }
}

