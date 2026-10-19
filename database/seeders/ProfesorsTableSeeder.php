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

        $profesores = [
            [
                'nombre' => 'Nilton',
                'apellido' => 'Chucos Baquerizo',
                'foto' => 'https://www.sistemasunas.edu.pe/sites/default/files/nilton.png',
                'descripcion' => 'Docente especializado en inteligencia artificial, analítica de datos y gobierno de TI.',
            ],
            [
                'nombre' => 'Marco Arturo',
                'apellido' => 'Canales Aguirre',
                'foto' => 'https://www.sistemasunas.edu.pe/sites/default/files/canales.png',
                'descripcion' => 'Docente del área de arquitectura empresarial y gestión de procesos de negocio.',
            ],
            [
                'nombre' => 'William Rogelio',
                'apellido' => 'Marchand Niño',
                'foto' => 'https://www.sistemasunas.edu.pe/sites/default/files/marchand.png',
                'descripcion' => 'Docente investigador en metodología y diseño de proyectos de investigación.',
            ],
            [
                'nombre' => 'Pedro Crisólogo',
                'apellido' => 'Trujillo Natividad',
                'foto' => 'https://www.sistemasunas.edu.pe/sites/default/files/pedro.png',
                'descripcion' => 'Docente de programación básica, soporte técnico e infraestructura tecnológica.',
            ],
            [
                'nombre' => 'Noel',
                'apellido' => 'Juipa Campo',
                'foto' => 'https://www.sistemasunas.edu.pe/sites/default/files/juipa.png',
                'descripcion' => 'Especialista en diseño de bases de datos y desarrollo de software.',
            ],
            [
                'nombre' => 'Edwin Jesús',
                'apellido' => 'Vega Ventocilla',
                'foto' => 'https://www.sistemasunas.edu.pe/sites/default/files/EdwinVega.jpg',
                'descripcion' => 'Docente en gestión de proyectos de TI, prácticas pre profesionales y soporte de TI.',
            ],
            [
                'nombre' => 'Rannoverg',
                'apellido' => 'Yanac Montesino',
                'foto' => 'https://www.sistemasunas.edu.pe/sites/default/files/YanacMontesino.jpg',
                'descripcion' => 'Especialista en calidad de procesos de software y modelos de madurez.',
            ],
            [
                'nombre' => 'William George',
                'apellido' => 'Paucar Palomino',
                'foto' => null,
                'descripcion' => 'Docente en analítica de datos, arquitectura empresarial y diseño de sistemas.',
            ],
            [
                'nombre' => 'Eudolio Gregorio',
                'apellido' => 'Vásquez Pinedo',
                'foto' => 'https://www.sistemasunas.edu.pe/sites/default/files/Gregorio_Vasquez_Director_DACIS_FIIS.png',
                'descripcion' => 'Director DACIS - FIIS. Docente en sistemas operativos y lenguaje técnico.',
            ],
            [
                'nombre' => 'Brian César',
                'apellido' => 'Pando Soto',
                'foto' => 'https://www.sistemasunas.edu.pe/sites/default/files/BrianPando.jpg',
                'descripcion' => 'Especialista en desarrollo de aplicaciones distribuidas y computación en la nube.',
            ],
            [
                'nombre' => 'Ronald Eduardo',
                'apellido' => 'Ibarra Zapata',
                'foto' => 'https://www.sistemasunas.edu.pe/sites/default/files/RonaldIbarra_0.jpg',
                'descripcion' => 'Docente en desarrollo de tesis y proyectos de investigación académica.',
            ],
            [
                'nombre' => 'Christian',
                'apellido' => 'García Villegas',
                'foto' => 'https://www.sistemasunas.edu.pe/sites/default/files/GarciaVillegas.jpg',
                'descripcion' => 'Docente de programación estructurada e investigación tecnológica.',
            ],
            [
                'nombre' => 'Jorge Luis',
                'apellido' => 'Pozo Malpartida',
                'foto' => 'https://www.sistemasunas.edu.pe/sites/default/files/JorgePozo.jpg',
                'descripcion' => 'Especialista en pensamiento sistémico y gestión de procesos de negocio.',
            ],
            [
                'nombre' => 'Gardyn',
                'apellido' => 'Olivera Ruiz',
                'foto' => 'https://www.sistemasunas.edu.pe/sites/default/files/Gardyn_Olivera_Ruiz_Director_EPICI.png',
                'descripcion' => 'Director EPICI. Docente en desarrollo de investigación científica.',
            ],
            [
                'nombre' => 'Julio César',
                'apellido' => 'Gonzales Paico',
                'foto' => null,
                'descripcion' => 'Docente en redes de computadoras, conectividad y sistemas distribuidos.',
            ],
            [
                'nombre' => 'Alberto Lucio',
                'apellido' => 'Acevedo Aliaga',
                'foto' => null,
                'descripcion' => 'Especialista en calidad de producto, diseño detallado y mantenimiento de software.',
            ],
            [
                'nombre' => 'José Antonio',
                'apellido' => 'Cárdenas Vega',
                'foto' => null,
                'descripcion' => 'Docente de sistemas operativos y arquitectura de computadoras.',
            ],
            [
                'nombre' => 'Hubel',
                'apellido' => 'Solís Bonifacio',
                'foto' => null,
                'descripcion' => 'Docente en construcción de software y arquitecturas de sistemas.',
            ],
            [
                'nombre' => 'Wilmer Julio',
                'apellido' => 'Bermúdez Pino',
                'foto' => null,
                'descripcion' => 'Docente especialista en Estadística y Probabilidades.',
            ],
            [
                'nombre' => 'Álvaro Ulises',
                'apellido' => 'Paredes Acuña',
                'foto' => null,
                'descripcion' => 'Docente especialista en Estadística y Métodos Cuantitativos.',
            ],
            [
                'nombre' => 'Michael',
                'apellido' => 'Mariño Gamboa',
                'foto' => null,
                'descripcion' => 'Docente en gestión de calidad de sistemas e innovación.',
            ],
            [
                'nombre' => 'José Orlando',
                'apellido' => 'Castillo Cornelio',
                'foto' => null,
                'descripcion' => 'Especialista en integración de sistemas de software.',
            ],
            [
                'nombre' => 'Jaime Eustaquio',
                'apellido' => 'Zelaya Abán',
                'foto' => null,
                'descripcion' => 'Docente en ciencias matemáticas e ingeniería básica.',
            ],
            [
                'nombre' => 'Darwin Emerson',
                'apellido' => 'Guerrero Vejarano',
                'foto' => null,
                'descripcion' => 'Docente especialista en Matemática Discreta.',
            ],
            [
                'nombre' => 'Jorge Luis',
                'apellido' => 'Gonzales Lafosse',
                'foto' => null,
                'descripcion' => 'Docente especialista en Matemática Superior.',
            ],
            [
                'nombre' => 'Javier Dionisio',
                'apellido' => 'Rosas Domínguez',
                'foto' => null,
                'descripcion' => 'Docente en redacción y comprensión lectora académica.',
            ],
            [
                'nombre' => 'Juan',
                'apellido' => 'Ramos Estela',
                'foto' => null,
                'descripcion' => 'Docente en seguridad informática y redes de datos.',
            ],
            [
                'nombre' => 'Carmela Mercedes',
                'apellido' => 'Pajuelo Maguiña',
                'foto' => null,
                'descripcion' => 'Docente en sostenibilidad y responsabilidad social universitaria.',
            ],
            [
                'nombre' => 'Macklean',
                'apellido' => 'Reátegui Guerra',
                'foto' => null,
                'descripcion' => 'Docente en taller de habilidades blandas y liderazgo estudiantil.',
            ],
        ];

        foreach ($profesores as $data) {
            Profesor::updateOrCreate(
                ['nombre' => $data['nombre'], 'apellido' => $data['apellido']],
                [
                    'foto' => $data['foto'],
                    'descripcion' => $data['descripcion'],
                    'activo' => true,
                    'institution_id' => $institutionId,
                ]
            );
        }
    }
}
