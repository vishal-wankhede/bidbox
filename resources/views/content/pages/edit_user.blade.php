@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'User List - Pages')

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

@section('page-script')
    <script>
        $(document).ready(function() {
            $('#campaigns').select2({
                placeholder: 'Select campaigns',
                allowClear: true
            });
        });
    </script>
@endsection

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Edit User</h4>
                <div class="header-button">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updatePasswordModal">Update
                        Password</button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Back</a>
                </div>
            </div>
            <div class="card-body">
                <!-- Add/Edit user form -->
                <form id="addUserForm" class="row g-3" method="POST" action="{{ route('users.update', $user->id) }}">
                    @csrf
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="first_name" name="first_name" class="form-control"
                                value="{{ $user->first_name }}" required>
                            <label for="first_name">First Name</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="last_name" name="last_name" class="form-control"
                                value="{{ $user->last_name }}" required>
                            <label for="last_name">Last Name</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <select id="legal_entity" name="legal_entity" class="form-select" required>
                                <option value="person" @if ($user->legal_entity == 'person') selected @endif>Person</option>
                                <option value="company" @if ($user->legal_entity == 'company') selected @endif>Company</option>
                            </select>
                            <label for="legal_entity">Legal Entity</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="company_name" name="company_name" class="form-control"
                                value="{{ $user->company_name }}">
                            <label for="company_name">Company Name</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="country" name="country" class="form-control"
                                value="{{ $user->country }}" required>
                            <label for="country">Country</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="city" name="city" class="form-control" value="City"
                                required>
                            <label for="city">City</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="phone" name="phone" class="form-control"
                                value="{{ $user->phone }}" required>
                            <label for="phone">Phone</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <select id="role" name="role" class="form-select" required>
                                <option value="">Select Role</option>
                                <option value="admin" @if ($user->role == 'admin') selected @endif>Admin</option>
                                <option value="sub-admin" @if ($user->role == 'sub-admin') selected @endif>Sub Admin
                                </option>
                                <option value="manager" @if ($user->role == 'manager') selected @endif>Manager</option>
                                <option value="executive" @if ($user->role == 'executive') selected @endif>Executive
                                </option>
                            </select>
                            <label for="role">Role</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="email" id="email" name="email" class="form-control"
                                value="{{ $user->email }}" required>
                            <label for="email">Email</label>
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <h5>Assign Permissions</h5>
                        @include('_partials.permissions')
                    </div>

                    <div class="col-12 mt-3">
                        @if (count($campaigns))
                            <label for="campaigns">Select Campaigns:</label>
                            <select name="campaigns[]" id="campaigns" class="form-control select2" multiple>
                                @foreach ($campaigns as $campaign)
                                    <option
                                        {{ isset($selectedCampaigns) && in_array($campaign->id, $selectedCampaigns) ? 'selected' : '' }}
                                        value="{{ $campaign->id }}">{{ $campaign->campaign_name }}</option>
                                @endforeach
                            </select>
                        @else
                            <p>No campaigns available</p>
                        @endif
                    </div>

                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Update Password Modal --}}
    <div class="modal fade" id="updatePasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('users.updatepassword', $user->id) }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
