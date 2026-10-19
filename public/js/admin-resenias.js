/**
 * Gestión Asíncrona de Moderación de Reseñas para el Panel de Administración.
 */
document.addEventListener('DOMContentLoaded', function() {
    function showAdminAlert(message, type = 'success') {
        const alert = document.createElement('div');
        alert.className = `admin-alert admin-alert-${type}`;
        alert.style.position = 'fixed';
        alert.style.top = '20px';
        alert.style.right = '20px';
        alert.style.zIndex = '100000';
        alert.style.boxShadow = '0 10px 15px -3px rgba(0,0,0,0.1)';
        alert.textContent = message;
        document.body.appendChild(alert);
        setTimeout(() => alert.remove(), 3000);
    }

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
               document.querySelector('input[name="_token"]')?.value;
    }

    // Toggle Visible / Oculto
    document.querySelectorAll('.js-toggle-hidden').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            const token = getCsrfToken();
            
            try {
                const res = await fetch(`/admin/resenias/${id}/toggle-hidden`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({})
                });
                
                if (res.ok) {
                    const data = await res.json();
                    const row = document.getElementById(`res-${id}`);
                    const ocultoBadge = row?.querySelector('.js-oculto-badge');
                    if (ocultoBadge) {
                        ocultoBadge.textContent = data.oculto ? '🚫 Oculto' : '✅ Visible';
                        ocultoBadge.style.background = data.oculto ? '#f8d7da' : '#d4edda';
                        ocultoBadge.style.color = data.oculto ? '#721c24' : '#155724';
                    }
                    
                    btn.textContent = data.oculto ? '👁️ Mostrar' : '🚫 Ocultar';
                    showAdminAlert(data.oculto ? 'Reseña ocultada (mantiene calificación)' : 'Reseña visible públicamente');
                } else if (res.status === 403) {
                    alert('No tienes autorización para realizar esta acción');
                } else {
                    alert('Error al cambiar estado');
                }
            } catch (err) {
                console.error('Error:', err);
                alert('Error al cambiar estado');
            }
        });
    });

    // Desestimar denuncia
    document.querySelectorAll('.js-dismiss-report').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            const token = getCsrfToken();
            
            try {
                const res = await fetch(`/admin/resenias/${id}/dismiss-report`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({})
                });
                
                if (res.ok) {
                    const row = document.getElementById(`res-${id}`);
                    const badge = row?.querySelector('.js-reported-badge');
                    if (badge) badge.remove();
                    btn.remove();
                    if (row) row.style.backgroundColor = '';
                    
                    showAdminAlert('Denuncia desestimada');
                } else if (res.status === 403) {
                    alert('No tienes autorización para realizar esta acción');
                } else {
                    alert('Error al desestimar denuncia');
                }
            } catch (err) {
                console.error('Error:', err);
                alert('Error al desestimar denuncia');
            }
        });
    });

    // Eliminar reseña
    document.querySelectorAll('.js-delete-resenia').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            if (!confirm('¿Estás seguro de que deseas eliminar permanentemente esta reseña? Esta acción eliminará el comentario y descontará su calificación del promedio del docente.')) {
                return;
            }
            const token = getCsrfToken();
            
            try {
                const res = await fetch(`/admin/resenias/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                });
                
                if (res.ok) {
                    const row = document.getElementById(`res-${id}`);
                    if (row) row.remove();
                    showAdminAlert('Reseña eliminada permanentemente del sistema');
                } else {
                    alert('Error al eliminar la reseña');
                }
            } catch (err) {
                console.error('Error:', err);
                alert('Error al eliminar la reseña');
            }
        });
    });
});
