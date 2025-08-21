@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Filters - Utilities')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
    <style>
        #filters-table_wrapper.dataTables_wrapper {
            padding: 1rem !important;
        }

        .action-head {
            max-width: 15rem;
        }

        .action-pills * {
            margin: 0 .2rem;
        }

        @media (max-width: 1465px) {
            .action-head {
                max-width: 16rem;
            }
        }

        @media (max-width: 1365px) {
            .action-head {
                max-width: 17rem;
            }
        }

        @media (max-width: 1285px) {
            .action-head {
                max-width: 18rem;
            }
        }
    </style>
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
@endsection

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#filters-table').DataTable({
                responsive: true
            });

            // Inventory Edit
            document.querySelectorAll('.edit-filter-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const title = this.dataset.title;
                    const isFix = this.dataset.isfix; // should be "1" or "0"

                    document.getElementById('editFilterId').value = id;
                    document.getElementById('editTitle').value = title;

                    // Set checkbox state based on value
                    if (isFix == 1) {
                        document.getElementById('editisFix').checked = true;
                    } else {
                        document.getElementById('editisFix').checked = false;
                    }
                    const BASE_URL = "{{ url('/') }}";
                    document.getElementById('editFilterForm').action =
                        `${BASE_URL}/utilities/filters/update/${id}`;
                });
            });

            // Add null check for add-filter-btn
            const addFilterBtn = document.querySelector('.add-filter-btn');
            if (addFilterBtn) {
                addFilterBtn.addEventListener('click', function() {
                    const pathSegments = window.location.pathname.split('/').filter(Boolean);
                    const lastSegment = pathSegments[pathSegments.length - 1];
                    const parentId = isNaN(lastSegment) ? 0 : lastSegment;
                    document.getElementById('addparent_id').value = parentId;
                });
            }
            document.querySelectorAll('.edit-filterValue-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const title = this.dataset.title;
                    const filter_id = this.dataset.filter_id; // should be "1" or "0"

                    document.getElementById('editFilterValueId').value = id;
                    document.getElementById('editValueTitle').value = title;
                    const BASE_URL = "{{ url('/') }}";
                    document.getElementById('editFilterValueForm').action =
                        `${BASE_URL}/utilities/filter_values/update/${id}`;
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
                    @if ($parent_id != null && $filter->child == 0)
                        <h5 class="card-title m-0">
                            {{ $filter->title }}
                        </h5>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addFilterValueModal-{{ $parent_id }}">
                            <i class="mdi mdi-plus"></i>
                        </button>
                    @else
                        @if ($parent_id != null && $filter->child != 0)
                            <h5 class="card-title m-0">
                                add filter in {{ $filter->title }}
                            </h5>
                        @else
                            <h5 class="card-title m-0">
                                Filters
                            </h5>
                        @endif
                        <button class="btn btn-sm btn-primary add-filter-btn" data-bs-toggle="modal"
                            data-bs-target="#addFilterModal">
                            <i class="mdi mdi-plus"></i>
                        </button>
                    @endif

                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="filters-table">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Order</th>
                                    <th class="text-center action-head">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($parent_id != null && $filter->child == 0)
                                    @foreach ($filter_values as $index => $filter)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $filter->title }}</td>
                                            <td>
                                                {{ $filter->filter_order }}
                                            </td>
                                            <td class="text-center">

                                                <button type="button"
                                                    class="btn btn-sm btn-outline-secondary edit-filterValue-btn"
                                                    data-id="{{ $filter->id }}" data-title="{{ $filter->title }}"
                                                    data-bs-toggle="modal" data-bs-target="#editFilterValueModal">
                                                    <i class="mdi mdi-pencil-outline"></i>
                                                </button>
                                                <a href="{{ route('utilities.filter_values.delete', $filter->id) }}"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this filter?');">
                                                    <i class="mdi mdi-delete-outline"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    @foreach ($filters as $index => $filter)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $filter->title }}</td>
                                            <td>
                                                @if ($filter->filter_order === 0)
                                                    <Form id="orderset" method="POST"
                                                        action="{{ route('utilities.setOrder', $filter->id) }}">
                                                        @csrf
                                                        <input type="number" name="order" required>
                                                        <button class="btn btn-sm btn-outline-primary" type="submit"><i
                                                                class="mdi mdi-content-save"></i></button>
                                                    </Form>
                                                @else
                                                    {{ $filter->filter_order }}
                                                @endif
                                            </td>
                                            <td class="text-left action-pills">
                                                <button type=" button"
                                                    class="btn btn-sm btn-outline-secondary edit-filter-btn"
                                                    data-id="{{ $filter->id }}" data-title="{{ $filter->title }}"
                                                    data-isFix="{{ $filter->isFix }}" data-bs-toggle="modal"
                                                    data-bs-target="#editFilterModal">
                                                    <i class="mdi mdi-pencil-outline"></i>
                                                </button>
                                                <a href="{{ route('utilities.filters.delete', $filter->id) }}"
                                                    class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Are you sure you want to delete this filter?');">
                                                    <i class="mdi mdi-delete-outline"></i>
                                                </a>
                                                <a href="{{ route('utilities.filters.changeStatus', $filter->id) }}"
                                                    class="btn btn-sm py-2 {{ $filter->status ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                                    {{ $filter->status ? 'Deactivate' : 'Activate' }}
                                                </a>
                                                @if ($filter->child == 1)
                                                    <a href="{{ route('utilities.filters', $filter->id) }}"
                                                        class="btn btn-sm btn-outline-secondary py-2">
                                                        Add Child
                                                    </a>
                                                @else
                                                    <a href="{{ route('utilities.filters', $filter->id) }}"
                                                        class="btn btn-sm btn-outline-secondary py-2">
                                                        Values
                                                    </a>
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @include('content.utilities.filters.modals')
        @include('content.utilities.filters.filterValueModals')

    </div>

@endsection
