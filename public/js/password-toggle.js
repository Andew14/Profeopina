/**
 * Alternar visibilidad de contraseña al hacer clic en el icono del ojo.
 * - Ojo cerrado (fa-eye-slash): contraseña oculta (type="password").
 * - Ojo abierto (fa-eye): contraseña visible (type="text").
 * - Activación mediante un simple clic (toggle persistente hasta nuevo clic).
 */
document.addEventListener('DOMContentLoaded', function () {
    initPasswordToggles();
});

function initPasswordToggles() {
    document.querySelectorAll('.toggle-password-btn').forEach(function (button) {
        if (button.dataset.toggleInitialized) return;
        button.dataset.toggleInitialized = 'true';

        button.addEventListener('click', function (e) {
            e.preventDefault();
            const wrapper = button.closest('.password-wrapper');
            if (!wrapper) return;

            const input = wrapper.querySelector('input');
            if (!input) return;

            const icon = button.querySelector('i');
            const isPassword = input.type === 'password';

            if (isPassword) {
                input.type = 'text';
                button.setAttribute('aria-label', 'Ocultar contraseña');
                button.title = 'Ocultar contraseña';
                if (icon) {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            } else {
                input.type = 'password';
                button.setAttribute('aria-label', 'Mostrar contraseña');
                button.title = 'Mostrar contraseña';
                if (icon) {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            }

            input.focus();
        });
    });
}

window.initPasswordToggles = initPasswordToggles;
