@extends('layouts.admin_base')

@section('title', __('messages.manage_profesors'))

@section('content')
<div class="admin-container">
    <div class="admin-actions-bar">
        <h1>{{ __('messages.manage_profesors') }}</h1>
        <div class="admin-actions-right">
            <a href="{{ route('admin.profesors.create') }}" class="btn-continue">+ {{ __('messages.add_profesor') }}</a>
        </div>
    </div>

    <!-- Barra de Filtros y Búsqueda -->
    <form method="GET" action="{{ route('admin.profesors.index') }}" style="background: #f8fafc; padding: 14px 18px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
        <div style="flex: 1; min-width: 200px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍 {{ __('messages.search_teacher') }}..." style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9em;">
        </div>

        <!-- Buscador de Curso por Escritura / Datalist -->
        <div style="min-width: 220px; flex: 1;">
            <input type="text" list="courseListOptions" name="course_search" value="{{ request('course_search') }}" placeholder="🔍 Escribe para buscar curso..." style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9em;" autocomplete="off">
            <datalist id="courseListOptions">
                @if(isset($courses))
                    @foreach($courses as $c)
                        <option value="{{ $c->name }}"></option>
                    @endforeach
                @endif
            </datalist>
        </div>

        <div style="min-width: 150px;">
            <select name="order" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9em;">
                <option value="asc" {{ request('order', 'asc') == 'asc' ? 'selected' : '' }}>🔤 Orden: A - Z</option>
                <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>🔤 Orden: Z - A</option>
            </select>
        </div>

        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn-continue" style="padding: 8px 16px; font-size: 0.9em;">Filtrar</button>
            @if(request()->hasAny(['search', 'course_id', 'course_search', 'order']))
                <a href="{{ route('admin.profesors.index') }}" class="btn-cancel" style="padding: 8px 14px; font-size: 0.9em; text-decoration: none; display: inline-flex; align-items: center;">Limpiar</a>
            @endif
        </div>
    </form>

    @if ($errors->any())
        <div class="admin-alert admin-alert-error">
            {{ $errors->first() }}
        </div>
    @endif

    @if (session('success'))
        <div class="admin-alert admin-alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($profesors->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('messages.name') }}</th>
                    <th>{{ __('messages.lastname') }}</th>
                    <th>{{ __('messages.description') }}</th>
                    <th style="text-align: center;">{{ __('messages.institution') }}</th>
                    <th style="text-align: center;">{{ __('messages.status') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($profesors as $profesor)
                    <tr id="prof-{{ $profesor->id }}">
                        <td><strong>{{ $profesor->nombre }}</strong></td>
                        <td><strong>{{ $profesor->apellido }}</strong></td>
                        <td>{{ Str::limit($profesor->descripcion, 50) ?? '-' }}</td>
                        <td style="text-align: center;">
                            <span class="badge" style="background: #eef2ff; color: #3730a3; font-weight: 600;">
                                {{ $profesor->institution?->name ?? 'UNAS' }}
                            </span>
                        </td>
                        <td class="activo" style="text-align: center;">
                            <span class="badge {{ $profesor->activo ? 'badge-success' : 'badge-danger' }}">
                                {{ $profesor->activo ? __('messages.active') : __('messages.inactive') }}
                            </span>
                        </td>
                        <td style="vertical-align: middle;">
                            <div class="action-buttons" style="display: flex; gap: 8px; align-items: center; justify-content: flex-start;">
                                <a href="{{ route('admin.profesors.edit', $profesor) }}" class="btn btn-edit btn-sm" style="display: inline-flex; align-items: center; justify-content: center; height: 32px; padding: 0 12px; font-size: 0.88em; border-radius: 4px; line-height: 1; text-decoration: none; box-sizing: border-box;" title="Editar profesor">✏️ {{ __('messages.edit') }}</a>
                                @if(auth()->user()->can('viewAny', 'App\Models\Resenia'))
                                    <a href="{{ route('profesor.resenias', $profesor->id) }}" class="btn btn-primary btn-sm" style="display: inline-flex; align-items: center; justify-content: center; height: 32px; padding: 0 12px; font-size: 0.88em; border-radius: 4px; line-height: 1; text-decoration: none; box-sizing: border-box;" title="Ver reseñas del profesor">📝 {{ __('messages.view_reviews') }}</a>
                                @endif
                                <button type="button" 
                                        class="btn btn-danger btn-sm js-open-delete-prof-modal" 
                                        data-id="{{ $profesor->id }}" 
                                        data-name="{{ $profesor->nombre }} {{ $profesor->apellido }}"
                                        style="background-color: #dc2626; color: white; border: none; height: 32px; padding: 0 12px; border-radius: 4px; font-size: 0.88em; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; box-sizing: border-box; line-height: 1;" 
                                        title="Eliminar profesor">
                                    🗑️ {{ __('messages.delete') }}
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="admin-alert admin-alert-info">
            {{ __('messages.no_data_found') }}
        </div>
    @endif
</div>

<!-- Modal Personalizado del Sistema para Confirmar Eliminación de Profesor -->
<div id="deleteProfModal" class="modal-overlay">
    <div class="modal-content">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 14px;">
            <div class="modal-icon-warning">
                ⚠️
            </div>
            <h3 style="font-size: 1.25em; color: #1e293b; margin: 0; font-weight: 700;">Eliminar Profesor</h3>
        </div>

        <p style="font-size: 0.95em; color: #475569; line-height: 1.5; margin-bottom: 12px;">
            ¿Estás seguro de que deseas eliminar a <strong id="modalProfName" style="color: #0f172a;"></strong> permanentemente del sistema?
        </p>

        <div class="modal-notice">
            Esta acción eliminará al docente de la base de datos, desvinculará sus cursos asignados y borrará sus reseñas históricas. Si solo deseas que no reciba nuevas reseñas, considera ponerlo como <strong>Inactivo</strong>.
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
            <button type="button" id="btnCancelDeleteProfModal" class="modal-btn-cancel">
                Cancelar
            </button>
            <form id="deleteProfForm" method="POST" action="" style="margin: 0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="modal-btn-confirm-danger">
                    Sí, Eliminar Profesor
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script src="/js/admin-profesors.js"></script>
@endpush
@endsection