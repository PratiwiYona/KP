<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KondisiMobil>
 */
class KondisiMobilFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_mobil' => \App\Models\Mobil::inRandomOrder()->first()->id_mobil,
            'catatan_defect' => $this->faker->sentence(),
            'tanggal_masuk_bengkel' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'tanggal_keluar_bengkel' => $this->faker->dateTimeBetween('now', '+1 month'),
            'klaim_warranty' => $this->faker->word(),
        ];
    }
}
