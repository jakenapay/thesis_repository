<?php
$uri = service('uri');
$segments = $uri->getSegments();

$isDocument = $segments[0] ?? '';
$path       = $segments[1] ?? '';
$action     = $segments[2] ?? '';

$displayPath = match ($path) {
  'graduateThesis' => 'Graduate Thesis',
  'facultyResearch' => 'Faculty Research',
  default => ucwords(str_replace('/', ' ', $path)),
};
?>

<?php if ($isDocument && empty($action) && !empty($path)) { ?>
  <div class="card mb-3">
    <div class="bg-red text-light card-header fw-bold">
      <?= "Submit " . $displayPath; ?>
    </div>
    <div class="card-body">
      <p class="text-muted mb-3">Please upload your <?= strtolower($displayPath); ?> document in PDF format.</p>
      <hr>
      <a href="<?= base_url('documents/' . $path . '/create'); ?>" class="btn btn-danger">
        <i class="fas fa-upload me-1"></i> Submit
      </a>
    </div>
  </div>
<?php } else if ($isDocument && $action === 'create' && !empty($path)) { ?>
  <div class="card mb-3">
    <div class="bg-red text-light card-header fw-bold">
      <?= "View list of " . $displayPath; ?>
    </div>
    <div class="card-body">
      <p class="text-muted mb-3">View list of submitted <?= strtolower($displayPath); ?>.</p>
      <hr>
      <a href="<?= base_url('documents/' . $path); ?>" class="btn btn-danger">
        <i class="fas fa-list me-1"></i> View
      </a>
    </div>
  </div>
<?php } ?>


<div class="card">
  <!-- Header and Quote -->
  <div class="bg-red text-light card-header fw-bold">
    Search
  </div>
  <div class="card-body">

    <!-- Search Bar -->
    <form action="" method="get" class="d-flex mb-3" role="search">
      <input class="form-control me-2" type="search" name="q" placeholder="Search..." aria-label="Search">
      <button class="btn btn-danger" type="submit">Search</button>
    </form>

    <!-- Filter Section -->
    <p class="fw-bold mb-1">Filter by</p>
    <hr>

    <!-- Category List -->
    <h6 class="mt-3">Category</h6>
    <ul class="list-group list-group-flush">
      <li class="list-group-item"><a href="/thesis/category/IT">Information Technology</a></li>
      <li class="list-group-item"><a href="/thesis/category/CS">Computer Science</a></li>
      <li class="list-group-item"><a href="/thesis/category/IS">Information Systems</a></li>
    </ul>
  </div>
</div>

<div class="card mt-3">
  <div class="bg-red text-light card-header fw-bold">
    Submit
  </div>
  <div class="card-body">
    <!-- Category List -->
    <ul class="list-group list-group-flush">
      <li class="list-group-item"><a href="">Submit Research</a></li>
      <li class="list-group-item"><a href="">Another Submission</a></li>
      <li class="list-group-item"><a href="">Consent Form</a></li>
    </ul>
  </div>
</div>