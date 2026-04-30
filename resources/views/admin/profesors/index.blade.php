@extends('layouts.admin_base')

@section('title', __('messages.manage_profesors'))

@section('content')
<div class="admin-container">
    <div class="admin-actions-bar">
        <h1>{{ __('messages.manage_profesors') }}</h1>
        <div class="admin-actions-right">
            <a href="{{ route('admin.profesors.create') }}" class="btn-continue">+ {{ __('messages.add_profesor') }}</a>
        </div>
    </div>

    @if ($errors->any())
        <div class="admin-alert admin-alert-error">
            {{ $errors->first() }}
        </div>
    @endif

    @if (session('success'))
        <div class="admin-alert admin-alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($profesors->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('messages.name') }}</th>
                    <th>{{ __('messages.lastname') }}</th>
                    <th>Descripción</th>
                    <th style="text-align: center;">Institución</th>
                    <th style="text-align: center;">Estado</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($profesors as $profesor)
                    <tr id="prof-{{ $profesor->id }}">
                        <td>{{ $profesor->nombre }}</td>
                        <td>{{ $profesor->apellido }}</td>
                        <td>{{ Str::limit($profesor->descripcion, 50) ?? '-' }}</td>
                        <td style="text-align: center;">
                            {{ $profesor->institution?->nombre ?? '-' }}
                        </td>
                        <td class="activo" style="text-align: center;">
                            <span class="badge {{ $profesor->activo ? 'badge-success' : 'badge-secondary' }}">
                                {{ $profesor->activo ? __('messages.yes') : __('messages.no') }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.profesors.edit', $profesor) }}" class="btn btn-edit btn-sm">✏️ {{ __('messages.edit') }}</a>
                                @if(auth()->user()->can('viewAny', 'App\Models\Resenia'))
                                    <a href="{{ route('profesor.resenias', $profesor->id) }}" class="btn btn-primary btn-sm">📝 {{ __('messages.view_reviews') }}</a>
                                @endif
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

<style>
    .badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .badge-success {
        background: #d4edda;
        color: #155724;
    }
    
    .badge-secondary {
        background: #e2e3e5;
        color: #383d41;
    }
</style>

<script>
// Script removido - ya no hay botón toggle en la tabla
</script>
@endsection