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

@section('page-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#Locations-table').DataTable({
                responsive: true
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-location-btn');
            const form = document.getElementById('edit-location-form');

            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const name = this.dataset.name;
                    const child = this.dataset.child;
                    const childName = this.dataset.childName;

                    // Populate modal inputs
                    document.getElementById('edit_child_name').value = childName ?? '';
                    document.getElementById('edit_location_id').value = id;
                    document.getElementById('edit_location_name').value = name;
                    document.getElementById('edit_location_child').checked = (child == 1);

                    const checkbox = document.getElementById('edit_location_child');
                    console.log(checkbox.checked)
                    if (checkbox.checked == true) {
                        document.getElementById('child_name_div').style.display = 'block';
                    } else {
                        document.getElementById('child_name_div').style.display = 'none';
                    }

                    // Add event listener to radio buttons
                    checkbox.addEventListener('change', function() {
                        if (this.checked == true) {
                            document.getElementById('child_name_div').style.display =
                                'block';
                        } else {
                            document.getElementById('child_name_div').style.display =
                                'none';
                        }
                    });

                    // Set dynamic action route
                    form.action = `/utilities/locations/update/${id}`;
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
                    <h5 class="card-title m-0">Locations</h5>
                    <a href="{{ route('utilities.locations.create', $parent_id) }}" class="btn btn-sm btn-primary">
                        <i class="mdi mdi-plus"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="Locations-table">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($locations as $index => $location)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $location->name }}</td>
                                        <td class="text-center">
                                            <button type="button"
                                                class="btn btn-sm btn-outline-secondary edit-location-btn"
                                                data-id="{{ $location->id }}" data-name="{{ $location->name }}"
                                                data-child="{{ $location->child }}"
                                                data-child-name="{{ $location->child_name }}" data-bs-toggle="modal"
                                                data-bs-target="#editlocationValueModal">
                                                <i class="mdi mdi-pencil-outline"></i>
                                            </button>
                                            @if ($location->child == 1)
                                                <a href="{{ route('utilities.locations', $location->id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    Add Child
                                                </a>
                                            @else
                                                <a href="{{ route('utilities.locations.addDetails', $location->id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    add details
                                                </a>
                                            @endif
                                            <a href="{{ route('utilities.locations.delete', $location->id) }}"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Are you sure you want to delete this location?');">
                                                <i class="mdi mdi-delete-outline"></i>
                                            </a>
                                            <a href="{{ route('utilities.locations.view', $location->id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="mdi mdi-eye-outline"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="editlocationValueModal" tabindex="-1" aria-labelledby="editlocationValueModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="edit-location-form" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-name" id="editlocationValueModalLabel">Edit Location</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="edit_location_id" name="id">

                        <div class="mb-3">
                            <label for="edit_location_name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit_location_name" name="name" required>
                        </div>

                        <div class="mb-3" id="child_name_div">
                            <label for="edit_child_name" class="form-label">Child Name</label>
                            <input type="text" class="form-control" id="edit_child_name" name="child_name">
                        </div>

                        <div class="mb-3 form-check">
                            <input type="hidden" name="child" id="hidden_location_child" value="0">
                            <input type="checkbox" class="form-check-input" id="edit_location_child" name="child"
                                value="1">
                            <label class="form-check-label" for="edit_location_child">Child</label>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
