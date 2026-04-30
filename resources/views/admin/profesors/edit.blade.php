@extends('layouts.admin_base')

@section('title', __('messages.edit') . ' ' . __('messages.teacher'))

@section('content')
<div class="admin-container">
    <div class="admin-actions-bar">
        <h1>{{ __('messages.edit') }} {{ __('messages.teacher') }}</h1>
    </div>

    @if ($errors->any())
        <div class="admin-alert admin-alert-error">
            <strong>{{ __('messages.error') }}:</strong>
            <ul style="margin: 5px 0 0 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.profesors.update', $profesor) }}" method="POST" class="admin-form">
        @csrf
        @method('PUT')
        
        <div class="admin-form-group">
            <label>{{ __('messages.name') }}</label>
            <input type="text" name="nombre" value="{{ old('nombre', $profesor->nombre) }}" required placeholder="{{ __('messages.name') }}">
            @error('nombre')
                <small style="color: #d9344c;">{{ $message }}</small>
            @enderror
        </div>

        <div class="admin-form-group">
            <label>{{ __('messages.lastname') }}</label>
            <input type="text" name="apellido" value="{{ old('apellido', $profesor->apellido) }}" required placeholder="{{ __('messages.lastname') }}">
            @error('apellido')
                <small style="color: #d9344c;">{{ $message }}</small>
            @enderror
        </div>

        <div class="admin-form-group">
            <label>Descripción</label>
            <textarea name="descripcion" placeholder="Descripción del profesor y su experiencia" rows="4">{{ old('descripcion', $profesor->descripcion) }}</textarea>
            @error('descripcion')
                <small style="color: #d9344c;">{{ $message }}</small>
            @enderror
        </div>

        <div class="admin-form-group">
            <label>{{ __('messages.teacher_photo') }}</label>
            <input type="text" name="foto" value="{{ old('foto', $profesor->foto) }}" placeholder="URL o ruta de la foto">
            @error('foto')
                <small style="color: #d9344c;">{{ $message }}</small>
            @enderror
        </div>

        <div class="admin-form-group">
            <label>Estado</label>
            <select name="activo">
                <option value="1" {{ old('activo', $profesor->activo) == 1 ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ old('activo', $profesor->activo) == 0 ? 'selected' : '' }}>Inactivo</option>
            </select>
            @error('activo')
                <small style="color: #d9344c;">{{ $message }}</small>
            @enderror
        </div>
        
        <div class="admin-form-actions">
            <button type="submit" class="btn-continue">{{ __('messages.save') }}</button>
            <a href="{{ route('admin.profesors.index') }}" class="btn-cancel">{{ __('messages.cancel') }}</a>
        </div>
    </form>
</div>
@endsection