<div class="container mt-4">
    <div class="row d-flex min-vh-100">
        <div class="col-md-8">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger text-center"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success text-center"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <div class="card mb-3 p-0">
                <div class="bg-red text-light card-header fw-bold">
                    List of Users
                </div>
                <div class="card-body table-responsive">
                    <table id="usersTable" class="table table-hover table-sm" style="width:100%">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>ID</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Last Name</th>
                                <th>Suffix</th>
                                <th>Email</th>
                                <th>Academic Status</th>
                                <th>Employment Status</th>
                                <th>College</th>
                                <th>Department</th>
                                <th>Profile Image</th>
                                <th>User Level</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th></th>
                            </tr>
                        </thead>
                        <?php if (!empty($userData) && is_array($userData)): ?>
                            <tbody>
                                <?php foreach ($userData as $user): ?>
                                    <tr>                                        
                                        # No. of loop
                                        <?php $no = isset($no) ? $no + 1 : 1; ?>
                                        <td><?= esc($no); ?></td>
                                        <td><?= esc($user['id']); ?></td>
                                        <td><?= esc($user['first_name']); ?></td>
                                        <td><?= esc($user['middle_name']); ?></td>
                                        <td><?= esc($user['last_name']); ?></td>
                                        <td><?= esc($user['suffix']); ?></td>
                                        <td><?= esc($user['email']); ?></td>
                                        <td class="text-capitalize"><?= esc($user['academic_status_text']); ?></td>
                                        <td class="text-capitalize"><?= esc($user['job_title_text']); ?></td>
                                        <!-- <td class="text-capitalize"><?= esc($user['job_title_text']); ?></td> -->
                                        <td class="text-capitalize"><?= esc($user['college_name']); ?></td>
                                        <td class="text-capitalize"><?= esc($user['department_name']); ?></td>
                                        <td><img src="<?= esc($user['profile_image']); ?>" alt="Profile Image" style="width:50px; height:50px;"></td>
                                        <td class="text-capitalize"><?= esc($user['user_level']); ?></td>
                                        <?php if ($user['status'] == '1') { ?>
                                            <td class="bg-success text-light text-capitalize">active</td>
                                        <?php } else { ?>
                                            <td class="bg-danger text-light text-capitalize">Inactive</td>
                                        <?php } ?>
                                        <td><?= esc($user['created_at']); ?></td>
                                        <td><?= esc($user['updated_at']); ?></td>
                                        <td>
                                            <a href="<?= base_url('documents/facultyResearch/view/' . esc($user['id'], 'url')); ?>" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        <?php else: ?>
                            <tbody></tbody>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

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

    $(document).ready(function() {
        let usersTable = new DataTable('#usersTable');
    });
</script>