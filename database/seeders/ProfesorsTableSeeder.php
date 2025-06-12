<?php

// database/seeders/ProfesorsTableSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profesor;

class ProfesorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Profesor::create([
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'especialidad' => 'Matemáticas',
            'foto' => 'fotos/juan_perez.jpg', // Asegúrate de que la ruta sea correcta
        ]);

        Profesor::create([
            'nombre' => 'María',
            'apellido' => 'García',
            'especialidad' => 'Historia',
            'foto' => 'fotos/maria_garcia.jpg', // Asegúrate de que la ruta sea correcta
        ]);
        Profesor::create([
            'nombre' => 'Hubel',
            'apellido' => 'Bonifacio',
            'especialidad' => 'FIIS',
            'foto' => 'fotos/hubel.jpg', // Asegúrate de que la ruta sea correcta
        ]);

        // Agrega más profesores según sea necesario
    }
}

