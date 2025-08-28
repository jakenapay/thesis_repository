<div class="container mt-4">
    <div class="row d-flex min-vh-100">
        <div class="col-md-8">
            <form action="<?= base_url('documents/dissertations/edit/' . $dissertations[0]['id']); ?>" method="POST" enctype="multipart/form-data" class="row g-3">
                <!-- Contact Card -->
                <div class="card p-0">
                    <div class="card-header bg-red text-light fw-bold d-flex justify-content-between align-items-center">
                        <span>View Dissertations</span>
                        <!-- <span class="small text-light">
                            <i class="fas fa-eye me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="View Count"></i><?= $dissertations[0]['view_count']; ?>
                            <i class="fas fa-download ms-2 me-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Download Count"></i><?= $dissertations[0]['download_count']; ?>

                        </span> -->
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
                                        placeholder="Enter dissertation title"
                                        value="<?= trim(esc($dissertations[0]['title'])) ?>"
                                        <?= ($session->get('user_id') == $dissertations[0]['user_id'] && $dissertations[0]['status'] == 'rejected' && $session->get('user_id') != $dissertations[0]['adviser_id']) ? '' : 'disabled'; ?>>
                                </div>

                                <!-- Department -->
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Department/Unit</label>
                                    <select name="department_id" class="form-control form-control-sm" required
                                        <?= ($session->get('user_id') == $dissertations[0]['user_id'] && $dissertations[0]['status'] == 'rejected' && $session->get('user_id') != $dissertations[0]['adviser_id'] && $dissertations[0]['status'] != 'rejected') ? '' : 'disabled'; ?>>
                                        <option value="">Select Department</option>
                                        <?php foreach ($department as $dept): ?>
                                            <option value="<?= esc($dept['id']); ?>"
                                                <?= ($dissertations[0]['department_id'] == $dept['id']) ? 'selected' : ''; ?>>
                                                <?= esc($dept['name']); ?>
                                                <?php
                                                if ($dissertations[0]['department_id'] == $dept['id']) {
                                                    if ($session->get('user_id') == $dissertations[0]['user_id']) {
                                                        echo ' (Current)';
                                                    }
                                                }
                                                ?>
                                                </opt ion>
                                            <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- Authors -->
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Authors</label>
                                    <!-- <input type="text" class="form-control form-control-sm" name="authors" required placeholder="Enter authors full names" value=""> -->
                                    <input type="text" class="form-control form-control-sm" name="authors" required placeholder="Enter authors full names" value="<?= trim(esc($dissertations[0]['authors'])) ?>"
                                        <?= ($session->get('user_id') == $dissertations[0]['user_id'] && $dissertations[0]['status'] == 'rejected' && $session->get('user_id') != $dissertations[0]['adviser_id']) ? '' : 'disabled'; ?>>
                                </div>

                                <!-- adviser -->
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Adviser<small class="text-muted text-red"></small></label>
                                    <select name="adviser_id" class="form-control form-control-sm" required
                                        <?= ($session->get('user_id') == $dissertations[0]['user_id'] &&
                                            $dissertations[0]['status'] == 'rejected' &&
                                            $session->get('user_id') != $dissertations[0]['adviser_id'] &&
                                            $dissertations[0]['status'] != 'rejected') ? '' : 'disabled'; ?>>
                                        <option value="">Select Adviser</option>
                                        <?php foreach ($advisers as $adviser): ?>
                                            <option value="<?= esc($adviser['id']); ?>"
                                                <?= ($dissertations[0]['adviser_id'] == $adviser['id']) ? 'selected' : ''; ?>>
                                                <?= ucwords(strtolower(trim(
                                                    esc($adviser['first_name']) . ' ' .
                                                        esc($adviser['middle_name']) . ' ' .
                                                        esc($adviser['last_name']) . ' ' .
                                                        esc($adviser['suffix'])
                                                ))); ?>
                                                <?php
                                                if ($dissertations[0]['adviser_id'] == $adviser['id']) {
                                                    if ($session->get('user_id') == $dissertations[0]['user_id']) {
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
                                    <label class="form-label">Tags</label>
                                    <input type="text" class="form-control form-control-sm" name="tags" placeholder="Enter tags separated by commas"
                                        value="<?= trim(esc($dissertations[0]['tags'])) ?>"
                                        <?= ($session->get('user_id') == $dissertations[0]['user_id'] && $dissertations[0]['status'] == 'rejected' && $session->get('user_id') != $dissertations[0]['adviser_id']) ? '' : 'disabled'; ?>>
                                </div>

                                <!-- Status -->
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Status</label>
                                    <input type="text" class="<?= ($dissertations[0]['status'] == 'rejected') ? 'bg-danger text-light' : '' ?> form-control form-control-sm text-capitalize" name="status" value="<?= (!empty($dissertations[0]['status'])) ? $dissertations[0]['status'] : '' ?>" readonly>
                                </div>

                                <!-- Thesis File -->
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">File <?= ($session->get('user_id') == $dissertations[0]['user_id']) ? '<small class="text-muted text-red">(optional)</small>' : ''; ?></label>
                                    <input type="file" class="form-control form-control-sm" name="thesis_file" accept=".pdf"
                                        <?= ($session->get('user_id') == $dissertations[0]['user_id'] && $dissertations[0]['status'] == 'rejected' && $session->get('user_id') != $dissertations[0]['adviser_id']) ? '' : 'disabled'; ?>>
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

                            <!-- Status -->
                            <?php
                            $user_level = $session->get('user_level');
                            $is_adviser = $session->get('is_adviser');
                            $current_status = $dissertations[0]['status'];

                            $options = [];

                            if ($user_level === 'faculty' && $is_adviser == 1) {
                                $options = ['submitted', 'endorsed', 'rejected'];
                            } elseif ($user_level === 'librarian') {
                                $options = ['submitted', 'published', 'rejected'];
                            }

                            $status_labels = [
                                'submitted' => 'Submitted',
                                'endorsed' => 'Endorsed',
                                'published' => 'Published',
                                'rejected' => 'Rejected'
                            ];
                            ?>
                            <br>
                            <?php if ($user_level == 'librarian' && $session->get('user_id') == $dissertations[0]['adviser_id'] || $user_level == 'faculty' && $session->get('user_id') == $dissertations[0]['adviser_id']) { ?>
                                <h6 class="card-title"><?= ($is_adviser == 1 && $user_level == 'faculty') ? "Adviser's" : "Librarian's" ?> Panel</h6>
                                <hr class="">
                                <div class="row mb-3">

                                    <div class="col-md-6 mb-4">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-control form-control-sm" required>
                                            <?php foreach ($options as $opt): ?>
                                                <option value="<?= $opt ?>" <?= ($current_status == $opt) ? 'selected' : '' ?>>
                                                    <?= $status_labels[$opt] ?> <?= ($current_status == $opt) ? '(Current)' : '' ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <!-- Remarks-->
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label" for="remarks">Feedbacks</label>
                                        <textarea name="remarks" id="remarks" class="form-control form-control-sm"></textarea>
                                    </div>
                                </div>

                            <?php } ?>

                            <br>
                            <hr class="">
                            <!-- Submit Button -->
                            <div class="col-md-12 mb-2 d-flex justify-content-end gap-2">
                                <a href="<?= base_url('documents/dissertations/download/' . $dissertations[0]['id']); ?>" class="btn btn-danger btn-sm px-5">
                                    <i class="fas fa-download me-2"></i>Download
                                </a>
                                <?php
                                // If the current user ID is match with the adviser ID = user can save edit
                                if ($session->get('user_id') == $dissertations[0]['adviser_id'] || $session->get('user_level') === 'librarian') {
                                    echo '<button type="submit" name="action" value="update" class="btn btn-danger btn-sm px-5">
                                        <i class="fas fa-sync-alt me-2"></i>Update
                                        </button>';
                                }

                                if ($session->get('user_id') == $dissertations[0]['user_id'] && $dissertations[0]['status'] == 'rejected' && $session->get('user_id') != $dissertations[0]['adviser_id']) {
                                    echo '<button type="submit" name="action" value="edit"  class="btn btn-danger btn-sm px-5">
                                        <i class="fas fa-sync-alt me-2"></i>Save Edit
                                        </button>';
                                    echo '<button type="submit" name="action" value="resubmit"  class="btn btn-danger btn-sm px-5">
                                        <i class="fas fas fa-save me-2"></i>Resubmit
                                        </button>';
                                }
                                ?>
                            </div>
                            <br>
                            <hr class="">
                            <h6 class="card-title mt-2">Feedbacks</h6>
                            <br>
                            <div class="row mb-3">
                                <div class="col-md-12 mb-4">
                                    <?php if (!empty($feedbacks)): ?>
                                        <!-- loop through feedbacks -->
                                        <?php foreach ($feedbacks as $fb): ?>
                                            <p class="p-0 m-0 mt-2 text-capitalize"><?= esc($fb['first_name'] . " " . $fb['last_name']); ?>
                                                <span class="text-muted small">
                                                    <?= esc($fb['user_level']) ?>
                                                    <?= ($fb['is_adviser'] == 1) ? ' (Adviser)' : '' ?>
                                                </span>
                                            </p>
                                            <div class="form-floating">
                                                <textarea class="form-control form-control-sm" id="feedbacks" name="feedbacks" rows="5" readonly><?= esc($fb['content']) ?></textarea>
                                                <label for="feedbacks"><?= date('F j, Y h:i A', strtotime($fb['created_at'])); ?></label>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="text-muted text-center">No feedbacks</p>
                                    <?php endif; ?>
                                </div>
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