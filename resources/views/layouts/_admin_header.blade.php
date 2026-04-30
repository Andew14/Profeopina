<header class="admin-header">
    <div class="admin-header-left">
        <div class="admin-logo">
            <img src="/logos/Logo_title_alt3.svg" alt="profeopina">
        </div>
    </div>

    <nav class="admin-header-nav">
        <a href="{{ route('admin.dashboard') }}" class="admin-nav-link">
            {{ __('messages.home') }}
        </a>
        <a href="{{ route('admin.profesors.index') }}" class="admin-nav-link">
            {{ __('messages.manage_profesors') }}
        </a>
        <a href="{{ route('admin.resenias.index') }}" class="admin-nav-link">
            {{ __('messages.manage_resenias') }}
        </a>
    </nav>

    <div class="admin-header-right">
        <div class="admin-lang-selector">
            <a href="{{ route('locale.change', ['locale' => 'en']) }}" class="lang-link">
                <img src="/imagenes/en.png" alt="English">
            </a>
            <a href="{{ route('locale.change', ['locale' => 'es']) }}" class="lang-link">
                <img src="/imagenes/es.png" alt="Español">
            </a>
        </div>
        <div class="admin-user-menu">
            <span class="admin-user-name">{{ auth()->user()->name }}</span>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-admin-header').submit();" class="admin-logout-btn">
                {{ __('messages.logout') }}
            </a>
            <form id="logout-form-admin-header" action="{{ route('logout.admin') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</header>