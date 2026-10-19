<header class="admin-header">
    <div class="admin-header-left">
        <div class="admin-logo">
            <a href="{{ route('admin.dashboard') }}" title="Ir al Panel de Administración" style="display: inline-block;">
                <img src="/logos/Logo_title_alt3.svg" alt="profeopina" style="cursor: pointer;">
            </a>
        </div>
    </div>

    <nav class="admin-header-nav">
        <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            {{ __('messages.home') }}
        </a>
        <a href="{{ route('admin.profesors.index') }}" class="admin-nav-link {{ request()->routeIs('admin.profesors.*') ? 'active' : '' }}">
            {{ __('messages.manage_profesors') }}
        </a>
        <a href="{{ route('admin.resenias.index') }}" class="admin-nav-link {{ request()->routeIs('admin.resenias.*') ? 'active' : '' }}">
            {{ __('messages.manage_resenias') }}
        </a>
        <a href="{{ route('admin.courses.index') }}" class="admin-nav-link {{ request()->routeIs('admin.courses.*') ? 'active' : '' }}">
            Cursos
        </a>
        <a href="{{ route('admin.periods.index') }}" class="admin-nav-link {{ request()->routeIs('admin.periods.*') ? 'active' : '' }}">
            Semestres Académicos
        </a>
    </nav>

    <div class="admin-header-right">
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