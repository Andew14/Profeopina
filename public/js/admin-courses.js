/**
 * Gestión de Cursos: Búsqueda interactiva y Modal de Confirmación de Eliminación.
 */
function normalizeText(str) {
    return (str || '').normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
}

function openDeleteCourseModal(actionUrl, courseName) {
    const form = document.getElementById('deleteCourseConfirmForm');
    const nameDisplay = document.getElementById('deleteCourseNameDisplay');
    const modal = document.getElementById('deleteCourseConfirmModal');

    if (form) form.action = actionUrl;
    if (nameDisplay) nameDisplay.textContent = '"' + courseName + '"';
    if (modal) modal.style.display = 'flex';
}

function closeDeleteCourseModal() {
    const modal = document.getElementById('deleteCourseConfirmModal');
    if (modal) modal.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
    // Filtro de búsqueda de cursos
    const searchInput = document.getElementById('adminCourseSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const term = normalizeText(this.value.trim());
            document.querySelectorAll('.course-row').forEach(row => {
                const name = normalizeText(row.getAttribute('data-name') || row.textContent);
                if (name.includes(term)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Cerrar modal al hacer clic en el backdrop
    const modal = document.getElementById('deleteCourseConfirmModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteCourseModal();
            }
        });
    }
});
