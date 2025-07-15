<!DOCTYPE html>
@php
$menuFixed =
$configData['layout'] === 'vertical'
? $menuFixed ?? ''
: ($configData['layout'] === 'front'
? ''
: $configData['headerType']);
$navbarType =
$configData['layout'] === 'vertical'
? $configData['navbarType']
: ($configData['layout'] === 'front'
? 'layout-navbar-fixed'
: '');
$isFront = ($isFront ?? '') == true ? 'Front' : '';
$contentLayout = isset($container) ? ($container === 'container-xxl' ? 'layout-compact' : 'layout-wide') : '';
@endphp

<html lang="{{ session()->get('locale') ?? app()->getLocale() }}"
    class="{{ $configData['style'] }}-style {{ $contentLayout ?? '' }} {{ $navbarType ?? '' }} {{ $menuFixed ?? '' }} {{ $menuCollapsed ?? '' }} {{ $menuFlipped ?? '' }} {{ $menuOffcanvas ?? '' }} {{ $footerFixed ?? '' }} {{ $customizerHidden ?? '' }}"
    dir="{{ $configData['textDirection'] }}" data-theme="{{ $configData['theme'] }}"
    data-assets-path="{{ asset('/assets') . '/' }}" data-base-url="{{ url('/') }}" data-framework="laravel"
    data-template="{{ $configData['layout'] . '-menu-' . $configData['theme'] . '-' . $configData['style'] }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@yield('title') |
        {{ config('variables.templateName') ? config('variables.templateName') : 'TemplateName' }} -
        {{ config('variables.templateSuffix') ? config('variables.templateSuffix') : 'TemplateSuffix' }}
    </title>
    <meta name="description"
        content="{{ config('variables.templateDescription') ? config('variables.templateDescription') : '' }}" />
    <meta name="keywords"
        content="{{ config('variables.templateKeyword') ? config('variables.templateKeyword') : '' }}">
    <!-- laravel CRUD token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Canonical SEO -->
    <link rel="canonical" href="{{ config('variables.productPage') ? config('variables.productPage') : '' }}">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Include Styles -->
    <!-- $isFront is used to append the front layout styles only on the front layout otherwise the variable will be blank -->
    @include('layouts/sections/styles' . $isFront)

    <!-- Include Scripts for customizer, helper, analytics, config -->
    <!-- $isFront is used to append the front layout scriptsIncludes only on the front layout otherwise the variable will be blank -->
    @include('layouts/sections/scriptsIncludes' . $isFront)

    <style>
        :root {
            --red-50: #fce9e9;
            --red-200: #f19a9a;
            --red-500: #e02424;

            --grey-100: #b8b8b8;
            --grey-300: #656565;
            --grey-400: #474747;
            --grey-500: #191919;

            --white-50: #fff;
            --white-500: #fafafa;

            --red-50-rgba1: rgba(241, 154, 154, 0.2);
            --red-50-rgba2: rgba(241, 154, 154, 0.3);
            --red-50-rgba3: rgba(241, 154, 154, 0.4);
            --red-50-rgba4: rgba(241, 154, 154, 0.5);
            --red-50-rgba5: rgba(241, 154, 154, 0);
        }

        body {
            background: var(--white-50);
            color: var(--grey-400);
        }

        .bg-body {
            background: var(--white-50) !important;
        }

        .layout-navbar-fixed .layout-page:not(.window-scrolled) .layout-navbar.navbar-detached {
            background: var(--white-50);
        }


        /* Menu */


        .menu-icon::before {
            font-size: 1rem;
        }


        /* Buttons */


        .btn-primary {
            color: var(--white-50);
            background-color: var(--grey-500);
            border-color: var(--grey-500);
        }

        .btn-primary:hover {
            color: var(--white-50);
            background-color: var(--grey-300) !important;
            border-color: var(--grey-300) !important;
        }

        .btn-outline-secondary {
            color: var(--grey-500);
            background-color: transparent;
            border-color: var(--grey-100) !important;
        }

        .btn-outline-secondary:hover {
            color: var(--white-50) !important;
            background-color: var(--grey-300) !important;
            border-color: var(--grey-300) !important;
        }

        .btn-secondary,
        .btn-outline-primary {
            color: var(--grey-500);
            background-color: var(--white-50);
            border-color: var(--grey-100) !important;
        }

        .btn-secondary:hover,
        .btn-outline-primary:hover {
            color: var(--red-500) !important;
            background-color: var(--red-50) !important;
            border-color: var(--red-500) !important;
        }

        .btn-check:focus+.btn-outline-primary,
        .btn-outline-primary:focus {
            color: var(--red-500);
            background-color: var(--red-50);
            border-color: var(--red-500);
        }

        .active-toggle.active {
            background-color: var(--red-50) !important;
            color: var(--grey-500) !important;
            border-color: var(--red-500) !important;
        }

        .btn-check:focus+.btn-primary,
        .btn-primary:focus,
        .btn-primary.focus {
            color: var(--white-50);
            background-color: var(--grey-500);
            border-color: var(--grey-500);
        }

        [class*="btn-"] {
            box-shadow: none !important;
        }

        /* Ripple Effects */
        .page-item.active .page-link.waves-effect .waves-ripple,
        .pagination li.active>a:not(.page-link).waves-effect .waves-ripple,
        .pagination-outline-primary .page-item.active .page-link.waves-effect .waves-ripple,
        .pagination-outline-primary.pagination li.active>a:not(.page-link).waves-effect .waves-ripple,
        .list-group-item.active.waves-effect .waves-ripple,
        .btn-label-primary.waves-effect .waves-ripple,
        .btn-text-primary.waves-effect .waves-ripple,
        .btn-outline-primary.waves-effect .waves-ripple,
        .dropdown-item.waves-effect .waves-ripple,
        .nav-tabs .nav-link.waves-effect .waves-ripple {
            background: radial-gradient(var(--red-50-rgba1) 0,
                    var(--red-50-rgba2) 40%,
                    var(--red-50-rgba3) 50%,
                    var(--red-50-rgba4) 60%,
                    var(--red-50-rgba5) 70%);
        }


        /* Inputs */


        input::placeholder,
        textarea::placeholder {
            color: var(--grey-100);
        }

        .form-control:not([disabled]):not([focus]) {
            border-color: var(--grey-100);
        }

        .form-control:hover:not([disabled]):not([focus]) {
            border-color: var(--red-200);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--red-500) !important;
        }


        /* Select */


        .light-style .select2-container--default .select2-selection,
        .select2-container--default.select2-container--focus .select2-selection,
        .select2-container--default.select2-container--open .select2-selection {
            border: 1px solid var(--grey-100) !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--red-50) !important;
            color: var(--grey-500) !important;
        }


        /* Cards and Layout */


        .card,
        .light-style .bs-stepper.wizard-modern .bs-stepper-content {
            box-shadow: 0px 2px 4px -2px #1018280F, 0px 4px 8px -2px #1018281A;
        }

        .light-style .wizard-vertical-icons.vertical .bs-stepper-header .step .avatar-initial {
            background-color: var(--white-50);
            color: var(--red-200);
        }

        .bs-stepper .step-trigger {
            color: var(--grey-500);
        }

        .bs-stepper .bs-stepper-header .step .step-trigger:focus {
            color: var(--grey-500);
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .h1,
        .h2,
        .h3,
        .h4,
        .h5,
        .h6 {
            color: var(--grey-500);
        }

        .light-style .wizard-vertical-icons.vertical .bs-stepper-header .step.active .avatar-initial {
            background-color: var(--red-50);
            color: var(--red-500);
        }

        #users-table_wrapper {
            height: 90%;
        }

        #layout-menu {
            box-shadow: 0 0.625rem 0.875rem rgba(76, 78, 100, 0.1);
        }

        .layout-wrapper .layout-content-navbar .layout-container {
            background-color: var(--white-50) !important;
        }


        /* Table */


        th.sorting {
            background-color: var(--white-500) !important;
        }

        .table> :not(caption)>*>* {
            color: var(--grey-500, var(--grey-400, var(--grey-300)));
            background-color: var(--white-50);
            box-shadow: inset 0 0 0 9999px var(--bs-table-bg-state, var(--bs-table-bg-type, var(--bs-table-accent-bg)));
        }

        .page-item.active .page-link,
        .page-item.active .page-link:hover,
        .page-item.active .page-link:focus,
        .pagination li.active>a:not(.page-link),
        .pagination li.active>a:not(.page-link):hover,
        .pagination li.active>a:not(.page-link):focus {
            border-color: var(--red-500);
            background-color: var(--red-500);
            color: var(--white-50);
        }

        span.fw-medium.text-heading {
            color: var(--grey-500) !important;
        }

        .light-style div.dataTables_wrapper div.dataTables_info {
            color: var(--grey-300);
        }



        /* Global Shadow Override */


        [class*="shadow"],
        [class*="box-shadow"],
        [class*="BoxShadow"],
        [class*="boxShadow"],
        [class*="shadow-"],
        [class*="-shadow"] {
            box-shadow:
                0px 2px 4px -2px #1018280F,
                0px 4px 8px -2px #1018281A !important;
        }


        /* Analytics */

        .analytics-fixed-height .card {
            min-height: 32rem;
        }
    </style>


</head>

<body>
    <!-- Layout Content -->
    @yield('layoutContent')
    <!--/ Layout Content -->

    <!-- Include Scripts -->
    <!-- $isFront is used to append the front layout scripts only on the front layout otherwise the variable will be blank -->
    @include('layouts/sections/scripts' . $isFront)

</body>

</html>