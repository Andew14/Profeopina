<?php

// database/seeders/ReseniasTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resenia;
use App\Models\Profesor; // Asegúrate de importar el modelo Profesor

class ReseniasTableSeeder extends Seeder
{
    public function run()
    {
        // Obtener los IDs de los profesores que has creado anteriormente
        $juan = Profesor::where('nombre', 'Juan')->first()->id;
        $maria = Profesor::where('nombre', 'María')->first()->id;

        $resenias = [
            [
                'contenido' => 'Esta es una reseña ejemplo 1.',
                'calificacion' => 5,
                'profesor_id' => $juan,
            ],
            [
                'contenido' => 'Buen profesor',
                'calificacion' => 4,
                'profesor_id' => $juan,
            ],
            [
                'contenido' => 'Esta es una reseña ejemplo 2.',
                'calificacion' => 3,
                'profesor_id' => $maria,
            ],
            // Agrega más reseñas aquí
        ];

        foreach ($resenias as $resenia) {
            Resenia::create([
                'contenido' => $resenia['contenido'],
                'calificacion' => $resenia['calificacion'],
                'profesor_id' => $resenia['profesor_id'],
            ]);
        }
    }
}
