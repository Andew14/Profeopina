@extends('layouts.main')

@push('styles')
    <link rel="stylesheet" href="/css/busqueda.css">
@endpush

@section('content')
<div class="search-page-wrapper">
    <!-- Barra de Búsqueda Principal (Sobria y Profesional) -->
    <div class="search-bar-card">
        <form action="{{ route('buscar.profesor') }}" method="GET" class="search-form-clean">
            <div class="search-input-wrapper">
                <i class="fas fa-search search-input-icon"></i>
                <input type="text" name="profesor" value="{{ $query }}" placeholder="Buscar profesor por nombre o apellido..." class="search-input-clean" autocomplete="off" required>
                <button type="submit" class="btn-search-clean">
                    Buscar
                </button>
            </div>
        </form>
    </div>

    <!-- Barra de Estado y Ordenamiento A-Z -->
    <div class="search-meta-bar">
        <div class="search-results-count">
            @if(!$resultados->isEmpty())
                Resultados para "<strong>{{ $query }}</strong>" (<span class="count-badge">{{ $resultados->count() }}</span>)
            @else
                Búsqueda para "<strong>{{ $query }}</strong>"
            @endif
        </div>
        <div class="search-sort-indicator">
            <i class="fas fa-sort-alpha-down" style="margin-right: 4px;"></i> Orden A-Z
        </div>
    </div>

    @if (! empty($error) || session('error'))
        <div class="alert-search-error">
            {{ $error ?? session('error') }}
        </div>
    @endif

    <!-- Lista de Resultados en Orden Alfabético A-Z -->
    <div class="search-results-list">
        @if ($resultados->isEmpty())
            <div class="search-empty-state">
                <h3>No se encontraron profesores</h3>
                <p>No hay coincidencias para "<strong>{{ $query }}</strong>". Intenta con otro término o apellido.</p>
            </div>
        @else
            @foreach ($resultados as $profesor)
                @php
                    $activeResenias = $profesor->resenias->where('oculto', false);
                    $avgRating = $activeResenias->avg('calificacion');
                    $countReviews = $activeResenias->count();
                @endphp
                <a href="{{ route('perfil.profesor', ['id' => $profesor->id]) }}" class="teacher-card-link">
                    <div class="teacher-card">
                        <div class="teacher-card-main">
                            <div class="teacher-avatar-container">
                                <img src="{{ $profesor->foto_url }}" alt="Foto de {{ $profesor->nombre }}" class="teacher-avatar">
                            </div>
                            
                            <div class="teacher-details">
                                <div class="teacher-header-row">
                                    <div class="teacher-main-info">
                                        <h2 class="teacher-name">{{ $profesor->nombre }} {{ $profesor->apellido }}</h2>
                                        <div class="teacher-institution">
                                            <i class="fas fa-university" style="margin-right: 4px; color: #94a3b8;"></i>{{ $profesor->institution->name ?? 'Universidad Nacional Agraria de la Selva (UNAS)' }}
                                        </div>
                                    </div>

                                    <!-- Insignia de Calificación -->
                                    <div class="teacher-rating-box">
                                        @if($countReviews > 0)
                                            <div class="rating-score">
                                                ⭐ {{ number_format($avgRating, 1) }}
                                            </div>
                                            <div class="rating-count">
                                                {{ $countReviews }} {{ $countReviews == 1 ? 'reseña' : 'reseñas' }}
                                            </div>
                                        @else
                                            <div class="no-rating-pill">
                                                Sin reseñas
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Badges de Cursos Impartidos -->
                        @if($profesor->courses->count() > 0)
                            <div class="teacher-courses-section">
                                <span class="courses-label"><i class="fas fa-book-reader" style="margin-right: 4px;"></i> Cursos:</span>
                                <div class="teacher-courses-row">
                                    @foreach($profesor->courses->take(4) as $curso)
                                        <span class="course-pill">
                                            {{ $curso->name }}
                                        </span>
                                    @endforeach
                                    @if($profesor->courses->count() > 4)
                                        <span class="course-pill-more">
                                            +{{ $profesor->courses->count() - 4 }} más
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </a>
            @endforeach
        @endif
    </div>
</div>
@endsection
