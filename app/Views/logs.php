<div class="container mt-4">
    <div class="row d-flex min-vh-100">
        <div class="col-md-12">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger text-center"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success text-center"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>
            <div class="card mb-3 p-0">
                <div class="bg-red text-light card-header fw-bold">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Logs</span>
                        <button class="btn btn-light text-danger btn-sm" id="exportLogsBtn" data-bs-toggle="tooltip" title="Export Logs">
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body table-responsive table-sm">
                    <table id="logsTable" class="table table-hover table-sm" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Resource Type</th>
                                <th>Resource ID</th>
                                <th>Description</th>
                                <th>IP Address</th>
                                <th>User Agent</th>
                                <th>Date/Time</th>
                            </tr>
                        </thead>
                        <?php if (!empty($logs) && is_array($logs)): ?>
                            <tbody>
                                <?php foreach ($logs as $log): ?>
                                    <tr>
                                        <td><?= esc($log['id']); ?></td>
                                        <td>
                                            <span data-bs-toggle="tooltip" title="<?= esc($log['first_name'] . ' ' . $log['last_name']); ?>">
                                                <?= esc(strlen($log['first_name'] . ' ' . $log['last_name']) > 10 ? substr($log['first_name'] . ' ' . $log['last_name'], 0, 20) . '...' : $log['first_name'] . ' ' . $log['last_name']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span data-bs-toggle="tooltip" title="<?= esc($log['action']); ?>">
                                                <?= esc(strlen($log['action']) > 25 ? substr($log['action'], 0, 10) . '...' : $log['action']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span data-bs-toggle="tooltip" title="<?= esc($log['resource_type']); ?>">
                                                <?= esc(strlen($log['resource_type']) > 15 ? substr($log['resource_type'], 0, 10) . '...' : $log['resource_type']); ?>
                                            </span>
                                        </td>
                                        <td><?= esc($log['resource_id']); ?></td>
                                        <td>
                                            <span data-bs-toggle="tooltip" title="<?= esc($log['description']); ?>">
                                                <?= esc(strlen($log['description']) > 30 ? substr($log['description'], 0, 10) . '...' : $log['description']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span data-bs-toggle="tooltip" title="<?= esc($log['ip_address']); ?>">
                                                <?= esc(strlen($log['ip_address']) > 15 ? substr($log['ip_address'], 0, 10) . '...' : $log['ip_address']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span data-bs-toggle="tooltip" title="<?= esc($log['user_agent']); ?>">
                                                <?= esc(strlen($log['user_agent']) > 40 ? substr($log['user_agent'], 0, 10) . '...' : $log['user_agent']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span data-bs-toggle="tooltip" title="<?= esc($log['created_at']); ?>">
                                                <?= esc($log['created_at']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        <?php else: ?>
                            <tbody>
                                <tr>
                                    <td colspan="9" class="text-center">No logs found.</td>
                                </tr>
                            </tbody>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        const style = document.createElement('style');
        style.innerHTML = `
        div.dt-container .dt-paging .dt-paging-button {
            color: #000000 !important;
            border: 1px solid transparent !important;
            border-radius: 2px !important;
            background: #f4f4f4 !important;
        }
        
        div.dt-container .dt-paging .dt-paging-button:hover {
            color: #f3f2f2 !important;
            background: #e12929 !important;
            border: 1px solid transparent !important;
            border-radius: 2px !important;
        }

        /* Make table text smaller and compact */
        #logsTable {
            font-size: 18px;
        }

        #logsTable thead th {
            font-size: 15px;
            font-weight: 600;
            padding: 8px 6px;
        }

        #logsTable tbody td {
            font-size: 14px;
            padding: 6px 6px;
            vertical-align: middle;
        }

        #logsTable tbody td code {
            font-size: 10px;
        }

        `;
        document.head.appendChild(style);

        // Export button functionality
        document.getElementById('exportLogsBtn').addEventListener('click', function() {
            let table = document.getElementById('logsTable');
            let csv = [];
            
            // Get headers
            let headers = [];
            table.querySelectorAll('thead th').forEach(th => {
                headers.push(th.textContent.trim());
            });
            csv.push(headers.join(','));
            
            // Get data rows
            table.querySelectorAll('tbody tr').forEach(tr => {
                let row = [];
                tr.querySelectorAll('td').forEach(td => {
                    let text = td.textContent.trim().replace(/"/g, '""');
                    row.push('"' + text + '"');
                });
                csv.push(row.join(','));
            });
            
            // Download CSV
            let csvContent = csv.join('\n');
            let blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            let link = document.createElement('a');
            let url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', 'logs_export_' + new Date().toISOString().slice(0,10) + '.csv');
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    });

    $(document).ready(function() {
       let logsTable = new DataTable('#logsTable', {
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            order: [[0, 'desc']]
        });
    });
</script>