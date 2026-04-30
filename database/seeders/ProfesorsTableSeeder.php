<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profesor;
use App\Models\Institution;

class ProfesorsTableSeeder extends Seeder
{
    public function run()
    {
        $unas = Institution::where('name', 'UNAS')->first();
        $institutionId = $unas ? $unas->id : null;

        Profesor::create([
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'descripcion' => 'Especialista en álgebra y geometría con 10 años de experiencia docente.',
            'foto' => 'fotos/juan_perez.jpg',
            'activo' => true,
            'institution_id' => $institutionId,
        ]);

        Profesor::create([
            'nombre' => 'María',
            'apellido' => 'García',
            'descripcion' => 'Licenciada en Historia del Arte con enfoque en Historia Latinoamericana.',
            'foto' => 'fotos/maria_garcia.jpg',
            'activo' => true,
            'institution_id' => $institutionId,
        ]);

        Profesor::create([
            'nombre' => 'Hubel',
            'apellido' => 'Bonifacio',
            'descripcion' => 'Ingeniero de Sistemas con especialización en desarrollo de software.',
            'foto' => 'fotos/hubel.jpg',
            'activo' => true,
            'institution_id' => $institutionId,
        ]);
    }
}
