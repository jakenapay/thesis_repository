<?= view('template/header') ?>

<div class="container mt-4">
    <div class="row">
        <!-- Left column: User profile -->
        <div class="col-md-8">
            <div class="row d-flex align-items-center justify-content-center">
                <div class="row d-flex align-items-stretch justify-content-center">
                    <!-- User Image Column -->
                    <div class="col-md-4 col-12 d-flex align-items-center justify-content-center mb-3 mb-md-0 p-0">
                        <div class="card w-100 text-center">
                            <img src="<?= $session->get('profile_image'); ?>" class="img-fluid rounded-start p-3" alt="User Profile">
                        </div>
                    </div>

                    <!-- User Details Column -->
                    <div class="col-md-8 col-12 d-flex">
                        <div class="card w-100 h-100">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">
                                            <?= $session->get('first_name') . ' ' . $session->get('middle_name') . ' ' . $session->get('last_name') . ' ' . $session->get('suffix'); ?>
                                        </h5>
                                        <div class="d-flex gap-2">
                                            <?php if ($session->get('is_adviser') == 1): ?>
                                                <p class="card-text mb-0 fs-6 text-capitalize bg-success text-light px-2 rounded">Adviser</p>
                                            <?php endif; ?>
                                            <p class="card-text mb-0 fs-6 text-capitalize bg-red text-light px-2 rounded">
                                                <?= $session->get('user_level'); ?>
                                            </p>
                                        </div>
                                    </div>
                                    <hr>
                                    <h6 class="text-muted fs-7"><?= $session->get('employment_status_status') . ' | ' . $session->get('academic_status_status'); ?></h6>
                                    <h6 class="text-muted fs-7"><small><?= $session->get('college') . ' | ' . $session->get('department_name'); ?></small></h6>
                                    <p class="card-text"><small class="text-muted"><?= $session->get('email'); ?></small></p>
                                    <div class="d-flex gap-2">
                                        <a href="<?= base_url('account'); ?>" class="btn btn-sm btn-danger d-flex align-items-center">
                                            <i class="fas fa-user-edit me-2"></i> Edit Profile
                                        </a>
                                        <a href="<?= base_url('logout'); ?>" class="btn btn-sm btn-danger d-flex align-items-center">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </a>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- List of researches -->
            <div class="card mt-3">
                <div class="bg-red text-light card-header fw-bold">
                    List of Researches
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Research Title</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Sample rows (these can be dynamic later) -->
                                <tr>
                                    <td>Smart Irrigation System using IoT</td>
                                    <td><span class="badge bg-success">Approved</span></td>
                                </tr>
                                <tr>
                                    <td>Online Voting System with Blockchain</td>
                                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                                </tr>
                                <tr>
                                    <td>Facial Recognition Attendance Monitoring</td>
                                    <td><span class="badge bg-danger">Declined</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="bg-red text-light card-header fw-bold">
                    Gallery
                </div>
                <div class="card-body p-0">
                    <!-- Bootstrap Carousel -->
                    <div id="galleryCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="<?= base_url('/assets/images/background.jpg'); ?>" class="d-block w-100" alt="Gallery Image 1">
                            </div>
                            <div class="carousel-item">
                                <img src="<?= base_url('/assets/images/background2.jpg'); ?>" class="d-block w-100" alt="Gallery Image 2">
                            </div>
                            <div class="carousel-item">
                                <img src="<?= base_url('/assets/images/background3.jpg'); ?>" class="d-block w-100" alt="Gallery Image 3">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#galleryCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="bg-red text-light card-header fw-bold">
                    Announcements
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong>New Research Submission Guidelines</strong>
                            <p>All students are advised to follow the updated guidelines for submitting their research papers to the thesis repository.</p>
                            <p><small class="text-muted">Effective Date: March 1, 2024</small></p>
                        </li>
                        <li class="list-group-item">
                            <strong>Repository Maintenance</strong>
                            <p>The thesis repository will undergo scheduled maintenance. Access may be limited during this time.</p>
                            <p><small class="text-muted">Date: March 10, 2024</small></p>
                        </li>
                        <li class="list-group-item">
                            <strong>Research Approval Updates</strong>
                            <p>Check your dashboard for the latest updates on the status of your submitted research papers.</p>
                            <p><small class="text-muted">Updated: Daily</small></p>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card mt-3 mb-5">
                <div class="bg-red text-light card-header fw-bold">
                    Links
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <a href="<?= base_url('documents/graduateThesis/'); ?>" class="btn bg-red w-100 h-100 p-3 border no-hover-white">
                                <i class="fas fa-graduation-cap fa-2x mb-2 text-white"></i>
                                <div class="text-white">Thesis</div>
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="<?= base_url('documents/facultyResearch'); ?>" class="btn bg-red w-100 h-100 p-3 border no-hover-white">
                                <i class="fas fa-file-alt fa-2x mb-2 text-white"></i>
                                <div class="text-white">Faculty Research</div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?= base_url('documents/dissertations'); ?>" class="btn bg-red w-100 h-100 p-3 border no-hover-white">
                                <i class="fas fas fa-book fa-2x mb-2 text-white"></i>
                                <div class="text-white">Dissertations</div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?= base_url('documents/published'); ?>" class="btn bg-red w-100 h-100 p-3 border no-hover-white">
                                <i class="fas fa-file-signature fa-2x mb-2 text-white"></i>
                                <div class="text-white">Publications</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right column: Sidebar -->
        <div class="col-md-4 mb-3">
            <?= view('template/sidebar') ?>
        </div>
    </div>
</div>

<?= view('template/footer') ?>