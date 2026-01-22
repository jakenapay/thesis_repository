<?php ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($document['title']) ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            margin: 0;
            padding: 10px;
            font-family: Arial, sans-serif;
        }
        .viewer-container {
            max-width: 900px;
            margin: 0 auto;
        }
        .toolbar {
            background-color: #fff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        .toolbar-left {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .toolbar-right {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .btn-group {
            display: flex;
            gap: 5px;
        }
        .btn-icon {
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            padding: 8px 12px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .btn-icon:hover {
            background-color: #e0e0e0;
        }
        .document-info {
            background-color: #fff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .info-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 10px;
        }
        .info-item {
            padding: 8px;
        }
        .info-label {
            font-weight: bold;
            color: #333;
            font-size: 0.9em;
        }
        .info-value {
            color: #666;
            margin-top: 3px;
        }
        .pdf-viewer {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            text-align: center;
        }
        iframe {
            width: 100%;
            height: 800px;
            border: none;
            border-radius: 4px;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 0.85em;
        }
        .badge-submitted {
            background-color: #ffc107;
            color: #000;
        }
        .badge-endorsed {
            background-color: #17a2b8;
            color: #fff;
        }
        .badge-published {
            background-color: #28a745;
            color: #fff;
        }
        .badge-revise {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="viewer-container">
        <!-- Toolbar -->
        <div class="toolbar">
            <div class="toolbar-left">
                <a href="<?= base_url('documents/published') ?>" class="btn-icon" title="Back">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <span style="color: #999;">|</span>
                <h5 style="margin: 0;"><?= esc($document['title']) ?></h5>
            </div>
            <div class="toolbar-right">
                <button class="btn-icon" onclick="window.print()" title="Print">
                    <i class="fas fa-print"></i>
                </button>
                <a href="<?= base_url($document['file_path']) ?>" class="btn-icon" download title="Download">
                    <i class="fas fa-download"></i>
                </a>
            </div>
        </div>

        <!-- Document Information -->
        <div class="document-info">
            <div class="info-row">
                <div class="info-item">
                    <div class="info-label">Title</div>
                    <div class="info-value"><?= esc($document['title']) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Type</div>
                    <div class="info-value"><?= esc(ucfirst(str_replace('_', ' ', $document['type']))) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        <span class="badge badge-<?= $document['status'] ?>">
                            <?= esc(ucfirst($document['status'])) ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="info-row">
                <div class="info-item">
                    <div class="info-label">Authors</div>
                    <div class="info-value"><?= esc($document['authors']) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Department</div>
                    <div class="info-value"><?= esc($document['department_name'] ?? 'N/A') ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Date Submitted</div>
                    <div class="info-value"><?= esc(date('F d, Y', strtotime($document['uploaded_at']))) ?></div>
                </div>
            </div>
            <?php if (!empty($document['abstract'])): ?>
            <div class="info-row">
                <div class="info-item">
                    <div class="info-label">Abstract</div>
                    <div class="info-value"><?= esc($document['abstract']) ?></div>
                </div>
            </div>
            <?php endif; ?>
            <?php if (!empty($document['tags'])): ?>
            <div class="info-row">
                <div class="info-item">
                    <div class="info-label">Tags</div>
                    <div class="info-value"><?= esc($document['tags']) ?></div>
                </div>
            </div>
            <?php endif; ?>
        </div>

       <!-- PDF Viewer -->
        <div class="pdf-viewer">
            <?php if (!empty($document['file_path'])): ?>
                <iframe src="<?= base_url('public/' . $document['file_path']) ?>#toolbar=0&navpanes=0&scrollbar=1"></iframe>
            <?php else: ?>
                <p style="color: #999; padding: 40px;">No PDF file available</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Optional: Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.history.back();
            }
        });
    </script>
</body>
</html>