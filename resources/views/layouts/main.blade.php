<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/css/navbar.css">
    <link rel="stylesheet" href="/css/ventas.css">
    <link rel="stylesheet" href="/css/sidebar-role.css">
    <link rel="icon" href="/logos/Logo_icon.svg" type="image/png">
    <title>@yield('title', __('messages.profeopina'))</title>
    @stack('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    @if(View::hasSection('header'))
        @yield('header')
    @else
        <header class="header">
            <div class="left-section">
                <div class="logo">
                    <label for="btn-menu">
                        <img src="/imagenes/menu.png" alt="profeopina">
                    </label>
                </div>
                <div class="prof">
                    <a href="{{ auth('student')->check() ? route('iniciologueado') : (auth('web')->check() ? route('admin.dashboard') : route('inicio')) }}" title="Ir al inicio" style="display: inline-block;">
                        <img id="logo-title" src="/logos/Logo_title_alt3.svg" alt="profeopina" style="cursor: pointer;">
                    </a>
                </div>
            </div>
            <nav class="right-section"></nav>
        </header>
    @endif

    <!-- Floating Toast Overlay -->
    @if(session('success') || session('error') || session('info') || $errors->any())
    <div id="toast-container" data-msg="{{ session('success') ?? session('error') ?? session('info') ?? ($errors->any() ? 'err' : '') }}" style="position: fixed; top: 80px; right: 20px; z-index: 99999; max-width: 350px; display: flex; flex-direction: column; gap: 10px;">
        @if(session('success'))
            <div class="toast-alert success" style="background: #e6f7ed; color: #1e7e34; border-left: 5px solid #28a745; padding: 15px 20px; border-radius: 6px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 12px; font-family: sans-serif; font-weight: 500; font-size: 0.95em; transition: opacity 0.5s ease;">
                <i class="fas fa-check-circle" style="font-size: 1.25em;"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif
        @if(session('error'))
            <div class="toast-alert error" style="background: #fdf2f2; color: #dc3545; border-left: 5px solid #dc3545; padding: 15px 20px; border-radius: 6px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 12px; font-family: sans-serif; font-weight: 500; font-size: 0.95em; transition: opacity 0.5s ease;">
                <i class="fas fa-exclamation-circle" style="font-size: 1.25em;"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif
        @if(session('info'))
            <div class="toast-alert info" style="background: #eef2ff; color: #3730a3; border-left: 5px solid #4f46e5; padding: 15px 20px; border-radius: 6px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 12px; font-family: sans-serif; font-weight: 500; font-size: 0.95em; transition: opacity 0.5s ease;">
                <i class="fas fa-info-circle" style="font-size: 1.25em;"></i>
                <div>{{ session('info') }}</div>
            </div>
        @endif
        @if($errors->any())
            <div class="toast-alert error" style="background: #fdf2f2; color: #dc3545; border-left: 5px solid #dc3545; padding: 15px 20px; border-radius: 6px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); display: flex; flex-direction: column; gap: 8px; font-family: sans-serif; font-size: 0.95em; transition: opacity 0.5s ease;">
                <div style="display: flex; align-items: center; gap: 10px; font-weight: bold;">
                    <i class="fas fa-exclamation-circle" style="font-size: 1.2em;"></i>
                    <span>Verifica lo siguiente:</span>
                </div>
                <ul style="margin: 0; padding-left: 20px; line-height: 1.4;">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    @endif

    <div class="main-content">
        @yield('content')
    </div>

    <!-- Barra lateral -->
    <input type="checkbox" id="btn-menu">
    <div class="container-menu">
        <label for="btn-menu" class="menu-overlay-backdrop" title="Cerrar Menú"></label>
        <div class="cont-menu">
            <nav>
                @auth('web')
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" title="Ir al Panel de Administración">📊 Ir al Dashboard Admin</a>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-admin-sidebar').submit();" title="Cerrar sesión de administrador">
                        🚪 {{ __('messages.logout') }}
                    </a>
                    <form id="logout-form-admin-sidebar" action="{{ route('logout.admin') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @elseauth('student')
                    <a href="{{ route('iniciologueado') }}" class="{{ request()->routeIs(['iniciologueado', 'inicio', 'perfil.profesor', 'buscar.profesor']) ? 'active' : '' }}" title="Ir al Inicio">{{ __('messages.home') }}</a>
                    <a href="{{ route('turesenia') }}" class="{{ request()->routeIs('turesenia') ? 'active' : '' }}" title="Ver mis reseñas escritas">{{ __('messages.youreviews') }}</a>
                    <a href="{{ route('tuperfil') }}" class="{{ request()->routeIs('tuperfil') ? 'active' : '' }}" title="Configurar mis datos de cuenta">{{ __('messages.account_settings') }}</a>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-student-sidebar').submit();" title="Cerrar sesión">
                        {{ __('messages.logout') }}
                    </a>
                    <form id="logout-form-student-sidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @else
                    <a href="{{ route('inicio') }}" class="{{ request()->routeIs('inicio') ? 'active' : '' }}" title="Ir al Inicio">{{ __('messages.home') }}</a>
                    <a href="{{ route('login.student') }}" class="{{ request()->routeIs(['login.student', 'login', 'iniciarsesion']) ? 'active' : '' }}" title="Iniciar Sesión Estudiante">{{ __('messages.login') }}</a>
                    <a href="{{ route('register.student') }}" class="{{ request()->routeIs(['register.student', 'registro']) ? 'active' : '' }}" title="Registrarse como Estudiante">{{ __('messages.register') }}</a>
                @endauth
            </nav>
            <div>
                <label for="btn-menu" class="icon-equis" title="Cerrar Menú Lateral">
                    <img src="/imagenes/xazul.png" alt="x">
                </label>
            </div>
        </div>
    </div>

    <script src="/js/toast-notifications.js"></script>
    <script src="/js/password-toggle.js"></script>
    @stack('scripts')
</body>
</html>
