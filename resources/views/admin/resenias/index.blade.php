@extends('layouts.admin_base')

@section('title', __('messages.moderate_reviews'))

@section('content')
<div class="admin-container">
    <div class="admin-actions-bar" style="margin-bottom: 15px;">
        <h1>{{ __('messages.manage_resenias') }}</h1>
    </div>

    <!-- Guía Informativa sobre Moderación (Ocultar vs Eliminar) -->
    <div style="background: #f8fafc; border-left: 4px solid #3b82f6; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 0.88em; color: #334155; line-height: 1.5; border-top: 1px solid #e2e8f0; border-right: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
        <strong>💡 Criterios de Moderación:</strong>
        <ul style="margin: 4px 0 0 0; padding-left: 20px;">
            <li><strong>🚫 Ocultar / 👁️ Mostrar:</strong> El comentario no será visible en el perfil público del docente, pero <em>sus estrellas y métricas se conservan</em> en el promedio.</li>
            <li><strong>🗑️ Eliminar:</strong> Borra la reseña permanentemente de la base de datos y <em>recalcula las calificaciones</em> del docente descontándola.</li>
        </ul>
    </div>

    <!-- Pestañas de Navegación Rápida por Estado -->
    <div style="display: flex; gap: 8px; margin-bottom: 18px; flex-wrap: wrap;">
        <a href="{{ route('admin.resenias.index', array_merge(request()->except(['status', 'page']), ['status' => 'denunciadas'])) }}" 
           style="padding: 8px 16px; border-radius: 20px; text-decoration: none; font-size: 0.9em; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; {{ $currentStatus === 'denunciadas' ? 'background: #fef2f2; color: #991b1b; border: 1.5px solid #f87171;' : 'background: white; color: #64748b; border: 1px solid #cbd5e1;' }}">
            🚩 {{ __('messages.reported') }} ({{ $reportedCount }})
        </a>

        <a href="{{ route('admin.resenias.index', array_merge(request()->except(['status', 'page']), ['status' => 'todas'])) }}" 
           style="padding: 8px 16px; border-radius: 20px; text-decoration: none; font-size: 0.9em; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; {{ $currentStatus === 'todas' ? 'background: #eef2ff; color: #3730a3; border: 1.5px solid #818cf8;' : 'background: white; color: #64748b; border: 1px solid #cbd5e1;' }}">
            📋 Todas ({{ $totalCount }})
        </a>

        <a href="{{ route('admin.resenias.index', array_merge(request()->except(['status', 'page']), ['status' => 'ocultas'])) }}" 
           style="padding: 8px 16px; border-radius: 20px; text-decoration: none; font-size: 0.9em; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; {{ $currentStatus === 'ocultas' ? 'background: #f1f5f9; color: #334155; border: 1.5px solid #94a3b8;' : 'background: white; color: #64748b; border: 1px solid #cbd5e1;' }}">
            🚫 {{ __('messages.hidden') }} ({{ $hiddenCount }})
        </a>

        <a href="{{ route('admin.resenias.index', array_merge(request()->except(['status', 'page']), ['status' => 'visibles'])) }}" 
           style="padding: 8px 16px; border-radius: 20px; text-decoration: none; font-size: 0.9em; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; {{ $currentStatus === 'visibles' ? 'background: #f0fdf4; color: #166534; border: 1.5px solid #4ade80;' : 'background: white; color: #64748b; border: 1px solid #cbd5e1;' }}">
            ✅ Visibles ({{ $visibleCount }})
        </a>
    </div>

    <!-- Barra de Búsqueda y Orden -->
    <form method="GET" action="{{ route('admin.resenias.index') }}" style="background: #f8fafc; padding: 14px 18px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
        <input type="hidden" name="status" value="{{ $currentStatus }}">

        <div style="flex: 1; min-width: 240px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="🔍 Buscar por profesor o texto del comentario..." style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9em;">
        </div>

        <div style="min-width: 160px;">
            <select name="order" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.9em;">
                <option value="desc" {{ request('order', 'desc') == 'desc' ? 'selected' : '' }}>📅 Más recientes</option>
                <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>📅 Más antiguas</option>
            </select>
        </div>

        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn-continue" style="padding: 8px 16px; font-size: 0.9em;">Filtrar</button>
            @if(request()->hasAny(['search', 'order']))
                <a href="{{ route('admin.resenias.index', ['status' => $currentStatus]) }}" class="btn-cancel" style="padding: 8px 14px; font-size: 0.9em; text-decoration: none; display: inline-flex; align-items: center;">Limpiar</a>
            @endif
        </div>
    </form>

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

    @if (session('success'))
        <div class="admin-alert admin-alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($resenias->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('messages.teacher') }}</th>
                    <th>Curso & Semestre</th>
                    <th>{{ __('messages.content') }} & {{ __('messages.status') }}</th>
                    <th style="text-align: center;">{{ __('messages.rating') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resenias as $res)
                    <tr id="res-{{ $res->id }}" style="{{ $res->denunciada ? 'background-color: #fff8eb;' : '' }}">
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                @if($res->profesor->foto)
                                    <img src="{{ $res->profesor->foto_url }}" alt="Foto" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 1px solid #cbd5e1;" onerror="this.onerror=null;this.src='{{ asset('imagenes/default-avatar.svg') }}';">
                                @endif
                                <div>
                                    <strong>{{ $res->profesor->nombre }} {{ $res->profesor->apellido }}</strong>
                                    <div style="font-size: 0.8em; color: #64748b;">
                                        🏛️ {{ $res->profesor->institution?->name ?? 'UNAS' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 600; font-size: 0.92em; color: #1e293b;">
                                📘 {{ $res->curso ?? 'General' }}
                            </div>
                            <div style="font-size: 0.8em; color: #64748b;">
                                🗓️ {{ $res->period?->name ?? 'Semestre N/A' }}
                            </div>
                        </td>
                        <td>
                            <div style="margin-bottom: 6px; display: flex; gap: 6px; flex-wrap: wrap;">
                                @if($res->denunciada)
                                    <span class="badge js-reported-badge" style="background: #ffeeba; color: #856404; font-weight: bold; padding: 3px 8px;" title="Esta reseña ha sido denunciada por un estudiante">🚩 Denunciada</span>
                                @endif
                                <span class="badge js-oculto-badge" style="background: {{ $res->oculto ? '#f8d7da' : '#d4edda' }}; color: {{ $res->oculto ? '#721c24' : '#155724' }}; padding: 3px 8px;">
                                    {{ $res->oculto ? '🚫 ' . __('messages.hidden') : '✅ Visible' }}
                                </span>
                            </div>
                            <div style="max-width: 360px; word-break: break-word; font-size: 0.9em; color: #334155; line-height: 1.4;">
                                "{{ $res->contenido }}"
                            </div>
                        </td>
                        <td style="text-align: center;">
                            <span class="badge" style="background: #fff3cd; color: #856404; padding: 6px 10px; font-weight: 700;">
                                ⭐ {{ $res->calificacion }}/5
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons" style="display: flex; gap: 5px; flex-wrap: wrap; align-items: center;">
                                <button class="btn js-toggle-hidden btn-sm" data-id="{{ $res->id }}" style="height: 32px; display: inline-flex; align-items: center; justify-content: center; padding: 0 10px; font-size: 0.85em; border-radius: 4px;" title="Ocultar o mostrar comentario públicamente">
                                    {{ $res->oculto ? '👁️ Mostrar' : '🚫 Ocultar' }}
                                </button>
                                @if($res->denunciada)
                                    <button class="btn btn-success js-dismiss-report btn-sm" data-id="{{ $res->id }}" style="background-color: #10b981; color: white; border: none; height: 32px; display: inline-flex; align-items: center; justify-content: center; padding: 0 10px; font-size: 0.85em; border-radius: 4px;" title="Desestimar denuncia">
                                        ✔️ Ignorar Denuncia
                                    </button>
                                @endif
                                <button class="btn btn-danger js-delete-resenia btn-sm" data-id="{{ $res->id }}" style="background-color: #dc2626; color: white; border: none; height: 32px; display: inline-flex; align-items: center; justify-content: center; padding: 0 10px; font-size: 0.85em; border-radius: 4px; cursor: pointer;" title="Eliminar permanentemente del sistema">
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

@push('scripts')
    <script src="/js/admin-resenias.js"></script>
@endpush
@endsection