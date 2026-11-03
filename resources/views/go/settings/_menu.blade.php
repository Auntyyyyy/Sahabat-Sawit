<nav class="settings-menu">
    <p class="settings-menu-title">PENGATURAN GA</p>
    <a href="{{ route('go.settings.divisions.index') }}" class="{{ request()->routeIs('go.settings.divisions.*') ? 'active' : '' }}">Master Divisi</a>
    <a href="{{ route('go.settings.dashboard') }}" class="{{ request()->routeIs('go.settings.dashboard*') ? 'active' : '' }}">Pengaturan Dashboard</a>
</nav>