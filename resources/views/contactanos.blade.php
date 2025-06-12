<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/logins.css">
    <link rel="stylesheet" href="/css/contactanos.css">
</head>
<body>
    @extends('layouts.navbarsinsesion')
    @section('content')
        <h1>@yield('titulo')</h1>
        <div class="contenedor">
            <div class="login-container">
                <h2>@yield('comentario')</h2>
                
                <form action="/login" method="POST">
                    <h3>{{ __('messages.email') }}</h3>
                    <div class="input-group">
                        <input type="text" id="text"  name="text" required>
                    </div>
                    <h3>{{ __('messages.typeconsult') }}</h3>
                    <div class="input-group">
                        <select name="consulta" id="lang">
                            <option value="queja">{{ __('messages.complaint') }}</option>
                            <option value="aviso">{{ __('messages.warning') }}</option>
                        </select>
                    </div>  
                    <h3>{{ __('messages.comment') }}</h3>
                    <div class="input-group">
                        <input type="text" id="text"  name="text" required>
                    </div>             
                    <button type="submit" class="btn-continue">{{ __('messages.send') }}</button>
                    <a href="/password/reset" class="forgot-password">@yield('reenvio')</a>
                </form>
            </div>
        </div>
        
    @endsection
</body>
</html>