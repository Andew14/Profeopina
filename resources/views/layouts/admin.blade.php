<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/admin-layout.css">
    <link rel="icon" href="/logos/Logo_icon.svg" type="image/png">
    <title>@yield('title', __('messages.profeopina'))</title>
    @stack('styles')
</head>
<body>
    <header class="header">
        <div class="left-section">
            <div class="logo">
                <label for="btn-menu">
                    <img src="/imagenes/menu.png" alt="profeopina">
                </label>
            </div>
            <div class="prof">
                <a href="{{ route('admin.dashboard') }}" title="Ir al Panel de Administración" style="display: inline-block;">
                    <img id="logo-title" src="/logos/Logo_title_alt3.svg" alt="profeopina" style="cursor: pointer;">
                </a>
            </div>
        </div>
        <nav class="right-section"></nav>
    </header>

    <main class="admin-main">
        @if(auth()->check() && auth()->user()->isAdmin())
            @include('layouts._admin_navbar')
        @endif
        <div class="admin-content">
            @yield('content')
        </div>
    </main>

</body>
</html>