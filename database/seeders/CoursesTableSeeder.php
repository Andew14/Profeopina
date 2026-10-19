<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institution;
use App\Models\Course;
use App\Models\Profesor;

class CoursesTableSeeder extends Seeder
{
    public function run(): void
    {
        $unas = Institution::where('name', 'UNAS')->first();
        $institutionId = $unas ? $unas->id : null;

        if (!$institutionId) {
            return;
        }

        // Mapeo completo de Cursos (Semestres 2026-1 y 2026-2 FIIS UNAS)
        $courseMapping = [
            'Analítica de Datos' => [
                ['nombre' => 'William George', 'apellido' => 'Paucar Palomino']
            ],
            'Arquitectura de Infraestructura de TI' => [
                ['nombre' => 'Edwin Jesús', 'apellido' => 'Vega Ventocilla']
            ],
            'Arquitectura de Software' => [
                ['nombre' => 'Hubel', 'apellido' => 'Solís Bonifacio']
            ],
            'Arquitectura Empresarial' => [
                ['nombre' => 'Marco Arturo', 'apellido' => 'Canales Aguirre'],
                ['nombre' => 'William George', 'apellido' => 'Paucar Palomino']
            ],
            'Calidad de Procesos de Software' => [
                ['nombre' => 'Rannoverg', 'apellido' => 'Yanac Montesino']
            ],
            'Calidad de Producto de Software' => [
                ['nombre' => 'Alberto Lucio', 'apellido' => 'Acevedo Aliaga']
            ],
            'Computación en la Nube' => [],
            'Construcción de Software I' => [
                ['nombre' => 'Hubel', 'apellido' => 'Solís Bonifacio']
            ],
            'Construcción de Software II' => [
                ['nombre' => 'Ronald Eduardo', 'apellido' => 'Ibarra Zapata'],
                ['nombre' => 'Rannoverg', 'apellido' => 'Yanac Montesino']
            ],
            'Desarrollo de Aplicaciones para la Nube' => [
                ['nombre' => 'Brian César', 'apellido' => 'Pando Soto']
            ],
            'Desarrollo de Investigación' => [
                ['nombre' => 'Gardyn', 'apellido' => 'Olivera Ruiz'],
                ['nombre' => 'Ronald Eduardo', 'apellido' => 'Ibarra Zapata']
            ],
            'Diseño de Base de Datos' => [
                ['nombre' => 'Noel', 'apellido' => 'Juipa Campo']
            ],
            'Diseño de Interfaz y Experiencia de Usuario' => [],
            'Diseño de Investigación I' => [
                ['nombre' => 'William Rogelio', 'apellido' => 'Marchand Niño'],
                ['nombre' => 'Christian', 'apellido' => 'García Villegas']
            ],
            'Diseño de Investigación II' => [
                ['nombre' => 'Eudolio Gregorio', 'apellido' => 'Vásquez Pinedo'],
                ['nombre' => 'Ronald Eduardo', 'apellido' => 'Ibarra Zapata']
            ],
            'Diseño de Sistemas de Información' => [
                ['nombre' => 'William George', 'apellido' => 'Paucar Palomino']
            ],
            'Diseño Detallado de Software' => [
                ['nombre' => 'Alberto Lucio', 'apellido' => 'Acevedo Aliaga']
            ],
            'Estadística y Probabilidades' => [
                ['nombre' => 'Wilmer Julio', 'apellido' => 'Bermúdez Pino'],
                ['nombre' => 'Álvaro Ulises', 'apellido' => 'Paredes Acuña']
            ],
            'Estructura de Datos y Algoritmos' => [
                ['nombre' => 'Hubel', 'apellido' => 'Solís Bonifacio'],
                ['nombre' => 'Christian', 'apellido' => 'García Villegas']
            ],
            'Ética y Práctica Profesional' => [
                ['nombre' => 'Jorge Luis', 'apellido' => 'Pozo Malpartida']
            ],
            'Física' => [],
            'Fundamentos de Computación' => [
                ['nombre' => 'Pedro Crisólogo', 'apellido' => 'Trujillo Natividad'],
                ['nombre' => 'Noel', 'apellido' => 'Juipa Campo'],
                ['nombre' => 'Hubel', 'apellido' => 'Solís Bonifacio']
            ],
            'Fundamentos de Investigación' => [
                ['nombre' => 'Rannoverg', 'apellido' => 'Yanac Montesino'],
                ['nombre' => 'Julio César', 'apellido' => 'Gonzales Paico']
            ],
            'Gestión de Base de Datos' => [
                ['nombre' => 'Noel', 'apellido' => 'Juipa Campo']
            ],
            'Gestión de Calidad de Sistemas' => [
                ['nombre' => 'Marco Arturo', 'apellido' => 'Canales Aguirre'],
                ['nombre' => 'Michael', 'apellido' => 'Mariño Gamboa']
            ],
            'Gestión de Procesos de Negocio' => [
                ['nombre' => 'Marco Arturo', 'apellido' => 'Canales Aguirre'],
                ['nombre' => 'Jorge Luis', 'apellido' => 'Pozo Malpartida']
            ],
            'Gestión de Proyectos de TI' => [
                ['nombre' => 'Edwin Jesús', 'apellido' => 'Vega Ventocilla']
            ],
            'Gestión de Servicios de TI' => [
                ['nombre' => 'Nilton', 'apellido' => 'Chucos Baquerizo'],
                ['nombre' => 'Marco Arturo', 'apellido' => 'Canales Aguirre']
            ],
            'Ingeniería de Requisitos' => [
                ['nombre' => 'Alberto Lucio', 'apellido' => 'Acevedo Aliaga']
            ],
            'Inglés Técnico para Informática' => [
                ['nombre' => 'Eudolio Gregorio', 'apellido' => 'Vásquez Pinedo']
            ],
            'Innovación y Emprendimiento' => [
                ['nombre' => 'William George', 'apellido' => 'Paucar Palomino'],
                ['nombre' => 'Michael', 'apellido' => 'Mariño Gamboa']
            ],
            'Integración de Sistemas de Software' => [
                ['nombre' => 'Christian', 'apellido' => 'García Villegas'],
                ['nombre' => 'José Orlando', 'apellido' => 'Castillo Cornelio']
            ],
            'Internet de las Cosas' => [
                ['nombre' => 'Gardyn', 'apellido' => 'Olivera Ruiz'],
                ['nombre' => 'José Antonio', 'apellido' => 'Cárdenas Vega']
            ],
            'Introducción a la Ciencia de Datos' => [
                ['nombre' => 'Nilton', 'apellido' => 'Chucos Baquerizo']
            ],
            'Linux Avanzado' => [
                ['nombre' => 'Julio César', 'apellido' => 'Gonzales Paico']
            ],
            'Machine Learning' => [
                ['nombre' => 'Nilton', 'apellido' => 'Chucos Baquerizo']
            ],
            'Mantenimiento de Software' => [
                ['nombre' => 'Alberto Lucio', 'apellido' => 'Acevedo Aliaga']
            ],
            'Matemática Básica I' => [
                ['nombre' => 'Jaime Eustaquio', 'apellido' => 'Zelaya Abán']
            ],
            'Matemática Básica II' => [],
            'Matemática Discreta' => [
                ['nombre' => 'Darwin Emerson', 'apellido' => 'Guerrero Vejarano']
            ],
            'Matemática Superior' => [
                ['nombre' => 'Jorge Luis', 'apellido' => 'Gonzales Lafosse']
            ],
            'Métodos Ágiles para el Desarrollo de Software' => [
                ['nombre' => 'Brian César', 'apellido' => 'Pando Soto']
            ],
            'Pensamiento Sistémico' => [
                ['nombre' => 'Jorge Luis', 'apellido' => 'Pozo Malpartida']
            ],
            'Planeamiento y Gobierno de TI' => [
                ['nombre' => 'Nilton', 'apellido' => 'Chucos Baquerizo']
            ],
            'Prácticas Pre Profesional' => [
                ['nombre' => 'Edwin Jesús', 'apellido' => 'Vega Ventocilla']
            ],
            'Programabilidad de Redes' => [
                ['nombre' => 'Julio César', 'apellido' => 'Gonzales Paico']
            ],
            'Programación Básica' => [
                ['nombre' => 'Noel', 'apellido' => 'Juipa Campo'],
                ['nombre' => 'Christian', 'apellido' => 'García Villegas'],
                ['nombre' => 'Pedro Crisólogo', 'apellido' => 'Trujillo Natividad']
            ],
            'Pruebas de Software' => [
                ['nombre' => 'Alberto Lucio', 'apellido' => 'Acevedo Aliaga'],
                ['nombre' => 'Michael', 'apellido' => 'Mariño Gamboa']
            ],
            'Redacción y Comprensión Lectora' => [
                ['nombre' => 'Javier Dionisio', 'apellido' => 'Rosas Domínguez']
            ],
            'Redes Avanzadas' => [
                ['nombre' => 'Julio César', 'apellido' => 'Gonzales Paico']
            ],
            'Redes y Conectividad I' => [
                ['nombre' => 'Gardyn', 'apellido' => 'Olivera Ruiz'],
                ['nombre' => 'Pedro Crisólogo', 'apellido' => 'Trujillo Natividad'],
                ['nombre' => 'Edwin Jesús', 'apellido' => 'Vega Ventocilla']
            ],
            'Redes y Conectividad II' => [
                ['nombre' => 'Julio César', 'apellido' => 'Gonzales Paico']
            ],
            'Seguridad de la Información' => [],
            'Seguridad Informática' => [
                ['nombre' => 'Juan', 'apellido' => 'Ramos Estela']
            ],
            'Seguridad Informática Avanzada' => [],
            'Servidores y Centro de Datos' => [
                ['nombre' => 'Gardyn', 'apellido' => 'Olivera Ruiz']
            ],
            'Sistemas de Información' => [
                ['nombre' => 'William George', 'apellido' => 'Paucar Palomino']
            ],
            'Sistemas Inalámbricos' => [
                ['nombre' => 'Eudolio Gregorio', 'apellido' => 'Vásquez Pinedo']
            ],
            'Sistemas Operativos I' => [
                ['nombre' => 'Eudolio Gregorio', 'apellido' => 'Vásquez Pinedo'],
                ['nombre' => 'José Antonio', 'apellido' => 'Cárdenas Vega']
            ],
            'Sistemas Operativos II' => [
                ['nombre' => 'Eudolio Gregorio', 'apellido' => 'Vásquez Pinedo'],
                ['nombre' => 'José Antonio', 'apellido' => 'Cárdenas Vega']
            ],
            'Soporte de TI' => [
                ['nombre' => 'Pedro Crisólogo', 'apellido' => 'Trujillo Natividad'],
                ['nombre' => 'Edwin Jesús', 'apellido' => 'Vega Ventocilla']
            ],
            'Sostenibilidad y Responsabilidad Social' => [
                ['nombre' => 'Carmela Mercedes', 'apellido' => 'Pajuelo Maguiña']
            ],
            'Taller de Habilidades Blandas' => [
                ['nombre' => 'Macklean', 'apellido' => 'Reátegui Guerra']
            ],
        ];

        foreach ($courseMapping as $courseName => $profesoresData) {
            $course = Course::firstOrCreate([
                'name' => $courseName,
                'institution_id' => $institutionId,
            ]);

            $profesorIds = [];
            foreach ($profesoresData as $profData) {
                $profesor = Profesor::where('nombre', $profData['nombre'])
                    ->where('apellido', $profData['apellido'])
                    ->first();
                if ($profesor) {
                    $profesorIds[] = $profesor->id;
                }
            }

            if (!empty($profesorIds)) {
                $course->profesors()->syncWithoutDetaching($profesorIds);
            }
        }
    }
}
