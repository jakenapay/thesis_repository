<div class="container mt-4">
    <div class="row d-flex min-vh-100">
        <div class="col-md-8">

            <div class="card mb-3 p-0">
                <div class="bg-red text-light card-header fw-bold">
                    List of Published Dissertations
                </div>
                <div class="card-body">
                    <table id="dissertationsTable" class="table table-hover table-sm" style="width:100%">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Department</th>
                                <th></th>
                            </tr>
                        </thead>
                        <?php if (!empty($dissertations) && is_array($dissertations)): ?>
                            <tbody>
                                <?php foreach ($dissertations as $dissertation): ?>
                                    <tr>
                                        <td><?= esc($dissertation['title']); ?></td>
                                        <td><?= esc($dissertation['authors']); ?></td>
                                        <td><?= esc($dissertation['department_name'] ?? ''); ?></td>
                                        <td>
                                            <a href="<?= base_url('documents/dissertations/view/' . esc($dissertation['id'], 'url')); ?>" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        <?php else: ?>
                            <tbody></tbody>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

        </div>
        <!-- Right column: Sidebar -->
        <div class="col-md-4 mb-3">
            <?= view('template/sidebar') ?>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    $(document).ready(function() {
        let dissertationsTable = new DataTable('#dissertationsTable');
    });
</script>