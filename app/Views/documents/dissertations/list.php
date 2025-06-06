<div class="container mt-4">
    <div class="row">

        <div class="col-md-12">

            <!-- About Card -->
            <div class="card mb-3">
                <div class="bg-red text-light card-header fw-bold">
                    DISSERTATIONS
                </div>
                <div class="card-body">
                    <table id="dissertationsTable" class="table table-hover table-sm" style="width:100%">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Year</th>
                                <th>Department</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Example row, replace with PHP loop for dynamic data -->
                            <tr>
                                <td>Sample Dissertation Title</td>
                                <td>John Doe</td>
                                <td>2023</td>
                                <td>Computer Science</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-sm">View</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTables JS initialization -->
<script>
    $(document).ready(function() {
        let table = new DataTable('#dissertationsTable');
    });
</script>