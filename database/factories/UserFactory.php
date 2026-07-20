<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            
            'role_id' => 3,
            'skpd_id' => null,
            'institusi_asal' => fake()->randomElement([
                'Universitas Lambung Mangkurat',
                'Politeknik Negeri Banjarmasin',
                'UNISKA MAB Banjarmasin',
                'SMKN 2 Banjarmasin',
                'SMKN 5 Banjarmasin',
            ]),
            'jurusan_prodi' => fake()->randomElement([
                'Teknik Informatika',
                'Sistem Informasi',
                'Rekayasa Perangkat Lunak',
                'Teknik Komputer Jaringan',
                'Manajemen Informatika',
            ]),
        ];
    }

    public function adminSkpd(int $skpdId): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => 2,
            'skpd_id' => $skpdId,
            'institusi_asal' => null,
            'jurusan_prodi' => null,
        ]);
    }

    public function superadmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => 1,
            'skpd_id' => null,
            'institusi_asal' => null,
            'jurusan_prodi' => null,
        ]);
    }
}
