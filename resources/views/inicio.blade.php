<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/inicio.css">
    <link rel="icon" href="/logos/Logo_icon.svg" type="image/png">
    <script src="/js/cambio.js"></script>
    <title>@yield('titulo', __('messages.profeopina'))</title>
    <meta name="description" content="{{ __('messages.meta_description') }}">
</head>
<body>
    @extends('layouts.navbarsinsesion')
    @section('titulo', __('messages.profeopina'))
    @section('content')
    <div class="centered-images">
        <div>
            <h1>{{ __('messages.welcome_to') }}</h1>
        </div>
        <div>
            <img id="subtitulo" src="/logos/Logo_subtitle.svg" alt="logo">
            <div class="icon">
                <img src="/logos/Logo_icon.svg" alt="logo">
            </div>
        </div>
    </div>
    <div class="search-section">
        <form action="{{ route('buscar.profesor') }}" method="GET">
            <input type="text" name="profesor" placeholder="{{ __('messages.search_teacher') }}" class="search-input">
        </form>
    </div>
    @endsection
</body>
</html>
