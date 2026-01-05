<li class="sidebar-menu-item">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i data-feather="home" class="sidebar-menu-icon"></i>
        <span>Dashboard</span>
    </a>
</li>
<li class="sidebar-menu-item">
    <a href="{{ route('admin.activity-log') }}" class="sidebar-menu-link {{ request()->routeIs('admin.activity-log*') ? 'active' : '' }}">
        <i data-feather="activity" class="sidebar-menu-icon"></i>
        <span>Riwayat Aktivitas</span>
    </a>
</li>
<li class="sidebar-menu-item">
    <a href="{{ route('admin.pengaturan') }}" class="sidebar-menu-link {{ request()->routeIs('admin.pengaturan*') ? 'active' : '' }}">
        <i data-feather="settings" class="sidebar-menu-icon"></i>
        <span>Pengaturan</span>
    </a>
</li>
<li class="sidebar-menu-item">
    <a href="{{ route('admin.billing.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.billing*') ? 'active' : '' }}">
        <i data-feather="credit-card" class="sidebar-menu-icon"></i>
        <span>Billing & Paket</span>
    </a>
</li>
<li class="sidebar-menu-item">
    <a href="{{ route('admin.withdrawal.index') }}" class="sidebar-menu-link {{ request()->routeIs('admin.withdrawal*') ? 'active' : '' }}">
        <i data-feather="download" class="sidebar-menu-icon"></i>
        <span>Withdrawal Saldo</span>
    </a>
</li>
<li class="sidebar-menu-item">
    <a href="{{ route('admin.branding') }}" class="sidebar-menu-link {{ request()->routeIs('admin.branding*') ? 'active' : '' }}">
        <i data-feather="image" class="sidebar-menu-icon"></i>
        <span>Branding</span>
    </a>
</li>

<li class="sidebar-menu-item" style="margin-top: 16px; padding-top: 16px; border-top: 1px solid rgba(255,255,255,0.05);">
    <span style="font-size: 10px; text-transform: uppercase; letter-spacing: 1.5px; color: #64748b; padding: 0 16px; margin-bottom: 8px; display: block;">Akses Modul</span>
</li>
<li class="sidebar-menu-item">
    <a href="{{ route('sekretaris.dashboard') }}" class="sidebar-menu-link">
        <i data-feather="users" class="sidebar-menu-icon"></i>
        <span>Sekretaris</span>
    </a>
</li>
<li class="sidebar-menu-item">
    <a href="{{ route('bendahara.dashboard') }}" class="sidebar-menu-link">
        <i data-feather="dollar-sign" class="sidebar-menu-icon"></i>
        <span>Bendahara</span>
    </a>
</li>
<li class="sidebar-menu-item">
    <a href="{{ route('pendidikan.dashboard') }}" class="sidebar-menu-link">
        <i data-feather="book-open" class="sidebar-menu-icon"></i>
        <span>Pendidikan</span>
    </a>
</li>

<li class="sidebar-menu-item" style="margin-top: 24px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 16px;">
    <a href="{{ route('backup.download') }}" class="sidebar-menu-link">
        <i data-feather="database" class="sidebar-menu-icon"></i>
        <span>Backup Database</span>
    </a>
</li>

<li class="sidebar-menu-item">
    <form method="POST" action="{{ route('tenant.logout') }}" style="margin: 0;">
        @csrf
        <button type="submit" class="sidebar-menu-link" style="width: 100%; background: none; border: none; cursor: pointer; text-align: left; padding: 12px 16px;">
            <i data-feather="log-out" class="sidebar-menu-icon"></i>
            <span>Logout</span>
        </button>
    </form>
</li>
