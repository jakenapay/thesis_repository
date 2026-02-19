<style>
    .filter-section {
        background-color: #f8f9fa;
        border-radius: 0.25rem;
    }

    .badge {
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }

    code {
        background-color: #f4f4f4;
        padding: 0.2rem 0.4rem;
        border-radius: 0.25rem;
        font-size: 0.9rem;
    }

    .logs-card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .action-badge {
        display: inline-block;
        font-weight: 500;
    }

    .description-truncate {
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: inline-block;
    }
</style>

<div class="container-fluid mt-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">
                        <i class="fas fa-history"></i> Activity Logs
                    </h2>
                    <small class="text-muted">System audit trail and activity monitoring</small>
                </div>
                <div class="buttons-menu">
                    <button id="refreshButton" class="btn btn-sm btn-outline-secondary me-2" title="Refresh">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                    <a href="<?= base_url('admin/logs/export') . '?' . http_build_query($filters) ?>" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-download"></i> Export CSV
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4 logs-card">
        <div class="card-header bg-light border-bottom">
            <h5 class="mb-0">
                <i class="fas fa-filter"></i> Filters
            </h5>
        </div>
        <div class="card-body filter-section">
            <form method="GET" action="<?= base_url('admin/logs') ?>" class="row g-3">
                <div class="col-md-2">
                    <label for="action" class="form-label fw-bold">Action</label>
                    <select class="form-select form-select-sm" id="action" name="action">
                        <option value="">All Actions</option>
                        <option value="LOGIN" <?= ($filters['action'] === 'LOGIN') ? 'selected' : '' ?>>LOGIN</option>
                        <option value="LOGOUT" <?= ($filters['action'] === 'LOGOUT') ? 'selected' : '' ?>>LOGOUT</option>
                        <option value="LOGIN_FAILED" <?= ($filters['action'] === 'LOGIN_FAILED') ? 'selected' : '' ?>>LOGIN_FAILED</option>
                        <option value="USER_REGISTERED" <?= ($filters['action'] === 'USER_REGISTERED') ? 'selected' : '' ?>>USER_REGISTERED</option>
                        <option value="UPLOAD_DOCUMENT" <?= ($filters['action'] === 'UPLOAD_DOCUMENT') ? 'selected' : '' ?>>UPLOAD_DOCUMENT</option>
                        <option value="UPDATE_DOCUMENT_STATUS" <?= ($filters['action'] === 'UPDATE_DOCUMENT_STATUS') ? 'selected' : '' ?>>UPDATE_DOCUMENT_STATUS</option>
                        <option value="CREATE_GRADUATE_THESIS" <?= ($filters['action'] === 'CREATE_GRADUATE_THESIS') ? 'selected' : '' ?>>CREATE_GRADUATE_THESIS</option>
                        <option value="CREATE_FACULTY_RESEARCH" <?= ($filters['action'] === 'CREATE_FACULTY_RESEARCH') ? 'selected' : '' ?>>CREATE_FACULTY_RESEARCH</option>
                        <option value="CREATE_DISSERTATION" <?= ($filters['action'] === 'CREATE_DISSERTATION') ? 'selected' : '' ?>>CREATE_DISSERTATION</option>
                        <option value="ENDORSE_DOCUMENT" <?= ($filters['action'] === 'ENDORSE_DOCUMENT') ? 'selected' : '' ?>>ENDORSE_DOCUMENT</option>
                        <option value="PUBLISH_DOCUMENT" <?= ($filters['action'] === 'PUBLISH_DOCUMENT') ? 'selected' : '' ?>>PUBLISH_DOCUMENT</option>
                        <option value="REQUEST_REVISION" <?= ($filters['action'] === 'REQUEST_REVISION') ? 'selected' : '' ?>>REQUEST_REVISION</option>
                        <option value="DELETE_DOCUMENT" <?= ($filters['action'] === 'DELETE_DOCUMENT') ? 'selected' : '' ?>>DELETE_DOCUMENT</option>
                        <option value="ADD_FEEDBACK" <?= ($filters['action'] === 'ADD_FEEDBACK') ? 'selected' : '' ?>>ADD_FEEDBACK</option>
                        <option value="EDIT_GRADUATE_THESIS" <?= ($filters['action'] === 'EDIT_GRADUATE_THESIS') ? 'selected' : '' ?>>EDIT_GRADUATE_THESIS</option>
                        <option value="RESUBMIT_GRADUATE_THESIS" <?= ($filters['action'] === 'RESUBMIT_GRADUATE_THESIS') ? 'selected' : '' ?>>RESUBMIT_GRADUATE_THESIS</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="resource_type" class="form-label fw-bold">Resource Type</label>
                    <select class="form-select form-select-sm" id="resource_type" name="resource_type">
                        <option value="">All Resources</option>
                        <option value="USER" <?= ($filters['resource_type'] === 'USER') ? 'selected' : '' ?>>USER</option>
                        <option value="DOCUMENT" <?= ($filters['resource_type'] === 'DOCUMENT') ? 'selected' : '' ?>>DOCUMENT</option>
                        <option value="FEEDBACK" <?= ($filters['resource_type'] === 'FEEDBACK') ? 'selected' : '' ?>>FEEDBACK</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="start_date" class="form-label fw-bold">Start Date</label>
                    <input type="date" class="form-control form-control-sm" id="start_date" name="start_date" value="<?= $filters['start_date'] ?? '' ?>">
                </div>

                <div class="col-md-2">
                    <label for="end_date" class="form-label fw-bold">End Date</label>
                    <input type="date" class="form-control form-control-sm" id="end_date" name="end_date" value="<?= $filters['end_date'] ?? '' ?>">
                </div>

                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="<?= base_url('admin/logs') ?>" class="btn btn-secondary btn-sm">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Logs Table Section -->
    <div class="card logs-card">
        <div class="card-header bg-light border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list"></i> Activity Log Records
                </h5>
                <span class="badge bg-primary"><?= count($logs) ?> records</span>
            </div>
        </div>
        <div class="card-body p-0">
            <?php if (empty($logs)): ?>
                <div class="alert alert-info m-3 mb-0">
                    <i class="fas fa-info-circle"></i> No activity logs found. Try adjusting your filters.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0" id="logsTable">
                        <thead class="table-dark sticky-top">
                            <tr>
                                <th width="6%">ID</th>
                                <th width="16%">User</th>
                                <th width="14%">Action</th>
                                <th width="10%">Resource</th>
                                <th width="8%">Res. ID</th>
                                <th width="28%">Description</th>
                                <th width="12%">IP Address</th>
                                <th width="14%">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $log): ?>
                                <tr data-log-id="<?= $log['id'] ?>">
                                    <td>
                                        <small class="text-muted fw-bold">#<?= $log['id'] ?></small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-start gap-2">
                                            <div>
                                                <strong><?= htmlspecialchars($log['user_name'] ?? 'System') ?></strong>
                                                <br>
                                                <small class="text-muted text-truncate" title="<?= htmlspecialchars($log['email'] ?? 'N/A') ?>">
                                                    <?= htmlspecialchars($log['email'] ?? 'N/A') ?>
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge <?= getActionBadgeClass($log['action']) ?> action-badge">
                                            <?= htmlspecialchars($log['action']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($log['resource_type']): ?>
                                            <span class="badge bg-info">
                                                <?= htmlspecialchars($log['resource_type']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted small">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($log['resource_id']): ?>
                                            <a href="javascript:void(0)" class="text-decoration-none" title="Resource ID: <?= $log['resource_id'] ?>">
                                                <code>#<?= $log['resource_id'] ?></code>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted small">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="description-truncate" title="<?= htmlspecialchars($log['description'] ?? '-') ?>">
                                            <?php 
                                                $description = $log['description'] ?? '-';
                                                echo htmlspecialchars($description);
                                            ?>
                                        </div>
                                        <?php if (strlen($log['description'] ?? '') > 50): ?>
                                            <br>
                                            <button class="btn btn-link btn-sm p-0" data-bs-toggle="modal" data-bs-target="#descriptionModal" onclick="showDescription('<?= addslashes(htmlspecialchars($log['description'])) ?>')">
                                                <small>View Full</small>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <small class="text-muted" title="<?= htmlspecialchars($log['ip_address']) ?>">
                                            <code><?= htmlspecialchars($log['ip_address']) ?></code>
                                        </small>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <div><?= date('M d, Y', strtotime($log['created_at'])) ?></div>
                                            <code><?= date('H:i:s', strtotime($log['created_at'])) ?></code>
                                        </small>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Description Modal -->
<div class="modal fade" id="descriptionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">
                    <i class="fas fa-align-left"></i> Full Description
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="fullDescription" class="text-break"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
    // Initialize DataTable
    $(document).ready(function() {
        $('#logsTable').DataTable({
            responsive: true,
            pageLength: 25,
            lengthMenu: [10, 25, 50, 100],
            order: [[7, 'desc']], // Sort by timestamp descending
            columnDefs: [
                {
                    targets: [0, 1, 2, 3, 4, 6, 7],
                    orderable: true
                }
            ],
            language: {
                search: '<i class="fas fa-search"></i> Search:',
                paginate: {
                    previous: '<i class="fas fa-chevron-left"></i>',
                    next: '<i class="fas fa-chevron-right"></i>'
                }
            },
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            initComplete: function() {
                // Add custom styling to search input
                $('.dataTables_filter input').addClass('form-control form-control-sm');
                $('.dataTables_length select').addClass('form-select form-select-sm');
            }
        });
    });

    // Refresh Button
    document.getElementById('refreshButton').addEventListener('click', function() {
        location.reload();
    });

    // Show full description in modal
    function showDescription(description) {
        document.getElementById('fullDescription').textContent = description;
    }

    // Action Badge Colors
    function getActionBadgeClass(action) {
        const badgeMap = {
            'LOGIN': 'bg-success',
            'LOGOUT': 'bg-info',
            'LOGIN_FAILED': 'bg-danger',
            'USER_REGISTERED': 'bg-primary',
            'UPLOAD_DOCUMENT': 'bg-primary',
            'CREATE_GRADUATE_THESIS': 'bg-primary',
            'CREATE_FACULTY_RESEARCH': 'bg-primary',
            'CREATE_DISSERTATION': 'bg-primary',
            'UPDATE_DOCUMENT_STATUS': 'bg-warning',
            'EDIT_GRADUATE_THESIS': 'bg-warning',
            'EDIT_FACULTY_RESEARCH': 'bg-warning',
            'EDIT_DISSERTATION': 'bg-warning',
            'ENDORSE_DOCUMENT': 'bg-success',
            'PUBLISH_DOCUMENT': 'bg-success',
            'REQUEST_REVISION': 'bg-warning',
            'DELETE_DOCUMENT': 'bg-danger',
            'ADD_FEEDBACK': 'bg-info',
            'RESUBMIT_GRADUATE_THESIS': 'bg-info',
            'RESUBMIT_FACULTY_RESEARCH': 'bg-info',
            'RESUBMIT_DISSERTATION': 'bg-info',
            'UPDATE_PROFILE': 'bg-secondary'
        };

        return badgeMap[action] || 'bg-secondary';
    }
</script>