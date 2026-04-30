@extends('layouts.main')

@push('styles')
    <link rel="stylesheet" href="/css/listaresenia.css">
@endpush

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
        
        @auth('student')
             <!-- If logged in, the button to add review is on the profile page. -->
             <!-- We can hide this button or link to profile with a hash or just link to profile -->
        @else
            <a href="{{ route('login.student') }}" class="boton">{{ __('messages.add_review') }}</a>
        @endauth
    </div>
</div>
@endsection
