<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/css/admin-layout.css?v={{ time() }}">
    <link rel="icon" href="/logos/Logo_icon.svg" type="image/png">
    <title>@yield('title', __('messages.profeopina'))</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #333;
            background: #f5f5f5;
            line-height: 1.5;
        }
        
        a {
            color: inherit;
        }
    </style>
    @stack('styles')
</head>
<body>
    @include('layouts._admin_header')

    <main class="admin-main-content">
        <div class="admin-container">
            @yield('content')
        </div>
    </main>

    <script src="/js/inicio_img_cambio.js"></script>
    @stack('scripts')
</body>
</html>