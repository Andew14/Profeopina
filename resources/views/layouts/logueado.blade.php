@extends('layouts.main')

@section('title', __('messages.profile_title'))

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
