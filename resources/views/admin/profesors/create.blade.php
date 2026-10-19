@extends('layouts.admin_base')

@section('title', __('messages.add_profesor'))

@section('content')
<div class="admin-container">
    <div class="admin-actions-bar">
        <h1>{{ __('messages.add_profesor') }}</h1>
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

    <form action="{{ route('admin.profesors.store') }}" method="POST" class="admin-form">
        @csrf
        
        <div class="admin-form-group">
            <label>{{ __('messages.name') }}</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}" required placeholder="{{ __('messages.name') }}">
            @error('nombre')
                <small style="color: #d9344c;">{{ $message }}</small>
            @enderror
        </div>

        <div class="admin-form-group">
            <label>{{ __('messages.lastname') }}</label>
            <input type="text" name="apellido" value="{{ old('apellido') }}" required placeholder="{{ __('messages.lastname') }}">
            @error('apellido')
                <small style="color: #d9344c;">{{ $message }}</small>
            @enderror
        </div>

        <div class="admin-form-group">
            <label>Descripción</label>
            <textarea name="descripcion" placeholder="Descripción del profesor y su experiencia" rows="4">{{ old('descripcion') }}</textarea>
            @error('descripcion')
                <small style="color: #d9344c;">{{ $message }}</small>
            @enderror
        </div>

        <div class="admin-form-group">
            <label>{{ __('messages.teacher_photo') }}</label>
            <input type="text" name="foto" value="{{ old('foto') }}" placeholder="URL o ruta de la foto">
            @error('foto')
                <small style="color: #d9344c;">{{ $message }}</small>
            @enderror
        </div>

        <div class="admin-form-group">
            <label>{{ __('messages.institution') }}</label>
            <select name="institution_id" required>
                @if(isset($institutions) && $institutions->count() > 0)
                    @foreach($institutions as $inst)
                        <option value="{{ $inst->id }}" {{ old('institution_id') == $inst->id ? 'selected' : '' }}>{{ $inst->name }}</option>
                    @endforeach
                @else
                    <option value="1" selected>Universidad Nacional Agraria de la Selva (UNAS)</option>
                @endif
            </select>
            @error('institution_id')
                <small style="color: #d9344c;">{{ $message }}</small>
            @enderror
        </div>

        <div class="admin-form-group">
            <label>{{ __('messages.status') }}</label>
            <select name="activo">
                <option value="1" {{ old('activo', '1') == '1' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                <option value="0" {{ old('activo') == '0' ? 'selected' : '' }}>{{ __('messages.inactive') }}</option>
            </select>
            @error('activo')
                <small style="color: #d9344c;">{{ $message }}</small>
            @enderror
        </div>

        <div class="admin-form-group">
            <label>Cursos que dicta</label>
            @if(isset($courses) && $courses->count() > 0)
                <input type="text" id="courseSearchInput" placeholder="🔍 Buscar curso por nombre..." style="width: 100%; padding: 8px 12px; margin-bottom: 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9em;">
                <div id="courseListContainer" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 10px; padding: 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; max-height: 220px; overflow-y: auto;">
                    @foreach($courses as $course)
                        <label class="course-item" data-name="{{ strtolower($course->name) }}" style="display: flex; align-items: center; gap: 8px; font-weight: normal; font-size: 0.9em; cursor: pointer; background: white; padding: 6px 10px; border-radius: 4px; border: 1px solid #e2e8f0;">
                            <input type="checkbox" name="courses[]" value="{{ $course->id }}" {{ is_array(old('courses')) && in_array($course->id, old('courses')) ? 'checked' : '' }}>
                            <span>{{ $course->name }}</span>
                        </label>
                    @endforeach
                </div>
            @else
                <p style="font-size: 0.9em; color: #718096; font-style: italic;">No hay cursos registrados en la institución. <a href="{{ route('admin.courses.index') }}">Crear cursos</a></p>
            @endif
        </div>
        
        <div class="admin-form-actions">
            <button type="submit" class="btn-continue">{{ __('messages.save') }}</button>
            <a href="{{ route('admin.profesors.index') }}" class="btn-cancel">{{ __('messages.cancel') }}</a>
        </div>
    </form>
</div>

@push('scripts')
    <script src="/js/admin-profesor-courses.js"></script>
@endpush
@endsection