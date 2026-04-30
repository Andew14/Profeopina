@extends('layouts.main')

@push('styles')
    <link rel="stylesheet" href="/css/perfil_profe.css">
    <script src="/js/aniadir_resenia.js"></script>
    <style>
        .resenia-period-title { font-size: 1.5em; color: #353564; margin-top: 20px; border-bottom: 2px solid #ccc; }
        .resenia { border: 1px solid #ddd; padding: 10px; border-radius: 5px; margin-bottom: 10px; position: relative; }
        .estrella { width: 15px; height: 15px; }
        .admin-controls { margin-top: 10px; background: #f8f9fa; padding: 10px; border-radius: 4px; border: 1px dashed #ccc; }
        .btn-ocultar { background-color: #dc3545; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px; }
        .btn-mostrar { background-color: #28a745; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px; }
        .hidden-comment { font-style: italic; color: #888; }
        .average-rating { font-size: 1.2em; font-weight: bold; background: #ffffd9; padding: 10px; display: inline-block; border-radius: 5px; margin-bottom: 15px; }
    </style>
    @auth('student')
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @endauth
@endpush

@section('content')
<div class="container">
    <div class="profesor-perfil">
        <div class="profesor-info">
            <!-- Asumiendo que foto puede ser null, usamos un fallback -->
            <img src="{{ asset($profesor->foto ?? '/imagenes/default-prof.svg') }}" alt="{{ __('messages.teacher_photo') }}" class="profesor-foto">
            <h1 class="profesor-nombre">{{ $profesor->nombre }} {{ $profesor->apellido }}</h1>
            
            @if($profesor->descripcion)
                <p class="profesor-descripcion">{{ $profesor->descripcion }}</p>
            @endif

            @php
                // Calcular valoración promedio (siempre considerando todas las reseñas)
                $avgRating = $profesor->resenias->avg('calificacion');
                $avgRatingFormatted = $avgRating ? number_format($avgRating, 1) : __('messages.no_reviews');
            @endphp
            <div class="average-rating">
                Promedio General: {{ $avgRatingFormatted }} <img src="/imagenes/estrella.png" alt="estrella" class="estrella" style="width: 20px; height: 20px;">
            </div>
        </div>

        <div class="lista-resenias">
            <h2>{{ __('messages.teacher_reviews') }}</h2>
            @if($reseniasPorPeriodo->isEmpty())
                <p class="no-resenias">{{ __('messages.no_reviews') }}</p>
            @else
                @foreach ($reseniasPorPeriodo as $periodoNombre => $resenias)
                    <h3 class="resenia-period-title">Periodo: {{ $periodoNombre }}</h3>
                    @foreach ($resenias as $resenia)
                        <div class="resenia">
                            <p class="resenia-calificacion"> 
                                {{ $resenia->calificacion }} <img src="/imagenes/estrella.png" alt="estrella" class="estrella">
                            </p>
                            
                            @if($resenia->oculto && !$isAdmin)
                                <p class="resenia-texto hidden-comment">El comentario ha sido ocultado por el administrador.</p>
                            @else
                                <p class="resenia-texto">{{ $resenia->contenido }}</p>
                            @endif

                            @if($isAdmin)
                                <div class="admin-controls">
                                    <strong>Respuestas Adicionales (Solo Admin):</strong>
                                    <ul>
                                        @foreach($resenia->answers as $answer)
                                            <li>
                                                <em>{{ $answer->question->text }}:</em> 
                                                @if($answer->question->type == 'likert')
                                                    @php
                                                        $options = $answer->question->options ?? ['Muy malo', 'Malo', 'Neutro', 'Bueno', 'Muy bueno'];
                                                        $label = $options[$answer->numeric_value - 1] ?? $answer->numeric_value;
                                                    @endphp
                                                    {{ $label }} ({{ $answer->numeric_value }})
                                                @else
                                                    {{ $answer->text_value }}
                                                @endif
                                            </li>
                                        @endforeach
                                        @if($resenia->answers->isEmpty())
                                            <li><small>No hay respuestas extra para esta reseña.</small></li>
                                        @endif
                                    </ul>
                                    <hr>
                                    <form action="{{ route('profesor.resenia.toggle', [$profesor->id, $resenia->id]) }}" method="POST">
                                        @csrf
                                        @if($resenia->oculto)
                                            <span style="color:red; font-weight:bold;">[OCULTO AL PÚBLICO]</span>
                                            <button type="submit" class="btn-mostrar">Mostrar Comentario</button>
                                        @else
                                            <button type="submit" class="btn-ocultar">Ocultar Comentario General</button>
                                        @endif
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endforeach
            @endif
        </div>

        <div class="botones">
            @if(isset($activePeriod) && $activePeriod)
                @auth('student')
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addReviewModal">
                        {{ __('messages.add_review') }}
                    </button>
                @else
                    <a href="{{ route('login.student') }}" class="boton">{{ __('messages.add_review') }}</a>
                @endauth
            @else
                <p style="color:red; margin-top:10px;">Las evaluaciones están cerradas actualmente.</p>
            @endif
        </div>
    </div>
</div>

@auth('student')
@if(isset($activePeriod) && $activePeriod)
<!-- Modal -->
<div class="modal fade" id="addReviewModal" tabindex="-1" aria-labelledby="addReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addReviewModalLabel">Añadir Reseña (Evaluación: {{ $activePeriod->name }})</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addReviewForm" method="POST" action="{{ route('add_review', ['profesor' => $profesor->id]) }}">
                    @csrf
                    <!-- Preguntas Fijas -->
                    <div class="form-group">
                        <label for="rating">{{ __('messages.rating') }} (1 a 5 Estrellas)</label>
                        <select class="form-control" id="rating" name="calificacion" required>
                            <option value="5">5 - Excelente</option>
                            <option value="4">4 - Bueno</option>
                            <option value="3">3 - Regular</option>
                            <option value="2">2 - Malo</option>
                            <option value="1">1 - Deficiente</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="review">{{ __('messages.review') }} (Comentario General público)</label>
                        <textarea class="form-control" id="review" name="contenido" rows="3" required></textarea>
                    </div>

                    <!-- Preguntas Extra Dinámicas -->
                    @if(isset($preguntasExtra) && $preguntasExtra->isNotEmpty())
                        <hr>
                        <h6><strong>Preguntas Específicas del Periodo</strong></h6>
                        <small class="text-muted">Tus respuestas aquí serán confidenciales (solo visibles para administradores de la UNAS).</small>
                        <div class="mt-3">
                        @foreach($preguntasExtra as $q)
                            <div class="form-group" style="background: #fdfdfd; padding:10px; border:1px solid #ebebeb; border-radius:5px;">
                                <label>{{ $q->text }}</label>
                                @if($q->type == 'likert')
                                    @php
                                        // Valor por defecto
                                        $options = $q->options ?? ['Muy malo', 'Malo', 'Neutro', 'Bueno', 'Muy bueno'];
                                    @endphp
                                    <select class="form-control" name="answers[{{ $q->id }}]" required>
                                        <option value="">Seleccione una opción...</option>
                                        @foreach($options as $index => $label)
                                            <option value="{{ $index + 1 }}">{{ $label }} ({{ $index + 1 }})</option>
                                        @endforeach
                                    </select>
                                @elseif($q->type == 'text')
                                    <textarea class="form-control" name="answers[{{ $q->id }}]" rows="2"></textarea>
                                @endif
                            </div>
                        @endforeach
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary mt-3">{{ __('actions.send') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endauth
@endsection
