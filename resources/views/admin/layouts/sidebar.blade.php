<!-- Settings Section -->
<div class="sidebar-heading">
    Settings
</div>

<!-- App Settings -->
<li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
        <i class="fas fa-cog"></i>
        <span>App Settings</span>
    </a>
</li> 