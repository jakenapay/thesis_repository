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
</head>

<body>
  <!-- Navbar -->
  <nav class="nav navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
      <a class="navbar-brand d-flex align-items-center" href="#">
        <img src="<?= base_url('/assets/images/lpu.jpg'); ?>" alt="Logo" width="30" height="30" class="d-inline-block align-text-top me-2">
        LPU THESIS<br>REPOSITORY
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link nav-links" href="/thesis/list">Home</a></li>
          <li class="nav-item"><a class="nav-link nav-links" href="/thesis/about">About</a></li>
          <li class="nav-item"><a class="nav-link nav-links" href="/thesis/faq">FAQ</a></li>
          <li class="nav-item"><a class="nav-link nav-links" href="/thesis/account">My Account</a></li>
          <li class="nav-item"><a class="nav-link nav-links" href="/thesis/contact">Contact</a></li>
        </ul>
      </div>
    </div>
  </nav>