<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/logins.css">
    
    <title>Registro de Estudiante - ProfeOpina</title>
</head>
<body>

@extends('layouts.main')

@section('content')
    <div class="contenedor">
        <div class="login-container" style="max-width: 460px;">
            <h2>{{ __('messages.register') }}</h2>
            <p style="color: #64748b; font-size: 0.9em; text-align: center; margin-top: -10px; margin-bottom: 20px;">
                Crea tu cuenta estudiantil anónima para evaluar y comentar
            </p>

            <form id="registerForm" method="POST" action="{{ route('register.student') }}" class="login-form">
                @csrf

                <div class="inputs">
                    <div class="input-group">
                        <x-label for="name" value="Nombre de usuario (Apodo / Alias)" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="username" placeholder="Ej. ZorroEstudioso, Alumno99" />
                        <small style="color: #64748b; font-size: 0.8em; display: block; margin-top: 5px;">
                            🛡️ <strong>Tu identidad es 100% anónima:</strong> Tu nombre real nunca será público ni visible para los profesores; solo se mostrará este apodo en tus comentarios.
                        </small>
                    </div>

                    <div class="input-group">
                        <x-label for="email" value="{{__('messages.email')}}" />
                        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="email" placeholder="correo@ejemplo.com" />
                    </div>

                    <div class="input-group">
                        <x-label for="password" value="{{__('messages.password')}}" />
                        <div class="password-wrapper">
                            <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" placeholder="{{__('messages.password')}}" />
                            <button type="button" class="toggle-password-btn" aria-label="Mostrar contraseña" title="Mostrar contraseña">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                        <small style="color: #64748b; font-size: 0.8em; display: block; margin-top: 3px;">Mínimo 8 caracteres.</small>
                    </div>

                    <div class="input-group">
                        <x-label for="password_confirmation" value="{{__('messages.confirmpassword')}}" />
                        <div class="password-wrapper">
                            <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="{{__('messages.confirmpassword')}}" />
                            <button type="button" class="toggle-password-btn" aria-label="Mostrar contraseña" title="Mostrar contraseña">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>

                    <div style="margin-top: 15px; margin-bottom: 15px; padding: 12px 14px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: flex-start; gap: 10px; text-align: left;">
                        <input type="checkbox" id="consent_checkbox" name="consent" required checked style="width: 18px; height: 18px; min-width: 18px; accent-color: #353564; cursor: pointer; margin-top: 2px;" />
                        <label for="consent_checkbox" style="cursor: pointer; font-size: 0.82rem; color: #475569; margin: 0; line-height: 1.35; user-select: none;">
                            Acepto participar de forma anónima en la evaluación docente para fines de investigación académica (Tesis universitaria).
                        </label>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn-continue">
                        Crear Cuenta Anónima
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

</body>
</html>
