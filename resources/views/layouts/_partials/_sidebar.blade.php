<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="@route('dashboard')">OSS</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="@route('dashboard')">OSS</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Menu</li>
            <li class="{{ Request::route()->getName() == 'dashboard' ? 'active' : '' }}"><a class="nav-link" href="@route('dashboard')"><i class="fas fa-fire"></i><span>Beranda</span></a></li>
            @can('customer.view')
                <li class="{{ Request::route()->getName() == 'customers' ? 'active' : '' }}"><a class="nav-link" href="@route('customers')"><i class="fas fa-users"></i><span>Pelanggan</span></a></li>
            @endcan
            @can('order.view')
                <li class="{{ Request::route()->getName() == 'orders' ? 'active' : '' }}"><a class="nav-link" href="@route('orders')"><i class="far fa-list-alt"></i><span>Servis</span></a></li>
            @endcan
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
