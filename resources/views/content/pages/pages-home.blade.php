@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Home')
@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />

@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
@endsection
@section('content')
    <div class="d-flex justify-content-between">
        <h4>Dashboard</h4>
<<<<<<< HEAD
        <a href="{{ route('campaign.add') }}" class="btn btn-primary"><i class="mdi mdi-plus"></i> Create Campaign</a>
=======
        <!-- <a href="{{ route('campaign.add') }}" class="btn btn-primary"><i class="mdi mdi-plus"></i> Create Campaign</a> -->
>>>>>>> 8ecc85ec2fb9a7f7e6b352750a47589f9882aaba
    </div>
    {{-- campaign stats --}}
    <div class="row">
        <div class="col-sm-6 col-lg-4 mb-4">
            <div class="card card-border-shadow-success h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-success"><i
                                    class="mdi mdi-bullhorn-variant mdi-20px"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0 display-6">{{ $active_campaigns }}</h4>
                    </div>
                    <h5 class="mb-0 text-heading">Active Campaigns</h5>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4 mb-4">
            <div class="card card-border-shadow-warning h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-warning"><i
                                    class="mdi mdi-bullhorn mdi-20px"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0 display-6">{{ $archived_campaigns }}</h4>
                    </div>
                    <h5 class="mb-0 text-heading">Archived Campaigns</h5>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4 mb-4">
            <div class="card card-border-shadow-primary h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-primary"><i
                                    class="mdi mdi-bullhorn-outline mdi-20px"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0 display-6">{{ $total_campaigns }}</h4>
                    </div>
                    <h5 class="mb-0 text-heading">Total Campaigns</h5>
                </div>
            </div>
        </div>
    </div>

    {{-- user stats --}}
    <div class="row">
<<<<<<< HEAD
        <div class="col-sm-6 col-lg-4 mb-4">
=======
        <!-- <div class="col-sm-6 col-lg-4 mb-4">
>>>>>>> 8ecc85ec2fb9a7f7e6b352750a47589f9882aaba
            <div class="card card-border-shadow-success h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-success"><i
                                    class="mdi mdi-account mdi-20px"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0 display-6">{{ $active_users }}</h4>
                    </div>
                    <h5 class="mb-0 text-heading">Active Clients</h5>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4 mb-4">
            <div class="card card-border-shadow-warning h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-warning"><i
                                    class="mdi mdi-account-outline mdi-20px"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0 display-6">{{ $archived_users }}</h4>
                    </div>
                    <h5 class="mb-0 text-heading">Archived Clients</h5>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4 mb-4">
            <div class="card card-border-shadow-primary h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                        <div class="avatar me-2">
                            <span class="avatar-initial rounded bg-label-primary"><i
                                    class="mdi mdi-account-group mdi-20px"></i></span>
                        </div>
                        <h4 class="ms-1 mb-0 display-6">{{ $total_users }}</h4>
                    </div>
                    <h5 class="mb-0 text-heading">Total Clients</h5>
                </div>
            </div>
<<<<<<< HEAD
        </div>
=======
        </div> -->
>>>>>>> 8ecc85ec2fb9a7f7e6b352750a47589f9882aaba
    </div>
@endsection
