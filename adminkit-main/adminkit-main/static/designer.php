<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>VibeUp – Analytics Dashboard</title>

	<link href="css/app.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
<div class="wrapper">

	<!-- SIDEBAR -->
	<nav id="sidebar" class="sidebar js-sidebar">
		<div class="sidebar-content js-simplebar">
			<a class="sidebar-brand" href="#">
				<span class="align-middle">VibeUp</span>
			</a>

			<ul class="sidebar-nav">
				<li class="sidebar-header">Pages</li>

				<li class="sidebar-item ">
					<a class="sidebar-link" href="#">
						<i data-feather="sliders"></i>
						<span>Dashboard</span>
					</a>
				</li>

				<li class="sidebar-item Active">
					<a class="sidebar-link" href="#">
						<i data-feather="codepen"></i>
						<span>Designer</span>
					</a>
				</li>

				<li class="sidebar-item">
					<a class="sidebar-link" href="#">
						<i data-feather="users"></i>
						<span>Users</span>
					</a>
				</li>

				<li class="sidebar-item">
					<a class="sidebar-link" href="#">
						<i data-feather="book"></i>
						<span>Project</span>
					</a>
				</li>
			</ul>
		</div>
	</nav>

	<!-- MAIN -->
	<div class="main">

		<!-- NAVBAR -->
		<nav class="navbar navbar-expand navbar-light navbar-bg">
			<a class="sidebar-toggle js-sidebar-toggle">
				<i class="hamburger"></i>
			</a>

			<div class="navbar-collapse collapse">
				<ul class="navbar-nav navbar-align">
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
							<img src="img/avatars/avatar.jpg" class="avatar img-fluid rounded me-1">
							<span class="text-dark">Charles Hall</span>
						</a>
						<div class="dropdown-menu dropdown-menu-end">
							<a class="dropdown-item" href="#">Profile</a>
							<a class="dropdown-item" href="#">Analytics</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="#">Log out</a>
						</div>
					</li>
				</ul>
			</div>
		</nav>

		<!-- CONTENT -->
		<main class="content">
			<div class="container-fluid p-0">

				<h1 class="h3 mb-3"><strong>Analytics</strong> Dashboard</h1>

				<div class="row">
					<div class="col-12">
						<div class="card flex-fill">

							<div class="card-header">
								<h5 class="card-title mb-0">Latest Projects</h5>
							</div>

							<div class="card-body p-0">
								<table class="table table-hover my-0">
									<thead>
										<tr>
											<th>Name</th>
											<th class="d-none d-xl-table-cell">Start Date</th>
											<th class="d-none d-xl-table-cell">End Date</th>
											<th>Status</th>
											<th class="d-none d-md-table-cell">Assignee</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Project Apollo</td>
											<td class="d-none d-xl-table-cell">01/01/2023</td>
											<td class="d-none d-xl-table-cell">31/06/2023</td>
											<td><span class="badge bg-success">Done</span></td>
											<td class="d-none d-md-table-cell">Vanessa Tucker</td>
										</tr>
										<tr>
											<td>Project Fireball</td>
											<td class="d-none d-xl-table-cell">01/01/2023</td>
											<td class="d-none d-xl-table-cell">31/06/2023</td>
											<td><span class="badge bg-danger">Cancelled</span></td>
											<td class="d-none d-md-table-cell">William Harris</td>
										</tr>
										<tr>
											<td>Project Hades</td>
											<td class="d-none d-xl-table-cell">01/01/2023</td>
											<td class="d-none d-xl-table-cell">31/06/2023</td>
											<td><span class="badge bg-success">Done</span></td>
											<td class="d-none d-md-table-cell">Sharon Lessman</td>
										</tr>
										<tr>
											<td>Project Nitro</td>
											<td class="d-none d-xl-table-cell">01/01/2023</td>
											<td class="d-none d-xl-table-cell">31/06/2023</td>
											<td><span class="badge bg-warning">In progress</span></td>
											<td class="d-none d-md-table-cell">Vanessa Tucker</td>
										</tr>
										<tr>
											<td>Project Phoenix</td>
											<td class="d-none d-xl-table-cell">01/01/2023</td>
											<td class="d-none d-xl-table-cell">31/06/2023</td>
											<td><span class="badge bg-success">Done</span></td>
											<td class="d-none d-md-table-cell">William Harris</td>
										</tr>
										<tr>
											<td>Project X</td>
											<td class="d-none d-xl-table-cell">01/01/2023</td>
											<td class="d-none d-xl-table-cell">31/06/2023</td>
											<td><span class="badge bg-success">Done</span></td>
											<td class="d-none d-md-table-cell">Sharon Lessman</td>
										</tr>
									</tbody>
								</table>
							</div>

						</div>
					</div>
				</div>

			</div>
		</main>

	</div>
</div>

<script src="js/app.js"></script>
</body>
</html>
