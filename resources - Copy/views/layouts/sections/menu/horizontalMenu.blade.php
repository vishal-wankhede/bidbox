@php
    $currentRouteName = Route::currentRouteName();
@endphp

<aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu bg-menu-theme flex-grow-0">
    <div class="container d-flex h-100">
        <ul class="menu-inner py-1">
            <!-- Dashboard -->
            <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="menu-link">
                    <i class='bx bx-home'></i>
                    <div>Dashboard</div>
                </a>
            </li>

            <!-- Users -->
            <li class="menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <a href="{{ route('users.index') }}" class="menu-link">
                    <i class='bx bx-user'></i>
                    <div>Users</div>
                </a>
            </li>

            <!-- Campaigns -->
            <li class="menu-item {{ request()->routeIs('campaigns.*') ? 'active' : '' }}">
                <a href="{{ route('campaigns.index') }}" class="menu-link">
                    <i class='bx bx-bullseye'></i>
                    <div>Campaigns</div>
                </a>
            </li>

            <!-- Utilities -->
            <li class="menu-item {{ request()->routeIs('utilities.*') ? 'active' : '' }}">
                <a href="#" class="menu-link">
                    <i class='bx bx-bullseye'></i>
                    <div>Utilities</div>
                </a>
            </li>

            <!-- Analytics -->
            <li class="menu-item {{ request()->routeIs('analytics.*') ? 'active' : '' }}">
                <a href="{{ route('analytics.index') }}" class="menu-link">
                    <i class='bx bx-bar-chart'></i>
                    <div>Analytics</div>
                </a>
            </li>
        </ul>
    </div>
</aside>
