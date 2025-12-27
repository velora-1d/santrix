@if(auth()->check() && strtolower(auth()->user()->role ?? '') === 'admin')
<li class="sidebar-menu-item" style="margin-bottom: 12px;">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-link" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border-radius: 8px;">
        <i data-feather="arrow-left" class="sidebar-menu-icon" style="color: white;"></i>
        <span>Kembali ke Admin</span>
    </a>
</li>
@endif
<li class="sidebar-menu-item">
    <a href="{{ route('sekretaris.dashboard') }}" class="sidebar-menu-link {{ request()->routeIs('sekretaris.dashboard') ? 'active' : '' }}">
        <i data-feather="home" class="sidebar-menu-icon"></i>
        <span>Dashboard</span>
    </a>
</li>
<li class="sidebar-menu-item">
    <a href="{{ route('sekretaris.data-santri') }}" class="sidebar-menu-link {{ request()->routeIs('sekretaris.data-santri*') ? 'active' : '' }}">
        <i data-feather="users" class="sidebar-menu-icon"></i>
        <span>Data Santri</span>
    </a>
</li>
<li class="sidebar-menu-item">
    <a href="{{ route('sekretaris.mutasi-santri') }}" class="sidebar-menu-link {{ request()->routeIs('sekretaris.mutasi-santri*') ? 'active' : '' }}">
        <i data-feather="repeat" class="sidebar-menu-icon"></i>
        <span>Mutasi Santri</span>
    </a>
</li>
<li class="sidebar-menu-item">
    <a href="{{ route('sekretaris.kenaikan-kelas') }}" class="sidebar-menu-link {{ request()->routeIs('sekretaris.kenaikan-kelas*') ? 'active' : '' }}">
        <i data-feather="trending-up" class="sidebar-menu-icon"></i>
        <span>Kenaikan Kelas</span>
    </a>
</li>
<li class="sidebar-menu-item">
    <a href="{{ route('sekretaris.perpindahan') }}" class="sidebar-menu-link {{ request()->routeIs('sekretaris.perpindahan*') ? 'active' : '' }}">
        <i data-feather="shuffle" class="sidebar-menu-icon"></i>
        <span>Perpindahan</span>
    </a>
</li>
<li class="sidebar-menu-item">
    <a href="{{ route('sekretaris.laporan') }}" class="sidebar-menu-link {{ request()->routeIs('sekretaris.laporan*') ? 'active' : '' }}">
        <i data-feather="file-text" class="sidebar-menu-icon"></i>
        <span>Laporan</span>
    </a>
</li>
<li class="sidebar-menu-item">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="sidebar-menu-link" style="width: 100%; background: none; border: none; cursor: pointer; text-align: left;">
            <i data-feather="log-out" class="sidebar-menu-icon"></i>
            <span>Logout</span>
        </button>
    </form>
</li>
