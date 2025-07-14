@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Campaign Report')
@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />

@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/chartjs/chartjs.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>

@endsection

@section('page-script')
    <script>
        (function() {
            let cardColor, headingColor, labelColor, borderColor, legendColor;

            if (isDarkStyle) {
                cardColor = config.colors_dark.cardColor;
                headingColor = config.colors_dark.headingColor;
                labelColor = config.colors_dark.textMuted;
                legendColor = config.colors_dark.bodyColor;
                borderColor = config.colors_dark.borderColor;
            } else {
                cardColor = config.colors.cardColor;
                headingColor = config.colors.headingColor;
                labelColor = config.colors.textMuted;
                legendColor = config.colors.bodyColor;
                borderColor = config.colors.borderColor;
            }

            const chartColors = {
                area: {
                    series1: '#ab7efd',
                    series2: '#b992fe',
                    series3: '#e0cffe'
                }
            };

            const areaChartEl = document.querySelector('#lineAreaChart'),
                areaChartConfig = {
                    chart: {
                        height: 400,
                        fontFamily: 'Inter',
                        type: 'area',
                        parentHeightOffset: 0,
                        toolbar: {
                            show: false
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: false,
                        curve: 'straight'
                    },
                    legend: {
                        show: true,
                        position: 'top',
                        horizontalAlign: 'start',
                        labels: {
                            colors: legendColor,
                            useSeriesColors: false
                        }
                    },
                    grid: {
                        borderColor: borderColor,
                        xaxis: {
                            lines: {
                                show: true
                            }
                        }
                    },
                    colors: [chartColors.area.series1, chartColors.area.series2, chartColors.area.series3],
                    series: [{
                            name: 'Impressions',
                            data: @php echo $report['impression_daily']; @endphp
                        },
                        {
                            name: 'Clicks',
                            data: @php echo $report['clicks_daily']; @endphp
                        },
                        {
                            name: 'Video Views',
                            data: @php echo $report['video_views_daily']; @endphp
                        }
                    ],
                    xaxis: {
                        categories: @php echo $report['dateLabels']; @endphp,
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        },
                        labels: {
                            style: {
                                colors: labelColor,
                                fontSize: '11px'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: labelColor,
                                fontSize: '11px'
                            },
                            formatter: function(val) {
                                return Math.round(val).toLocaleString();
                            }
                        }
                    },
                    fill: {
                        opacity: 1,
                        type: 'solid'
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
                        y: {
                            formatter: function(val) {
                                return Math.round(val).toLocaleString();
                            }
                        }
                    }
                };

            if (typeof areaChartEl !== undefined && areaChartEl !== null) {
                const areaChart = new ApexCharts(areaChartEl, areaChartConfig);
                areaChart.render();
            }
        })();
    </script>

    <script>
        (function() {
            let cardColor, headingColor, labelColor, borderColor, legendColor;

            if (isDarkStyle) {
                cardColor = config.colors_dark.cardColor;
                headingColor = config.colors_dark.headingColor;
                labelColor = config.colors_dark.textMuted;
                legendColor = config.colors_dark.bodyColor;
                borderColor = config.colors_dark.borderColor;
            } else {
                cardColor = config.colors.cardColor;
                headingColor = config.colors.headingColor;
                labelColor = config.colors.textMuted;
                legendColor = config.colors.bodyColor;
                borderColor = config.colors.borderColor;
            }

            const lineChartEl = document.querySelector('#lineChart');
            const lineChartConfig = {
                chart: {
                    height: 400,
                    fontFamily: 'Inter',
                    type: 'line',
                    parentHeightOffset: 0,
                    zoom: {
                        enabled: false
                    },
                    toolbar: {
                        show: false
                    }
                },
                series: [{
                        name: "CTR (%)",
                        data: @json($report['ctr_arr'])
                    },
                    {
                        name: "VTR (%)",
                        data: @json($report['vtr_arr'])
                    }
                ],
                markers: {
                    strokeWidth: 7,
                    strokeOpacity: 1,
                    strokeColors: [cardColor],
                    colors: ['#ff9f43', '#7367f0']
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'straight'
                },
                colors: ['#ff9f43', '#7367f0'],
                grid: {
                    borderColor: borderColor,
                    xaxis: {
                        lines: {
                            show: true
                        }
                    },
                    padding: {
                        top: -20
                    }
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: function(val) {
                            return val + '%';
                        }
                    }
                },
                xaxis: {
                    categories: @json($report['dateLabels_arr']),
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: '11px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: labelColor,
                            fontSize: '11px'
                        },
                        formatter: function(val) {
                            return val + '%';
                        }
                    }
                }
            };

            if (typeof lineChartEl !== 'undefined' && lineChartEl !== null) {
                const lineChart = new ApexCharts(lineChartEl, lineChartConfig);
                lineChart.render();
            }
        })();
    </script>

    <script>
        (function() {
            const ageRangeData = @json($report['ageRange']);
            const polarChart = document.getElementById('polarChart');

            if (polarChart && Object.keys(ageRangeData).length > 0) {
                const ageRangeLabels = Object.keys(ageRangeData);
                const ageRangeValues = Object.values(ageRangeData);

                const polarChartVar = new Chart(polarChart, {
                    type: 'polarArea',
                    data: {
                        labels: ageRangeLabels,
                        datasets: [{
                            label: 'Population',
                            backgroundColor: [
                                '#9966ff', // fallback colors
                                '#ffcd56',
                                '#ff9f40',
                                '#4bc0c0',
                                '#c9cbcf',
                                '#36a2eb'
                            ],
                            data: ageRangeValues,
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 500
                        },
                        scales: {
                            r: {
                                ticks: {
                                    display: false,
                                    color: '#6e6b7b'
                                },
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                rtl: false,
                                backgroundColor: '#fff',
                                titleColor: '#000',
                                bodyColor: '#333',
                                borderWidth: 1,
                                borderColor: '#ccc'
                            },
                            legend: {
                                rtl: false,
                                position: 'right',
                                labels: {
                                    usePointStyle: true,
                                    padding: 25,
                                    boxWidth: 8,
                                    boxHeight: 8,
                                    color: '#333',
                                    font: {
                                        family: 'Inter'
                                    }
                                }
                            }
                        }
                    }
                });
            } else {
                console.warn("Polar chart or age data not found");
            }
        })();
    </script>

    <script>
        const deviceData = @json($report['device']);
        const deviceLabels = Object.keys(deviceData);
        const deviceValues = Object.values(deviceData);

        const horizontalBarChartEl = document.querySelector('#horizontalBarChart');
        const horizontalBarChartConfig = {
            chart: {
                type: 'bar',
                height: 350,
                parentHeightOffset: 0,
                toolbar: {
                    show: false
                }
            },
            series: [{
                name: 'Impressions',
                data: deviceValues
            }],
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '40%',
                    distributed: true
                }
            },
            colors: ['#7367f0', '#28c76f', '#ff9f43', '#00cfe8', '#ea5455'], // You can rotate/add as needed
            dataLabels: {
                enabled: true
            },
            xaxis: {
                categories: deviceLabels,
                labels: {
                    style: {
                        colors: '#6e6b7b',
                        fontSize: '11px'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#6e6b7b',
                        fontSize: '11px'
                    }
                }
            },
            grid: {
                borderColor: '#e7e7e7'
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val + " impressions";
                    }
                }
            }
        };

        if (typeof horizontalBarChartEl !== 'undefined' && horizontalBarChartEl !== null) {
            const horizontalBarChart = new ApexCharts(horizontalBarChartEl, horizontalBarChartConfig);
            horizontalBarChart.render();
        }
    </script>

    <script>
        function toggleMoreFilters() {
            const moreFilters = document.getElementById('more-filters');
            const toggleBtn = document.getElementById('toggle-more-btn');

            if (moreFilters.classList.contains('d-none')) {
                moreFilters.classList.remove('d-none');
                toggleBtn.innerText = "Less Filters";
            } else {
                moreFilters.classList.add('d-none');
                toggleBtn.innerText = "More Filters";
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const dateSelect = document.getElementById('date-range-select');
            const modal = new bootstrap.Modal(document.getElementById('customRangeModal'));
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            dateSelect.addEventListener('change', function() {
                if (this.value === 'custom') {
                    modal.show();
                }
            });

            document.getElementById('apply-custom-range').addEventListener('click', function() {
                const start = startDateInput.value;
                const end = endDateInput.value;

                if (start && end && start <= end) {
                    modal.hide();
                    dateSelect.options[dateSelect.selectedIndex].text = `Custom: ${start} to ${end}`;
                } else {
                    alert('Please select a valid date range.');
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvases = document.querySelectorAll('.video-thumbnail');

            canvases.forEach(canvas => {
                const ctx = canvas.getContext('2d');
                const video = document.createElement('video');
                video.crossOrigin = "anonymous";
                video.src = canvas.dataset.src;
                video.muted = true;
                video.playsInline = true;

                video.addEventListener('loadeddata', function() {
                    // Seek to 0.1 second
                    video.currentTime = 0.1;
                });

                video.addEventListener('seeked', function() {
                    // Draw frame on canvas
                    canvas.classList.remove('d-none');
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                    // Replace canvas with image
                    const img = new Image();
                    img.src = canvas.toDataURL();
                    img.className = "img-fluid rounded w-100";
                    img.style.height = "180px";
                    img.style.objectFit = "cover";

                    canvas.parentNode.replaceChild(img, canvas);

                    // Hide fallback + overlay
                    const fallback = canvas.parentNode.querySelector('.fallback-thumbnail');
                    if (fallback) fallback.remove();
                    const overlay = canvas.parentNode.querySelector('.video-overlay');
                    if (overlay) overlay.remove();
                });

                video.addEventListener('error', function() {
                    // Show fallback and overlay
                    canvas.parentNode.querySelector('.fallback-thumbnail').style.display = 'block';
                });
            });
        });
    </script>

    <style>
        .dropdown-menu li {
            position: relative;
        }

        .dropdown-menu .dropdown-submenu {
            display: none;
            position: absolute;
            left: 100%;
            top: -7px;
        }

        .dropdown-menu .dropdown-submenu-left {
            right: 100%;
            left: auto;
        }

        .dropdown-menu>li:hover>.dropdown-submenu {
            display: block;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Campaign Report</h4>
            <div>
                <span class="badge bg-light text-dark me-2">Active Campaign</span>
                <button class="btn btn-dark btn-sm me-1"><i class="bi bi-download me-1"></i>Export</button>
                <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-share me-1"></i>Share</button>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <form action="{{ route('analytics.index') }}">
                <div class="card-body row g-3">
                    <!-- Date Range -->
                    <div class="col-md-2">
                        <label class="form-label ">Date Range</label>
                        <select class="form-select customSelect" id="date-range-select">
                            <option value="7">Last 7 days</option>
                            <option value="30">Last 30 days</option>
                            <option value="month">This Month</option>
                            <option value="custom">Custom Range</option>
                        </select>
                    </div>

                    <!-- Campaign -->
                    <div class="col-md-2">
                        <label class="form-label">Campaign</label>
                        <select name="campaign" class="form-select">
                            <option>Select Campaign</option>
                            @foreach ($campaigns as $campaign)
                                <option value="{{ $campaign->id }}" @if ($selectedCampaign ?? $selectedCampaign->id == $campaign->id) selected @endif>
                                    {{ $campaign->campaign_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- countries -->
                    <div class="col-md-2">
                        <label class="form-label">Countries</label>
                        <select name="country" class="form-select">
                            <option>Select country</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}"@if ($selectedcountry ?? $selectedcountry->id == $country->id) selected @endif>
                                    {{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- States -->
                    <div class="col-md-2">
                        <label class="form-label">States</label>
                        <select name="state" class="form-select">
                            <option>Select State</option>
                            @foreach ($states as $state)
                                <option value="{{ $state->id }}"@if ($selectedstate ?? $selectedstate->id == $state->id) selected @endif>
                                    {{ $state->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Cities -->
                    <div class="col-md-2">
                        <label class="form-label">Cities</label>
                        <select name="city" class="form-select">
                            <option>Select City</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}"@if ($selectedcity ?? $selectedcity->id == $city->id) selected @endif>
                                    {{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Gender -->
                    <div class="col-md-2">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option>Select Gender</option>
                            <option value="1" @if ($selectedGender ?? $selectedGender == 1) selected @endif>Male</option>
                            <option value="2" @if ($selectedGender ?? $selectedGender == 2) selected @endif>Female</option>
                            <option value="3" @if ($selectedGender ?? $selectedGender == 3) selected @endif>Other</option>
                        </select>
                    </div>
                </div>

                <!-- More Filters (Initially Hidden) -->
                <div id="more-filters" class="card-body row g-3 d-none">
                    @foreach ($filters as $filter)
                        <div class="col-md-3">
                            <label class="form-label">{{ $filter->title }}</label>
                            <select name="{{ $filter->title }}" class="form-select">
                                <option>Select {{ $filter->title }}</option>
                                @foreach ($filter->filter_values as $filter_value)
                                    <option value="{{ $filter_value->id }}">{{ $filter_value->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                    {{--
                    @foreach ($divisions as $division)
                        @if (isset($division['label']) && isset($division['values']) && is_array($division['values']))
                            <div class="formField__container___1xO6a commonEditor_input__KaVXh d-inline-block me-3">
                                <div class="formField__label-container___3IaJ7">
                                    <label class="formField__label___26Z07"
                                        for="contextual-segments-{{ $division['label'] }}">
                                        <span>{{ $division['label'] }}</span>
                                    </label>
                                </div>
                                <div class="contextualSegments_container__Bw0xW">
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" id="contextual-segments-{{ $division['label'] }}">
                                            Select {{ $division['label'] }}...
                                        </button>
                                        <ul class="dropdown-menu">
                                            @foreach ($division['values'] as $category => $items)
                                                @if (is_array($items))
                                                    <li class="dropdown-submenu dropend">
                                                        <a class="dropdown-item dropdown-toggle" href="#"
                                                            data-bs-toggle="dropdown">{{ $category }}</a>
                                                        <ul class="dropdown-menu">
                                                            @foreach ($items as $item)
                                                                @if (is_array($item) && isset($item['title']))
                                                                    <li><a class="dropdown-item"
                                                                            href="#">{{ $item['title'] }}</a></li>
                                                                @else
                                                                    <li><a class="dropdown-item" href="#">No Title
                                                                            Available</a></li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach --}}

                </div>

                <!-- Footer -->
                <div class="card-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="toggle-more-btn"
                        onclick="toggleMoreFilters()">
                        More Filters
                    </button>
                    <button type="submit" class="btn btn-secondary btn-sm">Apply Filters</button>
                </div>
            </form>
        </div>

        <!-- Custom Range Modal -->
        <div class="modal fade" id="customRangeModal" tabindex="-1" aria-labelledby="customRangeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Select Custom Range</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-2">
                            <div class="col-12">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" />
                            </div>
                            <div class="col-12">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-primary btn-sm" id="apply-custom-range">Apply</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stat Cards -->

        <div class="row g-3 mb-4">

            {{-- Impressions --}}
            <div class="col-md">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold text-muted">Impressions</span>
                            <i class="bi bi-eye fs-5 text-secondary"></i>
                        </div>
                        <h4 class="mb-1">{{ $report['impressions'] }}</h4>
                        <small class="text-muted">+12.5% vs prev period</small>
                    </div>
                </div>
            </div>

            {{-- CTR --}}
            <div class="col-md">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold text-muted">CTR</span>
                            <i class="bi bi-cursor fs-5 text-secondary"></i>
                        </div>
                        <h4 class="mb-1">{{ $report['ctr'] }}%</h4>
                        <small class="text-muted">+0.8% vs prev period</small>
                    </div>
                </div>
            </div>

            {{-- Clicks --}}
            <div class="col-md">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold text-muted">Clicks</span>
                            <i class="bi bi-hand-index-thumb fs-5 text-secondary"></i>
                        </div>
                        <h4 class="mb-1">{{ $report['clicks'] }}</h4>
                        <small class="text-muted">-2.1% vs prev period</small>
                    </div>
                </div>
            </div>

            {{-- Conditionally show VTR and Video Views --}}
            @if ($report['vtr'] > 0)
                {{-- VTR --}}
                <div class="col-md">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold text-muted">VTR</span>
                                <i class="bi bi-play-btn fs-5 text-secondary"></i>
                            </div>
                            <h4 class="mb-1">{{ $report['vtr'] }}%</h4>
                            <small class="text-muted">+5.2% vs prev period</small>
                        </div>
                    </div>
                </div>

                {{-- Video Views --}}
                <div class="col-md">
                    <div class="card h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-semibold text-muted">Video Views</span>
                                <i class="bi bi-camera-video fs-5 text-secondary"></i>
                            </div>
                            <h4 class="mb-1">{{ $report['video_views'] }}</h4>
                            <small class="text-muted">+18.3% vs prev period</small>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Charts Section -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div>
                            <h5 class="card-title mb-0">Campaign Analytics</h5>
                            <small class="text-muted">Impressions, Clicks & Video Views</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="lineAreaChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div>
                            <h5 class="card-title mb-0">Ctr and vtr</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="lineChart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row g-3 mb-4 align-items-stretch">
            <div class="col-md-4 h-100">
                <div class="card h-100">
                    <div class="card-header header-elements">
                        <h5 class="card-title mb-0">Age Range</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="polarChart" class="chartjs" data-height="237"></canvas>
                    </div>
                    <div class="card-footer header-elements">
                        <h5 class="card-title mb-2">Gender</h5>
                        <div class="d-flex flex-wrap gap-2 justify-content-around">
                            @foreach ($report['gender'] as $gender => $percent)
                                <div
                                    class="badge bg-label-{{ $loop->index % 2 == 0 ? 'primary' : 'warning' }} p-2 px-3 text-capitalize">
                                    {{ $gender }} {{ $percent }}%
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-4 h-100">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Top Locations</h5>
                    </div>
                    <div class="card-body">
                        @if (!empty($report['locations']))
                            <ul class="list-unstyled mb-0">
                                @foreach ($report['locations'] as $country => $count)
                                    <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                        <span>{{ $country }}</span>
                                        <span class="text-muted fw-semibold">
                                            {{ $count }} impressions
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-0">No data available</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4 h-100">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <p class="card-subtitle text-muted mb-1">Device Breakdown</p>
                            <h5 class="card-title mb-0">Total Impressions</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="horizontalBarChart"></div>
                    </div>
                </div>

            </div>

        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Inventory Performance</h5>
                        @if (count($report['inventories']) > 5)
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                data-bs-target="#inventoryModal">
                                View All
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <thead>
                                    <tr class="text-muted">
                                        <th>Inventory Source</th>
                                        <th>Impressions</th>
                                        <th>CTR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (array_slice($report['inventories'], 0, 5) as $name => $data)
                                        <tr>
                                            <td class="text-capitalize">{{ $name }}</td>
                                            <td>{{ $data['impressions'] }}</td>
                                            <td>{{ $data['ctr'] ?? '-' }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cohorts Analysis -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Cohorts Analysis</h5>
                        @if (count($report['cohorts']) > 5)
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                data-bs-target="#cohortModal">
                                View All
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <thead>
                                    <tr class="text-muted">
                                        <th>Cohort</th>
                                        <th>Impressions</th>
                                        <th>CTR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (array_slice($report['cohorts'], 0, 5) as $name => $data)
                                        <tr>
                                            <td class="text-capitalize">{{ $name }}</td>
                                            <td>{{ $data['impressions'] }}</td>
                                            <td>{{ $data['ctr'] ?? '-' }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="card-title mb-0">Creative Performance</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach ($report['creatives'] as $index => $creative)
                                <div class="col-md-4">
                                    <div class="border rounded p-3 h-100">
                                        {{-- Preview Thumbnail --}}
                                        @if (Str::endsWith($creative['file_path'], ['.mp4', '.webm', '.gif']))
                                            <div class="position-relative mb-3">
                                                <canvas class="video-thumbnail d-none" width="100%" height="180"
                                                    data-src="{{ url('public/' . $creative['file_path']) }}"></canvas>

                                                <img src="{{ url('public/' . $creative['file_path']) }}"
                                                    alt="Creative Asset"
                                                    class="fallback-thumbnail img-fluid rounded w-100"
                                                    style="height: 180px; object-fit: cover; display: none;">

                                                <div
                                                    class="video-overlay position-absolute top-50 start-50 translate-middle text-center bg-dark bg-opacity-50 text-white py-1 px-2 rounded">
                                                    Video is not supported
                                                </div>
                                            </div>
                                        @else
                                            <img src="{{ url('public/' . $creative['file_path']) }}" alt="Creative Asset"
                                                class="img-fluid rounded mb-3 w-100"
                                                style="height: 180px; object-fit: cover;">
                                        @endif

                                        {{-- Metrics --}}
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-dark fw-medium">Impressions:</span>
                                            <span class="text-dark fw-medium">{{ $creative['impressions'] }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-dark fw-medium">CTR:</span>
                                            <span class="text-dark fw-medium">{{ $creative['ctr'] }}%</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-dark fw-medium">VTR:</span>
                                            <span class="text-dark fw-medium">{{ $creative['vtr'] }}%</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('content.pages.analytics.modals')
@endsection
