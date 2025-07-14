@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Home')

@section('content')
    <h4>Dashboard</h4>

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
        <div class="col-sm-6 col-lg-4 mb-4">
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
        </div>
    </div>
@endsection
