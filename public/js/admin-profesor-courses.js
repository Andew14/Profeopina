/**
 * Gestión interactiva de Cursos en los formularios de Crear y Editar Profesor.
 */
document.addEventListener('DOMContentLoaded', function() {
    function normalizeText(str) {
        return (str || '').normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase();
    }

    const searchInput = document.getElementById('courseSearchInput');
    const courseItems = document.querySelectorAll('.course-item');
    const pillsContainer = document.getElementById('selectedCoursesPills');
    const countSpan = document.getElementById('selectedCount');
    const checkboxes = document.querySelectorAll('.js-course-checkbox, .course-item input[type="checkbox"]');

    // Búsqueda en tiempo real de cursos
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const term = normalizeText(this.value.trim());
            courseItems.forEach(item => {
                const name = normalizeText(item.getAttribute('data-name') || item.textContent);
                if (name.includes(term)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    // Actualización de píldoras / chips seleccionados
    function updateSelectedPills() {
        if (!pillsContainer) return;

        let selected = [];
        checkboxes.forEach(cb => {
            if (cb.checked) {
                const title = cb.getAttribute('data-title') || cb.parentElement?.textContent?.trim();
                selected.push({ id: cb.value, title: title });
            }
        });

        if (countSpan) countSpan.textContent = selected.length;

        if (selected.length === 0) {
            pillsContainer.innerHTML = '<span style="font-size: 0.85em; color: #94a3b8; font-style: italic;">No hay cursos seleccionados.</span>';
            return;
        }

        pillsContainer.innerHTML = '';
        selected.forEach(item => {
            const pill = document.createElement('span');
            pill.style.cssText = 'display: inline-flex; align-items: center; gap: 6px; background: #3b82f6; color: white; padding: 4px 10px; border-radius: 16px; font-size: 0.82em; font-weight: 500; box-shadow: 0 1px 2px rgba(0,0,0,0.05);';
            pill.innerHTML = `📘 ${item.title} <span class="js-remove-pill" data-id="${item.id}" style="cursor: pointer; font-weight: bold; background: rgba(255,255,255,0.3); border-radius: 50%; width: 16px; height: 16px; display: inline-flex; align-items: center; justify-content: center; font-size: 11px;">✕</span>`;
            pillsContainer.appendChild(pill);
        });

        pillsContainer.querySelectorAll('.js-remove-pill').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const targetCb = document.querySelector(`.js-course-checkbox[value="${id}"], .course-item input[value="${id}"]`);
                if (targetCb) {
                    targetCb.checked = false;
                    updateSelectedPills();
                }
            });
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateSelectedPills);
    });

    // Inicializar píldoras al cargar la página
    updateSelectedPills();
});
