<?php

namespace Database\Factories;

use App\Models\Persona;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Persona>
 */
class PersonaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombres' => $this->faker->firstName(),
            'apellidos' => $this->faker->lastName(),
            'documento_identidad' => $this->faker->numerify('###########'),
            'sexo' => $this->faker->randomElement(['Masculino', 'Femenino']),
            'fecha_nacimiento' => $this->faker->date(),
            'celular' => $this->faker->numerify('7########'),
            //
        ];
    }
}
