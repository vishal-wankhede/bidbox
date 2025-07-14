<!-- Inventory Modal -->
<div class="modal fade" id="inventoryModal" tabindex="-1" aria-labelledby="inventoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">All Inventory Performance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="inventoryTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Inventory Source</th>
                            <th>Impressions</th>
                            <th>CTR</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($report['inventories'] as $name => $data)
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

<!-- Cohort Modal -->
<div class="modal fade" id="cohortModal" tabindex="-1" aria-labelledby="cohortModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">All Cohorts Analysis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table id="cohortTable" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Cohort</th>
                            <th>Impressions</th>
                            <th>CTR</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($report['cohorts'] as $name => $data)
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var inventoryModal = document.getElementById('inventoryModal');
        inventoryModal.addEventListener('shown.bs.modal', function() {
            $('#inventoryTable').DataTable({
                scrollX: true,
                responsive: true,
                autoWidth: false,
                paging: true,
                searching: true,
                order: [
                    [1, 'desc']
                ],
            });
        });

        var cohortModal = document.getElementById('cohortModal');
        cohortModal.addEventListener('shown.bs.modal', function() {
            $('#cohortTable').DataTable({
                scrollX: true,
                responsive: true,
                autoWidth: false,
                paging: true,
                searching: true,
                order: [
                    [1, 'desc']
                ],
            });
        });
    });
</script>
