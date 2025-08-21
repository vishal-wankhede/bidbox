@extends('layouts/layoutMaster')

@section('title', 'Add Campaign')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />

    <style>
        .active-toggle.active {
            background-color: #0d6efd !important;
            color: #fff !important;
            border-color: #0d6efd !important;
        }

        .active-toggle {
            font-size: smaller;
            padding: .5rem;
        }

        .percentage-input {
            width: 100px;
            display: inline-block;
            margin-left: 10px;
        }

        .percentage-container {
            margin-top: 10px;
        }

        .card h4 {
            font-size: medium;
            text-align: center;
            padding-top: .5rem;
        }

        .bs-stepper-header {
            display: flex;
            justify-content: space-between;
            flex-direction: column;
        }

        .bs-stepper .bs-stepper-header {
            padding: 0 2rem;
            margin: 0;
        }

        .btn-prev,
        .btn-next {
            font-size: 14px !important;
        }

        .avatar {
            position: relative;
            width: 2rem;
            height: 2rem;
            cursor: pointer;
        }

        #brand-logo {
            margin-bottom: 0.5rem;
        }

        #dateInputs {
            align-items: flex-end;
        }

        #dateInputs button {
            padding: .675rem .5rem;
            border-radius: .5rem;
        }

        .bs-stepper .bs-stepper-header .step .step-trigger .bs-stepper-label .bs-stepper-title {
            line-height: normal;
        }

        #dynamic-filters-vertical-modern .row.g-3 {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .dynamic-filters-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 8rem;
        }

        .account-details-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
        }
    </style>
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/bs-stepper/bs-stepper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <!-- FormValidation core UMD -->
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>

    <!-- Plugins -->
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

@endsection

@section('page-script')
    {{-- <script src="{{ asset('assets/js/form-wizard-icons.js') }}"></script> --}}
    <script src="{{ asset('assets\js\form-wizard-validation.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jstree@3.3.15/dist/themes/default/style.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/jstree@3.3.15/dist/jstree.min.js"></script>


@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="bs-stepper vertical wizard-modern wizard-vertical-icons wizard-modern-vertical-icons-example mt-2">
                <div class="bs-stepper-header">
                    <div>
                        <div class="step" data-target="#account-details-vertical-modern">
                            <button type="button" class="step-trigger">
                                <span class="avatar">
                                    <span class="avatar-initial rounded-2">
                                        <i class="mdi mdi-card-account-details-outline mdi-18px"></i>
                                    </span>
                                </span>
                                <span class="bs-stepper-label flex-column align-items-start gap-1 ms-2">
                                    <span class="bs-stepper-title">Campaign Details</span>
                                </span>
                            </button>
                        </div>
                        <div class="step" data-target="#personal-info-vertical-modern">
                            <button type="button" class="step-trigger">
                                <span class="avatar">
                                    <span class="avatar-initial rounded-2">
                                        <i class="mdi mdi-projector-screen-variant-outline mdi-18px"></i>
                                    </span>
                                </span>
                                <span class="bs-stepper-label flex-column align-items-start gap-1 ms-2">
                                    <span class="bs-stepper-title">Projections Info</span>
                                </span>
                            </button>
                        </div>
                        <div class="step" data-target="#demographic-info-vertical-modern">
                            <button type="button" class="step-trigger">
                                <span class="avatar">
                                    <span class="avatar-initial rounded-2">
                                        <i class="mdi mdi-map-marker-outline mdi-18px"></i>
                                    </span>
                                </span>
                                <span class="bs-stepper-label flex-column align-items-start gap-1 ms-2">
                                    <span class="bs-stepper-title">Demographic Info</span>
                                </span>
                            </button>
                        </div>
                        <div class="step" data-target="#dynamic-filters-vertical-modern">
                            <button type="button" class="step-trigger">
                                <span class="avatar">
                                    <span class="avatar-initial rounded-2">
                                        <i class="mdi mdi-monitor-cellphone mdi-18px"></i>
                                    </span>
                                </span>
                                <span class="bs-stepper-label flex-column align-items-start gap-1 ms-2">
                                    <span class="bs-stepper-title">Dynamic Filters</span>
                                </span>
                            </button>
                        </div>
                        <div class="step" data-target="#creative-vertical-modern">
                            <button type="button" class="step-trigger">
                                <span class="avatar">
                                    <span class="avatar-initial rounded-2">
                                        <i class="mdi mdi-movie-open mdi-18px"></i>
                                    </span>
                                </span>
                                <span class="bs-stepper-label flex-column align-items-start gap-1 ms-2">
                                    <span class="bs-stepper-title">Creatives Details</span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="card mt-5 p-2 text-center">
                        <h4>Target Audience</h4>
                        <h5 id="target_audience">Please select geo and gender to get target audience estimate</h5>
                    </div>
                </div>
                <div class="bs-stepper-content">
                    <form method="POST" action="{{ route('campaign.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div id="account-details-vertical-modern" class="content">
                            <div class="content-header mb-3">
                                <h6 class="mb-1">Campaign Details</h6>
                                <small>Fill in the details to create a new advertising campaign</small>
                            </div>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="form-label" for="campaign-name">Campaign Name *</label>
                                    <input type="text" id="campaign-name" name="campaign_name" class="form-control"
                                        placeholder="Enter campaign name" required />
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label" for="campaign-description">Campaign Description</label>
                                    <textarea id="campaign-description" name="campaign_description" class="form-control" rows="3"
                                        placeholder="Enter campaign description"></textarea>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="brand-name">Brand Name *</label>
                                    <input type="text" id="brand-name" name="brand_name" class="form-control"
                                        placeholder="Enter brand name" required />
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label" for="brand-logo">Brand Logo</label>
                                    <input type="file" id="brand-logo" name="brand_logo" class="form-control" />
                                    <small class="text-muted">Accepted formats: PNG, JPG, SVG</small>
                                </div>
                                <div class="col-sm-12">
                                    <label class="form-label">Channels *</label>
                                    <div class="d-flex flex-wrap gap-2">
                                        <label class="btn btn-outline-primary active-toggle">
                                            <input type="radio" name="channel" required value="Display Advertising"
                                                class="d-none" />
                                            Display Advertising
                                        </label>
                                        <label class="btn btn-outline-primary active-toggle">
                                            <input type="radio" name="channel" required
                                                value="Programmatic Display Advertising" class="d-none" />
                                            Programmatic Display Advertising
                                        </label>
                                        <label class="btn btn-outline-primary active-toggle">
                                            <input type="radio" name="channel" required
                                                value="Connected TV Advertising" class="d-none" />
                                            Connected TV Advertising
                                        </label>
                                        <label class="btn btn-outline-primary active-toggle">
                                            <input type="radio" name="channel" required value="Native Advertising"
                                                class="d-none" />
                                            Native Advertising
                                        </label>
                                        <label class="btn btn-outline-primary active-toggle">
                                            <input type="radio" name="channel" required value="Push Notification Ads"
                                                class="d-none" />
                                            Push Notification Ads
                                        </label>
                                        <label class="btn btn-outline-primary active-toggle">
                                            <input type="radio" name="channel" required value="Video Advertising"
                                                class="d-none" />
                                            Video Advertising
                                        </label>
                                        <label class="btn btn-outline-primary active-toggle">
                                            <input type="radio" name="channel" required value="OTT Mobile Advertising"
                                                class="d-none" />
                                            OTT Mobile Advertising
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="account-details-footer">
                                <button class="btn btn-outline-secondary btn-prev" type="button" disabled>
                                    <i class="mdi mdi-arrow-left me-sm-1"></i>
                                    <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                </button>
                                <button class="btn btn-primary btn-next" type="button">
                                    <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span>
                                    <i class="mdi mdi-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                        <div id="personal-info-vertical-modern" class="content">
                            <div class="content-header mb-3">
                                <h6 class="mb-1">Projection Info</h6>
                                <small>Enter Projection details required for the campaign</small>
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label">Projections</label>
                                    </div>
                                    <div class="row rounded p-2">
                                        <div class="col-md-3 g-2 align-items-center mb-2">
                                            <label class="form-label">Client View Name *</label>
                                            <input type="text" name="client_view_name" class="form-control"
                                                placeholder="Client View Name" required>
                                        </div>
                                        <div class="col-md-3 g-2 align-items-center mb-2">
                                            <label class="form-label">Impressions *</label>
                                            <input type="number" name="impressions" class="form-control"
                                                placeholder="Enter impressions in numbers" required>
                                        </div>
                                        <div class="col-md-3 g-2 align-items-center mb-2">
                                            <label class="form-label">CTR (%) *</label>
                                            <input type="number" name="ctr" class="form-control percentage-field"
                                                placeholder="CTR %" value="0" max="100" required>
                                        </div>
                                        <div class="col-md-3 g-2 align-items-center">
                                            <label class="form-label">VTR (%)</label>
                                            <input type="number" name="vtr" class="form-control percentage-field"
                                                placeholder="VTR %" value="0" max="100">
                                        </div>
                                    </div>
                                    <div class="row rounded p-2">
                                        <div class="col-md-3 g-2 align-items-center mb-2">
                                            <label class="form-label">Budget Type *</label>
                                            <select class="form-select" name="budget_type" id="budget_type" required>
                                                <option value="cpm">CPM</option>
                                                <option value="cpc">CPC</option>
                                                <option value="cpv">CPV</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 g-2 align-items-center mb-2">
                                            <label class="form-label">Total Budget *</label>
                                            <input type="number" name="total_budget" class="form-control"
                                                placeholder="Enter the budget" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2" id="dateInputs">
                                    <div class="col-sm-4">
                                        <label class="form-label">Start Date *</label>
                                        <input type="date" name="start_date" class="form-control" required />
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-label">End Date *</label>
                                        <input type="date" name="end_date" class="form-control" required />
                                    </div>
                                    <div class="col-sm-4">
                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                            data-bs-target="#setProjectionTimeModal">Set Time</button>
                                    </div>
                                    <input type="hidden" name="projection_details" id="projection-details">
                                    @include('content.pages.setprojectiontime')
                                </div>
                                <div class="col-12 d-flex justify-content-between">
                                    <button class="btn btn-outline-secondary btn-prev" type="button">
                                        <i class="mdi mdi-arrow-left me-sm-1"></i>
                                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                    </button>
                                    <button class="btn btn-primary btn-next" type="button">
                                        <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span>
                                        <i class="mdi mdi-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="demographic-info-vertical-modern" class="content">
                            <div class="content-header mb-3">
                                <h6 class="mb-1">Demographic Details</h6>
                                <small>Enter demographic details of the campaign</small>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label for="master" class="form-label">Select Master *</label>
                                    <select name="master" id="master" class="form-select" required>
                                        @foreach ($masters as $master)
                                            <option value="{{ $master->id }}">{{ $master->master_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div id="location-container">
                                        <label class="form-label d-block">Countries *</label>
                                        <select class="form-select select2" id="level-0" name="locations[]" multiple
                                            onchange="handleLocationChange(0)" required>
                                            @foreach ($locations as $location)
                                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                                            @endforeach
                                        </select>
                                        <div id="location-percentages" class="percentage-container"></div>
                                    </div>
                                    <label class="form-label d-block">Gender *</label>
                                    <select class="form-select select2" name="gender[]" id="gender"
                                        onchange="loadTargetAudience()" multiple required>
                                        <option value="1">male</option>
                                        <option value="2">female</option>
                                        <option value="3">other</option>
                                    </select>
                                    <div id="gender-percentages" class="percentage-container"></div>
                                    <div id="gender-percentage-warning" class="alert alert-danger mt-2"
                                        style="display: none;">
                                        Gender percentages must sum to 100%. Please adjust the values.
                                    </div>
                                    <label class="form-label d-block">Filters</label>
                                    @foreach ($filters as $filter)
                                        <div class="d-flex flex-wrap gap-2 mb-3">
                                            <div class="d-flex align-items-start gap-2 flex-wrap">
                                                <button type="button" id="{{ $filter->id }}"
                                                    class="btn btn-outline-secondary btn-sm filterbtn">
                                                    {{ $filter->title }}
                                                </button>
                                                <select onchange="loadTargetAudience()" class="form-select filtervalue"
                                                    name="filtervalues[{{ $filter->id }}][]"
                                                    id="filtervalue[{{ $filter->id }}]" multiple>
                                                    <option value="">--</option>
                                                </select>
                                            </div>
                                            <div id="filter-percentages-{{ $filter->id }}"
                                                class="percentage-container w-100 mt-1"></div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-12 d-flex justify-content-between">
                                    <button class="btn btn-outline-secondary btn-prev" type="button">
                                        <i class="mdi mdi-arrow-left me-sm-1"></i>
                                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                    </button>
                                    <button class="btn btn-primary btn-next" type="button">
                                        <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span>
                                        <i class="mdi mdi-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="dynamic-filters-vertical-modern" class="content">
                            <div class="content-header mb-3">
                                <h6 class="mb-1">Division of Impression Requirements</h6>
                                <small>Dynamic Filters</small>
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    @foreach ($divisions as $division)
                                        <div class="mb-3">
                                            <button type="button" class="btn btn-outline-primary btn-sm show-division"
                                                id="{{ $division->id }}">
                                                {{ $division->title }}
                                                <span class="mdi mdi-menu-down"></span>
                                            </button>
                                            <div id="division-multiselect-tree-{{ $division->id }}"></div>
                                            <div id="division-percentages-{{ $division->id }}"
                                                class="percentage-container"></div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="dynamic-filters-footer">
                                    <button class="btn btn-outline-secondary btn-prev" type="button">
                                        <i class="mdi mdi-arrow-left me-sm-1"></i>
                                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                    </button>
                                    <button class="btn btn-primary btn-next" type="button">
                                        <span class="align-middle d-sm-inline-block d-none me-sm-1">Next</span>
                                        <i class="mdi mdi-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="creative-vertical-modern" class="content">
                            <div class="content-header mb-3">
                                <h6 class="mb-0">Creatives Details</h6>
                                <small>Give details of Creatives will be used in campaign</small>
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Upload Creative Files</label>
                                    <div class="input-group mb-2">
                                        <input type="file" id="creative-file" name="creative_files[]"
                                            class="form-control" multiple required>
                                        <button type="button" class="btn btn-outline-secondary" id="upload-creative">
                                            <i class="bx bx-upload"></i> Upload
                                        </button>
                                    </div>
                                    <p class="text-muted" style="font-size: 0.85rem;">
                                        Accepted formats: PNG, JPG, GIF, MP4
                                    </p>
                                    <div name="creative-details-container" id="creative-details-container"
                                        class="row g-2 mb-4"></div>
                                    <div class="text-end">
                                        <strong>Total: <span id="total-percentage">0</span>%</strong>
                                        <p class="text-danger" id="percentage-warning" style="display:none;">Total
                                            percentage must be 100%</p>
                                    </div>
                                    <div class="dynamic-filters-footer">
                                        <button class="btn btn-outline-secondary btn-prev" type="button">
                                            <i class="mdi mdi-arrow-left me-sm-1"></i>
                                            <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                        </button>
                                        {{-- <div class="d-flex mt-5" style="min-height: 100px;"> --}}
                                        <div class="d-flex align-items-end justify-content-end w-100">
                                            <button type="submit" class="btn btn-primary btn-submit">Submit</button>
                                        </div>
                                        {{-- </div> --}}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('savedateBtn').addEventListener('click', function() {
            const projectionJson = JSON.stringify(projectionData);
            document.getElementById('projection-details').value = projectionJson;
            const modalEl = document.getElementById('setProjectionTimeModal');
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            modalInstance.hide();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const toggles = document.querySelectorAll('.active-toggle');
            toggles.forEach(toggle => {
                const radio = toggle.querySelector('input[type="radio"]');
                if (radio.checked) {
                    toggle.classList.add('active');
                }
                toggle.addEventListener('click', function() {
                    toggles.forEach(t => t.classList.remove('active'));
                    radio.checked = true;
                    toggle.classList.add('active');
                });
            });
        });
    </script>

    <script>
        let levelCounter = 0;

        function handleLocationChange(level) {
            const select = document.getElementById(`level-${level}`);
            const selectedValues = Array.from(select.selectedOptions).map(opt => opt.value);
            const container = document.getElementById('location-container');
            const selectsToRemove = Array.from(container.querySelectorAll(`select`)).filter(s => parseInt(s.id.split('-')[
                1]) > level);
            selectsToRemove.forEach(s => {
                const percentageDiv = document.getElementById(`location-percentages-${s.id.split('-')[1]}`);
                if (percentageDiv) percentageDiv.remove();
                s.remove();
            });

            if (selectedValues.length === 0) {
                return;
            }
            updateLocationPercentages(level);

            const BASE_URL = "{{ url('/') }}";
            fetch(`${BASE_URL}/utilities/getchildlocations`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        parent_ids: selectedValues
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.locations.length > 0) {
                        levelCounter = level + 1;
                        const newSelect = document.createElement('select');
                        newSelect.className = 'form-select mt-3';
                        newSelect.id = `level-${levelCounter}`;
                        newSelect.setAttribute('multiple', true);
                        newSelect.setAttribute('name', 'locations[]');
                        newSelect.setAttribute('onchange', `handleLocationChange(${levelCounter})`);

                        data.locations.forEach(loc => {
                            const option = document.createElement('option');
                            option.value = loc.id;
                            option.textContent = loc.name;
                            newSelect.appendChild(option);
                        });

                        const label = document.createElement('label');
                        label.className = `form-label mt-3 d-block ${data.child_name}`;
                        label.textContent = data.child_name;
                        let existingLabel = container.querySelector(`.${data.child_name}`);

                        if (!existingLabel) {
                            container.appendChild(label);
                        }
                        // else {
                        //     Swal.fire({
                        //         icon: 'warning',
                        //         title: 'Warning',
                        //         text: `${data.child_name} already exists.`,
                        //         confirmButtonText: 'OK'
                        //     });
                        // }

                        // Append newSelect after the existing label if it exists, otherwise append to container
                        if (existingLabel) {
                            existingLabel.insertAdjacentElement('afterend', newSelect);
                        } else {
                            container.appendChild(newSelect);
                        }
                        if (window.jQuery && $(newSelect).select2) {
                            $(newSelect).select2();
                            $(newSelect).on('change', () => updateLocationPercentages(levelCounter));
                        }
                    }
                })
                .catch(error => console.error('Fetch error:', error));
        }

        function updateLocationPercentages(level) {
            const select = document.getElementById(`level-${level}`);
            const percentageContainer = document.getElementById(`location-percentages`) || document.createElement('div');
            percentageContainer.id = `location-percentages`;
            percentageContainer.className = 'percentage-container';
            percentageContainer.innerHTML = '';

            const selectedOptions = Array.from(select.selectedOptions);
            selectedOptions.forEach((opt, index) => {
                const div = document.createElement('div');
                div.className = 'd-flex align-items-center mb-2';
                div.innerHTML = `
                    <span>${opt.textContent}</span>
                    <input type="number" class="form-control percentage-input demographic-percentage" name="location_percentages[${opt.value}]" placeholder="%" min="0" max="100" value="0">
                `;
                percentageContainer.appendChild(div);
            });

            const locationContainer = document.getElementById('location-container');
            if (!document.getElementById(`location-percentages`)) {
                locationContainer.appendChild(percentageContainer);
            }
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('.filtervalue').hide();
            $('#gender').on('change', function() {
                updateGenderPercentages();
            });

            $('.filterbtn').on('click', function() {
                const filterId = this.id;
                const targetSelect = document.getElementById(`filtervalue[${filterId}]`);
                $(targetSelect).show();
                $(targetSelect).select2();
                targetSelect.innerHTML = '';

                const BASE_URL = "{{ url('/') }}";
                fetch(`${BASE_URL}/utilities/getfiltervalues/${filterId}`)
                    .then(res => res.json())
                    .then(data => {
                        data.forEach(filter => {
                            const option = document.createElement('option');
                            option.value = filter.id;
                            option.textContent = filter.title;
                            targetSelect.appendChild(option);
                        });
                        updateFilterPercentages(filterId);
                    })
                    .catch(error => console.error('Error fetching:', error));
            });

            $('.filtervalue').on('change', function() {
                const filterId = this.id.match(/\d+/g)?.[0];
                updateFilterPercentages(filterId);
            });

            function updateGenderPercentages() {
                const select = document.getElementById('gender');
                const percentageContainer = document.getElementById('gender-percentages') || document.createElement(
                    'div');
                percentageContainer.id = 'gender-percentages';
                percentageContainer.className = 'percentage-container';
                percentageContainer.innerHTML = '';

                const selectedOptions = Array.from(select.selectedOptions);
                selectedOptions.forEach((opt, index) => {
                    const div = document.createElement('div');
                    div.className = 'd-flex align-items-center mb-2';
                    div.innerHTML = `
                        <span>${opt.textContent}</span>
                        <input type="number" class="form-control percentage-input demographic-percentage" name="gender_percentages[${opt.value}]" placeholder="%" min="0" max="100" value="0">
                    `;
                    percentageContainer.appendChild(div);
                });

                const genderContainer = document.getElementById('gender').parentElement;
                if (!document.getElementById('gender-percentages')) {
                    genderContainer.appendChild(percentageContainer);
                }
            }

            function updateFilterPercentages(filterId) {
                const select = document.getElementById(`filtervalue[${filterId}]`);
                const percentageContainer = document.getElementById(`filter-percentages-${filterId}`) || document
                    .createElement('div');
                percentageContainer.id = `filter-percentages-${filterId}`;
                percentageContainer.className = 'percentage-container';
                percentageContainer.innerHTML = '';

                const selectedOptions = Array.from(select.selectedOptions);
                selectedOptions.forEach((opt, index) => {
                    const div = document.createElement('div');
                    div.className = 'd-flex align-items-center mb-2';
                    div.innerHTML = `
                        <span>${opt.textContent}</span>
                        <input type="number" class="form-control percentage-input demographic-percentage" name="filter_percentages[${filterId}][${opt.value}]" placeholder="%" min="0" max="100" value="0">
                    `;
                    percentageContainer.appendChild(div);
                });

                const filterContainer = select.parentElement;
                if (!document.getElementById(`filter-percentages-${filterId}`)) {
                    filterContainer.appendChild(percentageContainer);
                }
            }

            // Initialize target audience on page load
            loadTargetAudience();
        });
    </script>

    <script>
        function getAllSelectedLocations() {
            const locationSelects = document.querySelectorAll('select[name="locations[]"]');
            const selectedLocations = [];

            locationSelects.forEach((select, index) => {
                const level = select.id.split('-')[1]; // Extract level number (e.g., 0, 1, etc.)
                const selectedOptions = Array.from(select.selectedOptions).map(opt => ({
                    level: parseInt(level),
                    id: opt.value,
                    name: opt.textContent
                }));
                selectedLocations.push(...selectedOptions);
            });

            return selectedLocations;
        }

        function getSelectedGenders() {
            const genderSelect = document.getElementById('gender');
            const selectedGenders = Array.from(genderSelect.selectedOptions).map(opt => ({
                id: opt.value,
                name: opt.textContent
            }));
            return selectedGenders;
        }

        // Function to get selected master
        function getSelectedMaster() {
            const masterSelect = document.getElementById('master');
            const selectedMaster = {
                id: masterSelect.value,
                name: masterSelect.selectedOptions[0]?.textContent || ''
            };
            return selectedMaster;
        }

        function getSelectedFilters() {
            const selectedFilters = [];

            // Get selected regular filter values (from filtervalue selects)
            const filterSelects = document.querySelectorAll('select[name^="filtervalues["]');
            filterSelects.forEach(select => {
                const filterId = select.id.match(/\d+/g)?.[0]; // Extract filter ID from id="filtervalue[filterId]"
                const selectedOptions = Array.from(select.selectedOptions).map(opt => ({
                    type: 'filter',
                    filterId: filterId,
                    id: opt.value,
                    name: opt.textContent
                }));
                selectedFilters.push(...selectedOptions);
            });

            // Get selected division filter values (from division checkboxes)
            const divisionContainers = document.querySelectorAll('div[id^="division-multiselect-tree-"]');
            divisionContainers.forEach(container => {
                const divisionId = container.id.match(/\d+/g)?.[0]; // Extract division ID
                const selectedCheckboxes = container.querySelectorAll(
                    `input[name="division_value[${divisionId}][]"]:checked`);
                const selectedDivisionOptions = Array.from(selectedCheckboxes).map(checkbox => ({
                    type: 'division',
                    filterId: divisionId,
                    id: checkbox.value,
                    name: checkbox.parentElement.textContent.trim()
                }));
                selectedFilters.push(...selectedDivisionOptions);
            });

            return selectedFilters;
        }

        function loadTargetAudience() {
            const selectedLocations = getAllSelectedLocations();
            const selectedGenders = getSelectedGenders();
            const selectedMaster = getSelectedMaster();
            const selectedFilters = getSelectedFilters();
            const targetAudience = document.getElementById('target_audience');
            const BASE_URL = "{{ url('/') }}";

            // If no locations or genders are selected, show default message
            if (selectedLocations.length === 0 || selectedGenders.length === 0) {
                targetAudience.textContent = "Please select geo and gender to get target audience estimate";
                return;
            }

            // Construct query parameters
            const queryParams = new URLSearchParams();
            selectedLocations.forEach(loc => queryParams.append('locations[]', loc.id));
            selectedGenders.forEach(gender => queryParams.append('gender[]', gender.id));
            if (selectedMaster.id) {
                queryParams.append('master', selectedMaster.id);
            }
            // Add regular filters as filters[filterId][]
            selectedFilters
                .filter(f => f.type === 'filter')
                .forEach(filter => queryParams.append(`filters[${filter.filterId}][]`, filter.id));
            // Add division filters as divisions[divisionId][]
            selectedFilters
                .filter(f => f.type === 'division')
                .forEach(division => queryParams.append(`divisions[${division.filterId}][]`, division.id));

            console.log(`${BASE_URL}/campaign/getTargetAudience?${queryParams.toString()}`);

            fetch(`${BASE_URL}/campaign/getTargetAudience?${queryParams.toString()}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    targetAudience.textContent = data.population || "No target audience data available";
                    console.log(data);
                })
                .catch(error => {
                    console.error('Error fetching:', error);
                    targetAudience.textContent = "Error fetching target audience";
                });
        }
    </script>

    <style>
        .accordion-header {
            cursor: pointer;
            font-weight: bold;
            margin: 5px 0;
            padding: 5px;
            background: #f0f0f0;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .accordion-content {
            display: none;
            margin-left: 15px;
            padding: 5px 10px;
        }

        .accordion-content.open {
            display: block;
        }

        ul {
            list-style: none;
            padding-left: 10px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.show-division');
            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    const divisionId = this.id;
                    const container = document.getElementById(
                        `division-multiselect-tree-${divisionId}`);
                    const icon = this.querySelector('.mdi');

                    if (container && container.dataset.loaded === "true") {
                        if (container.style.display === 'none') {
                            container.style.display = 'block';
                            icon.classList.remove('mdi-menu-down');
                            icon.classList.add('mdi-menu-up');
                        } else {
                            container.style.display = 'none';
                            icon.classList.remove('mdi-menu-up');
                            icon.classList.add('mdi-menu-down');
                        }
                        return;
                    }

                    const BASE_URL = "{{ url('/') }}";
                    fetch(`${BASE_URL}/utilities/getDivisionChildren/${divisionId}`)
                        .then(res => res.json())
                        .then(data => {
                            if (!container) return;
                            container.innerHTML = '';
                            container.style.display = 'block';
                            container.dataset.loaded = "true";
                            icon.classList.remove('mdi-menu-down');
                            icon.classList.add('mdi-menu-up');
                            buildAccordion(data.values, container, divisionId);
                        });
                });

                function buildAccordion(data, container, divisionId) {
                    const ul = document.createElement('ul');
                    for (const key in data) {
                        const item = data[key];
                        const li = document.createElement('li');
                        const header = document.createElement('div');
                        header.classList.add('accordion-header');
                        const titleSpan = document.createElement('span');
                        titleSpan.textContent = key;
                        const iconSpan = document.createElement('span');
                        iconSpan.classList.add('mdi', 'mdi-menu-down', 'toggle-icon');
                        header.appendChild(titleSpan);
                        header.appendChild(iconSpan);

                        const content = document.createElement('div');
                        content.classList.add('accordion-content');

                        if (Array.isArray(item)) {
                            item.forEach(entry => {
                                const entryLi = document.createElement('li');
                                const label = document.createElement('label');
                                const checkbox = document.createElement('input');
                                checkbox.type = 'checkbox';
                                checkbox.name = `division_value[${divisionId}][]`;
                                checkbox.value = entry.id;
                                checkbox.addEventListener('change', function() {
                                    updateDivisionPercentages(divisionId);
                                    loadTargetAudience();
                                });
                                label.appendChild(checkbox);
                                label.append(' ' + entry.title);
                                entryLi.appendChild(label);
                                content.appendChild(entryLi);
                            });
                        } else if (typeof item === 'object') {
                            buildAccordion(item, content, divisionId);
                        }

                        header.addEventListener('click', function() {
                            const isOpen = content.classList.contains('open');
                            const allContents = container.querySelectorAll('.accordion-content');
                            const allIcons = container.querySelectorAll('.toggle-icon');
                            allContents.forEach(el => el.classList.remove('open'));
                            allIcons.forEach(icon => {
                                icon.classList.remove('mdi-menu-up');
                                icon.classList.add('mdi-menu-down');
                            });
                            if (!isOpen) {
                                content.classList.add('open');
                                iconSpan.classList.remove('mdi-menu-down');
                                iconSpan.classList.add('mdi-menu-up');
                            }
                        });

                        li.appendChild(header);
                        li.appendChild(content);
                        ul.appendChild(li);
                    }
                    container.appendChild(ul);
                    updateDivisionPercentages(divisionId);
                }
            });

            function updateDivisionPercentages(divisionId) {
                const container = document.getElementById(`division-multiselect-tree-${divisionId}`);
                const percentageContainer = document.getElementById(`division-percentages-${divisionId}`) ||
                    document.createElement('div');
                percentageContainer.id = `division-percentages-${divisionId}`;
                percentageContainer.className = 'percentage-container';
                percentageContainer.innerHTML = '';

                const checkboxes = container.querySelectorAll(
                    `input[name="division_value[${divisionId}][]"]:checked`);
                checkboxes.forEach((checkbox, index) => {
                    const div = document.createElement('div');
                    div.className = 'd-flex align-items-center mb-2';
                    const label = checkbox.parentElement.textContent.trim();
                    div.innerHTML = `
                        <span>${label}</span>
                        <input type="number" class="form-control percentage-input division-percentage" name="division_percentages[${divisionId}][${checkbox.value}]" placeholder="%" min="0" max="100" value="0">
                    `;
                    percentageContainer.appendChild(div);
                });

                const divisionContainer = document.getElementById(`division-multiselect-tree-${divisionId}`)
                    .parentElement;
                if (!document.getElementById(`division-percentages-${divisionId}`)) {
                    divisionContainer.appendChild(percentageContainer);
                }
            }
        });
    </script>

    <script>
        let creativeIndex = 0;
        document.getElementById('upload-creative').addEventListener('click', function() {
            const fileInput = document.getElementById('creative-file');
            const container = document.getElementById('creative-details-container');

            if (fileInput.files.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'warning',
                    text: `Please select file(s) first.`,
                    confirmButtonText: 'OK'
                });
                return;
            }

            Array.from(fileInput.files).forEach((file) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let dimensions = 'N/A';
                    let isImage = file.type.startsWith('image/');
                    let isVideo = file.type.startsWith('video/');

                    if (isImage) {
                        const img = new Image();
                        img.onload = function() {
                            dimensions = `${img.width} x ${img.height} px`;
                            addCreativeRow(creativeIndex++, file, dimensions, 'Static');
                        };
                        img.src = e.target.result;
                    } else if (isVideo) {
                        addCreativeRow(creativeIndex++, file, dimensions, 'Video');
                    } else {
                        addCreativeRow(creativeIndex++, file, dimensions, 'Unknown');
                    }
                };
                reader.readAsDataURL(file);
            });

            function addCreativeRow(index, file, dimensions, type) {
                const sizeInKB = file.size / 1024;
                const sizeText = sizeInKB > 1024 ? (sizeInKB / 1024).toFixed(2) + ' MB' : sizeInKB.toFixed(1) +
                    ' KB';
                const row = document.createElement('div');
                row.className = 'row g-2 mb-2 containerRow';
                row.innerHTML = `
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="creatives[${index}][name]" value="${file.name}" >
                        <label class="form-label">Name</label>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="creatives[${index}][size]" value="${sizeText}" readonly>
                        <label class="form-label">Size</label>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="creatives[${index}][dimensions]" value="${dimensions}" readonly>
                        <label class="form-label">Dimensions</label>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="creatives[${index}][type]" value="${type}" >
                        <label class="form-label">Type</label>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control creative-percentage" name="creatives[${index}][percentage]" placeholder="Enter %" min="0" max="100" value="0">
                        <label class="form-label">Percentage</label>
                    </div>
                `;
                container.appendChild(row);
                addPercentageListener();
            }

            function addPercentageListener() {
                const inputs = document.querySelectorAll('.creative-percentage');
                inputs.forEach(input => {
                    input.removeEventListener('input', calculateTotalPercentage);
                    input.addEventListener('input', calculateTotalPercentage);
                });
                calculateTotalPercentage();
            }

            function calculateTotalPercentage() {
                let total = 0;
                document.querySelectorAll('.creative-percentage').forEach(input => {
                    total += parseFloat(input.value) || 0;
                });
                document.getElementById('total-percentage').innerText = total.toFixed(1);
                document.getElementById('percentage-warning').style.display = total !== 100 ? 'block' : 'none';
            }
        });
    </script>
@endsection
