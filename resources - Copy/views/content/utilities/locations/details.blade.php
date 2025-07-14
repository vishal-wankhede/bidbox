@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Locations - edit')

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
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0">Add {{ $location->name }} Details</h5>
                    <a href="{{ route('utilities.locations') }}" class="btn btn-sm btn-primary">
                        <i class="mdi mdi-arrow-left"></i> Back to Locations
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('utilities.locations.updateDetails', $location->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $location->parent ?? null }}">

                        <div class="block" id="details">
                            <div class=" row mb-3">
                                <div class="col-md-3 d-flex text-allign-center">Male</div>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" id="Male" value="{{ $location->male }}"
                                        name="male">
                                </div>
                                <div class="col-md-5">
                                    <a href="{{ route('utilities.locations.addFilterDetails', ['id' => $location->id, 'gender_id' => '1']) }}"
                                        class="btn btn-sm btn-primary">
                                        Add Filter
                                    </a>
                                </div>
                            </div>
                            <div class=" row mb-3">
                                <div class="col-md-3 d-flex text-allign-center">Female</div>
                                <div class="col-md-4"><input type="number" class="form-control" id="female"
                                        value="{{ $location->female }}" name="female"></div>
                                <div class="col-md-5">
                                    <a href="{{ route('utilities.locations.addFilterDetails', ['id' => $location->id, 'gender_id' => '2']) }}"
                                        class="btn btn-sm btn-primary">
                                        Add Filter
                                    </a>
                                </div>
                            </div>
                            <div class=" row mb-3">
                                <div class="col-md-3 d-flex text-allign-center">Other</div>
                                <div class="col-md-4">
                                    <input type="number" class="form-control" id="other"
                                        value="{{ $location->other }}" name="other">
                                </div>
                                <div class="col-md-5">
                                    <a href="{{ route('utilities.locations.addFilterDetails', ['id' => $location->id, 'gender_id' => '3']) }}"
                                        class="btn btn-sm btn-primary">
                                        Add Filter
                                    </a>
                                </div>
                            </div>
                        </div>
                        <button type="submit justify-content-end" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
