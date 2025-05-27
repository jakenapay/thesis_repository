<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - Thesis Repository</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('/assets/css/style.css'); ?>">
    <!-- Font Awesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
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

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="card shadow-lg border-0">
                    <div class="row g-0">

                        <!-- Left Side: Image -->
                        <div class="col-md-5 d-none d-md-block">
                            <img src="<?= base_url('assets/images/background.jpg') ?>" class="img-fluid h-100" alt="Login Image" style="object-fit: cover; border-top-left-radius: .75rem; border-bottom-left-radius: .75rem;">
                        </div>

                        <!-- Right Side: Login Form -->
                        <div class="col-md-7 bg-red" style="border-top-right-radius: .75rem; border-bottom-right-radius: .75rem;">
                            <br>
                            <div class="card-body p-4 rounded overflow-hidden">
                                <h4 class="card-title mb-4 text-light text-center"><span class="fw-bold"> LPU Thesis Repository</span> | Login</h4>
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
                                <form action="<?= base_url('login') ?>" method="post">

                                    <div class="mb-3">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" class="form-control form-control-sm" name="email" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input type="password" class="form-control form-control-sm" name="password" required>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-danger btn">Login</button>
                                    </div><br>
                                    <div class="mt-3 text-center form-label">
                                        Don't have an account?
                                        <a href="<?= base_url('register') ?>" class="text-decoration-underline text-light"> Register here</a>
                                    </div>
                                </form>
                            </div>
                        </div> <!-- End right side -->

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>