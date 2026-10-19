/**
 * Lógica interactiva para la vista de Perfil del Profesor:
 * - Filtros dinámicos por Semestre, Estrellas y Dimensiones de IA.
 * - Gráficos Chart.js (Histograma de evolución histórica y Comparativa Mitad vs Fin).
 * - Selector interactivo de cursos con autocompletado para reseñas.
 * - Envío Asíncrono (AJAX) de creación/edición de reseñas con actualización fluida.
 */

let currentStarFilter = 'all';
let currentMetricFilter = 'all';
let currentPeriodFilter = 'all';

function filterByPeriod(periodId) {
    currentPeriodFilter = periodId;

    const container = document.getElementById('periodFilterPills');
    if (container) {
        container.querySelectorAll('.filter-pill').forEach(btn => {
            btn.classList.remove('active');
            const btnOnClick = btn.getAttribute('onclick') || '';
            if ((periodId === 'all' && btnOnClick.includes("'all'")) || btnOnClick.includes("'" + periodId + "'")) {
                btn.classList.add('active');
            }
        });
    }

    updateSemesterMetricsDisplay(periodId);
    applyReviewFilters();
}

function updateSemesterMetricsDisplay(periodId) {
    const metricsData = window.PERFIL_PROFE_DATA?.metricsByPeriod || {};
    const data = metricsData[periodId] || metricsData['all'];
    if (!data) return;

    // 1. Score box
    const scoreNum = document.getElementById('score_number_display');
    if (scoreNum) scoreNum.textContent = data.avg_rating;

    const totalCount = document.getElementById('total_reviews_count_display');
    if (totalCount) {
        totalCount.textContent = `${data.total_reviews} ${data.total_reviews === 1 ? 'reseña' : 'reseñas'} en total`;
    }

    const starsRow = document.getElementById('stars_row_display');
    if (starsRow) {
        let starsHtml = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= data.stars_count) {
                starsHtml += '★';
            } else {
                starsHtml += '<span style="color: #cbd5e1;">★</span>';
            }
        }
        starsRow.innerHTML = starsHtml;
    }

    // 2. Star breakdown bars
    [5, 4, 3, 2, 1].forEach(star => {
        const starInfo = data.star_breakdown?.[star] || { count: 0, percentage: 0 };
        const barFill = document.getElementById(`star_bar_fill_${star}`);
        const barValue = document.getElementById(`star_bar_val_${star}`);
        if (barFill) barFill.style.width = starInfo.percentage + '%';
        if (barValue) barValue.textContent = `${starInfo.percentage}% (${starInfo.count})`;
    });

    // 3. Detailed Dimensions (IA)
    [1, 2, 3, 4].forEach(dimId => {
        const val = data.dimensions?.[dimId];
        const valEl = document.getElementById(`dim_val_${dimId}`);
        const badgeEl = document.getElementById(`dim_badge_${dimId}`);
        const barEl = document.getElementById(`dim_bar_${dimId}`);
        const emptyEl = document.getElementById(`dim_empty_${dimId}`);
        const barContainer = document.getElementById(`dim_bar_container_${dimId}`);

        if (val === null || val === undefined) {
            if (valEl) valEl.textContent = 'N/A';
            if (badgeEl) badgeEl.style.display = 'none';
            if (emptyEl) emptyEl.style.display = 'block';
            if (barContainer) barContainer.style.display = 'none';
        } else {
            if (valEl) valEl.textContent = Number(val).toFixed(1);
            if (emptyEl) emptyEl.style.display = 'none';
            if (barContainer) barContainer.style.display = 'block';

            let badgeText = '🟢 Destacado';
            let badgeBg = '#dcfce7';
            let badgeColor = '#166534';
            let colorProgreso = '#16a34a';

            if (val < 2.5) {
                badgeText = '🔴 Deficiente';
                badgeBg = '#fee2e2';
                badgeColor = '#991b1b';
                colorProgreso = '#dc3545';
            } else if (val >= 2.5 && val < 3.8) {
                badgeText = '🟡 Neutral';
                badgeBg = '#fef3c7';
                badgeColor = '#92400e';
                colorProgreso = '#d97706';
            }

            if (badgeEl) {
                badgeEl.style.display = 'inline-block';
                badgeEl.style.backgroundColor = badgeBg;
                badgeEl.style.color = badgeColor;
                badgeEl.textContent = badgeText;
            }

            if (barEl) {
                const pct = Math.min(100, Math.max(0, val * 20));
                barEl.style.backgroundColor = colorProgreso;
                barEl.style.width = pct + '%';
            }
        }
    });
}

function filterByStar(star) {
    currentStarFilter = star;
    
    const container = document.getElementById('starFilterPills');
    if (container) {
        container.querySelectorAll('.filter-pill').forEach(btn => {
            btn.classList.remove('active-star');
            const btnText = btn.innerText.trim();
            if ((star === 'all' && btnText.includes('Todas')) || btnText.includes(star + ' ★')) {
                btn.classList.add('active-star');
            }
        });
    }
    applyReviewFilters();
}

function filterByMetric(metric) {
    currentMetricFilter = metric;

    const container = document.getElementById('metricFilterPills');
    if (container) {
        container.querySelectorAll('.filter-pill').forEach(btn => {
            btn.classList.remove('active');
            const btnText = btn.innerText.trim().toLowerCase();
            if ((metric === 'all' && btnText.includes('todas')) || btnText.includes(metric)) {
                btn.classList.add('active');
            }
        });
    }
    applyReviewFilters();
}

function applyReviewFilters() {
    const items = document.querySelectorAll('.review-item');
    let visibleCount = 0;

    items.forEach(item => {
        const itemStar = item.getAttribute('data-star');
        const itemMetrics = item.getAttribute('data-metrics') || '';
        const itemPeriod = item.getAttribute('data-period-id') || '';
        const metricsArray = itemMetrics.split(',');

        const matchStar = (currentStarFilter === 'all' || itemStar == currentStarFilter);
        const matchMetric = (currentMetricFilter === 'all' || metricsArray.includes(currentMetricFilter));
        const matchPeriod = (currentPeriodFilter === 'all' || itemPeriod == currentPeriodFilter);

        if (matchStar && matchMetric && matchPeriod) {
            item.style.display = 'block';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });

    const emptyMsg = document.getElementById('emptyFilterMessage');
    if (emptyMsg) {
        emptyMsg.style.display = (visibleCount === 0 && items.length > 0) ? 'block' : 'none';
    }
}

let currentSortOrder = 'recent';

function sortReviews(sortOrder) {
    currentSortOrder = sortOrder || 'recent';
    const container = document.querySelector('.lista-resenias');
    if (!container) return;

    const items = Array.from(container.querySelectorAll('.review-item'));
    if (items.length === 0) return;

    items.sort((a, b) => {
        const timeA = parseInt(a.getAttribute('data-timestamp') || '0', 10);
        const timeB = parseInt(b.getAttribute('data-timestamp') || '0', 10);
        const starA = parseInt(a.getAttribute('data-star') || '0', 10);
        const starB = parseInt(b.getAttribute('data-star') || '0', 10);

        switch (currentSortOrder) {
            case 'recent':
                return timeB - timeA;
            case 'oldest':
                return timeA - timeB;
            case 'highest':
                return starB - starA;
            case 'lowest':
                return starA - starB;
            default:
                return timeB - timeA;
        }
    });

    items.forEach(item => container.appendChild(item));
}

document.addEventListener("DOMContentLoaded", function() {
    sortReviews('recent');
    const dataHub = window.PERFIL_PROFE_DATA || {};

    // --- 1. HISTOGRAMA DE EVOLUCIÓN HISTÓRICA POR SEMESTRE ---
    const historyCtx = document.getElementById('ratingHistoryChart')?.getContext('2d');
    if (historyCtx && typeof Chart !== 'undefined') {
        const histData = dataHub.histogramData || {};
        const labels = Object.keys(histData);
        const dataValues = Object.values(histData);

        new Chart(historyCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Calificación Promedio',
                    data: dataValues,
                    borderColor: '#353564',
                    backgroundColor: 'rgba(53, 53, 100, 0.05)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.2,
                    pointBackgroundColor: '#28a745',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#28a745',
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        min: 1,
                        max: 5,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            color: '#f0f0f0'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // --- 2. COMPARATIVA MITAD VS FIN DE SEMESTRE (PERIODO ACTIVO) ---
    const compCtx = document.getElementById('midEndComparisonChart')?.getContext('2d');
    if (compCtx && typeof Chart !== 'undefined') {
        const chartComp = dataHub.chartComparison || { mid_scores: [0,0,0,0], end_scores: [0,0,0,0] };

        new Chart(compCtx, {
            type: 'bar',
            data: {
                labels: ['Claridad', 'Justicia', 'Empatía', 'Dominio'],
                datasets: [
                    {
                        label: 'Mitad de Semestre',
                        data: chartComp.mid_scores,
                        backgroundColor: '#3182ce',
                        borderColor: '#3182ce',
                        borderWidth: 1,
                        borderRadius: 4
                    },
                    {
                        label: 'Fin de Semestre',
                        data: chartComp.end_scores,
                        backgroundColor: '#28a745',
                        borderColor: '#28a745',
                        borderWidth: 1,
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 15
                        }
                    }
                },
                scales: {
                    y: {
                        min: 0,
                        max: 5,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            color: '#f0f0f0'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // --- 3. SELECCIONADOR BUSCABLE DE CURSOS PARA RESEÑAS ---
    function normalizeText(str) {
        return (str || '').normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
    }

    function setupSearchableCourseSelect(inputId, hiddenId, listId, optionClass, alertId, formId) {
        const input = document.getElementById(inputId);
        const hidden = document.getElementById(hiddenId);
        const list = document.getElementById(listId);
        const alertBox = document.getElementById(alertId);
        const form = document.getElementById(formId);

        if (!input || !hidden || !list || !form) return;

        function filterOptions() {
            const term = normalizeText(input.value.trim());
            document.querySelectorAll('.' + optionClass).forEach(item => {
                const val = normalizeText(item.getAttribute('data-value') || '');
                if (val.includes(term)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        input.addEventListener('focus', function() {
            filterOptions();
            list.style.display = 'block';
        });

        input.addEventListener('input', function() {
            const term = normalizeText(this.value.trim());
            hidden.value = '';
            if (alertBox) alertBox.style.display = 'none';

            let exactMatch = null;
            document.querySelectorAll('.' + optionClass).forEach(item => {
                const val = item.getAttribute('data-value') || '';
                if (normalizeText(val) === term) {
                    exactMatch = val;
                }
            });

            if (exactMatch) {
                hidden.value = exactMatch;
            }

            filterOptions();
            list.style.display = 'block';
        });

        document.querySelectorAll('.' + optionClass).forEach(item => {
            item.addEventListener('mousedown', function(e) {
                e.preventDefault();
                const val = this.getAttribute('data-value');
                input.value = val;
                hidden.value = val;
                list.style.display = 'none';
                if (alertBox) alertBox.style.display = 'none';
            });
        });

        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !list.contains(e.target)) {
                list.style.display = 'none';
            }
        });

        form.addEventListener('submit', function(e) {
            if (!hidden.value || hidden.value.trim() === '') {
                e.preventDefault();
                if (alertBox) {
                    alertBox.style.display = 'block';
                }
                input.focus();
            }
        });
    }

    setupSearchableCourseSelect('course_search_input', 'curso_hidden', 'add_course_dropdown_list', 'add-course-option-item', 'add_course_alert', 'addReviewForm');
    setupSearchableCourseSelect('edit_course_search_input', 'edit_curso_hidden', 'edit_course_dropdown_list', 'edit-course-option-item', 'edit_course_alert', 'editReviewForm');

    // --- 4. PASAR DATOS AL FORMULARIO DE EDICIÓN ---
    document.querySelectorAll('.btn-editar-resenia').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const rating = this.getAttribute('data-rating');
            const content = this.getAttribute('data-content');
            const curso = this.getAttribute('data-curso');
            const periodId = this.getAttribute('data-period-id');

            const form = document.getElementById('editReviewForm');
            if (form) form.action = `/resenia/editar/${id}`;

            const ratingEl = document.getElementById('edit_rating');
            if (ratingEl) ratingEl.value = rating;

            const contentEl = document.getElementById('edit_content');
            if (contentEl) contentEl.value = content;

            const editSearchInput = document.getElementById('edit_course_search_input');
            const editHiddenInput = document.getElementById('edit_curso_hidden');
            if (editSearchInput && editHiddenInput) {
                editSearchInput.value = curso || '';
                editHiddenInput.value = curso || '';
            }

            const periodEl = document.getElementById('edit_period_id');
            if (periodEl) periodEl.value = periodId;
        });
    });

    // --- 5. MODAL DE PROCESAMIENTO VISUAL Y SUBMIT VIA AJAX ---
    function showProcessingModal(title, subtitle) {
        let modal = document.getElementById('reviewProcessingModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'reviewProcessingModal';
            modal.style.cssText = 'position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(15, 23, 42, 0.68); backdrop-filter: blur(4px); z-index: 999999; display: flex; align-items: center; justify-content: center;';
            modal.innerHTML = `
                <div style="background: #ffffff; border-radius: 16px; padding: 28px 32px; max-width: 390px; width: 90%; text-align: center; box-shadow: 0 20px 35px -5px rgba(0,0,0,0.3);">
                    <div id="processingIconContainer" style="margin-bottom: 16px;">
                        <div class="spinner-border" style="width: 3.5rem; height: 3.5rem; color: #353564; border-width: 0.28em;" role="status">
                            <span class="sr-only">Cargando...</span>
                        </div>
                    </div>
                    <h5 id="processingModalTitle" style="font-weight: 700; color: #1e1b4b; margin-bottom: 8px; font-size: 1.25rem;">
                        ${title}
                    </h5>
                    <p id="processingModalSubtitle" style="color: #64748b; font-size: 0.95rem; margin-bottom: 0; line-height: 1.4;">
                        ${subtitle}
                    </p>
                </div>
            `;
            document.body.appendChild(modal);
        } else {
            const iconContainer = document.getElementById('processingIconContainer');
            if (iconContainer) {
                iconContainer.innerHTML = `
                    <div class="spinner-border" style="width: 3.5rem; height: 3.5rem; color: #353564; border-width: 0.28em;" role="status">
                        <span class="sr-only">Cargando...</span>
                    </div>
                `;
            }
            document.getElementById('processingModalTitle').textContent = title;
            document.getElementById('processingModalSubtitle').textContent = subtitle;
            modal.style.display = 'flex';
        }
        return modal;
    }

    function setProcessingModalSuccess(title, subtitle) {
        const iconContainer = document.getElementById('processingIconContainer');
        const titleEl = document.getElementById('processingModalTitle');
        const subEl = document.getElementById('processingModalSubtitle');

        if (iconContainer) {
            iconContainer.innerHTML = `
                <div style="width: 56px; height: 56px; background: #dcfce7; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 6px;">
                    <i class="fas fa-check" style="font-size: 26px; color: #16a34a;"></i>
                </div>
            `;
        }
        if (titleEl) titleEl.textContent = title;
        if (subEl) subEl.textContent = subtitle;
    }

    function hideProcessingModal() {
        const modal = document.getElementById('reviewProcessingModal');
        if (modal) modal.style.display = 'none';
    }

    function handleAjaxFormSubmit(formId) {
        const form = document.getElementById(formId);
        if (!form) return;

        form.addEventListener('submit', function(e) {
            const hiddenInput = form.querySelector('input[type="hidden"][name="curso"]');
            if (!hiddenInput || !hiddenInput.value || hiddenInput.value.trim() === '') {
                return;
            }

            e.preventDefault();
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) submitBtn.disabled = true;

            showProcessingModal('Procesando reseña...', 'Analizando aspectos pedagógicos y sentimiento con Inteligencia Artificial...');

            const formData = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    setProcessingModalSuccess('¡Listo!', 'Tu reseña ha sido registrada exitosamente.');
                    setTimeout(() => {
                        sessionStorage.setItem('shown_toast_msg', data.message || 'Tu reseña ha sido añadida y procesada con éxito.');
                        window.location.replace(window.location.href);
                    }, 650);
                } else {
                    hideProcessingModal();
                    alert(data.error || 'Ocurrió un error al procesar la reseña.');
                    if (submitBtn) submitBtn.disabled = false;
                }
            })
            .catch(err => {
                hideProcessingModal();
                form.submit();
            });
        });
    }

    handleAjaxFormSubmit('addReviewForm');
    handleAjaxFormSubmit('editReviewForm');
});
