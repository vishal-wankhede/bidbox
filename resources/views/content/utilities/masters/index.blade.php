@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Masters - Utilities')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
    <style>
        #master-table tbody tr td {
            font-size: 14px;
        }

        #master-table .dataTables_scroll {
            min-height: 80%;
        }
    </style>

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
    <div class="card-datatable pt-0">
        <div class="card-header mb-3 d-flex justify-content-between">
            <h5 class="card-title">Masters</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMasterModal">
                <i class="mdi mdi-plus me-1"></i> Add New Master
            </button>
        </div>
        <div class="table-responsive" style="overflow-x: auto;">
            <table class="table table-bordered nowrap" id="master-table" style="width:100%;">
                <thead class="table-light">
                    <tr>
                        <th>Id</th>
                        <th>Master Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($masters as $master)
                        <tr>
                            <td>{{ $master->id }}</td>
                            <td>{{ $master->master_name }}</td>
                            <td>
                                <a href="{{ route('utilities.masters.addDetails', $master->id) }}"
                                    class="btn btn-sm btn-secondary"><i class="mdi mdi-plus-outline">add details</i></a>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>

    {{-- //modal for adding new master --}}
    <div class="modal fade" id="addMasterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-simple">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4 mt-3">
                        <h4 class="modal-title">Add New Master</h4>
                        <p>Enter the master name below</p>
                    </div>

                    <!-- Add Master Form -->
                    <form id="addMasterForm" class="px-4 pb-4" method="POST"
                        action="{{ route('utilities.masters.store') }}">
                        @csrf
                        <div class="mb-3">
                            <div class="form-floating form-floating-outline">
                                <input type="text" id="master_name" name="name" class="form-control"
                                    placeholder="Master Name" required>
                                <label for="master_name">Master Name</label>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary me-2">Submit</button>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                    <!--/ Add Master Form -->
                </div>
            </div>
        </div>
    </div>

@endsection

@section('page-script')
    <script>
        $(function() {
            $('#master-table').DataTable();
        });
    </script>

@endsection
