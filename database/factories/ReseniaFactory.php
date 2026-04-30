<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Resenia;
use App\Models\Profesor;

class ReseniaFactory extends Factory
{
    protected $model = Resenia::class;

    public function definition()
    {
        return [
            'contenido' => $this->faker->sentence,
            'calificacion' => 5,
            'profesor_id' => Profesor::factory(),
        ];
    }
}
