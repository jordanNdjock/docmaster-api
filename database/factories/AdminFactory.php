<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class AdminFactory extends Factory
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
            'nom_utilisateur' => $this->faker->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'mdp' => static::$password ??= Hash::make('password'),
            'tel' => $this->faker->phoneNumber(),
            'supprime' => false,
        ];
    }
}
