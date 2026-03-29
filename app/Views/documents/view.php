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

        /* ── Edit & Save buttons ── */
.btn-edit {
    background-color: #0d6efd;
    color: #fff;
    border: none;
    padding: 8px 14px;
    border-radius: 4px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.9em;
    transition: background-color 0.2s;
}
.btn-edit:hover { background-color: #0b5ed7; }

.btn-save {
    background-color: #198754;
    color: #fff;
    border: none;
    padding: 8px 14px;
    border-radius: 4px;
    cursor: pointer;
    display: none;
    align-items: center;
    gap: 6px;
    font-size: 0.9em;
    transition: background-color 0.2s;
}
.btn-save:hover { background-color: #157347; }

.btn-edit:disabled,
.btn-save:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* ── Edit mode banner ── */
.edit-banner {
    display: none;
    align-items: center;
    gap: 8px;
    background-color: #cfe2ff;
    border: 1px solid #9ec5fe;
    border-radius: 5px;
    padding: 10px 15px;
    margin-bottom: 15px;
    font-size: 0.9em;
    color: #084298;
}
.edit-banner.visible { display: flex; }

/* ── Inputs look like plain text when disabled ── */
.edit-input,
.edit-select,
.edit-textarea {
    width: 100%;
    font-family: Arial, sans-serif;
    font-size: 0.95em;
    color: #666;
    background: transparent;
    border: 1.5px solid transparent;
    border-radius: 4px;
    padding: 4px 6px;
    outline: none;
    resize: vertical;
    transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
    cursor: default;
    box-sizing: border-box;
}
.edit-input:not(:disabled),
.edit-select:not(:disabled),
.edit-textarea:not(:disabled) {
    background: #f8f9fa;
    border-color: #86b7fe;
    cursor: text;
    box-shadow: 0 0 0 3px rgba(13,110,253,0.1);
}
.edit-input:not(:disabled):focus,
.edit-select:not(:disabled):focus,
.edit-textarea:not(:disabled):focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 3px rgba(13,110,253,0.2);
}

/* ── Status select — badge style when disabled ── */
#field-status:disabled {
    -webkit-appearance: none;
    appearance: none;
    border-radius: 3px;
    font-size: 0.85em;
    padding: 4px 8px;
    font-weight: 600;
    cursor: default;
    border-color: transparent !important;
    box-shadow: none !important;
    width: auto;
}
#field-status[data-status="submitted"]:disabled  { background-color: #ffc107; color: #000; }
#field-status[data-status="endorsed"]:disabled   { background-color: #17a2b8; color: #fff; }
#field-status[data-status="published"]:disabled  { background-color: #28a745; color: #fff; }
#field-status[data-status="revise"]:disabled     { background-color: #dc3545; color: #fff; }
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
                <?php if (session()->get('user_level') === 'admin'): ?>
                    <button class="btn-edit" id="btn-edit" onclick="enterEditMode()">
                        <i class="fas fa-pen-to-square"></i> Edit
                    </button>
                    <button class="btn-save" id="btn-save" onclick="saveDocument()">
                        <i class="fas fa-floppy-disk"></i> Save
                    </button>
                <?php endif; ?>
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
            <iframe 
                id="pdfIframe"
                src="<?= base_url('pdfjs/web/viewer.html') ?>?file=<?= base_url('documents/pdf/' . $document['id']) ?>"
                width="100%" 
                height="700px"
                style="border: none;">
            </iframe>
        </div>
    </div>

    <script>
        // Optional: Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.history.back();
            }
        });

        var editableFields = Array.from(
            document.querySelectorAll('.edit-input, .edit-select, .edit-textarea')
        );

        document.getElementById('pdfIframe').addEventListener('load', function() {
            const iframeDoc = this.contentDocument || this.contentWindow.document;

            const style = iframeDoc.createElement('style');
            style.textContent = `
                #editorModeButtons, #editorModeSeparator, #print, #download, .textLayer {
                    display: none !important;
                }
            `;
            iframeDoc.head.appendChild(style);
        });
    </script>
</body>
</html>