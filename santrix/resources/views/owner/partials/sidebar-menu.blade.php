<li class="sidebar-menu-item">
    <a href="{{ route('owner.dashboard') }}" class="sidebar-menu-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
        <i data-feather="home" style="width: 20px; height: 20px; vertical-align: text-bottom; margin-right: 8px;"></i>
        Dashboard
    </a>
</li>

<li class="sidebar-menu-header" style="padding: 16px 24px 8px; font-size: 0.75rem; font-weight: 700; color: #718096; text-transform: uppercase; letter-spacing: 0.05em;">
    Management
</li>

<li class="sidebar-menu-item">
    <a href="{{ route('owner.pesantren.index') }}" class="sidebar-menu-link {{ request()->routeIs('owner.pesantren.*') ? 'active' : '' }}">
        <i data-feather="grid" style="width: 20px; height: 20px; vertical-align: text-bottom; margin-right: 8px;"></i>
        Data Pesantren
    </a>
</li>

<li class="sidebar-menu-item">
    <a href="{{ route('owner.packages.index') }}" class="sidebar-menu-link {{ request()->routeIs('owner.packages.*') ? 'active' : '' }}">
        <i data-feather="package" style="width: 20px; height: 20px; vertical-align: text-bottom; margin-right: 8px;"></i>
        Manajemen Paket
    </a>
</li>

<li class="sidebar-menu-item">
    <a href="{{ route('owner.withdrawal.index') }}" class="sidebar-menu-link {{ request()->routeIs('owner.withdrawal.*') ? 'active' : '' }}">
        <i data-feather="dollar-sign" style="width: 20px; height: 20px; vertical-align: text-bottom; margin-right: 8px;"></i>
        Pencairan Dana
    </a>
</li>

<li class="sidebar-menu-item">
    <a href="{{ route('owner.logs') }}" class="sidebar-menu-link {{ request()->routeIs('owner.logs') ? 'active' : '' }}">
        <i data-feather="activity" style="width: 20px; height: 20px; vertical-align: text-bottom; margin-right: 8px;"></i>
        Activity Logs
    </a>
</li>


