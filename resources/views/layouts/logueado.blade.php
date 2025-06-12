<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/logueado.css">
    <title>{{ __('messages.profile_title') }}</title>
</head>
<body>
    @extends('layouts.navbarconsesion')
    @section('content')
    <div class="profile-container">
        <div class="container">
            <!-- Saludo al usuario -->
            <h1 class="display-4">{{ __('messages.hello') }}, {{ Auth::guard('student')->user()->name }}!</h1>
            <p class="lead">@yield('titulo')</p>
            <hr class="my-4">
        
            <!-- Detalles del perfil -->
            <div class="profile-details">
                @yield('contenido')
            </div>
        </div>
    </div>
    @endsection
</body>
</html>
