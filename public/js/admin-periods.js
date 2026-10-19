/**
 * Control del Modal de Confirmación para Semestres Académicos.
 */
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('deleteSemesterModal');
    const modalSemesterName = document.getElementById('modalSemesterName');
    const modalReviewsNotice = document.getElementById('modalReviewsNotice');
    const modalReviewsCount = document.getElementById('modalReviewsCount');
    const deleteSemesterForm = document.getElementById('deleteSemesterForm');
    const btnCancel = document.getElementById('btnCancelDeleteModal');

    if (!modal) return;

    document.querySelectorAll('.js-open-delete-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const reviewsCount = parseInt(this.getAttribute('data-reviews-count') || '0', 10);

            if (modalSemesterName) modalSemesterName.textContent = name;
            if (deleteSemesterForm) deleteSemesterForm.action = `/admin/periods/${id}`;

            if (modalReviewsNotice && modalReviewsCount) {
                if (reviewsCount > 0) {
                    modalReviewsCount.textContent = reviewsCount;
                    modalReviewsNotice.style.display = 'block';
                } else {
                    modalReviewsNotice.style.display = 'none';
                }
            }

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
