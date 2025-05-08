<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Abonnement>
 */
class AbonnementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre_docs_par_type' => $this->faker->numberBetween(1, 10),
            'titre' => $this->faker->word(),
            'montant' => $this->faker->randomFloat(2, 1, 100),
            'supprime' => $this->faker->boolean(),
        ];
    }
}
