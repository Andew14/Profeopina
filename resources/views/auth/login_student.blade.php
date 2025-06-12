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

@section('title', __('messages.login_title'))

@section('content')

    <div class="contenedor">
        <div class="login-container">
            <h2>{{ __('messages.login') }}</h2>
            <form method="POST" action="{{ route('login.student.post') }}" class="login-form">
                @csrf

                <div class="inputs">
                    <div class="input-group">
                        <x-label for="email" value="{{ __('messages.email') }}" />
                        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="{{ __('messages.email') }}" />
                    </div>

                    <div class="input-group">
                        <x-label for="password" value="{{ __('messages.password') }}" />
                        <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" placeholder="{{ __('messages.password') }}" />
                    </div>
                </div>

                <!--<div class="block mt-4">
                    <label for="remember_me" class="flex items-center">
                        <x-checkbox id="remember_me" name="remember" />
                        <span class="ms-2 text-sm text-gray-600">{{ __('messages.remember_me') }}</span>
                    </label>
                </div>-->

                <div class="flex items-center justify-end mt-4">
                    <button type="submit" class="btn-continue ms-4">
                        {{ __('messages.login_button') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

</body>
</html>
