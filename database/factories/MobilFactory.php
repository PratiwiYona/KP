<?php

namespace Database\Factories;

use App\Models\Mobil; 
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mobil>
 */
class MobilFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nomor_rangka' => $this->faker->unique()->regexify('[A-Z0-9]{17}'), // Random VIN-like string
            'model' => $this->faker->word(), // Random model name
            'warna' => $this->faker->safeColorName(), // Random color name
            'tanggal_masuk' => $this->faker->date(), // Random date
            'kapal_pembawa' => $this->faker->optional()->company(), // Random company name or null
        ];
    }
}
