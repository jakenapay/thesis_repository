<style>
    #departmentChart {
        max-width: 400px;
        max-height: 400px;
        margin: 0 auto;
        display: block;
    }
</style>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="bg-red text-light card-header fw-bold">
                    Analytics
                </div>
                <div class="card-body">
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-8 p-2">
                            <div class="border rounded p-3">
                                <h6 class="text-center">Document Type Distribution</h6>
                                <canvas id="documentType" width="400" height="200"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4 p-2">
                            <div class="border rounded p-3">
                                <h6 class="text-center">Department</h6>
                                <canvas id="departmentChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let delayed = false;
    fetch('<?= base_url('getAnalyticsData') ?>')
        .then(response => response.json())
        .then(data => {
            // Graph 1: Document Type
            const ctxType = document.getElementById('documentType').getContext('2d');
            new Chart(ctxType, {
                type: 'bar',
                data: {
                    labels: data.typeData.labels,
                    datasets: [{
                        label: 'Total Count',
                        data: data.typeData.counts,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    animation: {
                        onComplete: () => {
                            delayed = true;
                        },
                        delay: (context) => {
                            let delay = 0;
                            if (context.type === 'data' && context.mode === 'default' && !delayed) {
                                delay = context.dataIndex * 300 + context.datasetIndex * 100;
                            }
                            return delay;
                        },
                    },
                }
            });

            // Graph 2: Department
            const ctxDept = document.getElementById('departmentChart').getContext('2d');
            new Chart(ctxDept, {
                type: 'doughnut',
                data: {
                    labels: data.deptData.labels,
                    datasets: [{
                        label: 'Total Count',
                        data: data.deptData.counts,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(153, 102, 255, 0.7)',
                            'rgba(255, 159, 64, 0.7)'
                        ],
                        borderColor: 'rgba(255, 255, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    animation: {
                        onComplete: () => {
                            delayed = true;
                        },
                        delay: (context) => {
                            let delay = 0;
                            if (context.type === 'data' && context.mode === 'default' && !delayed) {
                                delay = context.dataIndex * 300 + context.datasetIndex * 100;
                            }
                            return delay;
                        },
                    },
                }
            });
        });
</script>