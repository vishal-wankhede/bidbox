@php
$currentRouteName = Route::currentRouteName();
@endphp

<style>
    .layout-menu-horizontal .menu-horizontal {
        box-shadow: 0 0.625rem 0.875rem rgba(76, 78, 100, 0.1);
        background-color: #191919;
    }

    .active {
        background-color: color-mix(in oklab, var(#fff) 5%, transparent);
    }

    .menu-item {
        color: #fff;
    }
</style>

<aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu bg-menu-theme flex-grow-0 border-end">
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