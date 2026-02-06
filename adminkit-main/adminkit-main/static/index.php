<?php
session_start();
include 'config.php';

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
    $ratings[$row['rating'] - 1] = (int)$row['total'];
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
    $monthlyData[(int)$row['month']] = (int)$row['total'];
}

$chartData = array_values($monthlyData);

/* ============================
   Project Status Distribution
============================ */
$statusCounts = [
    'draft'       => 0,
    'in_progress' => 0,
    'completed'   => 0
];

$statusQuery = mysqli_query(
    $conn,
    "SELECT status, COUNT(*) AS total
     FROM project
     GROUP BY status"
);

while ($row = mysqli_fetch_assoc($statusQuery)) {
    $statusCounts[$row['status']] = (int)$row['total'];
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
	<meta name="author" content="AdminKit">
	<meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link rel="shortcut icon" href="img/icons/icon-48x48.png" />

	<link rel="canonical" href="https://demo-basic.adminkit.io/" />

	<title>AdminKit Demo - Bootstrap 5 Admin Template</title>

	<link href="css/app.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
	<div class="wrapper">
		<?php include 'includes/sidebar.php'; ?>

		<div class="main">
			<?php include 'includes/navbar.php'; ?>

			<div class="navbar-collapse collapse">
				<ul class="navbar-nav navbar-align">
					<li class="nav-item dropdown">
						<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown">
							<div class="list-group">
								<a href="#" class="list-group-item">
									<div class="row g-0 align-items-center">
										<div class="col-2">
											<i class="text-primary" data-feather="home"></i>
										</div>
										<div class="col-10">
											<div class="text-dark">Login from 192.186.1.8</div>
											<div class="text-muted small mt-1">5h ago</div>
										</div>
									</div>
								</a>
								<a href="#" class="list-group-item">
									<div class="row g-0 align-items-center">
										<div class="col-2">
											<i class="text-success" data-feather="user-plus"></i>
										</div>
										<div class="col-10">
											<div class="text-dark">New connection</div>
											<div class="text-muted small mt-1">Christina accepted your request.</div>
											<div class="text-muted small mt-1">14h ago</div>
										</div>
									</div>
								</a>
							</div>
							<div class="dropdown-menu-footer">
								<a href="#" class="text-muted">Show all notifications</a>
							</div>
						</div>
					</li>

					<li class="nav-item dropdown">
						<a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
							<i class="align-middle" data-feather="settings"></i>
						</a>

						<a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
							<img src="img/avatars/avatar.jpg" class="avatar img-fluid rounded me-1" alt="Charles Hall" /> <span class="text-dark">Charles Hall</span>
						</a>
						<div class="dropdown-menu dropdown-menu-end">
							<a class="dropdown-item" href="pages-profile.html"><i class="align-middle me-1" data-feather="user"></i> Profile</a>
							<a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="pie-chart"></i> Analytics</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="index.html"><i class="align-middle me-1" data-feather="settings"></i> Settings & Privacy</a>
							<a class="dropdown-item" href="#"><i class="align-middle me-1" data-feather="help-circle"></i> Help Center</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="#">Log out</a>
						</div>
					</li>
				</ul>
			</div>
			</nav>

			<main class="content">
				<div class="container-fluid p-0">

					<h1 class="h3 mb-3"><strong>Analytics</strong> Dashboard</h1>

					<div class="row">
						<div class="col-xl-6 col-xxl-5 d-flex">
							<div class="w-100">
								<div class="row">
									<div class="col-sm-6">
										<div class="card">
											<div class="card-body">
												<div class="row">
													<div class="col mt-0">
														<h5 class="card-title">Projects</h5>
													</div>

													<div class="col-auto">
														<div class="stat text-primary">
															<i class="align-middle" data-feather="truck"></i>
														</div>
													</div>
												</div>
												<h1 class="mt-1 mb-3"><?php echo $totalProjects; ?></h1>
												<div class="mb-0">
													<span class="<?= $projectGrowth >= 0 ? 'text-success' : 'text-danger' ?>">
														<?= $projectGrowth ?>%
													</span>
													<span class="text-muted">Since last week</span>

												</div>
											</div>
										</div>
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
													<span class="<?= $userGrowth >= 0 ? 'text-success' : 'text-danger' ?>">
														<?= $userGrowth ?>%
													</span>
													<span class="text-muted">Since last week</span>

												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="card">
											<div class="card-body">
												<div class="row">
													<div class="col mt-0">
														<h5 class="card-title">Designer</h5>
													</div>

													<div class="col-auto">
														<div class="stat text-primary">
															<i class="align-middle" data-feather="dollar-sign"></i>
														</div>
													</div>
												</div>
												<h1 class="mt-1 mb-3"><?php echo $totalDesigners; ?></h1>
												<div class="mb-0">
													<span class="<?= $designerGrowth >= 0 ? 'text-success' : 'text-danger' ?>">
														<?= $designerGrowth ?>%
													</span>
													<span class="text-muted">Since last week</span>
												</div>
											</div>
										</div>
										<div class="card">
											<div class="card-body">
												<div class="row">
													<div class="col mt-0">
														<h5 class="card-title">Pending Request</h5>
													</div>

													<div class="col-auto">
														<div class="stat text-primary">
															<i class="align-middle" data-feather="shopping-cart"></i>
														</div>
													</div>
												</div>
												<h1 class="mt-1 mb-3"><?php echo $pendingRequests; ?></h1>
												<div class="mb-0">
													<span class="<?= $pendingGrowth >= 0 ? 'text-success' : 'text-danger' ?>">
														<?= $pendingGrowth ?>%
													</span>
													<span class="text-muted">Since last week</span>
												</div>
											</div>
										</div>
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
						<div class="col-12 col-md-6 col-xxl-3 d-flex order-2 order-xxl-3">
							<div class="card flex-fill w-100">
								<div class="card-header">

									<h5 class="card-title mb-0">Project Status</h5>

								</div>
								<div class="card-body d-flex">
									<div class="align-self-center w-100">
										<div class="py-3">
											<div class="chart chart-xs">
												<canvas id="chartjs-dashboard-pie"></canvas>
											</div>
										</div>

										<table class="table mb-0">
											<tbody>
												<tr>
													<td>Draft</td>
													<td class="text-end"><?php echo $statusCounts['draft']; ?></td>
												</tr>
												<tr>
													<td>In Progress</td>
													<td class="text-end"><?php echo $statusCounts['in_progress']; ?></td>
												</tr>
												<tr>
													<td>Completed</td>
													<td class="text-end"><?php echo $statusCounts['completed']; ?></td>
												</tr>
											</tbody>
										</table>

									</div>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-12 col-xxl-6 d-flex order-3 order-xxl-2">
							<div class="card flex-fill w-100">
								<div class="card-header">
									<h5 class="card-title mb-0">Real-Time</h5>
								</div>
								<div class="card-body px-4">

									<div class="activity-divider">
										<span>Recent Activity</span>
									</div>

									<ul class="list-group list-group-flush">
										<?php while ($p = mysqli_fetch_assoc($recentProjects)) { ?>
											<li class="list-group-item px-0 activity-item">
												<strong class="text-primary">New Project:</strong> <?php echo $p['name']; ?>
												<br>
												<small class="text-muted">
													<?php echo date("d M Y, h:i A", strtotime($p['created_at'])); ?>
												</small>
											</li>
										<?php } ?>
									</ul>
								</div>


							</div>
						</div>
						<div class="col-12 col-md-6 col-xxl-3 d-flex order-1 order-xxl-1">
							<div class="card flex-fill">
								<div class="card-header">

									<h5 class="card-title mb-0">Calendar</h5>
								</div>
								<div class="card-body d-flex">
									<div class="align-self-center w-100">
										<div class="chart">
											<div id="datetimepicker-dashboard"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>


				</div>
			</main>

			<footer class="footer">
				<div class="container-fluid">
					<div class="row text-muted">
						<div class="col-6 text-start">
							<p class="mb-0">
								<a class="text-muted" href="https://adminkit.io/" target="_blank"><strong>AdminKit</strong></a> - <a class="text-muted" href="https://adminkit.io/" target="_blank"><strong>Bootstrap Admin Template</strong></a> &copy;
							</p>
						</div>
						<div class="col-6 text-end">
							<ul class="list-inline">
								<li class="list-inline-item">
									<a class="text-muted" href="https://adminkit.io/" target="_blank">Support</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="https://adminkit.io/" target="_blank">Help Center</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="https://adminkit.io/" target="_blank">Privacy</a>
								</li>
								<li class="list-inline-item">
									<a class="text-muted" href="https://adminkit.io/" target="_blank">Terms</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>

	<script src="js/app.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/jsvectormap/dist/maps/india.js"></script>

	<script>
		document.addEventListener("DOMContentLoaded", function() {

			new Chart(document.getElementById("chartjs-dashboard-line"), {
				type: "line",
				data: {
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
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
			// Bar chart
			new Chart(document.getElementById("chartjs-dashboard-bar"), {
				type: "bar",
				data: {
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					datasets: [{
						label: "This year",
						backgroundColor: window.theme.primary,
						borderColor: window.theme.primary,
						hoverBackgroundColor: window.theme.primary,
						hoverBorderColor: window.theme.primary,
						data: [54, 67, 41, 55, 62, 45, 55, 73, 60, 76, 48, 79],
						barPercentage: .75,
						categoryPercentage: .5
					}]
				},
				options: {
					maintainAspectRatio: false,
					legend: {
						display: false
					},
					scales: {
						yAxes: [{
							gridLines: {
								display: false
							},
							stacked: false,
							ticks: {
								stepSize: 20
							}
						}],
						xAxes: [{
							stacked: false,
							gridLines: {
								color: "transparent"
							}
						}]
					}
				}
			});
		});
	</script>
	<script>
		document.addEventListener("DOMContentLoaded", function() {

			var map = new jsVectorMap({
				selector: "#world_map",
				map: "india",

				zoomButtons: true,
				zoomOnScroll: false,

				regionStyle: {
					initial: {
						fill: "#e0e0e0",
						stroke: "#ffffff",
						strokeWidth: 1
					},
					hover: {
						fill: "#4f46e5" // hover color
					},
					selected: {
						fill: "#1d4ed8" // selected state color
					}
				},

				regionsSelectable: true,

				// Highlight specific states
				selectedRegions: [
					"IN-MH", // Maharashtra
					"IN-GJ", // Gujarat
					"IN-RJ", // Rajasthan
					"IN-UP", // Uttar Pradesh
					"IN-DL" // Delhi
				]
			});

			window.addEventListener("resize", () => {
				map.updateSize();
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