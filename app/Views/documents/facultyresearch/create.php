<div class="container mt-4">
    <div class="row d-flex min-vh-100">
        <div class="col-md-8">
            <form action="<?= base_url('documents/facultyResearch/create'); ?>" method="POST" enctype="multipart/form-data" class="row g-3">
                <!-- Contact Card -->
                <div class="card p-0">
                    <div class="card-header bg-red text-light fw-bold d-flex justify-content-between align-items-center">
                        <span>Upload Faculty Research</span>
                        <!-- <span class="small text-light">Last update: <?= $session->get('updated_at'); ?></span> -->
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Error messag -->
                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger alert-dismissible fade show mt-3 text-center small py-2 px-3" role="alert" style="font-size: 0.9rem;">
                                    <span class="small"><?= session()->getFlashdata('error') ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if (session()->getFlashdata('success')): ?>
                                <div class="alert alert-success alert-dismissible fade show mt-3 text-center small py-2 px-3" role="alert" style="font-size: 0.9rem;">
                                    <span class="small"><?= session()->getFlashdata('success') ?></span>
                                </div>
                            <?php endif; ?>
                            <!-- End of error message -->

                            <h6 class="card-title">Your Information</h6>
                            <hr>

                            <!-- User ID -->
                            <input type="hidden" class="form-control form-control-sm" name="user_id" required value="<?= $session->get('user_id'); ?>">

                            <div class="row mb-3">
                                <!-- First Name -->
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label">First Name</label>
                                    <input type="text" class="form-control form-control-sm" name="first_name" required value="<?= $session->get('first_name'); ?>" readonly>
                                </div>

                                <!-- Middle Name -->
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" class="form-control form-control-sm" name="middle_name" value="<?= $session->get('middle_name'); ?>" readonly>
                                </div>

                                <!-- Last Name -->
                                <div class="col-lg-3 col-md-6 mb-2">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" class="form-control form-control-sm" name="last_name" required value="<?= $session->get('last_name'); ?>" readonly>
                                </div>

                                <!-- Suffix -->
                                <div class="col-lg-3 col-md-6 mb-4">
                                    <label class="form-label">Suffix <small class="text-muted text-red">(optional)</small></label>
                                    <input type="text" class="form-control form-control-sm" name="suffix" value="<?= $session->get('suffix'); ?>" readonly>
                                </div>
                            </div>

                            <br>
                            <h6 class="card-title">Document Information</h6>
                            <hr class="">

                            <div class="row mb-3">
                                <!-- Department -->
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Department/Unit</label>
                                    <?php
                                    // Find the department name based on the department id stored in session
                                    $departmentName = '';
                                    $departmentId = $session->get('department');
                                    if (!empty($departmentData) && !empty($departmentId)) {
                                        foreach ($departmentData as $dept) {
                                            if ($dept['id'] == $departmentId) {
                                                $departmentName = $dept['name'];
                                                break;
                                            }
                                        }
                                    }
                                    ?>
                                    <input type="text" class="form-control form-control-sm" name="department" required value="<?= esc($departmentName); ?>" readonly>
                                    <input type="hidden" name="department_id" value="<?= esc($departmentId); ?>">
                                </div>

                                <!-- Thesis File -->
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">File</label>
                                    <input type="file" class="form-control form-control-sm" name="thesis_file" required accept=".pdf">
                                </div>

                                <!-- Thesis Title -->
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Title</label>
                                    <textarea class="form-control form-control-sm" name="thesis_title" required placeholder="Enter dissertation title" rows="3"></textarea>
                                </div>

                                <!-- Authors -->
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Authors</label>
                                    <!-- <input type="text" class="form-control form-control-sm" name="authors" required placeholder="Enter authors full names"> -->
                                    <textarea class="form-control form-control-sm" name="authors" required placeholder="Enter authors full names" rows="3"></textarea>
                                </div>

                                <!-- adviser -->
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Adviser<small class="text-muted text-red"></small></label>
                                    <select name="adviser_id" class="form-control form-control-sm" required>
                                        <option value="">Select Adviser</option>
                                        <?php foreach ($advisers as $adviser): ?>
                                            <option value="<?= esc($adviser['id']); ?>">
                                                <?= ucwords(strtolower(trim(
                                                    esc($adviser['first_name']) . ' ' .
                                                        esc($adviser['middle_name']) . ' ' .
                                                        esc($adviser['last_name']) . ' ' .
                                                        esc($adviser['suffix'])
                                                ))); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- tags -->
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Tags <small class="text-muted text-red">(optional)</small></label>
                                    <input type="text" class="form-control form-control-sm" name="tags" placeholder="Enter tags separated by commas">
                                </div>

                                <!-- Accept Terms -->
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Accept Terms</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="accept_terms" id="acceptTerms" required>
                                        <label class="form-check-label" for="acceptTerms">
                                            I accept the <a href="<?= base_url('terms') ?>" target="_blank">terms and conditions</a>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <hr class="">
                            <!-- Submit Button -->
                            <div class="col-md-12 mb-2 d-flex justify-content-end">
                                <button type="submit" class="btn btn-danger btn-sm px-5">
                                    <i class="fas fa-save me-2"></i>Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3 p-0">

                    <div class="bg-red text-light card-header fw-bold">
                        Submitted Faculty Research
                    </div>
                    <div class="card-body">
                        <table id="facultyResearchTable" class="table table-hover table-sm" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Department</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <?php if (!empty($submittedFacultyResearch) && is_array($submittedFacultyResearch)): ?>
                                <tbody>
                                    <?php foreach ($submittedFacultyResearch as $facultyResearch): ?>
                                        <tr>
                                            <td><?= esc($facultyResearch['title']); ?></td>
                                            <td><?= esc($facultyResearch['authors']); ?></td>
                                            <td><?= esc($facultyResearch['department_name'] ?? ''); ?></td>
                                            <td>
                                                <a href="<?= base_url('documents/facultyResearch/view/' . esc($facultyResearch['id'], 'url')); ?>" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View">
                                                    <i class="fas fa-eye"></i>
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

                    <!-- DataTables JS initialization -->
                    <script>
                        $(document).ready(function() {
                            let table = new DataTable('#facultyResearchTable');
                        });
                    </script>
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