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
                <input type="hidden" name="Filter_parent_id" id="Filter_parent_id">
                <input type="hidden" name="master_id" id="master_id" value="{{ $master->id }}">
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
                            <tbody id="dynamic-filter-inputs">
                                <!-- JS will inject rows here -->
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
                    <button id="saveBtn" type="submit" class="btn btn-sm btn-primary">Save</button>
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
                const maleInputs = modal.querySelectorAll('.male-input');
                const femaleInputs = modal.querySelectorAll('.female-input');
                const otherInputs = modal.querySelectorAll('.other-input');

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
                modal.querySelector('#male_total_modal').textContent = maleTotal.toFixed(2);
                modal.querySelector('#female_total_modal').textContent = femaleTotal.toFixed(2);
                modal.querySelector('#other_total_modal').textContent = otherTotal.toFixed(2);
            }

            // Function to attach input event listeners
            function attachInputListeners() {
                const inputs = modal.querySelectorAll('.male-input, .female-input, .other-input');
                inputs.forEach(input => {
                    // Remove existing listeners to prevent duplicates
                    input.removeEventListener('input', calculateTotals);
                    input.addEventListener('input', calculateTotals);
                });
            }

            // Bootstrap 5 modal show event
            modal.addEventListener('show.bs.modal', async function(event) {
                const button = event.relatedTarget;
                const filterTitle = button.getAttribute('data-filter-value-title') || 'Details';
                const filterValueId = button.getAttribute('data-filter-value-id');
                const master_id = modal.querySelector('#master_id').value;

                // Scope saveBtn to this modal instance
                const saveBtn = modal.querySelector('#saveBtn');
                const filterParentInput = modal.querySelector('#Filter_parent_id');

                // Reset save button state
                saveBtn.disabled = false;

                // Set Filter_parent_id
                filterParentInput.value = filterValueId;

                // Set modal title
                const modalTitle = modal.querySelector('.modal-title');
                if (modalTitle) {
                    modalTitle.textContent = `Add Details: ${filterTitle} - ${filterValueId}`;
                }

                // Clear previous rows
                const tbody = modal.querySelector('#dynamic-filter-inputs');
                tbody.innerHTML = `<tr><td colspan="5">Loading...</td></tr>`;

                try {
                    const BASE_URL = "{{ url('/') }}";
                    const res = await fetch(
                        `${BASE_URL}/utilities/masters/load-filter-values/${filterValueId}/${master_id}`
                    );
                    const json = await res.json();

                    // Disable save button if data is already saved
                    if (json.savedData === true) {
                        saveBtn.disabled = true;
                    }

                    // Generate table rows
                    const rowsHtml = json.data.map((item, index) => `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.title}</td>
                        <td>
                            <input type="number" name="filterValue[${item.id}][male]"
                                class="form-control male-input" value="${item.male}">
                        </td>
                        <td>
                            <input type="number" name="filterValue[${item.id}][female]"
                                class="form-control female-input" value="${item.female}">
                        </td>
                        <td>
                            <input type="number" name="filterValue[${item.id}][other]"
                                class="form-control other-input" value="${item.other}">
                        </td>
                    </tr>
                `).join('');

                    tbody.innerHTML = rowsHtml;

                    // Re-attach events
                    attachInputListeners();
                    calculateTotals();
                } catch (err) {
                    tbody.innerHTML = `<tr><td colspan="5">Failed to load data</td></tr>`;
                    console.error('Error loading filter values:', err);
                }
            });

            // Recalculate totals when modal is fully shown
            modal.addEventListener('shown.bs.modal', calculateTotals);

            // Reset modal state when hidden to prevent lingering values
            modal.addEventListener('hidden.bs.modal', function() {
                const tbody = modal.querySelector('#dynamic-filter-inputs');
                tbody.innerHTML = ''; // Clear table body
                modal.querySelector('#Filter_parent_id').value = ''; // Reset Filter_parent_id
                modal.querySelector('#male_total_modal').textContent = '0.00'; // Reset totals
                modal.querySelector('#female_total_modal').textContent = '0.00';
                modal.querySelector('#other_total_modal').textContent = '0.00';
                modal.querySelector('#saveBtn').disabled = false; // Reset save button
            });
        }
    });
</script>
