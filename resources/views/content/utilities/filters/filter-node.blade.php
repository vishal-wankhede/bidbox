<style>
    .accordion-header {
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .accordion-header:hover {
        background-color: #e2e6ea;
    }

    .accordion-content {
        display: none;
        transition: all 0.2s ease;
    }

    .accordion-content.open {
        display: block;
    }

    .form-check-label {
        font-size: 14px;
    }

    /* Optional: better table spacing */
    table.table td,
    table.table th {
        vertical-align: middle;
        padding: 8px 12px;
    }

</style>

@foreach ($nodes as $label => $items)
@if (is_array($items))
{{-- Accordion Group --}}
<li class="mb-2">
    <div class="accordion-header bg-light px-3 py-2 rounded d-flex justify-content-between align-items-center">
        <span class="fw-semibold text-capitalize">{{ $label }}</span>
        <i class="mdi mdi-menu-down toggle-icon"></i>
    </div>
    <div class="accordion-content ps-4 py-2">
        <ul class="list-unstyled">

            {{-- Check if this is final level: array of id-title items --}}
            @if (isset($items[0]) && is_array($items[0]) && isset($items[0]['id'], $items[0]['title']))
            {{-- ✅ Render Table --}}
            <table class="table">
                <thead class="text-muted">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th style="width: 200px;">Title</th>
                        <th>Value</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $i => $leaf)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $leaf['title'] }}</td>
                        <td>
                            <input type="number" value="{{ $leaf['population_value'] ?? 0 }}"
                                name="values[{{ $leaf['id'] }}]"
                                class="form-control rounded-pill shadow-sm" step="any"
                                placeholder="Enter value" style="max-width: 300px;" />
                        </td>
                        <td>

                            @if ($leaf['population_value'] != 0 && $filter->id != $last_node->id)
                            @php
                            if (count($filter_value_id)) {
                            array_push($filter_value_id, $leaf['id']);
                            $filterValueId = $filter_value_id;
                            } else {
                            $filterValueId[] = $leaf['id'];
                            }

                            $query = http_build_query([
                            'filter_id' => $filter->id,
                            'filter_value_id' => $filterValueId,
                            ]);
                            @endphp

                            <a class="btn btn-primary btn-sm"
                                href="{{ route('utilities.locations.addFilterDetails', [
                                                        'id' => $location->id,
                                                        'gender_id' => $gender_id,
                                                    ]) .
                                                        '?' .
                                                        $query }}">
                                fill values
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            {{-- 🔁 Recurse further into children --}}
            @include('content.utilities.filters.filter-node', ['nodes' => $items])
            @endif

        </ul>
    </div>
</li>
@endif
@endforeach



<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.accordion-header').forEach(header => {
            header.addEventListener('click', function() {
                console.log('header clicked')
                const content = this.nextElementSibling;
                const icon = this.querySelector('.toggle-icon');
                // console.log(content.classList)
                if (content.classList.contains('open')) {
                    content.classList.remove('open');
                    icon.classList.remove('mdi-menu-up');
                    icon.classList.add('mdi-menu-down');
                } else {
                    content.classList.add('open');
                    icon.classList.remove('mdi-menu-down');
                    icon.classList.add('mdi-menu-up');
                }
            });
        });
    });
</script>