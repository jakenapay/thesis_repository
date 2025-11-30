<style>
    .img-box {
        width: 250px;
        height: 250px;
        overflow: hidden;
    }

    .img-box img {
        object-fit: cover;
        width: 100%;
        height: 100%;
    }
</style>
<div class="container mt-4">
    <div class="row d-flex min-vh-100">
        <form action="<?= base_url('edit/' . $session->get('user_id')) ?>" method="POST" enctype="multipart/form-data" class="row g-3">
            <div class="col-md-8">
                <!-- Contact Card -->
                <div class="card mb-3">
                    <div class="card-header bg-red text-light fw-bold d-flex justify-content-between align-items-center">
                        <span>Account</span>
                        <span class="small text-light">Last update: <?= $session->get('updated_at'); ?></span>
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

                            <h6 class="card-title">Basic Information</h6>
                            <hr>

                            <!-- User ID -->
                            <input type="hidden" class="form-control form-control-sm" name="user_id" required value="<?= $session->get('user_id'); ?>">

                            <!-- First Name -->
                            <div class="col-md-6 mb-2">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control form-control-sm" name="first_name" required value="<?= $session->get('first_name'); ?>">
                            </div>

                            <!-- Middle Name -->
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Middle Name</label>
                                <input type="text" class="form-control form-control-sm" name="middle_name" value="<?= $session->get('middle_name'); ?>">
                            </div>

                            <!-- Last Name -->
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control form-control-sm" name="last_name" required value="<?= $session->get('last_name'); ?>">
                            </div>

                            <!-- Suffix -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Suffix <small class="text-muted text-red">(optional)</small></label>
                                <input type="text" class="form-control form-control-sm" name="suffix" value="<?= $session->get('suffix'); ?>">
                            </div>

                            <br>
                            <h6 class="card-title">Work and Study Background</h6>
                            <hr class="">

                            <!-- Job Title -->
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Job Title</label>
                                <select class="form-control form-control-sm" name="employment_status" required>
                                    <option value="" disabled <?= empty($session->get('employment_status')) ? 'selected' : '' ?>>Select Job Title</option>
                                    <?php foreach ($jobTitleData as $job): ?>
                                        <option value="<?= esc($job['id']) ?>" <?= $session->get('employment_status') == $job['id'] ? 'selected' : '' ?>>
                                            <?= esc($job['title']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Academic Status -->
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Academic Status</label>
                                <select class="form-control form-control-sm" name="academic_status" required>
                                    <option value="" disabled <?= empty($session->get('academic_status')) ? 'selected' : '' ?>>Select Academic Status</option>
                                    <?php foreach ($AcademicStatusData as $status): ?>
                                        <option value="<?= esc($status['id']) ?>" <?= $session->get('academic_status') == $status['id'] ? 'selected' : '' ?>>
                                            <?= esc($status['status']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- College -->
                            <div class="col-md-6 mb-2">
                                <label class="form-label">College</label>
                                <!-- <input type="text" class="form-control form-control-sm" name="college" required value="<?= $session->get('college'); ?>"> -->
                                <select class="form-control form-control-sm" name="college" required>
                                    <option value="" disabled <?= empty($session->get('college')) ? 'selected' : '' ?>>Select College</option>
                                    <?php foreach ($collegeData as $college): ?>
                                        <option value="<?= esc($college['id']) ?>" <?= $session->get('college') == $college['id'] ? 'selected' : '' ?>>
                                            <?= esc($college['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Department -->
                             <div class="col-md-6 mb-4">
                                <label class="form-label">Department</label>
                                <select class="form-control form-control-sm" name="department" required>
                                    <option value="" disabled <?= empty($session->get('department')) ? 'selected' : '' ?>>Select Department</option>
                                    <?php foreach ($departmentData as $status): ?>
                                        <option value="<?= esc($status['id']) ?>" <?= $session->get('department') == $status['id'] ? 'selected' : '' ?>>
                                            <?= esc($status['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>


                            <br>
                            <h6 class="card-title">Login Information</h6>
                            <hr class="">

                            <div class="mb-4 col-md-6">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control form-control-sm" required>
                                    <option value="" disabled <?= empty($session->get('status')) ? 'selected' : '' ?>>Select Status</option>
                                    <option value="1" <?= $session->get('status') == 1 ? 'selected' : '' ?>>Active</option>
                                    <option value="0" <?= $session->get('status') == 0 ? 'selected' : '' ?>>Inactive</option>
                                </select>
                            </div>

                            <div class="mb-4 col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control form-control-sm" name="email" required value="<?= $session->get('email'); ?>">
                            </div>

                            <div class="col-md-6 mb-2">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control form-control-sm" name="password" value="">
                            </div>

                            <div class="col-md-6 mb-2">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" class="form-control form-control-sm" name="confirm_password" value="">
                            </div>

                            <!-- Note -->
                            <div class="col-md-12 mb-4">
                                <div class="bg-light border-secondary p-2 border-1 rounded-3 form-control form-control-sm" style="border-style: dashed;">
                                    <i class="fas fa-info-circle me-2 text-muted"></i><small class="text-muted">Leave blank to keep current password</small>
                                </div>
                            </div>


                            <br>
                            <hr class="">
                            <!-- Submit Button -->
                            <div class="col-md-12 mb-2 d-flex justify-content-end">
                                <button type="submit" class="btn btn-danger btn-sm px-5">
                                    <i class="fas fa-save me-2"></i>Update
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right column: Sidebar -->
            <div class="col-md-4 mb-3">
                <div class="card">
                    <!-- Header and Quote -->
                    <div class="bg-red text-light card-header fw-bold">
                        Profile Image
                    </div>
                    <div class="card-body">
                        <div class="img-box mx-auto mb-3">
                            <img src="<?= $session->get('profile_image'); ?>" alt="profile image" class="img-fluid">
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label for="profile_image" class="form-label">Upload New Image</label>
                            <input type="file" class="form-control form-control-sm" id="profile_image" name="profile_image" accept="image/png, image/jpeg, image/jpg, image/gif">
                            <small class="text-muted">Accepted types: .jpg, .jpeg, .png, .gif</small>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>