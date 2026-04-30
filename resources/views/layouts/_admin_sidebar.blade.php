<div class="admin-sidebar">
    <a href="{{ route('admin.profesors.index') }}">{{ __('messages.admin_panel') }}</a>
    <a href="{{ route('admin.profesors.index') }}">{{ __('messages.manage_profesors') }}</a>
    <a href="{{ route('admin.resenias.index') }}">{{ __('messages.manage_resenias') }}</a>
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-admin').submit();">{{ __('messages.logout') }}</a>
    <form id="logout-form-admin" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>