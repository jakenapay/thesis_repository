  </div>

<footer class="nav" style="background-color: red; padding: 10px;">
    <div class="container d-flex justify-content-between align-items-center">
        <span class="text-white">LPU Thesis Repository</span>
        <ul class="nav">
            <li class="nav-item"><a class="nav-link text-white" href="#">Home</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">About</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">FAQ</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">My Account</a></li>
            <li class="nav-item"><a class="nav-link text-white" href="#">Contact</a></li>
        </ul>
    </div>
</footer>

<!-- Bootstrap JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    $(document).ready(function() {
        let researchTable = new DataTable('#researchTable');
    });
</script>

<script>
    document.getElementById('searchBtn').addEventListener('click', function() {
        const searchQuery = document.getElementById('searchDocsInput').value;
        const searchType = document.getElementById('searchDocsType').value;

        if (!searchQuery.trim()) {
            return;
        }

        fetch('<?= base_url("search") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'searchDocs=' + encodeURIComponent(searchQuery) + '&searchType=' + encodeURIComponent(searchType)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                populateDataTable(data.data);
                new bootstrap.Modal(document.getElementById('searchModal')).show();
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });

    function populateDataTable(results) {
        const tbody = document.querySelector('#searchResultsTable tbody');
        tbody.innerHTML = '';
        
        results.forEach(doc => {
            const viewUrl = getViewUrl(doc.id, doc.type);
            
            const statusColor = getStatusColor(doc.status);
            const row = `<tr>
                <td>
                    <button class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="View Document" onclick="viewDocument('${viewUrl}')">
                        <i class="fas fa-eye"></i>
                    </button>
                </td>
                <td>${getDocumentTypeLabel(doc.type)}</td>
                <td>${doc.title}</td>
                <td>${doc.authors}</td>
                <td>${doc.tags ?? ''}</td>
                <td>${doc.adviser_name}</td>
                <td>${doc.department_name}</td>
                <td class="bg-${statusColor} text-capitalize">${doc.status}</td>
            </tr>`;
            tbody.innerHTML += row;
        });
    }

    function getViewUrl(docId, docType) {
        const baseUrl = '<?= base_url() ?>';
        
        switch(docType) {
            case 'graduate_thesis':
                return baseUrl + 'documents/graduateThesis/view/' + docId;
            case 'dissertation':
                return baseUrl + 'documents/dissertations/view/' + docId;
            case 'faculty_research':
                return baseUrl + 'documents/facultyResearch/view/' + docId;
            default:
                return baseUrl + 'documents/viewDocument/' + docId;
        }
    }

    function getDocumentTypeLabel(type) {
        const labels = {
            'graduate_thesis': 'Graduate Thesis',
            'dissertation': 'Dissertation',
            'faculty_research': 'Faculty Research'
        };
        return labels[type] || type;
    }

    function viewDocument(url) {
        window.location.href = url;
    }

    function getStatusColor(status) {
        const colors = {
            'published': 'success',
            'endorsed': 'info',
            'submitted': 'warning'
        };
        return colors[status] || 'secondary';
    }
</script>

<!-- AI Content Check -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-ai-check-btn]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                handleAiCheckClick(btn);
            });
        });
    });

    const AI_CHECK_CSRF_HEADER = '<?= csrf_header() ?>';

    function getCsrfMetaTag() {
        return document.querySelector('meta[name="' + AI_CHECK_CSRF_HEADER + '"]');
    }

    let aiCheckIdCounter = 0;

    function handleAiCheckClick(btn) {
        const wrapper = btn.closest('[data-ai-check]');
        const badgeBox = wrapper.querySelector('[data-ai-check-badge]');
        const resultBox = wrapper.querySelector('[data-ai-check-result]');
        const input = document.querySelector(btn.getAttribute('data-target'));

        if (!input || !input.files || input.files.length === 0) {
            renderAiCheckError(badgeBox, resultBox, 'Please choose a PDF file first.');
            return;
        }

        const formData = new FormData();
        formData.append('thesis_file', input.files[0]);

        const csrfMeta = getCsrfMetaTag();
        const headers = {};
        if (csrfMeta) {
            headers[AI_CHECK_CSRF_HEADER] = csrfMeta.content;
        }

        btn.disabled = true;
        resultBox.innerHTML = '';
        badgeBox.innerHTML = '<span class="text-muted small"><i class="fas fa-spinner fa-spin me-1"></i>Checking document...</span>';

        fetch('<?= base_url('documents/checkAiContent') ?>', {
                method: 'POST',
                headers: headers,
                body: formData
            })
            .then(function(res) {
                return res.json();
            })
            .then(function(data) {
                updateAiCheckLimitState(btn, data.requests_remaining);

                // The CSRF token rotates on every request to this route; pick up
                // the new one so the next click on this page still works.
                if (data.csrf_hash && csrfMeta) {
                    csrfMeta.content = data.csrf_hash;
                }

                if (!data.success) {
                    renderAiCheckError(badgeBox, resultBox, data.message || 'AI check failed.');
                    return;
                }
                renderAiCheckResult(badgeBox, resultBox, data);
            })
            .catch(function() {
                btn.disabled = false;
                renderAiCheckError(badgeBox, resultBox, 'Network error. Please try again.');
            });
    }

    function updateAiCheckLimitState(btn, remaining) {
        const wrapper = btn.closest('[data-ai-check]');
        const existingIcon = wrapper.querySelector('[data-ai-check-limit-icon]');

        if (existingIcon) {
            const existingTooltip = bootstrap.Tooltip.getInstance(existingIcon);
            if (existingTooltip) {
                existingTooltip.dispose();
            }
            existingIcon.remove();
        }

        if (typeof remaining !== 'number' || remaining > 0) {
            btn.disabled = false;
            return;
        }

        btn.disabled = true;

        const icon = document.createElement('i');
        icon.className = 'fas fa-circle-info text-warning ms-1';
        icon.setAttribute('data-ai-check-limit-icon', '');
        icon.setAttribute('data-bs-toggle', 'tooltip');
        icon.setAttribute('title', 'Requests left: 0. Try again in a while.');
        btn.insertAdjacentElement('afterend', icon);
        new bootstrap.Tooltip(icon);
    }

    function renderAiCheckError(badgeBox, resultBox, message) {
        badgeBox.innerHTML = '<span class="text-danger small"><i class="fas fa-exclamation-circle me-1"></i>' + escapeAiCheckHtml(message) + '</span>';
        resultBox.innerHTML = '';
    }

    function renderAiCheckResult(badgeBox, resultBox, data) {
        const badgeClass = data.passed ? 'text-success' : 'text-danger';
        const badgeIcon = data.passed ? 'fa-check-circle' : 'fa-exclamation-triangle';
        const badgeText = data.passed ? 'Passed' : 'Needs Revision';

        badgeBox.innerHTML = '<span class="' + badgeClass + ' fw-bold small"><i class="fas ' + badgeIcon + ' me-1"></i>' + badgeText + ' (' + data.overall + '% Human content)</span>';

        if (!Array.isArray(data.chapters) || data.chapters.length === 0) {
            resultBox.innerHTML = '';
            return;
        }

        let listHtml = '<ul class="list-unstyled mb-0 mt-1">';
        data.chapters.forEach(function(ch) {
            const chClass = ch.passed ? 'text-success' : 'text-danger';
            listHtml += '<li class="' + chClass + '">' + escapeAiCheckHtml(ch.title) + ': ' + ch.score + '% Human content';
            if (!ch.passed && ch.note) {
                listHtml += ' &mdash; <span class="text-muted">' + escapeAiCheckHtml(ch.note) + '</span>';
            }
            listHtml += '</li>';
        });
        listHtml += '</ul>';

        const collapseId = 'aiCheckDetails' + (aiCheckIdCounter++);

        resultBox.innerHTML =
            '<button type="button" class="btn btn-link btn-sm p-0 small text-decoration-none" ' +
            'data-bs-toggle="collapse" data-bs-target="#' + collapseId + '" aria-expanded="true" aria-controls="' + collapseId + '">' +
            '<i class="fas fa-chevron-up me-1"></i><span>Hide details</span>' +
            '</button>' +
            '<div class="collapse show small" id="' + collapseId + '">' + listHtml + '</div>';

        const toggleBtn = resultBox.querySelector('button');
        const collapseEl = resultBox.querySelector('.collapse');

        collapseEl.addEventListener('show.bs.collapse', function() {
            toggleBtn.innerHTML = '<i class="fas fa-chevron-up me-1"></i><span>Hide details</span>';
        });
        collapseEl.addEventListener('hide.bs.collapse', function() {
            toggleBtn.innerHTML = '<i class="fas fa-chevron-down me-1"></i><span>Show details</span>';
        });
    }

    function escapeAiCheckHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }
</script>
</body>
</html>