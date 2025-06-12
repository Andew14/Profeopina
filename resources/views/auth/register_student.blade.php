<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/logins.css">
    
    <title>@yield('title')</title>
</head>
<body>

@extends('layouts.navbarsinsesion')

@section('content')
    <div class="contenedor">
        <div class="login-container">
            <h2>{{ __('messages.register') }}</h2>
            <form id="registerForm" method="POST" action="{{ route('register.student') }}" class="login-form">
                @csrf

                <div class="inputs">
                    <div class="input-group">
                        <x-label for="name" value="{{ __('messages.name') }}" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="{{ __('messages.name') }}" />
                    </div>

                    <div class="input-group">
                        <x-label for="email" value="{{__('messages.email')}}" />
                        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="email" placeholder="{{__('messages.email')}}" />
                        
                    </div>

                    <div class="input-group">
                        <x-label for="password" value="{{__('messages.password')}}" />
                        <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" placeholder="{{__('messages.password')}}" />
                        <small>La contraseña debe tener al menos 8 caracteres.</small>
                    </div>

                    <div class="input-group">
                        <x-label for="password_confirmation" value="{{__('messages.confirmpassword')}}" />
                        <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="{{__('messages.confirmpassword')}}" />
                    </div>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button type="submit" class="btn-continue ms-4">
                        {{ __('messages.register') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

</body>
</html>
