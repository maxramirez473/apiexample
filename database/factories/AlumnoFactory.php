<?php

namespace Database\Factories;

use App\Models\Alumno;
use App\Models\Grupo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Alumno>
 */
class AlumnoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->firstName(),
            'apellido' => $this->faker->lastName(),
            'legajo' => $this->faker->unique()->numberBetween(20000, 50000),
            'email' => $this->faker->unique()->safeEmail(),
            'grupo_id' => Grupo::inRandomOrder()->value('id'),
        ];
    }
}
