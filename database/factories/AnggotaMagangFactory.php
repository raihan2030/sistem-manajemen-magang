<?php

namespace Database\Factories;

use App\Models\AnggotaMagang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AnggotaMagang>
 */
class AnggotaMagangFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nim_nisn' => fake()->unique()->numerify('##########'),
            'nama_lengkap' => fake()->name(),
            'kartu_identitas' => 'documents/ktm_dummy.jpg',
        ];
    }
}
