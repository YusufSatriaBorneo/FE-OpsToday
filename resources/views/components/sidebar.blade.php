@auth
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin.index') }}" class="app-brand-link">
            <span class="app-brand-logo demo me-1">
                <img src="https://iconape.com/wp-content/png_logo_vector/pt-kaltim-prima-coal-logo.png" alt="Logo" width="70" height="44">
            </span>

        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item {{ Request::routeIs('admin.index') ? 'active open' : '' }}">
            <a href="{{ route('admin.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ri-home-smile-line"></i>
                <div data-i18n="Dashboards">Dashboard</div>
            </a>
        </li>

        <!-- Manajemen Engineer 
        <li class="menu-item {{ Request::routeIs('admin.engineers') ? 'active open' : '' }}">
            <a href="{{ route('admin.engineers') }}" class="menu-link">
                <i class="menu-icon tf-icons ri-user-settings-line"></i>
                <div data-i18n="Manajemen Engineer">Manajemen Engineer</div>
            </a>
        </li>
        -->
        <!-- Manajemen User -->
        <li class="menu-item {{ Request::routeIs('admin.users') ? 'active open' : '' }}">
            <a href="{{ route('admin.users') }}" class="menu-link">
                <i class="menu-icon tf-icons ri-user-line"></i>
                <div data-i18n="Manajemen User">Manajemen User</div>
            </a>
        </li>


        <li class="menu-item {{ Request::routeIs('admin.tickets') ? 'active open' : '' }}">
            <a href="{{ route('admin.tickets') }}" class="menu-link">
                <i class="menu-icon tf-icons ri-ticket-line"></i>
                <div data-i18n="Manajemen Tiket">Manajemen Tiket</div>
            </a>
        </li>

        <li class="menu-item {{ Request::routeIs('admin.engineer.leaves') ? 'active open' : '' }}">
            <a href="{{ route('admin.engineer.leaves') }}" class="menu-link">
                <i class="menu-icon tf-icons ri-calendar-event-line"></i>
                <div data-i18n="Logout">Cuti Engineer</div>
            </a>
        </li>
        
        <li class="menu-item {{ Request::routeIs('admin.engineer.onprogress') ? 'active open' : '' }}">
            <a href="{{ route('admin.engineer.onprogress') }}" class="menu-link">
                <i class="menu-icon tf-icons ri-timer-flash-line"></i>
                <div data-i18n="On Progress Ticket">On Progress Ticket</div>
            </a>
        </li>

        <!-- Logout -->
        <li class="menu-item">
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <a href="{{ route('admin.logout') }}" class="menu-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="menu-icon tf-icons ri-logout-box-line"></i>
                <div data-i18n="Logout">Logout</div>
            </a>
        </li>
    </ul>
</aside>
@endauth