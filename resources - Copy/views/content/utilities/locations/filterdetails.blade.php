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

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.fill-values-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const currentId = parseInt(this.dataset.currentId);
                    const filterId = this.dataset.filterId;
                    const locationId = "{{ $location->id }}";
                    const genderId = "{{ $gender_id }}";

                    // Get all current hidden filter_value_id[] from the form
                    const hiddenInputs = document.querySelectorAll(
                        'input[name="filter_value_id[]"]');
                    let selectedIds = Array.from(hiddenInputs).map(input => parseInt(input.value));

                    // Add the new one
                    selectedIds.push(currentId);

                    // Remove duplicates
                    const uniqueIds = [...new Set(selectedIds)];

                    // Build query string
                    const query = new URLSearchParams({
                        filter_id: filterId,
                        filter_value_id: JSON.stringify(uniqueIds)
                    });

                    // Redirect to addFilterDetails route
                    const url =
                        `{{ route('utilities.locations.addFilterDetails', ['id' => $location->id, 'gender_id' => $gender_id]) }}?${query.toString()}`;

                    window.location.href = url;
                });
            });
        });
    </script>

@endsection
@section('content')

    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0">Add {{ $location->name }} - {{ $gender }} Details for
                        {{ $filter->title }}</h5>
                    <a href="{{ route('utilities.locations') }}" class="btn btn-sm btn-primary">
                        <i class="mdi mdi-arrow-left"></i> Back to Locations
                    </a>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('utilities.locations.storefiltervalues', $filter->id) }}"
                        id="filterValueForm">
                        @csrf
                        <input type="hidden" name="location_id" value="{{ $location->id ?? null }}">
                        <input type="hidden" name="gender_id" value="{{ $gender_id ?? null }}">
                        <input type="hidden" name="filter_id" value="{{ $filter->id ?? null }}">
                        @foreach ($filter_value_id ?? [] as $id)
                            <input type="hidden" name="filter_value_id[]" value="{{ $id }}">
                        @endforeach

                        <div class="block" id="details">
                            <div class=" row mb-3">
                                {{-- Accordion for Children --}}
                                @isset($children)
                                    <div class="mb-3">
                                        <div class="fw-bold mb-2">Children</div>
                                        <ul class="division-multiselect-tree">
                                            @include('content.utilities.filters.filter-node', [
                                                'nodes' => $children,
                                            ])
                                        </ul>
                                    </div>
                                @endisset


                                {{-- Table for Filter Values --}}
                                @isset($filter_values)
                                    <h5 class="mb-3">Filter Values</h5>
                                    <table class="table table-borderless align-middle">
                                        <thead class="text-muted">
                                            <tr>
                                                <th style="width: 50px;">#</th>
                                                <th style="width: 200px;">Title</th>
                                                <th>Value</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($filter_values as $index => $filtervalue)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $filtervalue->title }}</td>
                                                    <td>
                                                        <input type="number" name="values[{{ $filtervalue->id }}]"
                                                            class="form-control rounded-pill shadow-sm" step="any"
                                                            placeholder="Enter value"
                                                            value="{{ $filtervalue->population_value }}"
                                                            style="max-width: 300px;" />
                                                    </td>
                                                    <td>

                                                        @if ($filtervalue->population_value > 0)
                                                            <a class="btn btn-primary btn-sm fill-values-btn"
                                                                href="javascript:void(0);" data-filter-id="{{ $filter->id }}"
                                                                data-current-id="{{ $filtervalue->id }}">
                                                                fill values
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endisset

                            </div>
                        </div>

                        <div class="d-flex justify-content-center">
                            <button class="btn btn-sm btn-primary" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
