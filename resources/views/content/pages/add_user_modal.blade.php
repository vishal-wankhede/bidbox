<style>
    .modal-simple .modal-content {
        padding: 1rem 0;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-user">
        <div class="modal-content">
            <div class="modal-body p-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h4 class="user-title mb-2 pb-0">vishskasd</h4>
                    <p>Fill in user details and assign permissions</p>
                </div>

                <!-- Add user form -->
                <form id="addUserForm" class="row g-3 px-4" method="POST" action="{{ route('users.store') }}">
                    @csrf
                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="first_name" name="first_name" class="form-control"
                                placeholder="First Name" required>
                            <label for="first_name">First Name</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="last_name" name="last_name" class="form-control"
                                placeholder="Last Name" required>
                            <label for="last_name">Last Name</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <select id="legal_entity" name="legal_entity" class="form-select" required>
                                <option value="person">Person</option>
                                <option value="company">Company</option>
                            </select>
                            <label for="legal_entity">Legal Entity</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="company_name" name="company_name" class="form-control"
                                placeholder="Company Name">
                            <label for="company_name">Company Name</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="country" name="country" class="form-control"
                                placeholder="Country" required>
                            <label for="country">Country</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="city" name="city" class="form-control" placeholder="City"
                                required>
                            <label for="city">City</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="text" id="phone" name="phone" class="form-control" placeholder="Phone"
                                required>
                            <label for="phone">Phone</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <select id="role" name="role" class="form-select" required>
                                <option value="">Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="sub-admin">Sub Admin</option>
                                <option value="manager">Manager</option>
                                <option value="executive">Executive</option>
                            </select>
                            <label for="role">Role</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="email" id="email" name="email" class="form-control" placeholder="Email"
                                required>
                            <label for="email">Email</label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating form-floating-outline">
                            <input type="password" id="password" name="password" class="form-control"
                                placeholder="Password" required>
                            <label for="password">Password</label>
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <h5>Assign Permissions</h5>
                        @include('_partials.permissions') <!-- Keep your existing permissions table here -->
                    </div>
                    <div class="col-12 mt-3" id="campaignListContainer" style="display: none;">
                    @if(count($campaigns))
                        <label for="campaigns">Select Campaigns:</label>
                        <select name="campaigns[]" id="campaigns" class="form-control" multiple>
                            @foreach($campaigns as $campaign)
                                <option value="{{ $campaign->id }}">{{ $campaign->campaign_name }}</option>
                            @endforeach
                        </select>
                    @else
                        <p>No campaigns available</p>
                    @endif
                </div>


                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary me-2">Submit</button>
                        <button type="reset" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
                <!--/ Add user form -->
            </div>
        </div>
    </div>
</div>
<!--/ Add User Modal -->

<script>
    $(document).ready(function () {
        $('#addUserModal').on('shown.bs.modal', function () {
            $('#campaigns').select2({
                placeholder: "Select Campaigns",
                width: '100%',
                dropdownParent: $('#addUserModal') // Ensures dropdown appears inside modal
            });
        });
    });
document.addEventListener('DOMContentLoaded', function () {
    const analyticsCheckbox = document.getElementById('perm_5'); // Use ID instead of value
    const campaignContainer = document.getElementById('campaignListContainer');

    if (analyticsCheckbox) {
        analyticsCheckbox.addEventListener('change', function () {
            campaignContainer.style.display = this.checked ? 'block' : 'none';
        });

        // Show on load if already checked
        if (analyticsCheckbox.checked) {
            campaignContainer.style.display = 'block';
        }
    }
});
</script>
