@php
$configData = Helper::appClasses();
@endphp

<style>
    /* Target the entire sidebar */
    aside#layout-menu.theme {
        box-shadow: 0 0.625rem 0.875rem rgba(76, 78, 100, 0.1) !important;
        background-color: #191919 !important;
    }

    /* Target ALL text inside menu items */
    #layout-menu .menu-item *,
    #layout-menu .menu-item a {
        color: #fff !important;
    }

    /* Override background of active items */
    #layout-menu .menu-item.active>.menu-link,
    #layout-menu .menu-item.active.open>.menu-link,
    #layout-menu .menu-item.active.open .menu-sub .menu-item.active>.menu-link {
        background-color: color-mix(in oklab, #ffffff 5%, transparent) !important;
    }

    /* Optional: remove any external background */
    #layout-menu .menu-sub {
        background: transparent !important;
    }
</style>



<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme theme">

    <!-- ! Hide app brand if navbar-full -->
    @if (!isset($navbarFull))
    <div class="app-brand demo">
        <a href="{{ url('/') }}" class="app-brand-link">
            <span class="app-brand-logo demo" style="padding-left: .5rem;">@include('_partials.macros', ['width' => 25, 'withbg' => 'var(--bs-primary)'])</span>
            <!-- <span class="app-brand-text demo menu-text fw-bold ms-2">{{ config('variables.templateName') }}</span> -->
            <span class="app-brand-text demo menu-text fw-bold ms-2"><img src="{{asset('public\assets\img\app_logo\app_name.png')}}" alt="" width="150" /></span>

        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M11.4854 4.88844C11.0081 4.41121 10.2344 4.41121 9.75715 4.88844L4.51028 10.1353C4.03297 10.6126 4.03297 11.3865 4.51028 11.8638L9.75715 17.1107C10.2344 17.5879 11.0081 17.5879 11.4854 17.1107C11.9626 16.6334 11.9626 15.8597 11.4854 15.3824L7.96672 11.8638C7.48942 11.3865 7.48942 10.6126 7.96672 10.1353L11.4854 6.61667C11.9626 6.13943 11.9626 5.36568 11.4854 4.88844Z"
                    fill="currentColor" fill-opacity="0.6" />
                <path
                    d="M15.8683 4.88844L10.6214 10.1353C10.1441 10.6126 10.1441 11.3865 10.6214 11.8638L15.8683 17.1107C16.3455 17.5879 17.1192 17.5879 17.5965 17.1107C18.0737 16.6334 18.0737 15.8597 17.5965 15.3824L14.0778 11.8638C13.6005 11.3865 13.6005 10.6126 14.0778 10.1353L17.5965 6.61667C18.0737 6.13943 18.0737 5.36568 17.5965 4.88844C17.1192 4.41121 16.3455 4.41121 15.8683 4.88844Z"
                    fill="currentColor" fill-opacity="0.38" />
            </svg>
        </a>
    </div>
    @endif

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item ">
            <a href="{{ route('dashboard') }}" class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                <div>Dashboard</div>
            </a>
        </li>

        <!-- Users -->
        <li class="menu-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <a href="{{ route('users.index') }}" class="menu-link">
                <i class="menu-icon tf-icons mdi mdi-account-outline"></i>
                <div>Users</div>
            </a>
        </li>

        <!-- Campaigns -->
        <li class="menu-item {{ request()->routeIs('campaign.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle d-flex">
                <i class="menu-icon tf-icons mdi mdi-notebook-outline"></i>
                <div>Campaigns</div>
            </a>

            <ul class="menu-sub">

                <li class="menu-item {{ request()->routeIs('campaign.index') ? 'active' : '' }}">
                    <a href="{{ route('campaign.index') }}" class="menu-link">
                        <div>List</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('campaign.add') ? 'active' : '' }}">
                    <a href="{{ route('campaign.add') }}" class="menu-link">
                        <div>New</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->routeIs('campaign.getarchive') ? 'active' : '' }}">
                    <a href="{{ route('campaign.getarchive') }}" class="menu-link">
                        <div>Archived</div>
                    </a>
                </li>

            </ul>
        </li>


        <!-- Utilities -->
        <li class="menu-item {{ request()->routeIs('utilities.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle d-flex">
                <i class="menu-icon tf-icons mdi mdi-tools"></i>
                <div>Utilities</div>
            </a>

            <ul class="menu-sub">
                {{-- <li class="menu-item {{ request()->routeIs('utilities.demographics') ? 'active' : '' }}">
                <a href="{{ route('utilities.demographics') }}" class="menu-link">
                    <div>Demographics</div>
                </a>
        </li>

        <li class="menu-item {{ request()->routeIs('utilities.division') ? 'active' : '' }}">
            <a href="{{ route('utilities.division') }}" class="menu-link">
                <div>Divisions</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('utilities.cohorts') ? 'active' : '' }}">
            <a href="{{ route('utilities.cohorts') }}" class="menu-link">
                <div>Cohorts</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('utilities.inventories') ? 'active' : '' }}">
            <a href="{{ route('utilities.inventories') }}" class="menu-link">
                <div>Inventories</div>
            </a>
        </li> --}}

        <li class="menu-item {{ request()->routeIs('utilities.filters') ? 'active' : '' }}">
            <a href="{{ route('utilities.filters') }}" class="menu-link">
                <div>Filters</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('utilities.locations') ? 'active' : '' }}">
            <a href="{{ route('utilities.locations') }}" class="menu-link">
                <div>Locations</div>
            </a>
        </li>
    </ul>
    </li>



    <!-- Analytics -->
    <li class="menu-item {{ request()->routeIs('analytics.*') ? 'active' : '' }}">
        <a href="{{ route('analytics.index') }}" class="menu-link">
            <i class="menu-icon tf-icons mdi mdi-graph-outline"></i>
            <div>Analytics</div>
        </a>
    </li>
    </ul>

</aside>