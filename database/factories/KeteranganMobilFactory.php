<?php

namespace Database\Factories;

use App\Models\Mobil;
use App\Models\Keterangan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KeteranganMobil>
 */
class KeteranganMobilFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_mobil' => Mobil::inRandomOrder()->first()->id_mobil,
            'id_keterangan' => Keterangan::inRandomOrder()->first()->id,
        ];
    }
}
