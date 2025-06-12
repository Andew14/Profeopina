<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ __('messages.document_title') }}</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="/css/perfil_profelogueado.css">
        
    </head>
    
<body>
    @extends('layouts.navbarconsesion')
    @section('content')
    <div class="container">
        <div class="profesor-perfil">
            <!--<button id="btn-guardar" class="btn-guardar" onclick="cambiarImagen()">
                <img id="icono-guardar" src="/imagenes/singuardar.png" alt="{{ __('messages.save') }}">
            </button>-->
            <!-- Primera Parte: Foto, Nombre, Ciudad, Colegio -->
            <div class="profesor-info">
                <img src="{{ asset($profesor->foto) }}" alt="{{ __('messages.teacher_photo') }}" class="profesor-foto">
                <h1 class="profesor-nombre">{{ $profesor->nombre }} {{ $profesor->apellido }}</h1>
                <p class="profesor-ciudad">{{ $profesor->ciudad }}</p>
                <p class="profesor-colegio">{{ $profesor->colegio }}</p>
            </div>
            
            <!-- Nueva Parte: Reseñas -->
            <div class="lista-resenias">
                <h2>{{ __('messages.teacher_reviews') }}</h2>
                @if($profesor->resenias->isEmpty())
                    <p class="no-resenias">{{ __('messages.no_reviews') }}</p>
                @else
                    @foreach ($profesor->resenias as $resenia)
                        <div class="resenia">
                            <p class="resenia-calificacion" > 
                                {{ $resenia->calificacion }} <img src="/imagenes/estrella.png" alt="estrella" class="estrella">
                            </p>
                            <p class="resenia-texto">{{ $resenia->contenido }}</p>
                        </div>
                    @endforeach
                @endif
            </div>
            
            <!-- Botones -->
            <div class="botones">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addReviewModal">
                    {{ __('messages.add_review') }}
                </button>
            </div>

            <!-- Enlace -->
            <!--<div class="enlace-profesor">
                <a href="ruta_a_la_vista_del_profesor" class="enlace-texto">{{ __('messages.are_you_the_teacher') }}</a>
            </div>-->
        </div>
    </div>
    <!-- Modal -->
<div class="modal fade" id="addReviewModal" tabindex="-1" aria-labelledby="addReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addReviewModalLabel">{{ __('messages.add_review') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addReviewForm" method="POST" action="{{ route('add_review', ['profesor' => $profesor->id]) }}">
                    @csrf
                    <div class="form-group">
                        <label for="rating">{{ __('messages.rating') }}</label>
                        <select class="form-control" id="rating" name="calificacion">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="review">{{ __('messages.review') }}</label>
                        <textarea class="form-control" id="review" name="contenido" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('actions.send') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>


    @endsection
</body>
</html>
