<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="/css/configcuenta.css">
    <link rel="stylesheet" href="/css/perfil_student.css">
    <title>{{ __('messages.profile') }}</title>
    
</head>
<body>
    @extends('layouts.logueado')
    @section('titulo', __('messages.profile'))
    @section('contenido')
        @if (session('success'))
            <div style="background-color: #d1fae5; color: #065f46; padding: 0.8rem; border-radius: 6px; margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div style="background-color: #fee2e2; color: #991b1b; padding: 0.8rem; border-radius: 6px; margin-bottom: 1rem;">
                <ul style="margin: 0; padding-left: 1.2rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <p><strong>{{ __('messages.name') }}:</strong> {{ $student->name }}</p>
        <p><strong>{{ __('messages.email') }}:</strong> {{ $student->email }}</p>
        <p><strong>{{ __('messages.password') }}:</strong> ********</p>
        
        <div style="display: flex; gap: 1rem; align-items: center; margin-top: 1.5rem; flex-wrap: wrap;">
            <a href="#" class="editar" id="editProfileBtn">
                <i class="fas fa-pencil-alt"></i> <strong>{{ __('messages.edit') }}</strong>
            </a>
            <button type="button" id="deleteAccountBtn" style="background: transparent; color: #dc2626; border: 1.5px solid #dc2626; padding: 0.45rem 1rem; border-radius: 6px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s ease;">
                <i class="fas fa-trash-alt"></i> Eliminar cuenta
            </button>
        </div>
        
        <!-- Modal Editar Perfil -->
        <div id="editProfileModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>{{ __('messages.edit_profile') }}</h2>
                <form action="{{ route('profile.update', $student->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="name">{{ __('messages.name') }}:</label>
                        <input type="text" id="name" name="name" value="{{ $student->name }}">
                    </div>
                    <div>
                        <label for="email">{{ __('messages.email') }}:</label>
                        <input type="email" id="email" name="email" value="{{ $student->email }}">
                    </div>
                    <div>
                        <label for="password">{{ __('messages.new_password') }}:</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" placeholder="Opcional: ingresar nueva contraseña">
                            <button type="button" class="toggle-password-btn" aria-label="Mostrar contraseña" title="Mostrar contraseña">
                                <i class="fas fa-eye-slash"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit">{{ __('messages.save') }}</button>
                </form>
            </div>
        </div>

        <!-- Modal Eliminar Cuenta -->
        <div id="deleteAccountModal" class="modal">
            <div class="modal-content" style="max-width: 480px; border-top: 4px solid #dc2626;">
                <span class="close-delete" style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
                <h2 style="color: #dc2626; margin-top: 0; font-size: 1.4rem;">
                    <i class="fas fa-exclamation-triangle"></i> Eliminar Cuenta
                </h2>
                <p style="color: #4b5563; font-size: 0.95rem; line-height: 1.5; margin: 1rem 0;">
                    Esta acción es <strong>permanente e irreversible</strong>. Se eliminará tu perfil de estudiante y tus credenciales de acceso de forma definitiva.
                </p>
                <form action="{{ route('profile.destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div style="margin-bottom: 1.2rem;">
                        <label for="password_delete" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #374151;">
                            Ingresa tu contraseña para confirmar:
                        </label>
                        <input type="password" id="password_delete" name="password_delete" required placeholder="Tu contraseña actual" style="width: 100%; padding: 0.65rem; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box;">
                    </div>
                    <div style="display: flex; gap: 0.8rem; justify-content: flex-end;">
                        <button type="button" id="cancelDeleteBtn" style="background: #9ca3af; color: white; padding: 0.6rem 1.2rem; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                            Cancelar
                        </button>
                        <button type="submit" style="background: #dc2626; color: white; padding: 0.6rem 1.2rem; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                            Confirmar Eliminación
                        </button>
                    </div>
                </form>
            </div>
        </div>
@endsection

@push('scripts')
    <script src="/js/tuperfil-modal.js"></script>
@endpush
