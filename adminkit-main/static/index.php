<?php
session_start();
include 'config.php';
include 'auth_admin.php';

// ALTER TABLE `designer_request` ADD created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;


/* ============================
   Helper Function
============================ */
function percentChange($current, $previous)
{
	if ($previous == 0) {
		return $current > 0 ? 100 : 0;
	}
	return round((($current - $previous) / $previous) * 100, 2);
}

/* ============================
   Projects (This Week vs Last Week)
============================ */
$thisWeekProjects = mysqli_fetch_assoc(
	mysqli_query($conn, "
        SELECT COUNT(*) AS total
        FROM project
        WHERE created_at >= CURDATE() - INTERVAL 7 DAY
    ")
)['total'];

$lastWeekProjects = mysqli_fetch_assoc(
	mysqli_query($conn, "
        SELECT COUNT(*) AS total
        FROM project
        WHERE created_at BETWEEN
            CURDATE() - INTERVAL 14 DAY
            AND CURDATE() - INTERVAL 7 DAY
    ")
)['total'];

$projectGrowth = percentChange($thisWeekProjects, $lastWeekProjects);

/* ============================
   Pending Requests (This Week vs Last Week)
============================ */
$thisWeekPending = mysqli_fetch_assoc(
	mysqli_query($conn, "
        SELECT COUNT(*) AS total
        FROM designer_request
        WHERE status = 'pending'
        AND created_at >= CURDATE() - INTERVAL 7 DAY
    ")
)['total'];

$lastWeekPending = mysqli_fetch_assoc(
	mysqli_query($conn, "
        SELECT COUNT(*) AS total
        FROM designer_request
        WHERE status = 'pending'
        AND created_at BETWEEN
            CURDATE() - INTERVAL 14 DAY
            AND CURDATE() - INTERVAL 7 DAY
    ")
)['total'];

$pendingGrowth = percentChange($thisWeekPending, $lastWeekPending);

/* ============================
   Users (Clients)
============================ */
$thisWeekUsers = mysqli_fetch_assoc(
	mysqli_query($conn, "
        SELECT COUNT(*) AS total
        FROM user
        WHERE role = 'user'
        AND created_at >= CURDATE() - INTERVAL 7 DAY
    ")
)['total'];

$lastWeekUsers = mysqli_fetch_assoc(
	mysqli_query($conn, "
        SELECT COUNT(*) AS total
        FROM user
        WHERE role = 'user'
        AND created_at BETWEEN
            CURDATE() - INTERVAL 14 DAY
            AND CURDATE() - INTERVAL 7 DAY
    ")
)['total'];

$userGrowth = percentChange($thisWeekUsers, $lastWeekUsers);

/* ============================
   Designers
============================ */
$thisWeekDesigners = mysqli_fetch_assoc(
	mysqli_query($conn, "
        SELECT COUNT(*) AS total
        FROM user
        WHERE role = 'designer'
        AND created_at >= CURDATE() - INTERVAL 7 DAY
    ")
)['total'];

$lastWeekDesigners = mysqli_fetch_assoc(
	mysqli_query($conn, "
        SELECT COUNT(*) AS total
        FROM user
        WHERE role = 'designer'
        AND created_at BETWEEN
            CURDATE() - INTERVAL 14 DAY
            AND CURDATE() - INTERVAL 7 DAY
    ")
)['total'];

$designerGrowth = percentChange($thisWeekDesigners, $lastWeekDesigners);

/* ============================
   Dashboard Totals
============================ */
$totalUsers = mysqli_fetch_assoc(
	mysqli_query($conn, "SELECT COUNT(*) AS total FROM user WHERE role='user'")
)['total'];

$totalDesigners = mysqli_fetch_assoc(
	mysqli_query($conn, "SELECT COUNT(*) AS total FROM user WHERE role='designer'")
)['total'];

$pendingRequests = mysqli_fetch_assoc(
	mysqli_query($conn, "SELECT COUNT(*) AS total FROM designer_request WHERE status='pending'")
)['total'];

$totalProjects = mysqli_fetch_assoc(
	mysqli_query($conn, "SELECT COUNT(*) AS total FROM project")
)['total'];

/* ============================
   Projects List
============================ */
$projects = mysqli_query(
	$conn,
	"SELECT name, description, status, currency, total_estimated_cost
     FROM project"
);

/* ============================
   Ratings Distribution
============================ */
$ratings = [0, 0, 0, 0, 0];

$ratingQuery = mysqli_query(
	$conn,
	"SELECT rating, COUNT(*) AS total
     FROM feedback
     GROUP BY rating"
);

while ($row = mysqli_fetch_assoc($ratingQuery)) {
	$ratings[$row['rating'] - 1] = (int) $row['total'];
}

/* ============================
   Monthly Project Chart Data
============================ */
$monthlyData = array_fill(1, 12, 0); // Jan–Dec

$monthlyQuery = mysqli_query(
	$conn,
	"SELECT MONTH(created_at) AS month, COUNT(*) AS total
     FROM project
     GROUP BY MONTH(created_at)"
);

while ($row = mysqli_fetch_assoc($monthlyQuery)) {
	$monthlyData[(int) $row['month']] = (int) $row['total'];
}

$chartData = array_values($monthlyData);

/* ============================
   Project Status Distribution
============================ */
$statusCounts = [
	'draft' => 0,
	'in_progress' => 0,
	'completed' => 0
];

$statusQuery = mysqli_query(
	$conn,
	"SELECT status, COUNT(*) AS total
     FROM project
     GROUP BY status"
);

while ($row = mysqli_fetch_assoc($statusQuery)) {
	$statusCounts[$row['status']] = (int) $row['total'];
}

/* ============================
   Recent Projects
============================ */
$recentProjects = mysqli_query(
	$conn,
	"SELECT name, created_at
     FROM project
     ORDER BY created_at DESC
     LIMIT 5"
);


/* ============================
   Dashboard Cards (RIGHT SIDE)
============================ */

// New Projects (last 7 days)
$newProjectQuery = mysqli_query($conn,
    "SELECT COUNT(*) AS total
     FROM project
     WHERE created_at >= NOW() - INTERVAL 7 DAY");
$newProjects = mysqli_fetch_assoc($newProjectQuery)['total'] ?? 0;

// Completed Projects
$completedQuery = mysqli_query($conn,
    "SELECT COUNT(*) AS total
     FROM project
     WHERE status = 'completed'");
$completedProjects = mysqli_fetch_assoc($completedQuery)['total'] ?? 0;

// Under Process Projects
$underProcessQuery = mysqli_query($conn,
    "SELECT COUNT(*) AS total
     FROM project
     WHERE status = 'in_progress'");
$underProcessProjects = mysqli_fetch_assoc($underProcessQuery)['total'] ?? 0;

// Designers Online
// $designerOnlineQuery = mysqli_query($conn,
//     "SELECT COUNT(*) AS total
//      FROM user
//      WHERE role = 'designer'
//      AND is_online = 1");
// $designerOnline = mysqli_fetch_assoc($designerOnlineQuery)['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords"
        content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="img/icons/icon-48x48.png" />

    <link rel="canonical" href="https://demo-basic.adminkit.io/" />

    <title>VibeUp Admin Dashboard</title>

    <link href="css/app.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
    /* Match right section height with calendar */
    .calendar-card {
        height: 100%;
    }

    /* 2x2 grid that fits exactly */
    .card-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-template-rows: repeat(2, 1fr);
        gap: 1rem;
        height: 100%;
    }

    /* Prevent cards from growing */
    .card-grid .card {
        height: 100%;
    }

    /* No overflow anywhere */
    .card,
    .card-body {
        overflow: hidden;
    }
    </style>
</head>

<body>
    <div class="wrapper">
        <?php include 'includes/sidebar.php'; ?>

        <div class="main">
            <!-- <?php //include 'includes/navbar.php'; ?> -->



            <main class="content">
                <div class="container-fluid p-0">

                    <h1 class="h3 mb-3"><strong>Analytics</strong> Dashboard</h1>

                    <div class="row">
                        <div class="col-xl-6 col-xxl-5 d-flex">
                            <div class="w-100">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <a href="project.php" class="card-link">
                                            <div class="card clickable-card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col mt-0">
                                                            <h5 class="card-title">Projects</h5>
                                                        </div>

                                                        <div class="col-auto">
                                                            <div class="stat text-primary">
                                                                <i class="align-middle" data-feather="book"></i>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <h1 class="mt-1 mb-3"><?= $totalProjects; ?></h1>

                                                    <div class="mb-0">
                                                        <span
                                                            class="<?= $projectGrowth >= 0 ? 'text-success' : 'text-danger' ?>">
                                                            <?= $projectGrowth ?>%
                                                        </span>
                                                        <span class="text-muted">Since last week</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="user.php" class="card-link">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col mt-0">
                                                            <h5 class="card-title">Clients</h5>
                                                        </div>

                                                        <div class="col-auto">
                                                            <div class="stat text-primary">
                                                                <i class="align-middle" data-feather="users"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <h1 class="mt-1 mb-3"><?php echo $totalUsers; ?></h1>
                                                    <div class="mb-0">
                                                        <span
                                                            class="<?= $userGrowth >= 0 ? 'text-success' : 'text-danger' ?>">
                                                            <?= $userGrowth ?>%
                                                        </span>
                                                        <span class="text-muted">Since last week</span>
                                                        <!-- <a href="user.php" class="btn btn-sm btn-primary w-100">View Users</a> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-sm-6">
                                        <a href="designer.php" class="card-link">
                                            <div class="card">
                                                <div class="card-body" href="designer.php">
                                                    <div class="row">
                                                        <div class="col mt-0">
                                                            <h5 class="card-title">Designer</h5>
                                                        </div>

                                                        <div class="col-auto">
                                                            <div class="stat text-primary">
                                                                <i class="align-middle" data-feather="codepen"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <h1 class="mt-1 mb-3"><?php echo $totalDesigners; ?></h1>
                                                    <div class="mb-0">
                                                        <span
                                                            class="<?= $designerGrowth >= 0 ? 'text-success' : 'text-danger' ?>">
                                                            <?= $designerGrowth ?>%
                                                        </span>
                                                        <span class="text-muted">Since last week</span>
                                                        <!-- <a href="designer.php" class="btn btn-sm btn-primary w-100">View Designers</a> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                        <a href="designer_requests.php" class="card-link">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col mt-0">
                                                            <h5 class="card-title">Pending Request</h5>
                                                        </div>

                                                        <div class="col-auto">
                                                            <div class="stat text-primary">
                                                                <i class="align-middle" data-feather="database"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <h1 class="mt-1 mb-3"><?php echo $pendingRequests; ?></h1>
                                                    <div class="mb-0">
                                                        <span
                                                            class="<?= $pendingGrowth >= 0 ? 'text-success' : 'text-danger' ?>">
                                                            <?= $pendingGrowth ?>%
                                                        </span>
                                                        <span class="text-muted">Since last week</span>
                                                        <!-- <a href="designer_requests.php" class="btn btn-sm btn-primary w-100">View Requests</a> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-xxl-7">
                            <div class="card flex-fill w-100">
                                <div class="card-header">

                                    <h5 class="card-title mb-0">System Overview</h5>
                                </div>
                                <div class="card-body py-3">
                                    <div class="chart chart-sm">
                                        <canvas id="chartjs-dashboard-line"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <!-- Calendar -->
                        <div class="col-12 col-xxl-4">
                            <div class="card h-100 calendar-card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Calendar</h5>
                                </div>
                                <div class="card-body">
                                    <div id="datetimepicker-dashboard"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side Cards -->
                        <div class="col-12 col-xxl-8">
                            <div class="card-grid">

                                <!-- New Projects -->
                                <a href="project.php" class="card-link">
                                    <div class="card stat-card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <h5 class="card-title">New Projects</h5>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="stat text-primary">
                                                        <i data-feather="plus-circle"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <h1 class="mt-1"><?= $newProjects ?></h1>
                                            <small class="text-muted">Last 7 days</small>
                                        </div>
                                    </div>
                                </a>

                                <!-- Project Completed -->
                                <a href="project.php" class="card-link">
                                    <div class="card stat-card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <h5 class="card-title">Project Completed</h5>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="stat text-success">
                                                        <i data-feather="check-circle"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <h1 class="mt-1"><?= $completedProjects ?></h1>
                                            <small class="text-muted">Finished</small>
                                        </div>
                                    </div>
                                </a>

                                <!-- Under Process -->
                                <a href="project.php" class="card-link">
                                    <div class="card stat-card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <h5 class="card-title">Under Process</h5>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="stat text-warning">
                                                        <i data-feather="activity"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <h1 class="mt-1"><?= $underProcessProjects ?></h1>
                                            <small class="text-muted">In progress</small>
                                        </div>
                                    </div>
                                </a>

                                <!-- Designer Online -->
                                <a href="designer.php" class="card-link">
                                    <div class="card stat-card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col">
                                                    <h5 class="card-title">Designer Online</h5>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="stat text-info">
                                                        <i data-feather="users"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <h1 class="mt-1">00</h1>
                                            <small class="text-muted">Currently active</small>
                                        </div>
                                    </div>
                                </a>

                            </div>
                        </div>

                    </div>


                </div>
            </main>


        </div>
    </div>

    <script src="js/app.js"></script>


    <script>
    document.addEventListener("DOMContentLoaded", function() {

        new Chart(document.getElementById("chartjs-dashboard-line"), {
            type: "line",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov",
                    "Dec"
                ],
                datasets: [{
                    label: "Projects Created",
                    data: <?php echo json_encode($chartData); ?>,
                    borderColor: window.theme.primary,
                    backgroundColor: "transparent",
                    fill: false,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointBackgroundColor: "#fff",
                    pointBorderColor: window.theme.primary,
                    pointBorderWidth: 3
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: false,
                        grid: {
                            display: false
                        },
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });
    </script>


    <script>
    document.addEventListener("DOMContentLoaded", function() {
        new Chart(document.getElementById("chartjs-dashboard-pie"), {
            type: "doughnut",
            data: {
                labels: ["Draft", "In Progress", "Completed"],
                datasets: [{
                    data: [
                        <?php echo $statusCounts['draft']; ?>,
                        <?php echo $statusCounts['in_progress']; ?>,
                        <?php echo $statusCounts['completed']; ?>
                    ],
                    backgroundColor: [
                        window.theme.warning,
                        window.theme.primary,
                        window.theme.success
                    ],
                    borderWidth: 4
                }]
            },
            options: {
                maintainAspectRatio: false,
                cutout: "70%",
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    });
    </script>




    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var date = new Date(Date.now() - 5 * 24 * 60 * 60 * 1000);
        var defaultDate = date.getUTCFullYear() + "-" + (date.getUTCMonth() + 1) + "-" + date.getUTCDate();
        document.getElementById("datetimepicker-dashboard").flatpickr({
            inline: true,
            prevArrow: "<span title=\"Previous month\">&laquo;</span>",
            nextArrow: "<span title=\"Next month\">&raquo;</span>",
            defaultDate: defaultDate
        });
    });
    </script>

</body>

</html>