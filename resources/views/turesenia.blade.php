@extends('layouts.logueado')

@section('titulo', __('messages.youreviews'))

@push('styles')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @media (max-width: 768px) {
            .table-responsive {
                border: none !important;
                overflow-x: visible !important;
            }
            .table-responsive thead {
                display: none !important;
            }
            .table-responsive table,
            .table-responsive tbody {
                display: block !important;
                width: 100% !important;
            }
            .table-responsive tr.my-review-row {
                display: block !important;
                background: #ffffff !important;
                border: 1px solid #cbd5e1 !important;
                border-top: 3px solid #353564 !important;
                border-radius: 12px !important;
                padding: 14px 16px !important;
                margin-bottom: 16px !important;
                box-shadow: 0 4px 12px rgba(53, 53, 100, 0.08) !important;
            }
            .table-responsive tr.my-review-row td {
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                border: none !important;
                padding: 7px 0 !important;
                border-bottom: 1px dashed #f1f5f9 !important;
                font-size: 0.92rem !important;
            }
            .table-responsive tr.my-review-row td[data-label="Comentario"] {
                flex-direction: column !important;
                align-items: flex-start !important;
                gap: 4px !important;
            }
            .table-responsive tr.my-review-row td[data-label="Comentario"] span.text-truncate {
                max-width: 100% !important;
                white-space: normal !important;
                color: #334155 !important;
                background: #f8fafc !important;
                padding: 8px 10px !important;
                border-radius: 6px !important;
                border: 1px solid #e2e8f0 !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }
            .table-responsive tr.my-review-row td:last-child {
                border-bottom: none !important;
                padding-top: 10px !important;
                justify-content: flex-end !important;
                gap: 8px !important;
            }
            .table-responsive tr.my-review-row td::before {
                content: attr(data-label);
                font-weight: 700;
                font-size: 0.8rem;
                color: #64748b;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            .table-responsive tr.my-review-row td:last-child::before {
                display: none !important;
            }
        }
    </style>
@endpush

@section('contenido')
    <div class="my-reviews-list mt-4">
        @if($resenias->isEmpty())
            <div class="text-center py-5" style="background: #fafafa; border-radius: 8px; border: 1px solid #eaeaea;">
                <i class="far fa-comments fa-3x text-muted mb-3"></i>
                <p class="text-muted">Aún no has escrito ninguna reseña. ¡Visita los perfiles de tus profesores para opinar!</p>
                <a href="{{ route('iniciologueado') }}" class="btn btn-primary btn-sm mt-2">Buscar Profesores</a>
            </div>
        @else
            <!-- Toolbar de Filtros para Mis Reseñas -->
            <div class="card p-3 mb-4 border-0 shadow-sm" style="border-radius: 10px; background: #ffffff; border: 1px solid #cbd5e1 !important; border-top: 3px solid #353564 !important;">
                <div class="row align-items-center">
                    <!-- Filtro por Nombre de Profesor -->
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label class="small font-weight-bold text-uppercase text-muted mb-1" style="letter-spacing: 0.5px;">Buscar por Profesor</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0"><i class="fas fa-search text-muted"></i></span>
                            </div>
                            <input type="text" id="teacherSearchInput" class="form-control border-left-0" placeholder="Escribe el nombre del profesor..." onkeyup="filterMyReviews()">
                        </div>
                    </div>

                    <!-- Filtro por Estrellas -->
                    <div class="col-md-6">
                        <label class="small font-weight-bold text-uppercase text-muted mb-1" style="letter-spacing: 0.5px;">Filtrar por Calificación</label>
                        <div class="d-flex flex-wrap" style="gap: 5px;" id="starFilterPills">
                            <button type="button" class="btn btn-sm btn-dark filter-pill-star active" onclick="filterMyReviewsByStar('all', this)">Todas</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary filter-pill-star" onclick="filterMyReviewsByStar(5, this)">5 ★</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary filter-pill-star" onclick="filterMyReviewsByStar(4, this)">4 ★</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary filter-pill-star" onclick="filterMyReviewsByStar(3, this)">3 ★</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary filter-pill-star" onclick="filterMyReviewsByStar(2, this)">2 ★</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary filter-pill-star" onclick="filterMyReviewsByStar(1, this)">1 ★</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="myReviewsEmptyMsg" style="display: none; background: #f8fafc; padding: 25px; border-radius: 8px; border: 1px dashed #cbd5e1; text-align: center; color: #64748b; margin-bottom: 20px;">
                No se encontraron reseñas que coincidan con la búsqueda o filtro seleccionado.
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle" style="background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.01);">
                    <thead class="thead-light">
                        <tr>
                            <th>Profesor</th>
                            <th>Semestre</th>
                            <th>Curso</th>
                            <th>Puntuación</th>
                            <th>Comentario</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resenias as $res)
                            <tr class="my-review-row" data-star="{{ $res->calificacion }}" data-teacher="{{ mb_strtolower(($res->profesor?->nombre ?? '') . ' ' . ($res->profesor?->apellido ?? '')) }}">
                                <td data-label="Profesor">
                                    <a href="{{ route('perfil.profesor', $res->profesor_id) }}" class="font-weight-bold" style="color: #353564; text-decoration: none;">
                                        {{ $res->profesor?->nombre }} {{ $res->profesor?->apellido }}
                                    </a>
                                </td>
                                <td data-label="Semestre">{{ $res->period?->name ?? 'General/Antiguo' }}</td>
                                <td data-label="Curso"><span class="badge badge-secondary" style="font-size: 0.9em;">{{ $res->curso ?? 'N/A' }}</span></td>
                                <td data-label="Puntuación">
                                    <div class="d-flex align-items-center">
                                        <span class="mr-1 font-weight-bold">{{ $res->calificacion }}</span>
                                        <i class="fas fa-star text-warning" style="font-size: 0.85em;"></i>
                                    </div>
                                </td>
                                <td data-label="Comentario">
                                    @if(is_null($res->contenido))
                                        <em class="text-muted">[Comentario eliminado]</em>
                                    @else
                                        <span class="text-truncate d-inline-block" style="max-width: 250px;" title="{{ $res->contenido }}">
                                            {{ $res->contenido }}
                                        </span>
                                    @endif
                                </td>
                                <td data-label="Estado">
                                    @if($res->denunciada)
                                        <span class="badge badge-warning text-dark" title="Esta reseña ha sido denunciada y se encuentra bajo revisión.">⚠️ Bajo revisión</span>
                                    @elseif($res->oculto)
                                        <span class="badge badge-danger" title="Esta reseña está oculta temporalmente por administración.">🔒 En observación</span>
                                    @elseif(is_null($res->contenido))
                                        <span class="badge badge-secondary" title="El comentario fue eliminado.">🗑️ Eliminado</span>
                                    @else
                                        <span class="badge badge-success" title="Reseña publicada y visible en la plataforma.">Publicado</span>
                                    @endif
                                </td>
                                <td data-label="Acciones" class="text-center">
                                    @if(is_null($res->contenido) || $res->contenido === '[Comentario eliminado por el estudiante]')
                                        <span class="badge badge-secondary" style="font-size: 0.82em;" title="Este comentario ha sido eliminado y no se puede editar.">
                                            🔒 Eliminado
                                        </span>
                                    @else
                                        <div class="d-flex justify-content-center gap-2" style="gap: 5px;">
                                            <button type="button" class="btn btn-sm btn-outline-primary btn-editar-resenia-dashboard"
                                                data-id="{{ $res->id }}"
                                                data-rating="{{ $res->calificacion }}"
                                                data-content="{{ $res->contenido }}"
                                                data-curso="{{ $res->curso }}"
                                                data-period-id="{{ $res->period_id }}"
                                                data-toggle="modal" data-target="#editReviewModal"
                                                title="Editar mi reseña">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <button type="button" class="btn btn-sm btn-outline-danger btn-trigger-delete" 
                                                data-action="{{ route('resenia.eliminar', $res->id) }}" 
                                                data-toggle="modal" data-target="#deleteReviewConfirmModal" 
                                                title="Eliminar mi reseña">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Modal Personalizado para Confirmar Eliminación de Reseña -->
    <div class="modal fade" id="deleteReviewConfirmModal" tabindex="-1" aria-labelledby="deleteReviewConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 10px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.15);">
                <div class="modal-header" style="background-color: #353564; color: #ffffff; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                    <h5 class="modal-title" id="deleteReviewConfirmModalLabel" style="font-weight: bold; font-size: 1.1em;">
                        Confirmar Eliminación
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-exclamation-triangle text-warning mb-3" style="font-size: 3rem;"></i>
                    <h5 class="font-weight-bold" style="color: #353564;">¿Estás seguro de que deseas eliminar esta reseña?</h5>
                    <p class="text-muted small mb-0 mt-2">Esta acción removerá el comentario de la vista pública del profesor.</p>
                </div>
                <div class="modal-footer justify-content-center border-0 pb-4">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal" style="border-radius: 20px;">Cancelar</button>
                    <form id="deleteReviewForm" method="POST" action="" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4" style="border-radius: 20px; font-weight: bold;">Eliminar Reseña</button>
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
                    <h5 class="modal-title" id="editReviewModalLabel">Editar Mi Reseña</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left">
                    <form id="editReviewForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="redirect_to" value="turesenia">
                        
                        <div class="form-group">
                            <label for="edit_period_id" class="font-weight-bold">Semestre Académico</label>
                            <select class="form-control" id="edit_period_id" name="period_id" required>
                                @foreach($periods as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit_curso" class="font-weight-bold">Curso</label>
                            <select class="form-control" id="edit_curso" name="curso" required>
                                <option value="" disabled selected>-- Selecciona un curso --</option>
                                @foreach($courses as $c)
                                    <option value="{{ $c->name }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit_rating" class="font-weight-bold">Calificación (1 a 5 Estrellas)</label>
                            <select class="form-control" id="edit_rating" name="calificacion" required>
                                <option value="5">5 - Excelente</option>
                                <option value="4">4 - Bueno</option>
                                <option value="3">3 - Regular</option>
                                <option value="2">2 - Malo</option>
                                <option value="1">1 - Deficiente</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit_content" class="font-weight-bold">Comentario General público</label>
                            <textarea class="form-control" id="edit_content" name="contenido" rows="3" required placeholder="Modifica tu comentario de manera constructiva..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block mt-3" title="Guardar cambios editados">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="/js/turesenia-modal.js"></script>
@endpush