<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('admin.dashboard') }}" class="brand-link d-flex align-items-center">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="brand-image me-2" style="height:28px;width:auto;object-fit:contain;" />
            <span class="brand-text fw-light">Admin</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.tenants.index') }}" class="nav-link {{ request()->routeIs('admin.tenants.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-people"></i>
                        <p>Tenants</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>


