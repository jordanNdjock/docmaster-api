<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TypeDocument>
 */
class TypeDocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'titre' => $this->faker->sentence(),
            'libelle' => $this->faker->sentence(),
            'frais' => $this->faker->randomFloat(2, 0, 100),
            'recompense' => $this->faker->randomFloat(2, 0, 100),
            'validite' => $this->faker->boolean(50),
            'supprime' => false,
        ];
    }
}
