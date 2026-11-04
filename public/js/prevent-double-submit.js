/**
 * Previene envíos múltiples accidentales (doble o triple clic rápido)
 * en formularios de autenticación y registro.
 */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form.login-form').forEach(function (form) {
        form.addEventListener('submit', function () {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.disabled) {
                setTimeout(function () {
                    submitBtn.disabled = true;
                    submitBtn.style.opacity = '0.65';
                    submitBtn.style.cursor = 'wait';
                }, 30);
            }
        });
    });
});
