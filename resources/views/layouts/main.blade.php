<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Dynamic CSS based on auth status could be handled here or via push stacks, but for now we unify -->
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="icon" href="/logos/Logo_icon.svg" type="image/png">
    <script src="/js/inicio_img_cambio.js"></script>
    <title>@yield('titulo', __('messages.profeopina'))</title>
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
            <div class="logo">
                <a href="https://www.facebook.com/profile.php?id=61557749877856"><img src="/imagenes/facebook.png" alt="profeopina"></a>
            </div>
        </nav>
    </header>

    @yield('content')
    
    <script src="/js/inicio_img_cambio.js"></script>
    
    <!-- Barra lateral -->
    <input type="checkbox" id="btn-menu">
    <div class="container-menu">
        <div class="cont-menu">
            <nav>
                @auth('student')
                    <a href="{{ route('iniciologueado') }}">{{ __('messages.home') }}</a>
                    <a href="{{ route('tuperfil') }}">{{ __('messages.profile') }}</a>
                    <!-- Pending: Add other links like turesenia, etc if needed -->
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __('messages.logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @else
                        <a href="{{ route('inicio') }}">{{ __('messages.inicio') }}</a>
                        <a href="{{ route('login.student') }}" class="btn btn-outline-danger">{{ __('messages.login') }}</a>
                        <a href="{{ route('register.student') }}" class="btn btn-outline-danger">{{ __('messages.register') }}</a>
                @endauth
            </nav>
            <div>
                <label for="btn-menu" class="icon-equis">
                    <img src="/imagenes/xazul.png" alt="x">
                </label>
            </div>
        </div>
    </div>
</body>
</html>
