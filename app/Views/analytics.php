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
                        <div class="row text-center mb-4">
                            <div class="col-md-3">
                                <div class="border rounded p-3 bg-red text-light shadow-sm">
                                    <h6>Total Theses</h6>
                                    <h4 id="totalTheses">0</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded p-3 bg-red text-light shadow-sm">
                                    <h6>Total Users</h6>
                                    <h4 id="totalUsers">0</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded p-3 bg-red text-light shadow-sm">
                                    <h6>Total Downloads</h6>
                                    <h4 id="totalDownloads">0</h4>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded p-3 bg-red text-light shadow-sm">
                                    <h6>Total Views</h6>
                                    <h4 id="totalViews">0</h4>
                                </div>
                            </div>
                        </div>
                        <div class="row text-center mb-4">
                            <div class="col-md-12">
                                <div class="border rounded p-3 bg-red text-light shadow-sm">
                                    <h6>Average Downloads per Thesis</h6>
                                    <h4 id="avgDownloadRatio">0</h4>
                                </div>
                            </div>
                        </div>

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
                        <div class="col-md-12 p-2">
                            <div class="border rounded p-3">
                                <h6 class="text-center">Thesis Submissions Over Time</h6>
                                <canvas id="submissionChart" height="100"></canvas>
                            </div>
                        </div>
                        <div class="col-md-6 p-2">
                            <div class="border rounded p-3">
                                <h6 class="text-center">Top 5 Most Viewed/Downloaded Theses</h6>
                                <canvas id="popularChart" height="200"></canvas>
                            </div>
                        </div>
                        <div class="col-md-6 p-2">
                            <div class="border rounded p-3">
                                <h6 class="text-center">Top Contributors</h6>
                                <canvas id="contributorChart" width="400" height="200"></canvas>
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

            console.log(data);

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

            // Graph 3: Submissions Over Time
            const ctxTime = document.getElementById('submissionChart').getContext('2d');
            new Chart(ctxTime, {
                type: 'line',
                data: {
                    labels: data.timeData.labels,
                    datasets: [{
                        label: 'Theses Submitted',
                        data: data.timeData.counts,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        tension: 0.3,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Graph 4: Popular Theses (Views & Downloads)
            const ctxPopular = document.getElementById('popularChart').getContext('2d');
            new Chart(ctxPopular, {
                type: 'bar',
                data: {
                    labels: data.popularData.labels,
                    datasets: [{
                            label: 'Views',
                            data: data.popularData.views,
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Downloads',
                            data: data.popularData.downloads,
                            backgroundColor: 'rgba(255, 206, 86, 0.7)',
                            borderColor: 'rgba(255, 206, 86, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Graph 5: Top Contributors
            const ctxContributors = document.getElementById('contributorChart').getContext('2d');
            new Chart(ctxContributors, {
                type: 'bar',
                data: {
                    labels: data.contributorData.labels,
                    datasets: [{
                        label: 'Uploads',
                        data: data.contributorData.counts,
                        backgroundColor: 'rgba(153, 102, 255, 0.7)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            document.getElementById('totalTheses').textContent = data.totals.theses;
            document.getElementById('totalUsers').textContent = data.totals.users;
            document.getElementById('totalDownloads').textContent = data.totals.downloads;
            document.getElementById('totalViews').textContent = data.totals.views;
            document.getElementById('avgDownloadRatio').textContent = data.totals.avgRatio;



        });
</script>