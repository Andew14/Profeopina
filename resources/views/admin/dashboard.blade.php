@extends('layouts.admin_base')

@section('title', __('messages.admin_dashboard_title'))

@section('content')
    <div class="admin-dashboard" style="max-width: 1000px; margin: 0 auto; padding: 20px;">
        <h1 style="font-size: 2em; color: #353564; margin-bottom: 5px;">{{ __('messages.admin_dashboard_welcome') }}</h1>
        <p style="color: #666; margin-bottom: 25px;">{{ __('messages.admin') }}: <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->institution->name ?? 'N/A' }})</p>

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

        @if(session('info'))
            <div style="background-color: #d1ecf1; color: #0c5460; padding: 12px 15px; border-radius: 6px; border: 1px solid #bee5eb; margin-bottom: 20px;">
                {{ session('info') }}
            </div>
        @endif

        <!-- Seccion: Estadísticas del Semestre Académico -->
        <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 20px; margin-bottom: 30px;">
            
            <!-- Panel del Semestre Seleccionado con Filtro -->
            <div style="background: white; border-radius: 8px; border: 1px solid #e2e8f0; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 2px solid #eaeaea; padding-bottom: 10px; flex-wrap: wrap; gap: 10px;">
                    <h2 style="font-size: 1.2em; color: #353564; margin: 0;">
                        📊 Estadísticas por Semestre
                    </h2>
                    
                    <!-- Selector Dinámico de Semestre -->
                    <form method="GET" action="{{ route('admin.dashboard') }}" style="margin: 0;">
                        <select name="period_id" onchange="this.form.submit()" style="padding: 6px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-weight: bold; color: #353564; background: #f8fafc; font-size: 0.9em; cursor: pointer;">
                            @foreach($allPeriods as $p)
                                <option value="{{ $p->id }}" {{ isset($selectedPeriod) && $selectedPeriod->id == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }} {{ $p->is_active ? '(Activo)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                @if($selectedPeriod)
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; background: #f8fafc; padding: 12px 15px; border-radius: 6px; border: 1px solid #e2e8f0;">
                        <div>
                            <div style="font-size: 1.1em; color: #1e293b; font-weight: bold;">
                                {{ $selectedPeriod->name }}
                            </div>
                            <div style="font-size: 0.85em; color: #64748b; margin-top: 2px;">
                                📅 {{ $selectedPeriod->start_time?->format('d/m/Y') ?? 'N/A' }} - {{ $selectedPeriod->end_time?->format('d/m/Y') ?? 'N/A' }}
                            </div>
                        </div>
                        <div>
                            @if($selectedPeriod->is_active)
                                <span style="background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 12px; font-size: 0.8em; font-weight: 700; border: 1px solid #bbf7d0;">
                                    ● Semestre Activo
                                </span>
                            @else
                                <span style="background: #f1f5f9; color: #475569; padding: 4px 10px; border-radius: 12px; font-size: 0.8em; font-weight: 600; border: 1px solid #cbd5e1;">
                                    ○ Semestre Pasado
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Estadisticas de reseñas limpias -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 10px;">
                        <div style="background: #eef2ff; border-radius: 6px; padding: 14px; border: 1px solid #e0e7ff; text-align: center;">
                            <div style="font-size: 0.85em; color: #4338ca; font-weight: 600;">Total Reseñas</div>
                            <div style="font-size: 1.6em; color: #312e81; font-weight: 800; margin-top: 4px;">{{ $stats['total_reviews'] }}</div>
                        </div>

                        <div style="background: #fefce8; border-radius: 6px; padding: 14px; border: 1px solid #fef08a; text-align: center;">
                            <div style="font-size: 0.85em; color: #a16207; font-weight: 600;">Promedio de Calificación</div>
                            <div style="font-size: 1.6em; color: #854d0e; font-weight: 800; margin-top: 4px;">
                                ⭐ {{ $stats['average_rating'] > 0 ? $stats['average_rating'] . ' / 5' : 'N/A' }}
                            </div>
                        </div>

                        <div style="background: #fff8eb; border-radius: 6px; padding: 12px; border: 1px solid #fed7aa; text-align: center;">
                            <div style="font-size: 0.8em; color: #c2410c; font-weight: 600;">🚩 Reseñas Denunciadas</div>
                            <div style="font-size: 1.3em; color: #9a3412; font-weight: 700; margin-top: 2px;">{{ $stats['reported_reviews'] }}</div>
                        </div>

                        <div style="background: #f8fafc; border-radius: 6px; padding: 12px; border: 1px solid #e2e8f0; text-align: center;">
                            <div style="font-size: 0.8em; color: #64748b; font-weight: 600;">🚫 Reseñas Ocultas</div>
                            <div style="font-size: 1.3em; color: #334155; font-weight: 700; margin-top: 2px;">{{ $stats['hidden_reviews'] }}</div>
                        </div>
                    </div>
                @else
                    <p style="color: #888; font-style: italic; text-align: center; margin: 30px 0;">
                        No hay ningún semestre académico registrado actualmente.
                    </p>
                @endif
            </div>

            <!-- Panel de Configuración -->
            <div style="background: white; border-radius: 8px; border: 1px solid #e2e8f0; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <h2 style="font-size: 1.2em; color: #353564; margin-bottom: 15px; border-bottom: 2px solid #eaeaea; padding-bottom: 10px;">
                        ⚙️ Configuración de Semestres
                    </h2>
                    <p style="font-size: 0.92em; color: #4a5568; margin-bottom: 15px; line-height: 1.6;">
                        Los semestres académicos permiten categorizar las evaluaciones de los estudiantes por cada periodo lectivo. La IA procesa y segmenta las opiniones en base al semestre seleccionado por el estudiante.
                    </p>
                    <p style="font-size: 0.85em; color: #718096; margin-bottom: 20px; font-style: italic;">
                        * Puedes activar nuevos semestres y consultar el histórico de periodos anteriores en cualquier momento.
                    </p>
                </div>
                <a href="{{ route('admin.periods.index') }}" style="display: block; width: 100%; background: #353564; color: white; border: none; padding: 12px; border-radius: 6px; font-weight: bold; font-size: 0.95em; cursor: pointer; text-decoration: none; text-align: center; transition: background 0.2s;" onmouseover="this.style.background='#28284e'" onmouseout="this.style.background='#353564'">
                    Administrar Semestres Académicos
                </a>
            </div>
        </div>
    </div>
@endsection