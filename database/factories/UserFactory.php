<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
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
            'id' => (string) Str::uuid(),
            'nom_famille' => fake()->firstName(),
            'prenom' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'nom_utilisateur' => fake()->userName(),
            'tel' => fake()->phoneNumber(),
            'date_naissance' => fake()->date(),
            'infos_paiement' => fake()->text(),
            'localisation' => fake()->address(),
            'code_invitation' => fake()->text(6),
            'solde' => fake()->randomFloat(2, 0, 1000),
            'supprime' => false,
            'initial_2_prenom' => fake()->name(),
            'email_verified_at' => now(),
            'mdp' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
