<div class="container mt-4">
    <div class="row d-flex min-vh-100">
        <div class="col-md-8">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger text-center"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success text-center"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <div class="card mb-3 p-0">
                <div class="bg-red text-light card-header fw-bold">
                    List of Graduate Thesis
                </div>
                <div class="card-body table-responsive">
                    <table id="graduateThesisTable" class="table table-hover table-sm table-responsive" style="width:100%">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Adviser</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <?php if (!empty($graduateThesis) && is_array($graduateThesis)): ?>
                            <tbody>
                                <?php foreach ($graduateThesis as $thesis): ?>
                                    <tr>
                                        <td><?= esc($thesis['title']); ?></td>
                                        <td><?= esc($thesis['authors']); ?></td>
                                        <td class="text-capitalize"><?= esc($thesis['adviser_name']); ?></td>
                                        <td><?= esc($thesis['department_name'] ?? ''); ?></td>
                                        <?php if ($thesis['status'] == 'submitted') { ?>
                                            <td class="bg-warning text-capitalize"><?= esc($thesis['status']); ?></td>
                                        <?php } else if ($thesis['status'] == 'endorsed') { ?>
                                            <td class="bg-info text-capitalize"><?= esc($thesis['status']); ?></td>
                                        <?php } else if ($thesis['status'] == 'published') { ?>
                                            <td class="bg-success text-light text-capitalize"><?= esc($thesis['status']); ?></td>
                                        <?php } else { ?>
                                            <td class="bg-danger text-light text-capitalize"><?= esc($thesis['status']); ?></td>
                                        <?php } ?>
                                        <td>
                                            <a href="<?= base_url('documents/graduateThesis/view/' . esc($thesis['id'], 'url')); ?>" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View">
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
        let graduateThesisTable = new DataTable('#graduateThesisTable');
    });
</script>