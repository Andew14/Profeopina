<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/css/admin-layout.css?v={{ time() }}">
    <link rel="stylesheet" href="/css/admin-modals.css?v={{ time() }}">
    <link rel="icon" href="/logos/Logo_icon.svg" type="image/png">
    <title>@yield('title', __('messages.profeopina'))</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @stack('styles')
</head>
<body>
    @include('layouts._admin_header')

    <!-- Floating Toast Overlay for Admin Panel -->
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

    <main class="admin-main-content">
        <div class="admin-container">
            @yield('content')
        </div>
    </main>

    <script src="/js/toast-notifications.js"></script>
    @stack('scripts')
</body>
</html>