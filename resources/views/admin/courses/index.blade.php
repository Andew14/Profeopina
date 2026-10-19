@extends('layouts.admin_base')

@section('title', 'Gestionar Cursos - ProfeOpina')

@section('content')
<div class="admin-container" style="max-width: 1000px; margin: 0 auto; padding: 20px;">
    <div class="admin-actions-bar" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <h1 style="font-size: 2em; color: #353564; margin: 0;">Gestionar Cursos</h1>
    </div>

    <!-- Layout de dos columnas: Listado a la izquierda, Creación a la derecha -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
        
        <!-- Listado de Cursos -->
        <div style="background: white; border-radius: 8px; border: 1px solid #e2e8f0; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
            <h2 style="font-size: 1.2em; color: #353564; margin-bottom: 15px; border-bottom: 2px solid #eaeaea; padding-bottom: 8px;">Cursos Registrados</h2>
            
            @if($courses->isEmpty())
                <p style="color: #888; font-style: italic; text-align: center; margin: 30px 0;">No hay cursos registrados para tu institución aún.</p>
            @else
                <div style="margin-bottom: 15px;">
                    <input type="text" id="adminCourseSearch" placeholder="Buscar curso por nombre..." style="width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9em;">
                </div>

                <table class="table" style="width: 100%; border-collapse: collapse; overflow: hidden; border-radius: 6px;">
                    <thead>
                        <tr style="background: #353564;">
                            <th style="padding: 12px 15px; color: #ffffff !important; font-weight: bold; font-size: 14px; text-align: left;">Nombre del Curso</th>
                            <th style="padding: 12px 15px; color: #ffffff !important; font-weight: bold; font-size: 14px; text-align: center; width: 180px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="courseTableBody">
                        @foreach($courses as $course)
                            <tr class="course-row" data-name="{{ strtolower($course->name) }}" style="border-bottom: 1px solid #edf2f7;">
                                <td style="padding: 12px 15px; font-weight: 500; color: #2d3748; vertical-align: middle;">{{ $course->name }}</td>
                                <td style="padding: 12px 15px; text-align: center; vertical-align: middle;">
                                    <div style="display: flex; justify-content: center; align-items: center; gap: 8px;">
                                        <a href="{{ route('admin.courses.edit', $course->id) }}" style="display: inline-flex; align-items: center; gap: 4px; background: #3b82f6; color: white; padding: 6px 12px; border-radius: 5px; font-size: 0.85em; font-weight: bold; text-decoration: none; transition: background 0.2s;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'" title="Editar curso">
                                            ✏️ {{ __('messages.edit') }}
                                        </a>
                                        
                                        <button type="button" onclick="openDeleteCourseModal('{{ route('admin.courses.destroy', $course->id) }}', '{{ addslashes($course->name) }}')" style="display: inline-flex; align-items: center; gap: 4px; background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 5px; font-size: 0.85em; font-weight: bold; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='#ef4444'" title="Eliminar curso">
                                            🗑️ {{ __('messages.delete') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Modal Personalizado de Confirmación de Eliminación de Curso -->
        <div id="deleteCourseConfirmModal" class="modal-overlay">
            <div class="modal-content" style="text-align: center;">
                <div class="modal-icon-warning" style="margin: 0 auto 15px;">
                    🗑️
                </div>
                <h3 style="margin: 0 0 10px 0; color: #1e293b; font-size: 1.2em; font-weight: 700;">¿Eliminar Curso?</h3>
                <p style="margin: 0 0 20px 0; color: #64748b; font-size: 0.92em; line-height: 1.5;">
                    Estás a punto de eliminar el curso <strong id="deleteCourseNameDisplay" style="color: #353564;"></strong>. Esta acción lo quitará del catálogo activo de la institución.
                </p>
                <form id="deleteCourseConfirmForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <div style="display: flex; gap: 10px; justify-content: center;">
                        <button type="button" onclick="closeDeleteCourseModal()" class="modal-btn-cancel" style="flex: 1;">
                            Cancelar
                        </button>
                        <button type="submit" class="modal-btn-confirm-danger" style="flex: 1;">
                            Sí, Eliminar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Formulario de Creación -->
        <div style="background: white; border-radius: 8px; border: 1px solid #e2e8f0; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); height: fit-content;">
            <h2 style="font-size: 1.25em; color: #353564; margin-bottom: 15px; border-bottom: 2px solid #eaeaea; padding-bottom: 8px;">Agregar Nuevo Curso</h2>
            
            <form action="{{ route('admin.courses.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
                @csrf
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label style="font-size: 0.9em; font-weight: bold; color: #4a5568;">Nombre del Curso</label>
                    <input type="text" name="name" required placeholder="Ej: Álgebra Lineal o Cálculo I" style="padding: 10px 12px; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 1em;">
                </div>
                
                <button type="submit" style="background: #353564; color: white; border: none; padding: 12px; border-radius: 6px; font-weight: bold; font-size: 1em; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#28284e'" onmouseout="this.style.background='#353564'">
                    + Crear Curso
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script src="/js/admin-courses.js"></script>
@endpush
@endsection
