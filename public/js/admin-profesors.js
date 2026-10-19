/**
 * Control del Modal de Confirmación para Eliminación de Profesores.
 */
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('deleteProfModal');
    const modalProfName = document.getElementById('modalProfName');
    const deleteProfForm = document.getElementById('deleteProfForm');
    const btnCancel = document.getElementById('btnCancelDeleteProfModal');

    if (!modal) return;

    document.querySelectorAll('.js-open-delete-prof-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');

            if (modalProfName) modalProfName.textContent = name;
            if (deleteProfForm) deleteProfForm.action = `/admin/profesors/${id}`;
            modal.style.display = 'flex';
        });
    });

    if (btnCancel) {
        btnCancel.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    }

    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
});
