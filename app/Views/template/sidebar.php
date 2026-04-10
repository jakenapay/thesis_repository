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

<?php if ($isDocument && empty($action) && !empty($path) && $path != 'published') { ?>
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


<?php 
$session = session();
$userLevel = $session->get("user_level");
$isAdviser = $session->get("is_adviser");

if (!($userLevel === 'masters' ) || ( $userLevel === 'faculty' && $isAdviser == 1)) {
?>
<div class="card">
  <!-- Header and Quote -->
  <div class="bg-red text-light card-header fw-bold">
    Search Documents
  </div>
  <div class="card-body">

    <!-- Search Bar -->
    <form id="searchForm" class="d-flex mb-3" role="search">
      <input class="form-control me-2" type="search" id="searchDocsInput" name="searchDocs" placeholder="Authors, Tags, Title..." aria-label="Search" required>
      <button class="btn btn-danger" type="button" id="searchBtn"><i class="fas fa-search"></i></button>
    </form>
  </div>
</div>
<?php } ?>

<div class="card mt-3">
  <div class="bg-red text-light card-header fw-bold">
    Submit
  </div>
  <div class="card-body">
    <!-- Category List -->
    <div class="accordion" id="submitAccordion">
      <div class="accordion-item">
      <h2 class="accordion-header">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#submitResearch">
        Submit Research
        </button>
      </h2>
      <div id="submitResearch" class="accordion-collapse collapse show" data-bs-parent="#submitAccordion">
        <div class="accordion-body p-0">
        <ul class="list-group list-group-flush">
          <li class="list-group-item"><a href="<?= base_url('documents/facultyResearch'); ?>">Faculty Research</a></li>
          <li class="list-group-item"><a href="<?= base_url('documents/graduateThesis'); ?>">Graduate Thesis</a></li>
          <li class="list-group-item"><a href="<?= base_url('documents/dissertations'); ?>">Dissertations</a></li>
        </ul>
        </div>
      </div>
      </div>
      <div class="accordion-item">
      <!-- <h2 class="accordion-header">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#anotherSubmission">
        Another Submission
        </button>
      </h2> -->
      <!-- <div id="anotherSubmission" class="accordion-collapse collapse" data-bs-parent="#submitAccordion">
        <div class="accordion-body p-0">
        <ul class="list-group list-group-flush">
          <li class="list-group-item"><a href="">Option 1</a></li>
          <li class="list-group-item"><a href="">Option 2</a></li>
        </ul>
        </div>
      </div> -->
      </div>
    </div>
  </div>
</div>

<!-- Search Results Modal -->
<div class="modal fade" id="searchModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Search Results</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <table id="searchResultsTable" class="table table-striped">
          <thead>
            <tr>
              <th></th>
              <th>Type</th>
              <th>Title</th>
              <th>Author</th>
              <th>Adviser</th>
              <th>Department</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>