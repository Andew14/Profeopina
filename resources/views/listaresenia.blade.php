<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('messages.review_list_title') }}</title>
    <link rel="stylesheet" href="/css/listaresenia.css">
</head>
<body>
    @extends('layouts.navbarsinsesion')
    @section('content')
    <div class="contenedor">
        <h1 class="titulo">{{ __('messages.teacher_reviews') }} - {{ $profesor->nombre }} {{ $profesor->apellido }}</h1>
        <div class="lista-resenias">
            @if($resenias->isEmpty())
                <p class="no-resenias">{{ __('messages.no_reviews') }}</p>
            @else
                @foreach ($resenias as $resenia)
                    <div class="resenia">
                        <p class="resenia-calificacion">
                            {{ $resenia->calificacion }} <img src="/imagenes/estrella.png" alt="estrella" class="estrella">
                        </p>
                        <p class="resenia-texto">{{ $resenia->contenido }}</p>
                    </div>
                @endforeach
            @endif
        </div>
        <div class="botones">
            <a href="{{ route('perfil.profesor', ['id' => $profesor->id]) }}" class="boton">{{ __('messages.back_to_profile') }}</a>
            <a href="{{ route('iniciarsesion') }}" class="boton">{{ __('messages.add_review') }}</a>
        </div>
    </div>
    @endsection
</body>
</html>
