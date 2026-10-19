<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <style>
            .password-wrapper { position: relative; width: 100%; display: flex; align-items: center; }
            .password-wrapper input { width: 100% !important; box-sizing: border-box !important; padding-right: 42px !important; }
            .toggle-password-btn { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: transparent; border: none; padding: 6px; margin: 0; cursor: pointer; color: #64748b; display: flex; align-items: center; justify-content: center; font-size: 1.05rem; transition: color 0.2s ease; z-index: 2; }
            .toggle-password-btn:hover { color: #353564; }
        </style>
        @livewireStyles
    </head>
    <body>
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>

        @livewireScripts
        <script src="/js/password-toggle.js"></script>
    </body>
</html>
