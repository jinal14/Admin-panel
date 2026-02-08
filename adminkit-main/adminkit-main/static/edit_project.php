<?php
session_start();
include 'config.php';

/* ===============================
   HANDLE FORM SUBMIT (POST)
================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_POST['id'])) {
        die('Project ID missing');
    }

    $id          = $_POST['id'];
    $name        = $_POST['name'];
    $description = $_POST['description'];
    $status      = $_POST['status'];
    $currency    = $_POST['currency'];
    $cost        = $_POST['total_estimated_cost'];

    // BASIC VALIDATION
    if (empty($name)) {
        die('Project name is required');
    }

    $stmt = mysqli_prepare(
        $conn,
        "
        UPDATE project
        SET
            name = ?,
            description = ?,
            status = ?,
            currency = ?,
            total_estimated_cost = ?
        WHERE id = ?
        "
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ssssds",
        $name,
        $description,
        $status,
        $currency,
        $cost,
        $id
    );

    mysqli_stmt_execute($stmt);

    header("Location: view_project.php?id=$id&success=updated");
    exit;
}

/* ===============================
   LOAD PROJECT DATA (GET)
================================ */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Project ID missing');
}

$id = $_GET['id'];

$stmt = mysqli_prepare(
    $conn,
    "
    SELECT
        id,
        name,
        description,
        status,
        currency,
        total_estimated_cost
    FROM project
    WHERE id = ?
    "
);

mysqli_stmt_bind_param($stmt, "s", $id);
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
	<title>Edit Project</title>

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
					<strong>Edit</strong> Project
				</h1>

				<div class="row">
					<div class="col-lg-8 mx-auto">

						<div class="card">
							<div class="card-body">

								<form method="POST">

									<input type="hidden" name="id" value="<?= $project['id'] ?>">

									<div class="mb-3">
										<label class="form-label">Project Name</label>
										<input type="text"
											class="form-control"
											name="name"
											value="<?= htmlspecialchars($project['name']) ?>"
											required>
									</div>

									<div class="mb-3">
										<label class="form-label">Description</label>
										<textarea class="form-control"
											name="description"
											rows="5"><?= htmlspecialchars($project['description']) ?></textarea>
									</div>

									<div class="row">

										<div class="col-md-4 mb-3">
											<label class="form-label">Status</label>
											<select class="form-select" name="status">
												<?php
												$statuses = ['draft', 'in_progress', 'completed'];
												foreach ($statuses as $s):
												?>
													<option value="<?= $s ?>"
														<?= $project['status'] === $s ? 'selected' : '' ?>>
														<?= ucfirst(str_replace('_', ' ', $s)) ?>
													</option>
												<?php endforeach; ?>
											</select>
										</div>

										<div class="col-md-4 mb-3">
											<label class="form-label">Currency</label>
											<input type="text"
												class="form-control"
												name="currency"
												value="<?= htmlspecialchars($project['currency']) ?>"
												maxlength="3">
										</div>

										<div class="col-md-4 mb-3">
											<label class="form-label">Estimated Cost</label>
											<input type="number"
												step="0.01"
												class="form-control"
												name="total_estimated_cost"
												value="<?= htmlspecialchars($project['total_estimated_cost']) ?>">
										</div>

									</div>

									<div class="d-flex justify-content-between mt-4">
										<a href="view_project.php?id=<?= $project['id'] ?>"
											class="btn btn-secondary">
											Cancel
										</a>

										<button class="btn btn-primary">
											Update Project
										</button>
									</div>

								</form>

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
