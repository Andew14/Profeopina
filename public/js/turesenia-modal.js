/**
 * Lógica interactiva para la vista de "Tus Reseñas" del estudiante:
 * - Filtros por estrellas y por nombre del profesor.
 * - Edición y eliminación asíncrona de reseñas.
 */

let selectedStarFilter = 'all';
let activeDeleteRow = null;

function filterMyReviewsByStar(star, btn) {
    selectedStarFilter = star;
    document.querySelectorAll('.filter-pill-star').forEach(b => {
        b.classList.remove('btn-dark', 'active');
        b.classList.add('btn-outline-secondary');
    });
    if (btn) {
        btn.classList.remove('btn-outline-secondary');
        btn.classList.add('btn-dark', 'active');
    }
    filterMyReviews();
}

function filterMyReviews() {
    const searchVal = (document.getElementById('teacherSearchInput')?.value || '').toLowerCase().trim();
    const rows = document.querySelectorAll('.my-review-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const star = row.getAttribute('data-star');
        const teacher = row.getAttribute('data-teacher') || '';

        const matchesStar = (selectedStarFilter === 'all' || star == selectedStarFilter);
        const matchesTeacher = (!searchVal || teacher.includes(searchVal));

        if (matchesStar && matchesTeacher) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    const emptyMsg = document.getElementById('myReviewsEmptyMsg');
    if (emptyMsg) {
        emptyMsg.style.display = (visibleCount === 0 && rows.length > 0) ? 'block' : 'none';
    }
}

document.addEventListener("DOMContentLoaded", function() {
    // Pre-llenar modal de edición
    document.querySelectorAll('.btn-editar-resenia-dashboard').forEach(btn => {
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

            const cursoEl = document.getElementById('edit_curso');
            if (cursoEl) cursoEl.value = curso;

            const periodEl = document.getElementById('edit_period_id');
            if (periodEl) periodEl.value = periodId;
        });
    });

    // Preparar modal de confirmación de eliminación
    document.querySelectorAll('.btn-trigger-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const actionUrl = this.getAttribute('data-action');
            const deleteForm = document.getElementById('deleteReviewForm');
            if (deleteForm) deleteForm.action = actionUrl;
            activeDeleteRow = this.closest('tr');
        });
    });

    // Envío Asíncrono de Eliminación
    const deleteForm = document.getElementById('deleteReviewForm');
    if (deleteForm) {
        deleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const actionUrl = this.getAttribute('action');
            const token = this.querySelector('input[name="_token"]')?.value;

            fetch(actionUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ _method: 'DELETE' })
            })
            .then(response => response.json())
            .then(data => {
                if (window.$ && $('#deleteReviewConfirmModal').length) {
                    $('#deleteReviewConfirmModal').modal('hide');
                }
                if (data.success) {
                    if (activeDeleteRow) {
                        activeDeleteRow.remove();
                    }
                    filterMyReviews();
                    
                    if (typeof showToastSuccess === 'function') {
                        showToastSuccess(data.message || 'El comentario ha sido eliminado con éxito.');
                    } else {
                        window.location.reload();
                    }
                } else {
                    alert(data.error || 'No se pudo eliminar el comentario.');
                }
            })
            .catch(() => {
                if (window.$ && $('#deleteReviewConfirmModal').length) {
                    $('#deleteReviewConfirmModal').modal('hide');
                }
                deleteForm.submit();
            });
        });
    }
});
