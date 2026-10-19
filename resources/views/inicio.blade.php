@extends('layouts.main')

@section('titulo', __('messages.profeopina'))

@push('styles')
    <link rel="stylesheet" href="/css/inicio.css">
@endpush

@section('content')
<div class="home-hero-container">
    <div class="centered-images">
        <div class="logo-container">
            <img id="subtitulo" src="/logos/Logo_subtitle.svg" alt="ProfeOpina" class="main-logo-hero">
            <div class="icon">
                <img src="/logos/Logo_icon.svg" alt="Estudiante ProfeOpina" class="icon-hero">
            </div>
        </div>
    </div>

    <div class="search-section">
        <form action="{{ route('buscar.profesor') }}" method="GET" class="search-form-hero">
            <div class="search-box-pill">
                <i class="fas fa-search search-hero-icon" aria-hidden="true"></i>
                <input type="text" name="profesor" placeholder="Buscar profesor por nombre o apellido..." class="search-input-hero" autocomplete="off" required aria-label="Buscar profesor">
                <button type="submit" class="search-hero-btn" aria-label="Buscar">
                    <span class="btn-text">Buscar</span>
                    <i class="fas fa-arrow-right btn-icon-mobile" aria-hidden="true"></i>
                </button>
            </div>
        </form>
        <p class="search-helper-text">
            Encuentra y evalúa docentes de tu universidad de forma 100% anónima
        </p>
    </div>
</div>
@endsection
