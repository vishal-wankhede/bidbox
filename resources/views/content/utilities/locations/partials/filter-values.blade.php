<ul>
    @foreach ($filters as $title => $value)
        @if (is_array($value))
            <li>
                <strong>{{ $title }}:</strong>
                @include('content.utilities.locations.partials.filter-values', ['filters' => $value])
            </li>
        @else
            <li>{{ $title }} — <span class="text-primary">{{ $value }}</span></li>
        @endif
    @endforeach
</ul>
