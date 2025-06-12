<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('messages.search_results') }}</title>
    <link rel="stylesheet" href="/css/busqueda.css">
</head>
<body>
    @extends('layouts.navbarsinsesion')

    @section('content')
        <h1>{{ __('messages.search_teacher') }}: {{ $query }}</h1>
    
        <div class="search-container">
            <form action="{{ route('buscar.profesor') }}" method="GET">
                <input type="text" name="profesor" placeholder="{{ __('messages.teacher') }}" class="search-input">
            </form>
        </div>
    
        <h2>{{ __('messages.teacher') }}:</h2>
    
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
    
</body>
</html>
