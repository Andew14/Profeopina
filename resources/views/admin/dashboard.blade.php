@extends('layouts.admin_base')

@section('title', __('messages.admin_dashboard_title'))

@section('content')
    <div class="admin-dashboard">
        <h1>{{ __('messages.admin_dashboard_welcome') }}</h1>
        <p>{{ __('messages.admin') }}: {{ auth()->user()->name }} ({{ auth()->user()->institution->name ?? 'N/A' }})</p>

        <div class="admin-quick-actions">
            <h2>{{ __('messages.quick_actions') }}</h2>
            <div class="actions-grid">
                <a href="{{ route('admin.profesors.create') }}" class="action-card">
                    <div class="action-title">{{ __('messages.add_profesor') }}</div>
                    <div class="action-description">{{ __('messages.add_profesor_desc') }}</div>
                </a>
                <a href="{{ route('admin.profesors.index') }}" class="action-card">
                    <div class="action-title">{{ __('messages.manage_profesors') }}</div>
                    <div class="action-description">{{ __('messages.view_manage_profesors') }}</div>
                </a>
                <a href="{{ route('admin.resenias.index') }}" class="action-card">
                    <div class="action-title">{{ __('messages.manage_resenias') }}</div>
                    <div class="action-description">{{ __('messages.view_manage_resenias') }}</div>
                </a>
            </div>
        </div>
    </div>
@endsection