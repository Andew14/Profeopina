<aside class="admin-sidebar">
    <div class="admin-sidebar-header">
        <h3>{{ __('messages.admin_panel') }}</h3>
    </div>
    <nav class="admin-sidebar-nav">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">
            {{ __('messages.home') }}
        </a>
        <a href="{{ route('admin.profesors.index') }}" class="nav-link">
            {{ __('messages.manage_profesors') }}
        </a>
        <a href="{{ route('admin.resenias.index') }}" class="nav-link">
            {{ __('messages.manage_resenias') }}
        </a>
    </nav>
    <nav class="admin-sidebar-footer">
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-admin').submit();" class="nav-link logout-link">
            {{ __('messages.logout') }}
        </a>
        <form id="logout-form-admin" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </nav>
</aside>