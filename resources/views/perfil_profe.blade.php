@extends('layouts.main')

@push('styles')
    <link rel="stylesheet" href="/css/perfil_profe.css">
    <script src="/js/aniadir_resenia.js"></script>
    @auth('student')
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    @endauth
@endpush

@section('content')
<div class="container">

    @if(session('error'))
        <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 12px 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    <div class="profesor-perfil">
        <div class="perfil-grid-layout">
            
            <!-- COLUMNA IZQUIERDA (Perfil, Desglose Google Play, Métricas IA, Acción) -->
            <div class="perfil-sidebar">
                <!-- Tarjeta del Profesor -->
                <div class="profesor-card">
                    <img src="{{ $profesor->foto_url }}" alt="{{ __('messages.teacher_photo') }}" class="profesor-foto" onerror="this.onerror=null;this.src='{{ asset('imagenes/default-avatar.svg') }}';">
                    <h1 class="profesor-nombre">{{ $profesor->nombre }} {{ $profesor->apellido }}</h1>
                    
                    <div style="font-size: 0.88em; color: #475569; margin-top: 4px; margin-bottom: 8px; font-weight: 500;">
                        🏛️ {{ $profesor->institution->name ?? 'Universidad Nacional Agraria de la Selva (UNAS)' }}
                    </div>

                    <div style="margin-bottom: 12px;">
                        @if($profesor->activo)
                            <span style="display: inline-block; background: #dcfce7; color: #166534; font-weight: 600; font-size: 0.78em; padding: 3px 10px; border-radius: 12px; border: 1px solid #bbf7d0;">
                                ● Docente Activo
                            </span>
                        @else
                            <span style="display: inline-block; background: #f8fafc; color: #64748b; font-weight: 600; font-size: 0.78em; padding: 3px 10px; border-radius: 12px; border: 1px solid #cbd5e1;">
                                ○ Docente Inactivo
                            </span>
                        @endif
                    </div>

                    @if($profesor->descripcion)
                        <p class="profesor-descripcion">{{ $profesor->descripcion }}</p>
                    @else
                        <p class="profesor-descripcion" style="font-style: italic; color: #94a3b8; font-size: 0.88em; margin-top: 8px;">
                            Información académica del docente aún no registrada.
                        </p>
                    @endif
                </div>

                <!-- Desglose de Calificaciones -->
                <div class="gp-rating-card">
                    <h3 class="gp-rating-title">
                        <span>Evaluación General</span>
                    </h3>
                    <div class="gp-rating-content">
                        <div class="gp-rating-score-box">
                            <div class="gp-score-number" id="score_number_display">
                                {{ isset($avgRating) && $avgRating ? number_format($avgRating, 1) : 'N/A' }}
                            </div>
                            <div class="gp-score-details">
                                <div class="gp-stars-row" id="stars_row_display">
                                    @php
                                        $starsCount = round($avgRating ?? 0);
                                    @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $starsCount)★@else<span style="color: #cbd5e1;">★</span>@endif
                                    @endfor
                                </div>
                                <div class="gp-total-count" id="total_reviews_count_display">
                                    {{ $totalReviewsCount ?? 0 }} {{ ($totalReviewsCount ?? 0) == 1 ? 'reseña' : 'reseñas' }} en total
                                </div>
                            </div>
                        </div>

                        <!-- Barras de Distribución de Estrellas (5 a 1) -->
                        <div class="gp-rating-bars">
                            @foreach([5, 4, 3, 2, 1] as $starNum)
                                @php
                                    $starData = $starBreakdown[$starNum] ?? ['count' => 0, 'percentage' => 0];
                                @endphp
                                <div class="gp-bar-row" onclick="filterByStar({{ $starNum }})" title="Filtrar reseñas de {{ $starNum }} estrellas">
                                    <span class="gp-star-label">{{ $starNum }} ★</span>
                                    <div class="gp-bar-track">
                                        <div class="gp-bar-fill" id="star_bar_fill_{{ $starNum }}" style="width: {{ $starData['percentage'] }}%;"></div>
                                    </div>
                                    <span class="gp-bar-value" id="star_bar_val_{{ $starNum }}">{{ $starData['percentage'] }}% ({{ $starData['count'] }})</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Métricas Detalladas (IA) con Tooltips Explicativos (i) -->
                @php
                    $dimensiones = [
                        1 => ['nombre' => 'Claridad de Explicación', 'desc' => 'Evalúa la didáctica, precisión técnica, uso de ejemplos y comprensión de conceptos explicados en clase.'],
                        2 => ['nombre' => 'Justicia en Evaluación', 'desc' => 'Mide la equidad, consistencia y transparencia en la calificación de exámenes, prácticas y criterios académicos.'],
                        3 => ['nombre' => 'Empatía y Relación', 'desc' => 'Valora la accesibilidad del docente, resolución de dudas, trato respetuoso y apoyo continuo al estudiante.'],
                        4 => ['nombre' => 'Dominio de la Materia', 'desc' => 'Evalúa el nivel de dominio conceptual, actualización temática y solidez académica del profesor.']
                    ];
                @endphp
                <div class="dimensiones-valoracion">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">
                        <h4 style="font-size: 1.02em; color: #353564; font-weight: bold; margin: 0;">Métricas Detalladas (IA)</h4>
                        <div style="font-size: 0.72em; display: flex; gap: 6px;">
                            <span style="color: #dc3545;">🔴 Deficiente</span>
                            <span style="color: #d97706;">🟡 Neutral</span>
                            <span style="color: #16a34a;">🟢 Destacado</span>
                        </div>
                    </div>
                    @foreach($dimensiones as $id => $dim)
                        @php
                            $answers = $profesor->resenias->flatMap->answers->where('question_id', $id);
                            $promedioDimension = $answers->avg('numeric_value');
                            
                            $hasValue = !is_null($promedioDimension);
                            if ($hasValue) {
                                if ($promedioDimension < 2.5) {
                                    $badgeText = '🔴 Deficiente';
                                    $badgeBg = '#fee2e2';
                                    $badgeColor = '#991b1b';
                                    $colorProgreso = '#dc3545';
                                } elseif ($promedioDimension >= 2.5 && $promedioDimension < 3.8) {
                                    $badgeText = '🟡 Neutral';
                                    $badgeBg = '#fef3c7';
                                    $badgeColor = '#92400e';
                                    $colorProgreso = '#d97706';
                                } else {
                                    $badgeText = '🟢 Destacado';
                                    $badgeBg = '#dcfce7';
                                    $badgeColor = '#166534';
                                    $colorProgreso = '#16a34a';
                                }
                                $porcentaje = min(100, max(0, $promedioDimension * 20));
                            }
                        @endphp
                        <div class="dimension-row" style="margin-bottom: 14px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.84em; font-weight: 600; color: #475569; margin-bottom: 5px;">
                                <div class="metric-row-header">
                                    <span>{{ $dim['nombre'] }}</span>
                                    <div class="metric-info-wrapper">
                                        <span class="metric-info-icon" tabindex="0">i</span>
                                        <div class="metric-tooltip">
                                            <strong>{{ $dim['nombre'] }}:</strong><br>
                                            {{ $dim['desc'] }}
                                        </div>
                                    </div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <span id="dim_badge_{{ $id }}" style="font-size: 0.75em; padding: 2px 6px; border-radius: 10px; background-color: {{ $hasValue ? $badgeBg : 'transparent' }}; color: {{ $hasValue ? $badgeColor : 'inherit' }}; font-weight: 600; display: {{ $hasValue ? 'inline-block' : 'none' }};">
                                        {{ $hasValue ? $badgeText : '' }}
                                    </span>
                                    <span id="dim_val_{{ $id }}" style="font-size: 0.82em;">{{ $hasValue ? number_format($promedioDimension, 1) : 'N/A' }}</span>
                                </div>
                            </div>
                            
                            <div id="dim_empty_{{ $id }}" style="display: {{ $hasValue ? 'none' : 'block' }}; background: #f1f5f9; color: #94a3b8; font-size: 0.78em; padding: 4px 8px; border-radius: 4px; font-style: italic;">
                                Sin datos suficientes aún
                            </div>

                            <div id="dim_bar_container_{{ $id }}" style="display: {{ $hasValue ? 'block' : 'none' }}; background: #e2e8f0; border-radius: 4px; height: 7px; width: 100%; overflow: hidden;">
                                <div id="dim_bar_{{ $id }}" style="background-color: {{ $hasValue ? $colorProgreso : '#16a34a' }}; width: {{ $hasValue ? $porcentaje : 0 }}%; height: 100%; transition: width 0.6s ease-out, background-color 0.4s ease; border-radius: 4px;"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Botón Añadir Evaluación -->
                <div class="botones" style="margin-bottom: 20px;">
                    @auth('student')
                        <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#addReviewModal" title="Evaluar a este profesor">
                            Añadir evaluación
                        </button>
                    @elseauth('web')
                        <p class="text-muted font-italic text-center" style="font-size: 0.85em;">Los administradores no pueden publicar evaluaciones.</p>
                    @else
                        <a href="{{ route('login.student') }}" class="btn btn-primary btn-block text-center" title="Inicia sesión como estudiante para evaluar">Añadir evaluación</a>
                    @endauth
                </div>
            </div>

            <!-- COLUMNA DERECHA (Gráficos y Sección Unificada de Reseñas) -->
            <div class="perfil-main-content">
                <!-- Sección de Gráficos Interactivos -->
                <div class="charts-section" style="background: white; border-radius: 10px; border: 1px solid #cbd5e1; border-top: 3px solid #353564; padding: 20px; margin-bottom: 25px; box-shadow: 0 2px 6px rgba(15,23,42,0.05);">
                    <h3 style="font-size: 1.1em; color: #353564; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; font-weight: bold;">
                        Historial de Estrellas por Semestre
                    </h3>
                    
                    <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; text-align: center;">
                        <div style="position: relative; height: 230px; width: 100%;">
                            <canvas id="ratingHistoryChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Sección Unificada: Reseñas del Profesor + Toolbar de Filtros -->
                <div class="resenias-seccion-card">
                    <div class="resenias-seccion-header">
                        <h2 class="resenias-seccion-titulo">{{ __('messages.teacher_reviews') }}</h2>
                        
                        <!-- Toolbar de Filtros Integrada -->
                        <div class="review-filters-toolbar">
                            @if(isset($allPeriods) && $allPeriods->count() > 0)
                                <div class="filter-group-title">Filtrar por Semestre Académico</div>
                                <div class="filter-pills-container" id="periodFilterPills" style="margin-bottom: 12px;">
                                    <button type="button" class="filter-pill active" onclick="filterByPeriod('all')">Todos los Semestres</button>
                                    @foreach($allPeriods as $p)
                                        <button type="button" class="filter-pill" onclick="filterByPeriod('{{ $p->id }}')">📅 {{ $p->name }}</button>
                                    @endforeach
                                </div>
                            @endif

                            <div class="filter-group-title">Filtrar por Estrellas</div>
                            <div class="filter-pills-container" id="starFilterPills" style="margin-bottom: 12px;">
                                <button type="button" class="filter-pill active-star" onclick="filterByStar('all')">Todas</button>
                                <button type="button" class="filter-pill" onclick="filterByStar(5)">5 ★</button>
                                <button type="button" class="filter-pill" onclick="filterByStar(4)">4 ★</button>
                                <button type="button" class="filter-pill" onclick="filterByStar(3)">3 ★</button>
                                <button type="button" class="filter-pill" onclick="filterByStar(2)">2 ★</button>
                                <button type="button" class="filter-pill" onclick="filterByStar(1)">1 ★</button>
                            </div>

                            <div class="filter-group-title">Filtrar por Métricas Destacadas</div>
                            <div class="filter-pills-container" id="metricFilterPills">
                                <button type="button" class="filter-pill active" onclick="filterByMetric('all')">Todas las Métricas</button>
                                <button type="button" class="filter-pill" onclick="filterByMetric('claridad')">Claridad</button>
                                <button type="button" class="filter-pill" onclick="filterByMetric('justicia')">Justicia</button>
                                <button type="button" class="filter-pill" onclick="filterByMetric('empatia')">Empatía</button>
                                <button type="button" class="filter-pill" onclick="filterByMetric('dominio')">Dominio</button>
                            </div>

                            <!-- Selector de Ordenamiento por Fecha y Calificación -->
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px; padding-top: 12px; border-top: 1px solid #e2e8f0; flex-wrap: wrap; gap: 10px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <label for="reviewSortSelect" style="font-size: 0.85em; font-weight: 700; color: #475569; margin: 0; text-transform: uppercase; letter-spacing: 0.5px;">
                                        <i class="fas fa-sort"></i> Ordenar por:
                                    </label>
                                    <select id="reviewSortSelect" class="form-control form-control-sm" style="width: auto; display: inline-block; border-radius: 6px; font-size: 0.88em; font-weight: 600; color: #334155; border-color: #cbd5e1;" onchange="sortReviews(this.value)">
                                        <option value="recent">Más recientes primero</option>
                                        <option value="oldest">Más antiguos primero</option>
                                        <option value="highest">Mayor calificación (5★ a 1★)</option>
                                        <option value="lowest">Menor calificación (1★ a 5★)</option>
                                    </select>
                                </div>
                                <div style="font-size: 0.82em; color: #64748b;">
                                    <i class="fas fa-user-shield" style="color: #4f46e5;"></i> Comentarios y apodos anónimos
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de Reseñas -->
                    <div class="lista-resenias">
                        <div id="emptyFilterMessage" style="display: none; background: #f8fafc; padding: 25px; border-radius: 8px; border: 1px dashed #cbd5e1; text-align: center; color: #64748b; margin-bottom: 15px;">
                            No hay comentarios que coincidan con los filtros seleccionados.
                        </div>

                    @if($reseniasPorPeriodo->isEmpty())
                        <p class="no-resenias" style="color: #64748b; font-style: italic; background: white; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">{{ __('messages.no_reviews') }}</p>
                    @else
                        @foreach ($reseniasPorPeriodo as $periodoNombre => $resenias)
                            @foreach ($resenias as $resenia)
                                @if(is_null($resenia->contenido) || $resenia->contenido === '[Comentario eliminado por el estudiante]')
                                    @continue
                                @endif
                                @php
                                    $studentNickname = $resenia->students->first()?->name ?? 'Estudiante Anónimo';
                                    $timestamp = $resenia->created_at ? $resenia->created_at->timestamp : 0;
                                    $formattedDate = $resenia->created_at ? $resenia->created_at->format('d/m/Y') : 'Reciente';

                                    $metricsList = [];
                                    
                                    if ($resenia->answers->where('question_id', 1)->first()) {
                                        $metricsList[] = 'claridad';
                                    }
                                    if ($resenia->answers->where('question_id', 2)->first()) {
                                        $metricsList[] = 'justicia';
                                    }
                                    if ($resenia->answers->where('question_id', 3)->first()) {
                                        $metricsList[] = 'empatia';
                                    }
                                    if ($resenia->answers->where('question_id', 4)->first()) {
                                        $metricsList[] = 'dominio';
                                    }
                                    $metricsAttr = implode(',', $metricsList);
                                @endphp

                                <div class="resenia review-item" data-star="{{ $resenia->calificacion }}" data-metrics="{{ $metricsAttr }}" data-period-id="{{ $resenia->period_id }}" data-timestamp="{{ $timestamp }}">
                                    <!-- Encabezado del Comentario: Autor (Apodo) y Fecha -->
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; border-bottom: 1px solid #f1f5f9; padding-bottom: 6px; flex-wrap: wrap; gap: 6px;">
                                        <div style="display: flex; align-items: center; gap: 6px;">
                                            <span style="background: #eef2ff; color: #3730a3; padding: 2px 9px; border-radius: 12px; font-size: 0.82em; font-weight: 700; display: inline-flex; align-items: center; gap: 5px;">
                                                <i class="fas fa-user-circle"></i> {{ $studentNickname }}
                                            </span>
                                            <span style="background: #f1f5f9; color: #64748b; padding: 1px 6px; border-radius: 4px; font-size: 0.74em; font-weight: 600;">
                                                <i class="fas fa-user-shield" style="font-size: 0.85em; color: #64748b;"></i> Anónimo
                                            </span>
                                        </div>
                                        <div style="color: #94a3b8; font-size: 0.82em; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="far fa-calendar-alt"></i> {{ $formattedDate }}
                                        </div>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; width: 100%; align-items: center; margin-bottom: 8px;">
                                        <p class="resenia-calificacion" style="margin: 0;"> 
                                            {{ $resenia->calificacion }} <img src="/imagenes/estrella.png" alt="estrella" class="estrella">
                                        </p>

                                        <!-- Etiquetas de métricas detectadas -->
                                        <div style="display: flex; gap: 4px; flex-wrap: wrap;">
                                            @foreach($metricsList as $mTag)
                                                <span style="font-size: 0.72em; background: #eef2ff; color: #3730a3; padding: 2px 7px; border-radius: 10px; font-weight: 600; text-transform: capitalize;">
                                                    #{{ $mTag }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Datos del curso y estado -->
                                    <div style="font-size: 0.85em; color: #64748b; margin-bottom: 8px;">
                                        <strong>Semestre:</strong> {{ $resenia->period?->name ?? 'General/Antiguo' }}
                                        @if($resenia->curso)
                                            | <strong>Curso:</strong> {{ $resenia->curso }}
                                        @endif
                                        @if($resenia->denunciada)
                                            <span style="background: #fef3c7; color: #92400e; padding: 1px 6px; border-radius: 4px; font-size: 0.75em; margin-left: 5px; font-weight: bold;">⚠️ Bajo revisión</span>
                                        @elseif($resenia->oculto)
                                            <span style="background: #fee2e2; color: #991b1b; padding: 1px 6px; border-radius: 4px; font-size: 0.75em; margin-left: 5px; font-weight: bold;">🔒 En observación</span>
                                        @endif
                                    </div>
                                    
                                    @if(is_null($resenia->contenido))
                                        <p class="resenia-texto" style="font-style: italic; color: #94a3b8;">[Comentario eliminado por el estudiante]</p>
                                    @elseif($resenia->oculto && !$isAdmin)
                                        <p class="resenia-texto hidden-comment">El comentario ha sido ocultado por el administrador.</p>
                                    @else
                                        <p class="resenia-texto">{{ $resenia->contenido }}</p>
                                        @if(!$resenia->oculto && !$isAdmin)
                                            @php
                                                $isOwnReview = auth('student')->check() && $resenia->students->contains(auth('student')->id());
                                            @endphp

                                            @if(!$isOwnReview)
                                                <div class="resenia-report-container">
                                                    @if($resenia->denunciada)
                                                        <span class="badge badge-warning" style="font-size: 0.8em; padding: 4px 8px; border-radius: 4px; background: #fef3c7; color: #92400e; font-weight: 600;">
                                                            ⚠️ En revisión
                                                        </span>
                                                    @else
                                                        @auth('student')
                                                            <form action="{{ route('resenias.denunciar', $resenia->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Está seguro de que desea denunciar este comentario por contenido inapropiado u ofensivo?');">
                                                                @csrf
                                                                <button type="submit" class="btn-candado-denuncia" title="Denunciar" aria-label="Denunciar">
                                                                    <i class="fas fa-lock"></i>
                                                                    <span class="label-denunciar">Denunciar</span>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <button type="button" class="btn-candado-denuncia" title="Denunciar" aria-label="Denunciar" onclick="alert('No se puede denunciar este comentario si no eres un usuario registrado. Debes iniciar sesión como estudiante para realizar una denuncia.');">
                                                                <i class="fas fa-lock"></i>
                                                                <span class="label-denunciar">Denunciar</span>
                                                            </button>
                                                        @endauth
                                                    @endif
                                                </div>
                                            @endif
                                        @endif
                                    @endif

                                    <!-- Acciones del Estudiante (Editar/Eliminar) -->
                                    @auth('student')
                                        @if($resenia->students->contains(auth('student')->id()))
                                            <div style="margin-top: 12px; width: 100%; display: flex; gap: 10px; align-items: center; border-top: 1px solid #f1f5f9; padding-top: 10px;">
                                                @if(is_null($resenia->contenido) || $resenia->contenido === '[Comentario eliminado por el estudiante]')
                                                    <span style="font-size: 0.82em; color: #64748b; font-style: italic;">
                                                        🗑️ Comentario eliminado por el estudiante
                                                    </span>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-outline-secondary btn-editar-resenia"
                                                        data-id="{{ $resenia->id }}"
                                                        data-rating="{{ $resenia->calificacion }}"
                                                        data-content="{{ $resenia->contenido }}"
                                                        data-curso="{{ $resenia->curso }}"
                                                        data-period-id="{{ $resenia->period_id }}"
                                                        data-toggle="modal" data-target="#editReviewModal"
                                                        title="Editar mi reseña">
                                                        ✏️ Editar
                                                    </button>

                                                    <form action="{{ route('resenia.eliminar', $resenia->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este comentario?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar mi reseña">
                                                            🗑️ Eliminar
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @endif
                                    @endauth

                                    @if($isAdmin)
                                        <div class="admin-controls" style="width: 100%; margin-top: 10px;">
                                            @if($resenia->denunciada)
                                                <div style="background: #fff3cd; color: #856404; border: 1px solid #ffeeba; padding: 5px 10px; border-radius: 4px; margin-bottom: 10px; font-weight: bold;">
                                                    🚩 ESTA RESEÑA HA SIDO DENUNCIADA POR UN ESTUDIANTE
                                                </div>
                                            @endif
                                            <strong>Respuestas Adicionales (Solo Admin):</strong>
                                            <ul>
                                                @foreach($resenia->answers as $answer)
                                                    <li>
                                                        <em>{{ $answer->question->text }}:</em> 
                                                        @if($answer->question->type == 'likert')
                                                            @php
                                                                $options = $answer->question->options ?? ['Muy malo', 'Malo', 'Neutro', 'Bueno', 'Muy bueno'];
                                                                $label = $options[$answer->numeric_value - 1] ?? $answer->numeric_value;
                                                            @endphp
                                                            {{ $label }} ({{ $answer->numeric_value }})
                                                        @else
                                                            {{ $answer->text_value }}
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <hr>
                                            <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                                                <form action="{{ route('profesor.resenia.toggle', [$profesor->id, $resenia->id]) }}" method="POST" style="margin: 0;">
                                                    @csrf
                                                    @if($resenia->oculto)
                                                        <span style="color:red; font-weight:bold;">[OCULTO AL PÚBLICO]</span>
                                                        <button type="submit" class="btn-mostrar">Mostrar Comentario</button>
                                                    @else
                                                        <button type="submit" class="btn-ocultar">Ocultar Comentario General</button>
                                                    @endif
                                                </form>

                                                <form action="{{ route('admin.resenias.destroy', $resenia->id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar permanentemente esta reseña del sistema por denuncia?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" style="background-color: #dc2626; color: white; border: none; padding: 4px 10px; border-radius: 4px; font-weight: 600;" title="Eliminar permanentemente este registro del sistema">
                                                        🗑️ Eliminar Definitivamente (Admin)
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endforeach
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@auth('student')
<!-- Modal Añadir Reseña -->
<div class="modal fade" id="addReviewModal" tabindex="-1" aria-labelledby="addReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addReviewModalLabel">Añadir Evaluación al Docente @if(isset($activePeriod) && $activePeriod) (Semestre: {{ $activePeriod->name }}) @endif</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addReviewForm" method="POST" action="{{ route('add_review', ['profesor' => $profesor->id]) }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="period_id">Semestre Académico</label>
                        <select class="form-control" id="period_id" name="period_id" required>
                            @php
                                $defaultSelectedPeriodId = $activePeriod ? $activePeriod->id : ($allPeriods->first() ? $allPeriods->first()->id : null);
                            @endphp
                            @foreach($allPeriods as $p)
                                <option value="{{ $p->id }}" {{ $defaultSelectedPeriodId == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" style="position: relative;">
                        <label for="course_search_input">Curso que dictó el profesor <span style="color: #e11d48;">*</span></label>
                        <input type="hidden" id="curso_hidden" name="curso" value="" required>
                        
                        <div style="position: relative;">
                            <input type="text" class="form-control" id="course_search_input" placeholder="Escribe para buscar curso (ej: Cálculo)..." autocomplete="off">
                        </div>

                        <div id="add_course_dropdown_list" style="display: none; position: absolute; top: 100%; left: 0; width: 100%; max-height: 180px; overflow-y: auto; background: white; border: 1px solid #cbd5e1; border-radius: 6px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); z-index: 9999; margin-top: 2px;">
                            @if(isset($courses) && $courses->count() > 0)
                                @foreach($courses as $c)
                                    <div class="add-course-option-item" data-value="{{ $c->name }}" style="padding: 10px 14px; cursor: pointer; font-size: 0.9em; border-bottom: 1px solid #f1f5f9; color: #334155; transition: background 0.15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='white'">
                                        {{ $c->name }}
                                    </div>
                                @endforeach
                            @else
                                <div style="padding: 12px; font-size: 0.88em; color: #94a3b8; font-style: italic;">
                                    Este docente aún no tiene cursos específicos asignados.
                                </div>
                            @endif
                        </div>

                        <div id="add_course_alert" style="display: none; margin-top: 6px; padding: 8px 12px; background: #fff1f2; border: 1px solid #fecdd3; border-radius: 6px; color: #be123c; font-size: 0.85em; font-weight: 600;">
                            ⚠️ Debes seleccionar un curso de la lista desplegable de opciones para poder enviar tu reseña.
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="rating">{{ __('messages.rating') }} (1 a 5 Estrellas)</label>
                        <select class="form-control" id="rating" name="calificacion" required>
                            <option value="5">5 - Excelente</option>
                            <option value="4">4 - Bueno</option>
                            <option value="3">3 - Regular</option>
                            <option value="2">2 - Malo</option>
                            <option value="1">1 - Deficiente</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="review">{{ __('messages.review') }} (Comentario General público)</label>
                        <textarea class="form-control" id="review" name="contenido" rows="3" required placeholder="Describe tu experiencia de aprendizaje de manera constructiva y respetuosa..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3" title="Enviar reseña">{{ __('actions.send') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Reseña -->
<div class="modal fade" id="editReviewModal" tabindex="-1" aria-labelledby="editReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editReviewModalLabel">Modificar Evaluación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editReviewForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="edit_period_id">Semestre Académico</label>
                        <select class="form-control" id="edit_period_id" name="period_id" required>
                            @foreach($allPeriods as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" style="position: relative;">
                        <label for="edit_course_search_input">Curso que dictó el profesor <span style="color: #e11d48;">*</span></label>
                        <input type="hidden" id="edit_curso_hidden" name="curso" value="" required>
                        
                        <div style="position: relative;">
                            <input type="text" class="form-control" id="edit_course_search_input" placeholder="🔍 Escribe para buscar curso (ej: Cálculo)..." autocomplete="off">
                        </div>

                        <div id="edit_course_dropdown_list" style="display: none; position: absolute; top: 100%; left: 0; width: 100%; max-height: 180px; overflow-y: auto; background: white; border: 1px solid #cbd5e1; border-radius: 6px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); z-index: 9999; margin-top: 2px;">
                            @if(isset($courses) && $courses->count() > 0)
                                @foreach($courses as $c)
                                    <div class="edit-course-option-item" data-value="{{ $c->name }}" style="padding: 10px 14px; cursor: pointer; font-size: 0.9em; border-bottom: 1px solid #f1f5f9; color: #334155; transition: background 0.15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='white'">
                                        {{ $c->name }}
                                    </div>
                                @endforeach
                            @else
                                <div style="padding: 12px; font-size: 0.88em; color: #94a3b8; font-style: italic;">
                                    Este docente aún no tiene cursos específicos asignados.
                                </div>
                            @endif
                        </div>

                        <div id="edit_course_alert" style="display: none; margin-top: 6px; padding: 8px 12px; background: #fff1f2; border: 1px solid #fecdd3; border-radius: 6px; color: #be123c; font-size: 0.85em; font-weight: 600;">
                            ⚠️ Debes seleccionar un curso de la lista desplegable de opciones para poder enviar tu reseña.
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_rating">Calificación (1 a 5 Estrellas)</label>
                        <select class="form-control" id="edit_rating" name="calificacion" required>
                            <option value="5">5 - Excelente</option>
                            <option value="4">4 - Bueno</option>
                            <option value="3">3 - Regular</option>
                            <option value="2">2 - Malo</option>
                            <option value="1">1 - Deficiente</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="edit_content">Comentario General público</label>
                        <textarea class="form-control" id="edit_content" name="contenido" rows="3" required placeholder="Modifica tu comentario de manera constructiva..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3" title="Guardar cambios editados">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endauth

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.PERFIL_PROFE_DATA = {
            metricsByPeriod: @json($metricsByPeriod ?? []),
            histogramData: @json($histogramData ?? []),
            chartComparison: @json($chartComparison ?? [])
        };
    </script>
    <script src="/js/perfil-profe-ratings.js"></script>
@endpush
@endsection
