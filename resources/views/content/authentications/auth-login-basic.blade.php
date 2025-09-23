@php
use App\Helpers\Helpers;

$configData = Helpers::appClasses();
$customizerHidden = 'customizer-hide';
@endphp

@extends('layouts.layoutMaster')

@section('title', 'Login Basic - Pages')

@section('vendor-style')
<!-- Vendor -->
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
@endsection

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}">
<style>
    .app-brand-link {
        margin: 0;
    }

    .card-body {
        padding-top: 0rem;
    }
</style>
@endsection

@section('vendor-script')
<script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
@endsection

@section('page-script')
<script src="{{ asset('assets/js/pages-auth.js') }}"></script>
@endsection

@section('content')
<div class="position-relative">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">

            <!-- Login -->
            <div class="card p-2">
                <!-- Logo -->

                <div class="app-brand justify-content-center">
                    <a href="{{ url('/') }}" class="app-brand-link gap-2 my-3">
                        <!-- <span class="app-brand-logo demo"></span> -->
                        <span class="app-brand-text demo text-heading fw-bold"><img src="{{asset('public\assets\img\app_logo\app_name.jpeg')}}" alt="" width="200" /></span>

                        <!-- <span
                            class="app-brand-text demo text-heading fw-bold">{{ config('variables.templateName') }}</span> -->
                    </a>
                </div>
                <!-- /Logo -->

                <div class="card-body">
                    <h4 class="mb-2">Welcome to {{ config('variables.templateName') }}! 👋</h4>
                    <p class="mb-4">Please sign-in to your account and start the adventure</p>

                    <form id="formAuthentication" class="mb-3" action="{{ route('auth-login-post') }}" method="POST">
                @csrf

                {{-- Show error messages --}}
                

                <div class="form-floating form-floating-outline mb-3">
                    <input type="text" class="form-control" id="email" name="email"
                        placeholder="Enter your email" value="{{ old('email') }}" autofocus>
                    <label for="email">Email</label>
                </div>

                <div class="mb-3">
                    <div class="form-password-toggle">
                        <div class="input-group input-group-merge">
                            <div class="form-floating form-floating-outline">
                                <input type="password" id="password" class="form-control" name="password"
                                    placeholder="••••••••••••" aria-describedby="password" />
                                <label for="password">Password</label>
                            </div>
                            <span class="input-group-text cursor-pointer">
                                <i class="mdi mdi-eye-off-outline"></i>
                            </span>
                        </div>
                    </div>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-3 d-flex justify-content-between">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember-me">
                        <label class="form-check-label" for="remember-me">
                            Remember Me
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                </div>
            </form>

                    <!-- 
                    <div class="divider my-4">
                        <div class="divider-text">or</div>
                    </div>

                    <div class="d-flex justify-content-center gap-2">

                        <a href="javascript:;" class="btn btn-icon btn-lg rounded-pill btn-text-google-plus">
                            <i class="tf-icons mdi mdi-24px mdi-google"></i>
                        </a>
                    </div> -->
                </div>
            </div>
            <!-- /Login -->
            <img alt="mask"
                src="{{ asset('assets/img/illustrations/auth-basic-login-mask-' . $configData['style'] . '.png') }}"
                class="authentication-image d-none d-lg-block"
                data-app-light-img="illustrations/auth-basic-login-mask-light.png"
                data-app-dark-img="illustrations/auth-basic-login-mask-dark.png" />
        </div>
    </div>
</div>
@endsection