<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/inicio.css">
    <title>Document</title>
    <script src="/js/cambio.js"></script>
</head>
<body>
    @extends('layouts.navbarconsesion')
    @section('titulo', __('messages.profeopina'))
    @section('content')
    <div class="centered-images">
        <div>
        <h1>{{ __('messages.welcome_to') }}</h1>
            <!--<<p>{{ __('messages.welcome_user', ['user' => Auth::guard('student')->user()->name]) }}</p>  Mostrando el nombre del usuario 
            @if (Auth::guard('student')->check())
    <p>Hola, {{ Auth::guard('student')->user()->name }}!</p>
@else
    <p>Usuario no autenticado.</p>
@endif
        </div>-->
        <div>
            <img id="subtitulo" src="/logos/Logo_subtitle.svg" alt="logo">
            <div class="icon">
                <img src="/logos/Logo_icon.svg" alt="logo">
            </div>
        </div>
    </div>
    <div class="search-section">
        <form action="{{ route('buscarlog.profesor') }}" method="GET">
            <input type="text" name="profesor" placeholder="{{ __('messages.search_teacher') }}" class="search-input">
            
        </form>
    </div>
    @endsection
</body>
</html>
