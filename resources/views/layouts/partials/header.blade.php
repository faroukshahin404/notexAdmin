<nav class="app-header navbar navbar-expand bg-body">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-block"><a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a></li>
        </ul>
        <ul class="navbar-nav ms-auto">
            @auth
            <li class="nav-item">
                <a href="{{ route('admin.password') }}" class="nav-link">Change Password</a>
            </li>
            <li class="nav-item">
                <form method="post" action="{{ route('admin.logout') }}" class="d-inline">
                    @csrf
                    <button class="btn btn-outline-secondary btn-sm">Logout</button>
                </form>
            </li>
            @endauth
        </ul>
    </div>
</nav>


