@extends('layouts.main')

@section('titulo', __('messages.profeopina'))

@push('styles')
    <link rel="stylesheet" href="/css/inicio.css">
    <script src="/js/cambio.js"></script>
    <style>
        /* Ensures the search section is centered if not handled by external css */
        .centered-images { text-align: center; margin-top: 50px; }
        .search-section { text-align: center; margin-top: 20px; }
    </style>
@endpush

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
