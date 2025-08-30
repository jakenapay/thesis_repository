<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Thesis Repository</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('/assets/css/style.css'); ?>">
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body,
        html {
            width: 100%;
            height: 100vh;
        }

        .container {
            height: 100vh;
        }

        .form-label,
        .text-red,
        .form-check-label,
        a {
            color: #fff !important;
        }

        .form-label {
            margin-bottom: 2px;
        }
    </style>
</head>

<body>

    <div class="container mt-5 mb-5 d-flex justify-content-center align-items-center">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-10">

                <div class="card shadow-lg border-0">
                    <div class="row g-0">

                        <!-- Left Side: Image -->
                        <div class="col-md-5 d-none d-md-block">
                            <img src="<?= base_url('assets/images/background.jpg') ?>" class="img-fluid h-100" alt="Register Image" style="object-fit: cover; border-top-left-radius: .75rem; border-bottom-left-radius: .75rem;">
                        </div>

                        <!-- Right Side: Registration Form -->
                        <div class="col-md-7 bg-red" style="border-top-right-radius: .75rem; border-bottom-right-radius: .75rem;">
                            <br>
                            <div class="card-body p-4 rounded overflow-hidden">
                                <h4 class="card-title mb-4 text-light text-center"><span class="fw-bold"> LPU Thesis Repository</span> | Register</h4>
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
                                <br>
                                <form action="<?= base_url('register') ?>" method="post">

                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">First Name</label>
                                            <input type="text" class="form-control form-control-sm" name="first_name" required>
                                        </div>

                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Middle Name</label>
                                            <input type="text" class="form-control form-control-sm" name="middle_name">
                                        </div>

                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Last Name</label>
                                            <input type="text" class="form-control form-control-sm" name="last_name" required>
                                        </div>

                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Suffix <small class="text-muted text-red">(optional)</small></label>
                                            <input type="text" class="form-control form-control-sm" name="suffix">
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" class="form-control form-control-sm" name="email" required>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Password</label>
                                            <input type="password" class="form-control form-control-sm" name="password" required>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Confirm Password</label>
                                            <input type="password" class="form-control form-control-sm" name="confirm_password" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Academic Status</label>
                                            <select class="form-select form-select-sm" name="academic_status" required>
                                                <option value="">-- Select Academic Status --</option>
                                                <?php if (isset($AcademicStatusData) && is_array($AcademicStatusData)): ?>
                                                    <?php foreach ($AcademicStatusData as $status): ?>
                                                        <option value="<?= esc($status['id']) ?>">
                                                            <?= esc($status['status']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Employment Role/Position</label>
                                            <select class="form-select form-select-sm" name="employment_status" id="employment_status" required>
                                                <option value="">-- Select Role/Position --</option>
                                                <?php if (isset($jobTitleData) && is_array($jobTitleData)): ?>
                                                    <?php foreach ($jobTitleData as $job): ?>
                                                        <option value="<?= esc($job['id']) ?>">
                                                            <?= esc($job['title']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">College</label>
                                            <select name="college" class="form-control form-control-sm" required>
                                                <option value="">Select College</option>
                                                <?php foreach ($collegesData as $college): ?>
                                                    <option value="<?= esc($college['id']) ?>"><?= esc($college['name']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label">Department</label>
                                            <select name="department" class="form-control form-control-sm" required>
                                                <option value="">Select Department</option>
                                                <?php foreach ($departmentsData as $dept): ?>
                                                    <option value="<?= esc($dept['id']) ?>"><?= esc($dept['name']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="agree" id="agree" required>
                                        <label class="form-check-label" for="agree">
                                            I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#myModal">
                                                terms and conditions.
                                            </a>
                                        </label>
                                    </div>

                                    <br>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-danger btn">Register</button>
                                    </div>
                                    <div class="mt-3 text-center form-label">
                                        Already have an account?
                                        <a href="<?= base_url('login') ?>" class="text-decoration-underline text-light"> Login here</a>
                                    </div>
                                </form>
                            </div>
                        </div> <!-- End right side -->

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable"> <!-- Makes the modal scrollable if content is long -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Terms and Conditions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="font-size: 0.95rem;">
                    <h6><strong>About the LPU Thesis Repository</strong></h6>
                    <p>Welcome to the Lyceum of the Philippines University Thesis Repository — a centralized digital archive developed to store, manage, and showcase the academic research outputs of our students.</p>
                    <p>This platform was created with the goal of promoting a culture of research, transparency, and innovation within the LPU academic community. It serves as a convenient hub for accessing undergraduate and graduate theses across various disciplines, making it easier for students, faculty, and researchers to explore scholarly works and draw inspiration for their own academic pursuits.</p>

                    <h6><strong>Purpose</strong></h6>
                    <ul>
                        <li>Preservation of student research for future reference.</li>
                        <li>Accessibility for students, faculty, and researchers to review completed theses anytime, anywhere.</li>
                        <li>Recognition of outstanding academic work produced by LPU students.</li>
                        <li>Support for ongoing research by offering examples of methodologies, literature reviews, and topic development.</li>
                    </ul>
bhg r43
                    <h6><strong>Key Features</strong></h6>
                    <ul>
                        <li>Easy search and browse functionality.</li>
                        <li>Downloadable copies of approved theses.</li>
                        <li>Author and course tagging for more relevant results.</li>
                        <li>Secure upload and archive process for researchers.</li>
                    </ul>

                    <h6><strong>Who Can Access?</strong></h6>
                    <p>LPU Students and Faculty Members with valid credentials may fully access the repository. Guests may view abstracts or limited information, depending on access rights.</p>

                    <blockquote class="blockquote mb-0 mt-3">
                        <p class="mb-0">“This repository reflects LPU’s commitment to academic excellence, intellectual contribution, and digital transformation in education.”</p>
                    </blockquote>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>