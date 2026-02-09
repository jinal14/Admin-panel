<?php
include 'config.php';

$query = "
    SELECT
        p.id,
        p.name AS project_name,
        p.description,
        p.status,
        p.currency,
        p.total_estimated_cost,
        p.created_at,
        u.name AS client_name,
        u.email AS client_email
    FROM project p
    JOIN user u ON p.user_id = u.id
    ORDER BY p.created_at DESC
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die('Query Failed: ' . mysqli_error($conn));
}
?>


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
		<?php include 'includes/sidebar.php'; ?>

		<div class="main">
			<?php include 'includes/navbar.php'; ?>
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
									<h5 class="card-title mb-0">Client List</h5>
								</div>
								<div class="card-body p-0">
									<table class="table table-hover my-0">
										<thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Project</th>
                                                <th>Client</th>
                                                <th>Status</th>
                                                <th>Cost</th>
                                                <th>Created</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>


                                                                            <tbody>
                                        <?php $i = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                                            <tr>
                                                <td><?= $i++ ?></td>

                                                <td>
                                                    <strong><?= htmlspecialchars($row['project_name']) ?></strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        <?= substr(strip_tags($row['description']), 0, 60) ?>...
                                                    </small>
                                                </td>

                                                <td>
                                                    <?= htmlspecialchars($row['client_name']) ?><br>
                                                    <small class="text-muted"><?= htmlspecialchars($row['client_email']) ?></small>
                                                </td>

                                                <td>
                                                    <?php
                                                    $badge = match ($row['status']) {
                                                        'draft' => 'secondary',
                                                        'in_progress' => 'warning',
                                                        'completed' => 'success',
                                                        default => 'dark'
                                                    };
                                                    ?>
                                                    <span class="badge bg-<?= $badge ?>">
                                                        <?= ucfirst(str_replace('_', ' ', $row['status'])) ?>
                                                    </span>
                                                </td>

                                                <td>
                                                    <?= $row['currency'] ?>
                                                    <?= number_format($row['total_estimated_cost'], 2) ?>
                                                </td>

                                                <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>

                                                <td>
                                                    <a href="view_project.php?id=<?= $row['id'] ?>"
                                                        class="btn btn-sm btn-info">View</a>

                                                    <a href="edit_project.php?id=<?= $row['id'] ?>"
                                                        class="btn btn-sm btn-primary">Edit</a>

                                                    <a href="delete_project.php?id=<?= $row['id'] ?>"
                                                        onclick="return confirm('Delete this project?')"
                                                        class="btn btn-sm btn-danger">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
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