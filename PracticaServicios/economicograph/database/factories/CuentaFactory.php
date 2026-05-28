<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cuenta>
 */
class CuentaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cuenta' => (string) $this->faker->unique()->numberBetween(1000, 9999),
            'ci' => (string) $this->faker->numberBetween(1000000, 9999999),
            'nombres' => $this->faker->firstName(),
            'apellidos' => $this->faker->lastName(),
            'saldo' => $this->faker->randomFloat(2, 0, 5000),
        ];
    }
}
