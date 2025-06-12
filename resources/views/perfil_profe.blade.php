<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('messages.document_title') }}</title>
    <link rel="stylesheet" href="/css/perfil_profe.css">
    <script src="/js/aniadir_resenia.js"></script>
</head>
<body>
    @extends('layouts.navbarsinsesion')
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
                <a href="{{ route('login.student') }}" class="boton">{{ __('messages.add_review') }}</a>
            </div>
            <!-- Enlace -->
            <!--<div class="enlace-profesor">
                <a href="ruta_a_la_vista_del_profesor" class="enlace-texto">{{ __('messages.are_you_the_teacher') }}</a>
            </div>-->
        </div>
    </div>
    @endsection
</body>
</html>
