<div class="modal fade" id="addDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <form action="{{ route('utilities.masters.storeDetails') }}" method="post">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <input type="hidden" name="Filter_id" value="{{ $filter->id }}">
                <input type="hidden" name="Filter_parent_id" value="{{ $filter_parent->id }}">
                <input type="hidden" name="master_id" value="{{ $master->id }}">
                <div class="modal-body overflow-auto" style="max-height: 400px;">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="modalDetails-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Child Filter</th>
                                    <th>Male</th>
                                    <th>Female</th>
                                    <th>Other</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($filter_parent->filter_values as $index => $filter_value)
                                    @php
                                        $id = $filter_value->id;
                                        $gender = isset($saved_data[$id])
                                            ? $saved_data[$id]
                                            : ['male' => 0, 'female' => 0, 'other' => 0];
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $filter_value->title }}</td>
                                        <td>
                                            <input type="number" name="filterValue[{{ $filter_value->id }}][male]"
                                                class="form-control male-input" value="{{ $gender['male'] ?? '' }}">
                                        </td>
                                        <td>
                                            <input type="number" name="filterValue[{{ $filter_value->id }}][female]"
                                                class="form-control female-input" value="{{ $gender['female'] ?? '' }}">
                                        </td>
                                        <td>
                                            <input type="number" name="filterValue[{{ $filter_value->id }}][other]"
                                                class="form-control other-input" value="{{ $gender['other'] ?? '' }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th>Total</th>
                                    <th class="text-center"><span id="male_total_modal">0.00</span>%</th>
                                    <th class="text-center"><span id="female_total_modal">0.00</span>%</th>
                                    <th class="text-center"><span id="other_total_modal">0.00</span>%</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    {{-- @if (count($saved_data) > 0)
                        <a class="btn btn-sm btn-primary"
                            href="{{ route('utilities.masters.addDetails', ['master_id' => $master->id, 'filter_id' => $filter->id]) }}">next</a>
                    @else --}}
                        <button type="submit" class="btn btn-sm btn-primary">Save</button>
                    {{-- @endif --}}
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('addDetailsModal');

        if (modal) {
            // Function to calculate and update totals
            function calculateTotals() {
                const maleInputs = document.querySelectorAll('.male-input');
                const femaleInputs = document.querySelectorAll('.female-input');
                const otherInputs = document.querySelectorAll('.other-input');

                let maleTotal = 0;
                let femaleTotal = 0;
                let otherTotal = 0;

                // Sum values for each gender
                maleInputs.forEach(input => {
                    const value = parseFloat(input.value) || 0;
                    if (value >= 0) maleTotal += value; // Ignore negative values
                });
                femaleInputs.forEach(input => {
                    const value = parseFloat(input.value) || 0;
                    if (value >= 0) femaleTotal += value;
                });
                otherInputs.forEach(input => {
                    const value = parseFloat(input.value) || 0;
                    if (value >= 0) otherTotal += value;
                });

                // Check if totals exceed 100% when isFix is enabled
                const isFixElement = document.getElementById('isFix');
                const isFix = isFixElement ? parseInt(isFixElement.value || '0') : 0;
                if (isFix !== 0 && (maleTotal > 100 || femaleTotal > 100 || otherTotal > 100)) {
                    alert('Total for each gender should not exceed 100%');
                }

                // Update total displays
                document.getElementById('male_total_modal').textContent = maleTotal.toFixed(2);
                document.getElementById('female_total_modal').textContent = femaleTotal.toFixed(2);
                document.getElementById('other_total_modal').textContent = otherTotal.toFixed(2);
            }

            // Function to attach input event listeners
            function attachInputListeners() {
                const inputs = document.querySelectorAll('.male-input, .female-input, .other-input');
                inputs.forEach(input => {
                    // Remove existing listeners to prevent duplicates
                    input.removeEventListener('input', calculateTotals);
                    input.addEventListener('input', calculateTotals);
                });
            }

            // Bootstrap 5 modal show event
            modal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const filterTitle = button.getAttribute('data-filter-value-title') || 'Details';
                const modalTitle = modal.querySelector('.modal-title');
                if (modalTitle) {
                    modalTitle.textContent = `Add Details: ${filterTitle}`;
                }

                // Attach input listeners and calculate initial totals
                attachInputListeners();
                calculateTotals();
            });

            // Recalculate totals when modal is fully shown to ensure values are updated
            modal.addEventListener('shown.bs.modal', calculateTotals);
        }
    });
</script>
