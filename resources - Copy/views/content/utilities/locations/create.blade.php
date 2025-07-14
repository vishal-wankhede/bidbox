@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Locations - addnew')

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
                    <h5 class="card-title m-0">Add New Location</h5>
                    <a href="{{ route('utilities.locations') }}" class="btn btn-sm btn-primary">
                        <i class="mdi mdi-arrow-left"></i> Back to Locations
                    </a>
                </div>
                <div class="card-body"></div>
                <form action="{{ route('utilities.locations.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $parent_id ?? null }}">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Location Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="child" class="form-label">Do wants to add Child?</label>
                            <div class="flex justify-content-between">
                                <label class="form-check-label" for="child_yes">Yes</label>
                                <input type="radio" name="child" id="child_yes" value="1">

                                <label class="form-check-label" for="child_no">No</label>
                                <input type="radio" name="child" id="child_no" value="0">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3 " id="child_name_div">
                            <label for="child_name" class="form-label">Child Name</label>
                            <input type="text" class="form-control" id="child_name" name="child_name">
                        </div>
                    </div>
                    <div class="row" id="details">
                        {{-- <div class="col-md-3 mb-3">
                            <label for="total" class="form-label">Total</label>
                            <input type="number" class="form-control" id="total" name="total">
                        </div> --}}
                        <div class="col-md-3 mb-3">
                            <label for="Male" class="form-label">Male</label>
                            <input type="number" class="form-control" id="male" name="male">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="female" class="form-label">Female</label>
                            <input type="number" class="form-control" id="female" name="female">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="other" class="form-label">Other</label>
                            <input type="number" class="form-control" id="other" name="other">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Location</button>
                </form>
            </div>
        </div>
    </div>
    </div>

@endsection
@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Hide details section by default
            document.getElementById('details').style.display = 'none';
            document.getElementById('child_name_div').style.display = 'none';

            // Add event listener to radio buttons
            document.querySelectorAll('input[name="child"]').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    if (this.value == 1) {
                        document.getElementById('details').style.display = 'none';
                        document.getElementById('child_name_div').style.display = 'block';
                    } else {
                        document.getElementById('details').style.display = 'flex';
                        document.getElementById('child_name_div').style.display = 'none';
                    }
                });
            });
        });
    </script>
