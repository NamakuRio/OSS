<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="@route('dashboard')">P</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="@route('dashboard')">P</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Menu</li>
            <li class="{{ Request::route()->getName() == 'dashboard' ? 'active' : '' }}"><a class="nav-link" href="@route('dashboard')"><i class="fas fa-fire"></i><span>Beranda</span></a></li>
            <li class="menu-header">Lainnya</li>
            <li class="{{ Request::route()->getName() == 'account' ? 'active' : '' }}"><a class="nav-link" href="@route('account')"><i class="far fa-user"></i> <span>Akun</span></a></li>
            @can('user.view')
                <li class="{{ Request::route()->getName() == 'users' ? 'active' : '' }}"><a class="nav-link" href="@route('users')"><i class="fas fa-users-cog"></i> <span>Pengguna</span></a></li>
            @endcan
            @can('role.view')
                <li class="{{ Request::route()->getName() == 'roles' ? 'active' : '' }}"><a class="nav-link" href="@route('roles')"><i class="fas fa-user-shield"></i> <span>Peran</span></a></li>
            @endcan
            @can('permission.view')
                <li class="{{ Request::route()->getName() == 'permissions' ? 'active' : '' }}"><a class="nav-link" href="@route('permissions')"><i class="fas fa-key"></i> <span>Izin</span></a></li>
            @endcan
        </ul>
    </aside>
</div>
