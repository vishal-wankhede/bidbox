@php
    $uid = uniqid($idPrefix . '-');
@endphp

<div class="accordion-item">
    <h2 class="accordion-header" id="heading-{{ $uid }}">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#collapse-{{ $uid }}" aria-expanded="false"
            aria-controls="collapse-{{ $uid }}">
            {{ $name }}
        </button>
    </h2>
    <div id="collapse-{{ $uid }}" class="accordion-collapse collapse"
        aria-labelledby="heading-{{ $uid }}">
        <div class="accordion-body">

            @if (isset($node['male']))
                {{-- Leaf location: show gender-wise population and filters --}}
                @foreach (['male', 'female', 'other'] as $gender)
                    <div class="mb-3">
                        <strong class="text-capitalize">{{ $gender }} ({{ $node[$gender]['value'] }})</strong>
                        @if (!empty($node[$gender]['filters']))
                            <ul>
                                @foreach ($node[$gender]['filters'] as $filterTitle => $filterData)
                                    <li>
                                        <strong>{{ $filterTitle }}:</strong>
                                        @include('content.utilities.locations.partials.filter-values', [
                                            'filters' => $filterData,
                                        ])
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-muted">No filters available</div>
                        @endif
                    </div>
                @endforeach
            @else
                {{-- Recursive children --}}
                <div class="accordion mt-2" id="accordion-{{ $uid }}">
                    @foreach ($node as $childName => $childNode)
                        @include('content.utilities.locations.partials.location-node', [
                            'node' => $childNode,
                            'name' => $childName,
                            'idPrefix' => $uid,
                        ])
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</div>
