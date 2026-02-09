<?php
session_start();
include 'config.php';

/* ===============================
   VALIDATE PROJECT ID
================================ */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Project ID missing');
}

$project_id = $_GET['id'];

/* ===============================
   FETCH PROJECT + CLIENT DATA
================================ */
$stmt = mysqli_prepare(
    $conn,
    "
    SELECT
        p.id,
        p.name AS project_name,
        p.description,
        p.status,
        p.currency,
        p.total_estimated_cost,
        p.created_at,
        p.updated_at,
        u.name AS client_name,
        u.email AS client_email,
        u.phone AS client_phone,
        u.profile_pic_url
    FROM project p
    JOIN user u ON p.user_id = u.id
    WHERE p.id = ?
    "
);

mysqli_stmt_bind_param($stmt, "s", $project_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$project = mysqli_fetch_assoc($result);

if (!$project) {
    die('Project not found');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>View Project</title>

	<link href="css/app.css" rel="stylesheet">
</head>

<body>
<div class="wrapper">

	<?php include 'includes/sidebar.php'; ?>

	<div class="main">

		<?php include 'includes/navbar.php'; ?>

		<main class="content">
			<div class="container-fluid p-0">

				<h1 class="h3 mb-3">
					<strong>Project</strong> Details
				</h1>

				<div class="row">

					<!-- PROJECT INFO -->
					<div class="col-lg-8">
						<div class="card">
							<div class="card-header">
								<h5 class="card-title mb-0">
									<?= htmlspecialchars($project['project_name']) ?>
								</h5>
							</div>

							<div class="card-body">

								<p>
									<strong>Description:</strong><br>
									<?= nl2br(htmlspecialchars($project['description'])) ?>
								</p>

								<hr>

								<div class="row">
									<div class="col-md-4">
										<strong>Status</strong><br>
										<?php
										$badge = match ($project['status']) {
											'draft' => 'secondary',
											'in_progress' => 'warning',
											'completed' => 'success',
											default => 'dark'
										};
										?>
										<span class="badge bg-<?= $badge ?>">
											<?= ucfirst(str_replace('_', ' ', $project['status'])) ?>
										</span>
									</div>

									<div class="col-md-4">
										<strong>Estimated Cost</strong><br>
										<?= $project['currency'] ?>
										<?= number_format($project['total_estimated_cost'], 2) ?>
									</div>

									<div class="col-md-4">
										<strong>Created On</strong><br>
										<?= date('d M Y', strtotime($project['created_at'])) ?>
									</div>
								</div>

								<hr>

								<div class="row">
									<div class="col-md-6">
										<strong>Last Updated</strong><br>
										<?= date('d M Y, h:i A', strtotime($project['updated_at'])) ?>
									</div>
								</div>

							</div>
						</div>
					</div>

					<!-- CLIENT INFO -->
					<div class="col-lg-4">
						<div class="card">
							<div class="card-header">
								<h5 class="card-title mb-0">Client Info</h5>
							</div>

							<div class="card-body text-center">

								<?php if (!empty($project['profile_pic_url'])): ?>
									<img src="../<?= htmlspecialchars($project['profile_pic_url']) ?>"
										style="width:90px;height:90px;border-radius:50%;object-fit:cover;">
								<?php else: ?>
									<img src="img/default-user.png"
										style="width:90px;height:90px;border-radius:50%;">
								<?php endif; ?>

								<h5 class="mt-3 mb-0">
									<?= htmlspecialchars($project['client_name']) ?>
								</h5>

								<p class="text-muted mb-1">
									<?= htmlspecialchars($project['client_email']) ?>
								</p>

								<p class="mb-0">
									<?= htmlspecialchars($project['client_phone']) ?>
								</p>

							</div>
						</div>

						<a href="project.php" class="btn btn-secondary w-50 mt-3">
							← Back to Projects
						</a>
                        <a href="generate_invoice.php?id=<?= htmlspecialchars($project_id) ?>"
                        class="btn btn-primary  mt-3"
                        target="_blank">
                        Download Invoice (PDF)
                        </a>


					</div>

				</div>

			</div>
		</main>

	</div>
</div>

<script src="js/app.js"></script>
</body>
</html>
