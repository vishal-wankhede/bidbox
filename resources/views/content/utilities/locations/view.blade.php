@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Locations - Utilities')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">Location: {{ $location->name }}</h5>
                </div>

                <div class="card-body">
                    <div class="accordion" id="accordionRoot">
                        @foreach ($children as $locationName => $locationData)
                            @include('content.utilities.locations.partials.location-node', [
                                'node' => $locationData,
                                'name' => $locationName,
                                'idPrefix' => 'root',
                            ])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
