@extends('layouts.main')

@section('titulo', __('messages.profeopina'))

@push('styles')
    <link rel="stylesheet" href="/css/inicio.css?v={{ file_exists(public_path('css/inicio.css')) ? filemtime(public_path('css/inicio.css')) : '2.0' }}">
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

    <!-- Sección Informativa Académica: PLN y Anonimato -->
    <section class="academic-info-section" aria-label="Información académica sobre PLN y Seguridad">
        <div class="academic-info-grid">
            <!-- Bloque 1: Procesamiento de Lenguaje Natural -->
            <article class="academic-info-block">
                <span class="academic-eyebrow">Inteligencia Artificial & PLN</span>
                <h2 class="academic-title">Procesamiento de Lenguaje Natural en la evaluación docente</h2>
                <p class="academic-text">
                    Las opiniones estudiantiles son procesadas mediante modelos de Deep Learning (BERT) que identifican aspectos pedagógicos y el sentimiento de cada comentario de forma objetiva, transformando el texto en métricas claras que orientan la mejora académica continua.
                </p>
            </article>

            <div class="academic-divider" aria-hidden="true"></div>

            <!-- Bloque 2: Seguridad y Anonimato -->
            <article class="academic-info-block">
                <span class="academic-eyebrow">Ética & Privacidad Universitaria</span>
                <h2 class="academic-title">Seguridad y anonimato garantizados para el estudiante</h2>
                <p class="academic-text">
                    Tu participación está resguardada bajo estrictos principios éticos de investigación universitaria. Toda evaluación se publica de manera anónima con un alias, asegurando que ningún docente ni tercero tenga acceso a tu nombre, código ni datos personales.
                </p>
            </article>
        </div>
    </section>
</div>
@endsection
