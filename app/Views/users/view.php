<div class="container mt-4">
    <div class="row d-flex min-vh-100">
        <div class="col-md-8 mb-3">
            <form action="<?= base_url('users/edit/' . $user['id']); ?>" method="POST" enctype="multipart/form-data" class="row g-3">
                <!-- Contact Card -->
                <div class="card p-0">
                    <div class="card-header bg-red text-light fw-bold d-flex justify-content-between align-items-center">
                        <span>View User</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Error message -->
                            <?php if ($error = session()->getFlashdata('error')): ?>
                                <?php if (is_array($error)): ?>
                                    <?php foreach ($error as $err): ?>
                                        <div class="alert alert-danger alert-dismissible fade show mt-3 text-center small py-2 px-3" role="alert" style="font-size: 0.9rem;">
                                            <span class="small"><?= esc($err) ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="alert alert-danger alert-dismissible fade show mt-3 text-center small py-2 px-3" role="alert" style="font-size: 0.9rem;">
                                        <span class="small"><?= esc($error) ?></span>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php if ($success = session()->getFlashdata('success')): ?>
                                <div class="alert alert-success alert-dismissible fade show mt-3 text-center small py-2 px-3" role="alert" style="font-size: 0.9rem;">
                                    <span class="small"><?= esc($success) ?></span>
                                </div>
                            <?php endif; ?>
                            <!-- End of error message -->

                            <!-- User ID -->
                            <input type="hidden" class="form-control form-control-sm" name="user_id" required value="<?= $session->get('user_id'); ?>">

                            <br>
                            <h6 class="card-title">User Information</h6>
                            <hr class="">

                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <label class="form-label">First Name</label>
                                    <input
                                        type="text"
                                        class="form-control form-control-sm text-capitalize"
                                        name="first_name"
                                        required
                                        placeholder="Enter first name"
                                        value="<?= trim(esc($user['first_name'])) ?>">
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label class="form-label">Middle Name</label>
                                    <input
                                        type="text"
                                        class="form-control form-control-sm text-capitalize"
                                        name="middle_name"
                                        placeholder="Enter middle name"
                                        value="<?= trim(esc($user['middle_name'])) ?>">
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label class="form-label">Last Name</label>
                                    <input
                                        type="text"
                                        class="form-control form-control-sm text-capitalize"
                                        name="last_name"
                                        placeholder="Enter last name"
                                        value="<?= trim(esc($user['last_name'])) ?>">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Suffix</label>
                                    <input
                                        type="text"
                                        class="form-control form-control-sm text-capitalize"
                                        name="suffix"
                                        placeholder="Enter suffix"
                                        value="<?= trim(esc($user['suffix'])) ?>">
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Email</label>
                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        name="email"
                                        required
                                        placeholder="Enter email"
                                        value="<?= trim(esc($user['email'])) ?>">
                                </div>


                                <br>
                                <!-- Academic Status -->
                                <div class="col-md-4 mb-4">
                                    <label class="form-label">Academic Status</label>
                                    <select name="academic_status" class="form-control form-control-sm" required>
                                        <option value="">Select Academic Status</option>
                                        <?php foreach ($academicStatusData as $academicStatus): ?>
                                            <option value="<?= esc($academicStatus['id']); ?>"
                                                <?= ($user['academic_status'] == $academicStatus['id']) ? 'selected' : ''; ?>>
                                                <?= esc($academicStatus['status']); ?> <?= ($user['academic_status'] == $academicStatus['id']) ? '(Current)' : ''; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Employment Status -->
                                <div class="col-md-4 mb-4">
                                    <label class="form-label">Employment Status</label>
                                    <select name="employment_status" class="form-control form-control-sm" required>
                                        <option value="">Select Employment Status</option>
                                        <?php foreach ($jobTitleData as $jobTitle): ?>
                                            <option value="<?= esc($jobTitle['id']); ?>"
                                                <?= ($user['employment_status'] == $jobTitle['id']) ? 'selected' : ''; ?>>
                                                <?= esc($jobTitle['title']); ?> <?= ($user['employment_status'] == $jobTitle['id']) ? '(Current)' : ''; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- College -->
                                <div class="col-md-4 mb-4">
                                    <label class="form-label">College</label>
                                    <select name="college" class="form-control form-control-sm" required>
                                        <option value="">Select College</option>
                                            <?php foreach ($collegeData as $college): ?>
                                            <option value="<?= esc($college['id']); ?>"
                                                <?= ($user['college'] == $college['id']) ? 'selected' : ''; ?>>
                                                <?= esc($college['name']); ?> <?= ($user['college'] == $college['id']) ? '(Current)' : ''; ?>
                                                </option>
                                            <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Department -->
                                <div class="col-md-4 mb-4">
                                    <label class="form-label">Department</label>
                                    <select name="department_id" class="form-control form-control-sm" required>
                                        <option value="">Select Department</option>
                                            <?php foreach ($departmentData as $dept): ?>
                                            <option value="<?= esc($dept['id']); ?>"
                                                <?= ($user['department_id'] == $dept['id']) ? 'selected' : ''; ?>>
                                                <?= esc($dept['name']); ?> <?= ($user['department_id'] == $dept['id']) ? '(Current)' : ''; ?>
                                                </option>
                                            <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- User level -->
                                <div class="col-md-4 mb-4">
                                    <label class="form-label">User Level</label>
                                    <select name="user_level" class="form-control form-control-sm" required>
                                        <option value="admin" <?= ($user['user_level'] == 'admin') ? 'selected' : ''; ?>>Admin <?= ($user['user_level'] == 'admin') ? '(Current)' : ''; ?></option>
                                        <option value="masters" <?= ($user['user_level'] == 'masters') ? 'selected' : ''; ?>>Masters <?= ($user['user_level'] == 'masters') ? '(Current)' : ''; ?></option>
                                        <option value="faculty" <?= ($user['user_level'] == 'faculty') ? 'selected' : ''; ?>>Faculty <?= ($user['user_level'] == 'faculty') ? '(Current)' : ''; ?></option>
                                        <option value="librarian" <?= ($user['user_level'] == 'librarian') ? 'selected' : ''; ?>>Librarian <?= ($user['user_level'] == 'librarian') ? '(Current)' : ''; ?></option>
                                    </select>
                                </div>

                                <!-- Status -->
                                <div class="col-md-4 mb-4">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control form-control-sm" required>
                                        <option value="1" <?= ($user['status'] == '1') ? 'selected' : ''; ?>>Active <?= ($user['status'] == '1') ? '(Current)' : ''; ?></option>
                                        <option value="0" <?= ($user['status'] == '0') ? 'selected' : ''; ?>>Inactive <?= ($user['status'] == '0') ? '(Current)' : ''; ?></option>
                                    </select>
                                </div>
                            
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Password</label>
                                    <input
                                        type="password"
                                        class="form-control form-control-sm"
                                        name="password"
                                        placeholder="Enter password"
                                        value="">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Confirm Password</label>
                                    <input
                                        type="password"
                                        class="form-control form-control-sm"
                                        name="confirm_password"
                                            placeholder="Enter confirm password"
                                            value="">
                                </div>
                                <!-- Note -->
                                <div class="col-md-12 mb-4">
                                    <div class="bg-light border-secondary p-2 border-1 rounded-3 form-control form-control-sm" style="border-style: dashed;">
                                        <i class="fas fa-info-circle me-2 text-muted"></i><small class="text-muted">Leave blank to keep current password</small>
                                    </div>
                                </div>

                            </div>

                            <hr class="">
                            <!-- Submit Button -->
                            <div class="col-md-12 mb-2 d-flex justify-content-end gap-2">
                                <a href="<?= base_url('users'); ?>" class="btn btn-secondary btn-sm">Back</a>
                                <button type="submit" class="btn btn-danger btn-sm">Update User</button>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- Right column: Sidebar -->
        <div class="col-md-4 mb-3">
            <?= view('template/sidebar') ?>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>