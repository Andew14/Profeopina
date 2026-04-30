@extends('layouts.main')

@push('styles')
    <link rel="stylesheet" href="/css/busqueda.css">
@endpush

@section('content')
    <h1>{{ __('messages.search_teacher') }}: {{ $query }}</h1>

    <div class="search-container">
        <form action="{{ route('buscar.profesor') }}" method="GET">
            <input type="text" name="profesor" placeholder="{{ __('messages.teacher') }}" class="search-input">
        </form>
    </div>

    <h2>{{ __('messages.teacher') }}:</h2>

    @if (! empty($error) || session('error'))
        <div class="alert alert-error">{{ $error ?? session('error') }}</div>
    @endif

    <div class="resultados">
        @if ($resultados->isEmpty())
            <p>No se encontraron resultados para: {{ $query }}</p>
        @else
            @foreach ($resultados as $profesor)
                <a href="{{ route('perfil.profesor', ['id' => $profesor->id]) }}" class="profesor-link">
                    <div class="profesor">
                        <img src="{{ asset($profesor->foto) }}" alt="Foto del Profesor">
                        <div class="profesor-info">
                            <div class="profesor-nombre">{{ $profesor->nombre }} {{ $profesor->apellido }}</div>
                            <div class="profesor-detalles">{{ $profesor->especialidad }}</div>
                            <!-- Agrega más detalles del profesor según sea necesario -->
                        </div>
                    </div>
                </a>
            @endforeach
        @endif
    </div>
@endsection
