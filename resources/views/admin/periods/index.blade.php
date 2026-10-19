@extends('layouts.admin_base')

@section('title', 'Gestionar Semestres Académicos - ProfeOpina')

@section('content')
<div class="admin-container" style="max-width: 1000px; margin: 0 auto; padding: 20px;">
    <div class="admin-actions-bar" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <h1 style="font-size: 2em; color: #353564; margin: 0;">Gestionar Semestres Académicos</h1>
    </div>

    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 12px 15px; border-radius: 6px; border: 1px solid #c3e6cb; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background-color: #f8d7da; color: #721c24; padding: 12px 15px; border-radius: 6px; border: 1px solid #f5c6cb; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
        
        <!-- Listado de Semestres -->
        <div style="background: white; border-radius: 8px; border: 1px solid #e2e8f0; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
            <h2 style="font-size: 1.2em; color: #353564; margin-bottom: 15px; border-bottom: 2px solid #eaeaea; padding-bottom: 8px;">Semestres Registrados</h2>
            
            @if($periods->isEmpty())
                <p style="color: #888; font-style: italic; text-align: center; margin: 30px 0;">No hay semestres académicos registrados aún.</p>
            @else
                <table class="table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid #eaeaea; text-align: left;">
                            <th style="padding: 10px 5px; color: #4a5568;">Semestre</th>
                            <th style="padding: 10px 5px; color: #4a5568;">Fechas</th>
                            <th style="padding: 10px 5px; color: #4a5568; text-align: center;">Estado</th>
                            <th style="padding: 10px 5px; color: #4a5568; text-align: center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($periods as $period)
                            <tr style="border-bottom: 1px solid #edf2f7;">
                                <td style="padding: 12px 5px; font-weight: bold; color: #2d3748;">
                                    {{ $period->name }}
                                </td>
                                <td style="padding: 12px 5px; font-size: 0.9em; color: #4a5568;">
                                    <strong>Inicio:</strong> {{ $period->start_time?->format('d/m/Y') }}<br>
                                    <strong>Fin:</strong> {{ $period->end_time?->format('d/m/Y') }}<br>
                                    <small style="color:#3182ce;"><strong>Mitad:</strong> {{ $period->mid_time?->format('d/m/Y') }}</small>
                                </td>
                                <td style="padding: 12px 5px; text-align: center;">
                                    @if($period->is_active)
                                        <span style="background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 12px; font-size: 0.8em; font-weight: 700; border: 1px solid #bbf7d0;">Activo</span>
                                    @else
                                        <span style="background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 12px; font-size: 0.8em; font-weight: 600; border: 1px solid #cbd5e1;">Inactivo</span>
                                    @endif
                                </td>
                                <td style="padding: 12px 5px; text-align: center; vertical-align: middle;">
                                    <div style="display: flex; justify-content: center; gap: 8px; align-items: center;">
                                        <a href="{{ route('admin.periods.edit', $period->id) }}" class="btn btn-edit btn-sm" style="background: #007bff; color: white; height: 32px; padding: 0 10px; border-radius: 4px; font-size: 0.88em; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; box-sizing: border-box;" title="Editar semestre académico">
                                            ✏️ {{ __('messages.edit') }}
                                        </a>
                                        
                                        <button type="button" 
                                                class="btn btn-danger btn-sm js-open-delete-modal" 
                                                data-id="{{ $period->id }}" 
                                                data-name="{{ $period->name }}" 
                                                data-reviews-count="{{ $period->resenias()->count() }}" 
                                                style="background: #dc2626; color: white; border: none; height: 32px; padding: 0 10px; border-radius: 4px; font-size: 0.88em; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; box-sizing: border-box;" 
                                                title="Eliminar semestre académico">
                                            🗑️ Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Formulario de Creación -->
        <div style="background: white; border-radius: 8px; border: 1px solid #e2e8f0; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); height: fit-content;">
            <h2 style="font-size: 1.25em; color: #353564; margin-bottom: 15px; border-bottom: 2px solid #eaeaea; padding-bottom: 8px;">Configurar Nuevo Semestre</h2>
            
            <form action="{{ route('admin.periods.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 15px;">
                @csrf
                
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label style="font-size: 0.9em; font-weight: bold; color: #4a5568;">Nombre del Semestre</label>
                    <input type="text" name="name" required placeholder="Ej: 2026-I o 2026-II" style="padding: 10px 12px; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 1em;">
                </div>

                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label style="font-size: 0.9em; font-weight: bold; color: #4a5568;">Fecha de Inicio</label>
                    <input type="date" name="start_time" required style="padding: 10px 12px; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 1em;">
                </div>

                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <label style="font-size: 0.9em; font-weight: bold; color: #4a5568;">Fecha de Fin</label>
                    <input type="date" name="end_time" required style="padding: 10px 12px; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 1em;">
                </div>

                <div style="display: flex; align-items: center; gap: 8px; margin: 5px 0;">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked style="width: 16px; height: 16px;">
                    <label for="is_active" style="font-size: 0.9em; font-weight: bold; color: #4a5568; cursor: pointer;">Establecer como semestre activo</label>
                </div>
                
                <button type="submit" style="background: #353564; color: white; border: none; padding: 12px; border-radius: 6px; font-weight: bold; font-size: 1em; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#28284e'" onmouseout="this.style.background='#353564'">
                    Crear Semestre Académico
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Modal Personalizado del Sistema para Confirmar Eliminación -->
<div id="deleteSemesterModal" class="modal-overlay">
    <div class="modal-content">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 14px;">
            <div class="modal-icon-warning">
                ⚠️
            </div>
            <h3 style="font-size: 1.25em; color: #1e293b; margin: 0; font-weight: 700;">Eliminar Semestre Académico</h3>
        </div>

        <p style="font-size: 0.95em; color: #475569; line-height: 1.5; margin-bottom: 12px;">
            ¿Estás seguro de que deseas eliminar el semestre <strong id="modalSemesterName" style="color: #0f172a;"></strong>?
        </p>

        <div id="modalReviewsNotice" class="modal-notice" style="display: none;">
            Este semestre tiene <strong id="modalReviewsCount"></strong> reseñas asociadas. Al eliminarlo, <em>las reseñas no se borrarán</em> y quedarán preservadas en el historial de los profesores.
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
            <button type="button" id="btnCancelDeleteModal" class="modal-btn-cancel">
                Cancelar
            </button>
            <form id="deleteSemesterForm" method="POST" action="" style="margin: 0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="modal-btn-confirm-danger">
                    Sí, Eliminar Semestre
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script src="/js/admin-periods.js"></script>
@endpush
@endsection
