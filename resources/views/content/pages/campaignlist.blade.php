@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Campaign List - Pages')

@section('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
<style>
    #users-table tbody tr td {
        font-size: 14px;
    }

    /* #users-table .dataTables_scroll {
        min-height: 80%;
    } */
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
    <div class="card-header mb-3">
        <h5 class="card-title">Campaign List</h5>
    </div>
    <div class="table-responsive" style="overflow-x: auto;">
        <table class="table table-bordered nowrap" id="users-table" style="width:100%;">
            <thead class="table-light">
                <tr>
                    <th>Campaign Name</th>
                    <th>Brand</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Impressions</th>
                    <th>CTR</th>
                    <th>VTR</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($campaigns as $campaign)
                <tr>
                    <td>{{ $campaign->campaign_name }}</td>
                    <td>{{ $campaign->brand_name }}</td>
                    <td>{{ $campaign->start_date }}</td>
                    <td>{{ $campaign->end_date }}</td>
                    <td>{{ $campaign->impressions }}</td>
                    <td>{{ $campaign->ctr }}</td>
                    <td>{{ $campaign->vtr }}</td>
                   <td>
                      <a href="{{ route('campaign.archive', $campaign->id) }}">
                          <i class="mdi mdi-archive-outline me-2"></i>
                      </a>

                      <form action="{{ route('campaign.destroy', $campaign->id) }}" method="POST" class="d-inline">
                          @csrf
                          <button type="submit" class="btn btn-link text-danger p-0 border-0">
                              <i class="mdi mdi-delete-outline"></i>
                          </button>
                      </form>

                      <a href="#">
                          <i class="mdi mdi-pencil-outline"></i>
                      </a>
                  </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>
@endsection

@section('page-script')
<script>
    $(function() {
        $('#users-table').DataTable({
            scrollX: true,
            responsive: false, // Turn off responsive to avoid row wrapping
            autoWidth: false,
            columnDefs: [{
                    targets: 0,
                    width: '200px'
                }, // Campaign Name
                {
                    targets: 1,
                    width: '150px'
                },
                {
                    targets: 7,
                    orderable: false
                } // Actions
            ]
        });
    });
</script>
@endsection
