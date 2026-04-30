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
                <img id="logo-title" src="/logos/Logo_title_alt3.svg" alt="profeopina">
            </div>
        </div>
        <nav class="right-section">
            <div class="logo">
                <a href="{{ route('locale.change', ['locale' => 'en']) }}" id="en-link" class="lang-link" onclick="changeLanguage('en'); return false;">
                    <img src="/imagenes/en.png" alt="Traducir a Inglés">
                </a>
            </div>
            <div class="logo">
                <a href="{{ route('locale.change', ['locale' => 'es']) }}" id="es-link" class="lang-link" onclick="changeLanguage('es'); return false;">
                    <img src="/imagenes/es.png" alt="Traducir a Español">
                </a>
            </div>
        </nav>
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