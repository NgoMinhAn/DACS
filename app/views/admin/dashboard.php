<!-- Admin Dashboard -->
<div class="container-fluid px-4">
    <h1 class="mt-3 mb-3">Dashboard</h1>
    
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-white-50">Total Users</div>
                            <div class="fs-2 fw-bold"><?php echo isset($userCount) ? $userCount : '0'; ?></div>
                        </div>
                        <div>
                            <i class="fas fa-users fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?php echo url('admin/users'); ?>">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-white-50">Total Guides</div>
                            <div class="fs-2 fw-bold"><?php echo isset($guideCount) ? $guideCount : '0'; ?></div>
                        </div>
                        <div>
                            <i class="fas fa-user-tie fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?php echo url('admin/guides'); ?>">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-white-50">Pending Applications</div>
                            <div class="fs-2 fw-bold"><?php echo isset($pendingCount) ? $pendingCount : '0'; ?></div>
                        </div>
                        <div>
                            <i class="fas fa-file-alt fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?php echo url('admin/guideApplications'); ?>">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-white-50">Categories</div>
                            <div class="fs-2 fw-bold"><?php echo isset($categoryCount) ? $categoryCount : '0'; ?></div>
                        </div>
                        <div>
                            <i class="fas fa-tags fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="<?php echo url('admin/categories'); ?>">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-3">
        <div class="col-xl-6">
            <div class="card mb-3 h-100">
                <div class="card-header">
                    <i class="fas fa-users-cog me-1"></i>
                    User Management
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="mb-3 flex-grow-1">Manage all registered users, view their profiles, and update their information.</p>
                    <a href="<?php echo url('admin/users'); ?>" class="btn btn-primary w-100">
                        <i class="fas fa-users me-1"></i>Manage Users
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-xl-6">
            <div class="card mb-3 h-100">
                <div class="card-header">
                    <i class="fas fa-user-tie me-1"></i>
                    Guide Management
                </div>
                <div class="card-body d-flex flex-column">
                    <p class="mb-3 flex-grow-1">Manage all registered guides, verify their profiles, and handle guide-related tasks.</p>
                    <a href="<?php echo url('admin/guides'); ?>" class="btn btn-success w-100">
                        <i class="fas fa-user-tie me-1"></i>Manage Guides
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mt-3">
        <div class="col-xl-8">
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i>
                    Users & Guides Growth
                </div>
                <div class="card-body">
                    <canvas id="myAreaChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Guides by Category
                </div>
                <div class="card-body">
                    <canvas id="myPieChart" width="100%" height="50"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Bar Chart Section -->
    <div class="row mt-3">
        <div class="col-xl-12">
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Bookings Overview
                </div>
                <div class="card-body">
                    <canvas id="myBarChart" width="100%" height="40"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Management Sections -->
    <div class="row mt-3">
        <div class="col-xl-12">
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fas fa-tags me-1"></i>
                    Category Management
                </div>
                <div class="card-body">
                    <p class="mb-3">Manage tour categories, add new categories, and organize guide specialties.</p>
                    <a href="<?php echo url('admin/categories'); ?>" class="btn btn-info">
                        <i class="fas fa-tags me-1"></i>Manage Categories
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Scripts -->
<script>
// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

// Area Chart Example - Users & Guides Growth
var ctx = document.getElementById("myAreaChart");
var myLineChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($monthLabels ?? []); ?>,
        datasets: [{
            label: "Users",
            lineTension: 0.3,
            backgroundColor: "rgba(2,117,216,0.2)",
            borderColor: "rgba(2,117,216,1)",
            pointRadius: 5,
            pointBackgroundColor: "rgba(2,117,216,1)",
            pointBorderColor: "rgba(255,255,255,0.8)",
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(2,117,216,1)",
            pointHitRadius: 50,
            pointBorderWidth: 2,
            data: <?php echo json_encode($usersByMonth ?? []); ?>,
        }, {
            label: "Guides",
            lineTension: 0.3,
            backgroundColor: "rgba(40,167,69,0.2)",
            borderColor: "rgba(40,167,69,1)",
            pointRadius: 5,
            pointBackgroundColor: "rgba(40,167,69,1)",
            pointBorderColor: "rgba(255,255,255,0.8)",
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(40,167,69,1)",
            pointHitRadius: 50,
            pointBorderWidth: 2,
            data: <?php echo json_encode($guidesByMonth ?? []); ?>,
        }],
    },
    options: {
        scales: {
            xAxes: [{
                time: {
                    unit: 'month'
                },
                gridLines: {
                    display: false
                },
                ticks: {
                    maxTicksLimit: 12
                }
            }],
            yAxes: [{
                ticks: {
                    min: 0,
                    maxTicksLimit: 5
                },
                gridLines: {
                    color: "rgba(0, 0, 0, .125)",
                }
            }],
        },
        legend: {
            display: true
        }
    }
});

// Pie Chart Example - Guides by Category
var ctxPie = document.getElementById("myPieChart");
var myPieChart = new Chart(ctxPie, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($categoryLabels ?? []); ?>,
        datasets: [{
            data: <?php echo json_encode($guidesByCategory ?? []); ?>,
            backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6f42c1', '#e83e8c', '#fd7e14', '#20c997', '#6c757d'],
        }],
    },
    options: {
        legend: {
            display: true,
            position: 'bottom'
        }
    }
});

// Bar Chart Example - Bookings Overview
var ctxBar = document.getElementById("myBarChart");
var myBarChart = new Chart(ctxBar, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($bookingLabels ?? []); ?>,
        datasets: [{
            label: "Bookings",
            backgroundColor: "rgba(2,117,216,1)",
            borderColor: "rgba(2,117,216,1)",
            data: <?php echo json_encode($bookingsByMonth ?? []); ?>,
        }],
    },
    options: {
        scales: {
            xAxes: [{
                time: {
                    unit: 'month'
                },
                gridLines: {
                    display: false
                },
                ticks: {
                    maxTicksLimit: 6
                }
            }],
            yAxes: [{
                ticks: {
                    min: 0,
                    maxTicksLimit: 5
                },
                gridLines: {
                    display: true
                }
            }],
        },
        legend: {
            display: false
        }
    }
});
</script>
