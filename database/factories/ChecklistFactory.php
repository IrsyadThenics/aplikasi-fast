<?php

namespace Database\Factories;

use App\Models\Checklist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Checklist>
 */
class ChecklistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dtl' => $this->faker->randomElement(['Ada', 'Tidak ada']),
            'ulp' => $this->faker->randomElement(['Lamongan']),
            'transaksi' => $this->faker->randomElement(['PASANG BARU', 'PERUBAHAN DAYA']),
            'status' => $this->faker->randomElement(['BAYAR', 'MOHON']),
            'no_agenda' => $this->faker->numberBetween(1000000000, 9999999999),
            'alamat' => $this->faker->address,
            'tarif_lama' => $this->faker->randomElement(['R1', 'R1M', 'R1T', 'R2T', 'B2T', '0']),
            'daya_lama' => $this->faker->randomElement([0, 450, 900, 1300, 2200, 3500, 5500]),
            'tarif_baru' => $this->faker->randomElement(['R1T', 'R2T', 'B2T', 'B3']),
            'daya_baru' => $this->faker->randomElement([900, 1300, 2200, 3500, 5500, 7700, 13200, 16500]),
        ];

    }
}
