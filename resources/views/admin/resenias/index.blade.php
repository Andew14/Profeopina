@extends('layouts.admin_base')

@section('title', __('messages.moderate_reviews'))

@section('content')
<div class="admin-container">
    <div class="admin-actions-bar">
        <h1>{{ __('messages.manage_resenias') }}</h1>
    </div>

    @if ($errors->any())
        <div class="admin-alert admin-alert-error">
            <strong>{{ __('messages.error') }}:</strong>
            <ul style="margin: 5px 0 0 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="admin-alert admin-alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($resenias->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('messages.teacher') }}</th>
                    <th>{{ __('messages.content') }}</th>
                    <th style="text-align: center;">{{ __('messages.rating') }}</th>
                    <th style="text-align: center;">{{ __('messages.hidden') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resenias as $res)
                    <tr id="res-{{ $res->id }}">
                        <td>
                            <strong>{{ $res->profesor->nombre }} {{ $res->profesor->apellido }}</strong>
                            <br>
                            <small style="color: #999;">{{ $res->profesor->especialidad ?? '-' }}</small>
                        </td>
                        <td>
                            <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $res->contenido }}
                            </div>
                        </td>
                        <td style="text-align: center;">
                            <span class="badge" style="background: #fff3cd; color: #856404; padding: 6px 10px;">
                                ⭐ {{ $res->calificacion }}/5
                            </span>
                        </td>
                        <td class="oculto" style="text-align: center;">
                            <span class="badge" style="background: {{ $res->oculto ? '#f8d7da' : '#d4edda' }}; color: {{ $res->oculto ? '#721c24' : '#155724' }}; padding: 6px 10px;">
                                {{ $res->oculto ? '🚫 ' . __('messages.yes') : '✅ ' . __('messages.no') }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn js-toggle-hidden btn-sm" data-id="{{ $res->id }}" title="Cambiar estado de visibilidad">
                                    {{ $res->oculto ? '👁️ Mostrar' : '🚫 Ocultar' }}
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="admin-alert admin-alert-info">
            {{ __('messages.no_data_found') }}
        </div>
    @endif
</div>

<script>
document.querySelectorAll('.js-toggle-hidden').forEach(btn => {
    btn.addEventListener('click', async () => {
        const id = btn.dataset.id;
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                      document.querySelector('input[name="_token"]')?.value;
        
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
                const ocultoBadge = row.querySelector('.oculto span');
                ocultoBadge.textContent = data.oculto ? '🚫 ' + '{{ __('messages.yes') }}' : '✅ ' + '{{ __('messages.no') }}';
                ocultoBadge.style.background = data.oculto ? '#f8d7da' : '#d4edda';
                ocultoBadge.style.color = data.oculto ? '#721c24' : '#155724';
                
                btn.textContent = data.oculto ? '👁️ Mostrar' : '🚫 Ocultar';
                
                // Show success feedback
                const alert = document.createElement('div');
                alert.className = 'admin-alert admin-alert-success';
                alert.style.position = 'fixed';
                alert.style.top = '20px';
                alert.style.right = '20px';
                alert.style.zIndex = '1000';
                alert.textContent = data.oculto ? 'Reseña ocultada' : 'Reseña mostrada';
                document.body.appendChild(alert);
                setTimeout(() => alert.remove(), 3000);
            } else if (res.status === 403) {
                alert('{{ __('messages.unauthorized') }}');
            } else {
                alert('Error al cambiar estado');
            }
        } catch (err) {
            console.error('Error:', err);
            alert('Error al cambiar estado');
        }
    });
});
</script>
@endsection