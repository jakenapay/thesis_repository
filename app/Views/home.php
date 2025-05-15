<?= view('template/header') ?>

<div class="container mt-4">
    <div class="row">
        <!-- Left column: User profile -->
        <div class="col-md-8">
            <div class="card mb-3" style="max-width: 100%;">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="<?= base_url('/assets/images/default-avatar.jpg'); ?>" class="img-fluid rounded-start" alt="User Profile">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">Juan Dela Cruz</h5>
                            <p class="card-text">Bachelor of Science in Information Technology</p>
                            <p class="card-text"><small class="text-muted">999-999</small></p>
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
                            <button class="btn bg-red w-100 h-100 p-3 border">
                                <i class="fas fa-graduation-cap fa-2x mb-2 text-white"></i>
                                <div class="text-white">Thesis and Dissertations</div>
                            </button>
                        </div>
                        <div class="col-6 mb-3">
                            <button class="btn bg-red w-100 h-100 p-3 border">
                                <i class="fas fa-file-alt fa-2x mb-2 text-white"></i>
                                <div class="text-white">Faculty Research</div>
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn bg-red w-100 h-100 p-3 border">
                                <i class="fas fa-folder-open fa-2x mb-2 text-white"></i>
                                <div class="text-white">Archive and Collections</div>
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn bg-red w-100 h-100 p-3 border">
                                <i class="fas fa-file-signature fa-2x mb-2 text-white"></i>
                                <div class="text-white">Publications</div>
                            </button>
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