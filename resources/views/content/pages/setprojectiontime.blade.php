<!-- Set Projection Details Modal -->
<div class="modal fade" id="setProjectionTimeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-set-projection-time">
        <div class="modal-content">
            <div class="modal-header sticky-top bg-white p-4 border-bottom">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="w-100 text-center">
                    <h4 class="mb-2 pb-0">Set Projection Time</h4>
                    <p class="mb-2">Fill in Projection details daily basis</p>
                    <strong>Total: <span id="dateTotal" style="color:red;">0</span>%</strong>
                </div>
            </div>
            <div class="modal-body p-4" style="max-height: 60vh; overflow-y: auto;">
                <div id="projectionDatesContainer" class="row g-3"></div>
                <div class="container px-0 pb-4">
                    <div class="text-end mt-2">
                        <button type="button" class="btn btn-primary" id="savedateBtn">Save</button>
                    </div>
                </div>

                <!-- Second Modal for Hour-wise -->
                <div class="modal fade" id="setHourModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-simple"
                        style="position: fixed; top: 15%; left: 9%; transform: translateX(-5%);">
                        <div class="modal-content">
                            <div class="modal-body p-2">
                                <button type="button" class="btn-close float-end" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                                <h5 class="mb-3">Set 24-Hour Distribution for <span id="hourModalDate"></span></h5>
                                <div id="hourInputs" class="row g-2"></div>
                                <div class="text-end mt-3">
                                    <strong>Total: <span id="hourDistributionTotal"
                                            style="color:red;">0</span>%</strong>
                                </div>
                                <div class="text-end mt-2">
                                    <button type="button" class="btn btn-outline-secondary" id="applyToAllBtn">Apply to
                                        All Days</button>
                                    <button type="button" class="btn btn-primary" id="saveHourBtn">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var projectionData = {};

    document.addEventListener('DOMContentLoaded', function() {
        const mainModal = document.getElementById('setProjectionTimeModal');
        const hourModalInstance = new bootstrap.Modal(document.getElementById('setHourModal'));
        const projectionContainer = document.getElementById('projectionDatesContainer');
        const hourInputs = document.getElementById('hourInputs');
        const hourModalDateLabel = document.getElementById('hourModalDate');
        const dateTotalSpan = document.getElementById('dateTotal');
        const hourTotalSpan = document.getElementById('hourDistributionTotal');
        const saveHourBtn = document.getElementById('saveHourBtn');
        const applyToAllBtn = document.getElementById('applyToAllBtn');
        const saveDateBtn = document.getElementById('savedateBtn');

        function generateDatePercentages(dateCount) {
            const base = Math.floor(100 / dateCount);
            let remaining = 100 - (base * dateCount);
            let result = Array(dateCount).fill(base);
            for (let i = 0; i < remaining; i++) result[i]++;
            return result;
        }

        function generateHourlyPercentages() {
            const base = Math.floor(100 / 24);
            let remaining = 100 - (base * 24);
            let result = Array(24).fill(base);
            for (let i = 0; i < remaining; i++) result[i]++;
            return result;
        }

        function getDateRange(startDate, endDate) {
            const dates = [];
            let tempDate = new Date(startDate);
            while (tempDate <= endDate) {
                dates.push(new Date(tempDate).toISOString().split('T')[0]);
                tempDate.setDate(tempDate.getDate() + 1);
            }
            return dates;
        }

        function renderDateRows() {
            projectionContainer.innerHTML = '';

            const startInput = document.querySelector('[name="start_date"]');
            const endInput = document.querySelector('[name="end_date"]');

            const startDate = new Date(startInput.value);
            const endDate = new Date(endInput.value);

            if (isNaN(startDate) || isNaN(endDate) || startDate > endDate) {
                projectionContainer.innerHTML =
                    '<div class="col-12 text-danger">Invalid Start or End Date.</div>';
                dateTotalSpan.textContent = 0;
                dateTotalSpan.style.color = 'red';
                return;
            }

            const dates = getDateRange(startDate, endDate);
            const datePercentages = generateDatePercentages(dates.length);
            let totalDatePercentage = 0;

            dates.forEach((dateStr, index) => {
                if (!projectionData[dateStr] || !projectionData[dateStr].hasOwnProperty('percentage')) {
                    projectionData[dateStr] = {
                        percentage: datePercentages[index],
                        hourlyPercentages: generateHourlyPercentages(),
                        hourlyTotal: 100,
                        saved: false
                    };
                } else {
                    projectionData[dateStr].percentage = parseInt(projectionData[dateStr].percentage) ||
                        datePercentages[index];
                }
            });

            dates.forEach(dateStr => {
                const savedStatus = projectionData[dateStr].saved;
                const datePercentage = projectionData[dateStr].percentage;
                totalDatePercentage += datePercentage;

                projectionContainer.innerHTML += `
                <div class="col-12 d-flex justify-content-between align-items-center border-bottom py-2">
                  <div class="d-flex align-items-center flex-wrap">
                      <strong>${dateStr}</strong>
                      <input type="number" class="form-control form-control-sm ms-3 me-1 date-percentage-input" style="width: 70px;" value="${datePercentage}" data-date="${dateStr}" min="0" step="1" />
                      <span class="me-2">%${savedStatus ? '<span class="badge bg-success ms-2">Saved</span>' : ''}</span>
                      <button type="button" class="btn btn-sm btn-outline-secondary apply-date-percentage ms-2" data-date="${dateStr}">Apply to All</button>
                  </div>
                  <button type="button" class="btn btn-sm btn-outline-primary" onclick="openHourModal('${dateStr}')">
                      ${savedStatus ? 'Edit' : 'Set 24-Hour Projection'}
                  </button>
              </div>
              `;
            });

            dateTotalSpan.textContent = totalDatePercentage;
            dateTotalSpan.style.color = totalDatePercentage === 100 ? 'green' : 'red';

            projectionContainer.querySelectorAll('.date-percentage-input').forEach(input => {
                input.addEventListener('input', function() {
                    const dateStr = this.dataset.date;
                    const value = parseInt(this.value) || 0;
                    if (value < 0) {
                        this.value = 0;
                        return;
                    }
                    projectionData[dateStr].percentage = value;

                    let total = 0;
                    Object.keys(projectionData).forEach(dateKey => {
                        total += parseInt(projectionData[dateKey].percentage) || 0;
                    });

                    dateTotalSpan.textContent = total;
                    dateTotalSpan.style.color = total === 100 ? 'green' : 'red';
                });
            });

            projectionContainer.querySelectorAll('.apply-date-percentage').forEach(btn => {
                btn.addEventListener('click', function() {
                    const sourceDate = this.dataset.date;
                    const sourceValue = parseInt(projectionData[sourceDate].percentage) || 0;

                    Object.keys(projectionData).forEach(dateKey => {
                        projectionData[dateKey].percentage = sourceValue;
                    });

                    renderDateRows();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: `Applied ${sourceValue}% to all days.`,
                        confirmButtonText: 'OK'
                    });
                });
            });
        }

        window.openHourModal = function(dateStr) {
            hourModalDateLabel.textContent = dateStr;
            hourInputs.innerHTML = '';

            const data = projectionData[dateStr].hourlyPercentages;

            data.forEach((val, hour) => {
                hourInputs.innerHTML += `
                    <div class="col-md-3 d-flex align-items-center">
                        <label class="me-1 mb-0">${hour}:00</label>
                        <input type="number" class="form-control hour-input" data-hour="${hour}" value="${val}" min="0" step="1" />
                    </div>`;
            });

            updateHourTotal();

            hourInputs.querySelectorAll('.hour-input').forEach(input => {
                input.addEventListener('input', function() {
                    const value = parseInt(input.value);
                    if (isNaN(value) || value < 0) input.value = 0;
                    updateHourTotal();
                });
            });

            saveHourBtn.onclick = function() {
                const inputs = hourInputs.querySelectorAll('.hour-input');
                let values = [];
                let total = 0;

                inputs.forEach(input => {
                    const val = parseInt(input.value) || 0;
                    values.push(val);
                    total += val;
                });

                if (total !== 100) {
                    Swal.fire({
                        icon: 'error',
                        title: 'error',
                        text: `24-hour distribution must total 100% to save.`,
                    });
                    return;
                }

                projectionData[dateStr] = {
                    percentage: projectionData[dateStr].percentage,
                    hourlyPercentages: values,
                    hourlyTotal: total,
                    saved: true
                };

                renderDateRows();
                hourModalInstance.hide();
            };

            hourModalInstance.show();
        };

        function updateHourTotal() {
            let total = 0;
            hourInputs.querySelectorAll('.hour-input').forEach(input => {
                total += parseInt(input.value) || 0;
            });
            hourTotalSpan.textContent = total;
            hourTotalSpan.style.color = total === 100 ? 'green' : 'red';
        }

        applyToAllBtn.onclick = function() {
            const inputs = hourInputs.querySelectorAll('.hour-input');
            let values = [];
            let total = 0;

            inputs.forEach(input => {
                const val = parseInt(input.value) || 0;
                values.push(val);
                total += val;
            });

            if (total !== 100) {
                Swal.fire({
                    icon: 'error',
                    title: 'error',
                    text: `24-hour distribution must total 100% before applying to all days.`,
                });
                return;
            }

            Object.keys(projectionData).forEach(date => {
                projectionData[date].hourlyPercentages = [...values];
                projectionData[date].hourlyTotal = total;
                projectionData[date].saved = true;
            });
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: `Applied current 24-hour distribution to all days.`,
                confirmButtonText: 'OK'
            });
            renderDateRows();
        };

        saveDateBtn.addEventListener('click', function(e) {
            const total = parseInt(dateTotalSpan.textContent) || 0;
            if (total !== 100) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'error',
                    text: `Date percentages must total exactly 100% to save.`,
                });
                return;
            }

            console.log("Final projectionData", projectionData);
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: `data saved successfully.`,
            });
        });

        mainModal.addEventListener('show.bs.modal', renderDateRows);
    });
</script>
