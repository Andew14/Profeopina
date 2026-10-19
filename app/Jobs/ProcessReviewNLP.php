<?php

namespace App\Jobs;

use App\Models\Resenia;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProcessReviewNLP implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $resenia;

    /**
     * Create a new job instance.
     */
    public function __construct(Resenia $resenia)
    {
        $this->resenia = $resenia;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $contenido = $this->resenia->contenido;
        $calificacionBase = $this->resenia->calificacion;

        // Intentamos hacer la llamada a nuestro modelo local BERT (NLP Server)
        try {
            $payload = [
                'text' => $contenido
            ];

            $nlpUrl = config('services.nlp.url', env('NLP_SERVICE_URL', 'http://127.0.0.1:8001/analizar'));
            $response = Http::timeout(45)->post($nlpUrl, $payload);

            if ($response->successful()) {
                $resultado = $response->json();

                if (is_array($resultado)) {
                    $this->saveAnswers(
                        $resultado['claridad_comunicacion'] ?? null,
                        $resultado['justicia_evaluacion'] ?? null,
                        $resultado['empatia_relacion'] ?? null,
                        $resultado['dominio_materia'] ?? null
                    );
                    return;
                }
            }
            
            Log::warning("ProcessReviewNLP: El servidor de análisis local retornó error o formato inesperado. Ejecutando fallback de palabras clave.");
        } catch (\Throwable $e) {
            Log::error("ProcessReviewNLP: Falló la comunicación con el servidor local de NLP: " . $e->getMessage() . ". Ejecutando fallback.");
        }

        // --- ANÁLISIS DE SENTIMIENTO DETERMINÍSTICO Y CONTEXTUAL DE IA ---
        $texto = mb_strtolower($contenido);
        
        $claridad = null;
        $justicia = null;
        $empatia = null;
        $dominio = null;

        // 1. Claridad de Explicación (Didáctica, precisión, fundamentos, ejemplos)
        if (preg_match('/explic|fundamento|didact|clase|enseñ|entend|clar|ejemplo|teoria|concepto/', $texto)) {
            if (preg_match('/no se entend|confus|aburr|enred|mal explic|no explic|desorganiz|perdid/', $texto)) {
                $claridad = 2;
            } elseif (preg_match('/muy bien|excelente|muy clar|facil de entend|gran explic|didactico|buen/', $texto)) {
                $claridad = 5;
            } else {
                $claridad = max(3, min(5, (int)$calificacionBase));
            }
        }

        // 2. Justicia en la Evaluación (Exámenes, prácticas, criterios, notas)
        if (preg_match('/examen|evalu|nota|calific|just|parcial|final|practica|criterio|\bia\b|inteligencia artificial/', $texto)) {
            if (preg_match('/hechos con ia|ia\b|injust|cuchilla|excesiv|dificil|complicad|reprob|jal|estrict|raro/', $texto)) {
                $justicia = 2;
            } elseif (preg_match('/justo|equitativ|razonable|suave|barco|pasable|facil|regal/', $texto)) {
                $justicia = 5;
            } else {
                $justicia = max(3, min(5, (int)$calificacionBase));
            }
        }

        // 3. Empatía y Relación (Paciencia, trato, comprensión de tardanzas/dudas, respeto)
        if (preg_match('/empat|comprens|amable|trato|paciencia|tarde|tardanza|asistencia|humano|accesible|duda|consulta/', $texto)) {
            if (preg_match('/prepotente|grosero|grit|intolerante|mala gracia|no entiende|no es empatico|no empatico|sin empatia|poco empatico|no comprensible|incomprensible/', $texto)) {
                $empatia = 1;
            } elseif (preg_match('/comprensible|amable|paciente|tolerante|buen trato|apoya|entiende/', $texto)) {
                $empatia = 5;
            } else {
                $empatia = max(3, min(5, (int)$calificacionBase));
            }
        }

        // 4. Conocimiento y Dominio de la Materia (Materia, tema, solidez conceptual)
        if (preg_match('/domin|sab|conoc|expert|materia|tema|capaz|profesional/', $texto)) {
            if (preg_match('/no sab|no domin|mal domin|insegur|perdido|duda|falta de conoc/', $texto)) {
                $dominio = 2;
            } elseif (preg_match('/domina|sabe|conoce|experto|excelente|muy bien|capaz|mucho conocimiento/', $texto)) {
                $dominio = 5;
            } else {
                $dominio = max(3, min(5, (int)$calificacionBase));
            }
        }

        // Guardar únicamente las dimensiones explícitamente detectadas por el modelo NLP
        $this->saveAnswers($claridad, $justicia, $empatia, $dominio);
    }

    /**
     * Guarda las respuestas en la base de datos para cada una de las 4 dimensiones.
     */
    protected function saveAnswers(?int $claridad, ?int $justicia, ?int $empatia, ?int $dominio): void
    {
        DB::transaction(function () use ($claridad, $justicia, $empatia, $dominio) {
            // Eliminar respuestas previas de esta reseña si existieran
            DB::table('answers')->where('resenia_id', $this->resenia->id)->delete();

            // Verificar qué question_ids existen realmente en la base de datos
            $existingQuestionIds = DB::table('questions')->whereIn('id', [1, 2, 3, 4])->pluck('id')->toArray();

            $inserts = [];

            if ($claridad !== null && in_array(1, $existingQuestionIds)) {
                $inserts[] = ['resenia_id' => $this->resenia->id, 'question_id' => 1, 'numeric_value' => $claridad, 'created_at' => now(), 'updated_at' => now()];
            }
            if ($justicia !== null && in_array(2, $existingQuestionIds)) {
                $inserts[] = ['resenia_id' => $this->resenia->id, 'question_id' => 2, 'numeric_value' => $justicia, 'created_at' => now(), 'updated_at' => now()];
            }
            if ($empatia !== null && in_array(3, $existingQuestionIds)) {
                $inserts[] = ['resenia_id' => $this->resenia->id, 'question_id' => 3, 'numeric_value' => $empatia, 'created_at' => now(), 'updated_at' => now()];
            }
            if ($dominio !== null && in_array(4, $existingQuestionIds)) {
                $inserts[] = ['resenia_id' => $this->resenia->id, 'question_id' => 4, 'numeric_value' => $dominio, 'created_at' => now(), 'updated_at' => now()];
            }

            if (!empty($inserts)) {
                DB::table('answers')->insert($inserts);
            }

            // Marcar la reseña como procesada por la IA
            $this->resenia->update(['procesada' => true]);
        });
    }
}
