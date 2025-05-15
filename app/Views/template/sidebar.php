<div class="card">
  <!-- Header and Quote -->
  <div class="bg-red text-light card-header fw-bold">
    Search
  </div>
  <div class="card-body">

    <!-- Search Bar -->
    <form action="/thesis/search" method="get" class="d-flex mb-3" role="search">
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
    Browse
  </div>
  <div class="card-body">
    <!-- Category List -->
    <ul class="list-group list-group-flush">
      <li class="list-group-item"><a href="">Collections</a></li>
      <li class="list-group-item"><a href="">College and Units</a></li>
      <li class="list-group-item"><a href="">Authors</a></li>
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
