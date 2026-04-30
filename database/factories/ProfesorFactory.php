<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Profesor;

class ProfesorFactory extends Factory
{
    protected $model = Profesor::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->firstName,
            'apellido' => $this->faker->lastName,
            'descripcion' => $this->faker->sentence(8),
            'foto' => 'fotos/' . $this->faker->word . '.jpg',
        ];
    }
}
