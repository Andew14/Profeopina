/**
 * Gestión centralizada de notificaciones Toast, soporte bfcache (pageshow),
 * y reemplazo de estado del historial para prevenir repetición al presionar Atrás.
 */
(function() {
    try {
        localStorage.removeItem('language');
    } catch (e) {}

    function removeToastImmediately() {
        const container = document.getElementById('toast-container');
        if (container) {
            container.style.display = 'none';
            container.remove();
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const toastMsg = container.dataset.msg || container.innerText.trim();

        if (toastMsg && sessionStorage.getItem('shown_toast_msg') === toastMsg) {
            removeToastImmediately();
            return;
        }

        if (toastMsg) {
            sessionStorage.setItem('shown_toast_msg', toastMsg);
            if (window.history && window.history.replaceState) {
                window.history.replaceState(null, '', window.location.href);
            }
        }

        setTimeout(() => {
            if (container) {
                container.style.opacity = '0';
                container.style.transition = 'opacity 0.4s ease';
                setTimeout(() => removeToastImmediately(), 400);
            }
        }, 5000);
    });

    window.addEventListener('pageshow', function(event) {
        const isBackForward = event.persisted || 
            (window.performance && window.performance.getEntriesByType && 
             window.performance.getEntriesByType('navigation')[0] && 
             window.performance.getEntriesByType('navigation')[0].type === 'back_forward');

        if (isBackForward) {
            removeToastImmediately();
        }
    });

    window.addEventListener('popstate', function() {
        removeToastImmediately();
    });

    // Cerrar menú lateral al presionar la tecla Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const btnMenu = document.getElementById('btn-menu');
            if (btnMenu && btnMenu.checked) {
                btnMenu.checked = false;
            }
        }
    });
})();
