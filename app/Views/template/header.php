<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Thesis Repository</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('/assets/css/style.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('/assets/css/table.css'); ?>">
  <!-- Font Awesome CDN -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css">
  <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.js"></script>
</head>

<body class="d-flex flex-column min-vh-100">
  <?php 
  $uri = service('uri');
  $path = $uri->getPath(); // Get the full URI path
  ?>

  <!-- Navbar -->
  <nav class="nav navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="<?= base_url('/assets/images/lpu.jpg'); ?>" alt="Logo" width="45" height="60" class="transparent-fake d-inline-block align-text-top me-2">
        LPU THESIS<br>REPOSITORY
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link nav-links <?= (strpos($path, 'home') !== false || $path === '/') ? 'active' : '' ?>" href="<?= base_url('home') ?>">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-links <?= strpos($path, 'about') !== false ? 'active' : '' ?>" href="<?= base_url('about') ?>">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-links <?= strpos($path, 'faq') !== false ? 'active' : '' ?>" href="<?= base_url('faq') ?>">FAQ</a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-links <?= strpos($path, 'account') !== false ? 'active' : '' ?>" href="<?= base_url('account') ?>">My Account</a>
          </li>
          <li class="nav-item">
            <a class="nav-link nav-links <?= strpos($path, 'contact') !== false ? 'active' : '' ?>" href="<?= base_url('contact') ?>">Contact</a>
          </li>
          <?php if ( $session->get('user_level') == 'admin') : ?>
            <li class="nav-item">
              <a class="nav-link nav-links <?= strpos($path, 'users') !== false ? 'active' : '' ?>" href="<?= base_url('users') ?>">Users</a>
            </li>
          <?php endif; ?>
          <?php if ( $session->get('user_level') == 'admin' || $session->get('user_level') == 'librarian' ) : ?>
            <li class="nav-item">
              <a class="nav-link nav-links <?= strpos($path, 'analytics') !== false ? 'active' : '' ?>" href="<?= base_url('analytics') ?>">Analytics</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>