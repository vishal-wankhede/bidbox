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
        #masterDetails-table tbody tr td {
            font-size: 14px;
        }

        #masterDetails-table .dataTables_scroll {
            min-height: 80%;
        }
    </style>

@endsection

@section('vendor-script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    <form action="{{ route('utilities.masters.storeDetails') }}" method="post">
        @csrf
        <div class="card-datatable pt-0">
            <div class="card-header mb-3 d-flex justify-content-between">
                <h5 class="card-title">{{ $master->master_name }} => Add {{ $filter->title }} Details</h5>
            </div>
            <input type="hidden" name="Filter_id" value="{{ $filter->id }}">
            <input type="hidden" name="master_id" value="{{ $master->id }}">

            @if ($filter_parent == null)

                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table table-bordered nowrap" id="masterDetails-table" style="width:100%;">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Filter</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Other</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($filter->filter_values as $index => $filter_value)
                                @php
                                    $id = $filter_value->id;
                                    $gender = isset($saved_data[$id])
                                        ? $saved_data[$id]
                                        : ['male' => 0, 'female' => 0, 'other' => 0];
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $filter_value->title }}</td>
                                    <td>
                                        <input type="number" name="filterValue[{{ $filter_value->id }}][male]"
                                            class="form-control" value="{{ $gender['male'] ?? '' }}">
                                    </td>
                                    <td>
                                        <input type="number" name="filterValue[{{ $filter_value->id }}][female]"
                                            class="form-control" value="{{ $gender['female'] ?? '' }}">
                                    </td>
                                    <td>
                                        <input type="number" name="filterValue[{{ $filter_value->id }}][other]"
                                            class="form-control" value="{{ $gender['other'] ?? '' }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <th></th>
                                <th>Total</th>
                                <th class="text-center"> <span id="male_total"> </span>%</th>
                                <th class="text-center"> <span id="female_total"></span>%</th>
                                <th class="text-center"> <span id="other_total"></span>%</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table table-bordered nowrap" id="masterDetails-table" style="width:100%;">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Filter</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($filter && $filter->filter_values->isNotEmpty())
                                @foreach ($filter->filter_values as $index => $filter_value)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $filter_value->title }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                                data-bs-target="#addDetailsModal"
                                                data-filter-value-id="{{ $filter_value->id }}"
                                                data-filter-value-title="{{ $filter_value->title }}">
                                                Add Details
                                            </button>

                                            {{-- Summary placeholder with initial saved data if available --}}
                                            <div id="summary_{{ $filter_value->id }}" class="mt-1 small text-muted">
                                                @if (isset($saved_data[$filter_value->id]))
                                                    Male: {{ $saved_data[$filter_value->id]['male'] }}%,
                                                    Female: {{ $saved_data[$filter_value->id]['female'] }}%,
                                                    Other: {{ $saved_data[$filter_value->id]['other'] }}%
                                                @else
                                                    Not filled
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @elseif ($filter->filterValues->isNotEmpty())
                                @foreach ($filter->filterValues as $index => $filter_value)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $filter_value['title'] }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                                data-bs-target="#addDetailsModal"
                                                data-filter-value-id="{{ $filter_value['id'] }}"
                                                data-filter-value-title="{{ $filter_value['title'] }}">
                                                Add Details
                                            </button>

                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" class="text-center">No filter values available.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @include('content.utilities.masters.modals')
            @endif
        </div>
        <input type="hidden" id="isFix" value="{{ $filter->isFix }}">
        <div class="d-flex justify-content-center">
            @if (count($saved_data) > 0)
                <a class="btn btn-sm btn-primary"
                    href="{{ route('utilities.masters.addDetails', ['master_id' => $master->id, 'filter_id' => $filter->id]) }}">next</a>
            @else
                <button type="submit" class="btn btn-sm btn-primary">Save</button>
            @endif
        </div>
    </form>

@endsection

@section('page-script')
    <script>
        // $(function() {
        //     $('#masterDetails-table').DataTable();
        // });


        function calculateTotals() {
            let male_total = 0;
            let female_total = 0;
            let other_total = 0;
            const isFix = parseInt(document.getElementById('isFix').value);

            // Loop through rows
            $('#masterDetails-table tbody tr').each(function() {
                const maleVal = parseFloat($(this).find('input[name$="[male]"]').val()) || 0;
                const femaleVal = parseFloat($(this).find('input[name$="[female]"]').val()) || 0;
                const otherVal = parseFloat($(this).find('input[name$="[other]"]').val()) || 0;

                male_total += maleVal;
                female_total += femaleVal;
                other_total += otherVal;
            });
            if (isFix != 0 && (male_total > 100 || female_total > 100 || other_total > 100)) {
                alert('Total for each gender should not exceed 100%');
                return;
            }

            // Show totals
            $('#male_total').text(male_total.toFixed(2));
            $('#female_total').text(female_total.toFixed(2));
            $('#other_total').text(other_total.toFixed(2));

        }


        $(document).ready(function() {
            calculateTotals(); // Initial call

            // Listen to all input changes
            $('#masterDetails-table').on('input', 'input', function() {
                calculateTotals();
            });

        });
    </script>
@endsection
