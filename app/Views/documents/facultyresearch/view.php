<div class="container mt-4">
    <div class="row d-flex min-vh-100">
        <div class="col-md-8">
            <form action="<?= base_url('documents/facultyResearch/edit/' . $facultyResearch[0]['id']); ?>" method="POST" enctype="multipart/form-data" class="row g-3">
                <!-- Contact Card -->
                <div class="card p-0">
                    <div class="card-header bg-red text-light fw-bold d-flex justify-content-between align-items-center">
                        <span>View Faculty Research</span>
                        <!-- <span class="small text-light">
                            <i class="fas fa-eye me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="View Count"></i><?= $facultyResearch[0]['view_count']; ?>
                            <i class="fas fa-download ms-2 me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Download Count"></i><?= $facultyResearch[0]['download_count']; ?>

                        </span> -->
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

                            <!-- User ID -->
                            <input type="hidden" class="form-control form-control-sm" name="user_id" required value="<?= $session->get('user_id'); ?>">

                            <br>
                            <h6 class="card-title">Document Information</h6>
                            <hr class="">

                            <div class="row mb-3">
                                <!-- Thesis Title -->
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Title</label>
                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        name="thesis_title"
                                        required
                                        placeholder="Enter faculty research title"
                                        value="<?= trim(esc($facultyResearch[0]['title'])) ?>"
                                        <?= ($session->get('user_id') == $facultyResearch[0]['user_id'] ? '' : 'disabled'); ?>>
                                </div>

                                <!-- Department -->
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Department/Unit</label>
                                    <select name="department_id" class="form-control form-control-sm" required
                                        <?= ($session->get('user_id') != $facultyResearch[0]['user_id']) ? 'disabled' : ''; ?>>
                                        <option value="">Select Department</option>
                                        <?php foreach ($department as $dept): ?>
                                            <option value="<?= esc($dept['id']); ?>"
                                                <?= ($facultyResearch[0]['department_id'] == $dept['id']) ? 'selected' : ''; ?>>
                                                <?= esc($dept['name']); ?>
                                                <?php
                                                if ($facultyResearch[0]['department_id'] == $dept['id']) {
                                                    if ($session->get('user_id') == $facultyResearch[0]['user_id']) {
                                                        echo ' (Current)';
                                                    }
                                                }
                                                ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Authors -->
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Authors</label>
                                    <!-- <input type="text" class="form-control form-control-sm" name="authors" required placeholder="Enter authors full names" value=""> -->
                                    <input type="text" class="form-control form-control-sm" name="authors" required placeholder="Enter authors full names" value="<?= trim(esc($facultyResearch[0]['authors'])) ?>"
                                        <?= ($session->get('user_id') != $facultyResearch[0]['user_id']) ? 'disabled' : ''; ?>>
                                </div>

                                <!-- adviser -->
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Adviser<small class="text-muted text-red"></small></label>
                                    <select name="adviser_id" class="form-control form-control-sm" required
                                        <?= ($session->get('user_id') != $facultyResearch[0]['user_id']) ? 'disabled' : ''; ?>>
                                        <option value="">Select Adviser</option>
                                        <?php foreach ($advisers as $adviser): ?>
                                            <option value="<?= esc($adviser['id']); ?>"
                                                <?= ($facultyResearch[0]['adviser_id'] == $adviser['id']) ? 'selected' : ''; ?>>
                                                <?= ucwords(strtolower(trim(
                                                    esc($adviser['first_name']) . ' ' .
                                                        esc($adviser['middle_name']) . ' ' .
                                                        esc($adviser['last_name']) . ' ' .
                                                        esc($adviser['suffix'])
                                                ))); ?>
                                                <?php
                                                if ($facultyResearch[0]['adviser_id'] == $adviser['id']) {
                                                    if ($session->get('user_id') == $facultyResearch[0]['user_id']) {
                                                        echo ' (Current)';
                                                    }
                                                }
                                                ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- tags -->
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Tags <?= ($session->get('user_id') == $facultyResearch[0]['user_id']) ? '<small class="text-muted text-red">(optional)</small>' : ''; ?></label>
                                    <input type="text" class="form-control form-control-sm" name="tags" placeholder="Enter tags separated by commas"
                                        value="<?= trim(esc($facultyResearch[0]['tags'])) ?>"
                                        <?= ($session->get('user_id') != $facultyResearch[0]['user_id']) ? 'disabled' : ''; ?>>
                                </div>

                                <!-- Accept Terms
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Accept Terms</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="accept_terms" id="acceptTerms" required>
                                        <label class="form-check-label" for="acceptTerms">
                                            I accept the <a href="<?= base_url('terms') ?>" target="_blank">terms and conditions</a>
                                        </label>
                                    </div>
                                </div> -->
                            </div>
                            <br>
                            <hr class="">
                            <!-- Submit Button -->
                            <div class="col-md-12 mb-2 d-flex justify-content-end gap-2">
                                <a href="<?= base_url('documents/facultyResearch/download/' . $facultyResearch[0]['id']); ?>" class="btn btn-danger btn-sm px-5">
                                    <i class="fas fa-download me-2"></i>Download
                                </a>
                                <?php
                                if ($session->get('user_id') == $facultyResearch[0]['user_id']) {
                                    echo '<button type="submit" class="btn btn-danger btn-sm px-5">
                                        <i class="fas fa-sync-alt me-2"></i>Save Edit
                                        </button>';
                                }
                                ?>
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