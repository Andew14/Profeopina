@extends('layouts.admin_base')

@section('title', 'Editar Curso - ProfeOpina')

@section('content')
<div class="admin-container" style="max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: white; border-radius: 8px; border: 1px solid #e2e8f0; padding: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
        <h1 style="font-size: 1.5em; color: #353564; margin-bottom: 20px; border-bottom: 2px solid #eaeaea; padding-bottom: 10px;">Editar Curso</h1>

        <form action="{{ route('admin.courses.update', $course->id) }}" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
            @csrf
            @method('PUT')
            
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <label style="font-size: 0.95em; font-weight: bold; color: #4a5568;">Nombre del Curso</label>
                <input type="text" name="name" required value="{{ old('name', $course->name) }}" placeholder="Ej: Álgebra Lineal" style="padding: 10px 12px; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 1em;">
            </div>

            <div style="display: flex; gap: 15px; justify-content: flex-end; margin-top: 10px;">
                <a href="{{ route('admin.courses.index') }}" style="background: #e2e8f0; color: #4a5568; padding: 12px 20px; border-radius: 6px; font-weight: bold; text-decoration: none; text-align: center; line-height: 1.2;" title="Cancelar edición">
                    Cancelar
                </a>
                <button type="submit" style="background: #353564; color: white; border: none; padding: 12px 25px; border-radius: 6px; font-weight: bold; font-size: 1em; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#28284e'" onmouseout="this.style.background='#353564'" title="Guardar cambios">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
