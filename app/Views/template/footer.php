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
        
        if (!searchQuery.trim()) {
            return;
        }

        fetch('<?= base_url("search") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'searchDocs=' + encodeURIComponent(searchQuery)
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
</body>
</html>