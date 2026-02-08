<?php
session_start();
include 'config.php';

// Optional admin protection
// if ($_SESSION['role'] !== 'admin') {
//     die('Access denied');
// }

/*
 Fetch designer performance stats
*/
$query = "
    SELECT
        d.id AS designer_id,
        u.name AS designer_name,
        d.experience_years,
        d.rating,

        COUNT(dr.id) AS total_requests,

        SUM(dr.status = 'accepted') AS accepted_requests,
        SUM(dr.status = 'live') AS live_projects,
        SUM(dr.status = 'solved') AS solved_projects

    FROM designer d

    JOIN user u ON u.id = d.id
    LEFT JOIN designer_request dr ON dr.designer_id = d.id

    GROUP BY d.id
    ORDER BY solved_projects DESC, rating DESC
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Designer Performance</title>
	<link href="css/app.css" rel="stylesheet">
</head>

<body>
<div class="wrapper">

	<?php include 'includes/sidebar.php'; ?>

	<div class="main">
		<?php include 'includes/navbar.php'; ?>

		<main class="content">
			<div class="container-fluid p-0">

				<h1 class="h3 mb-3"><strong>Designer</strong> Performance</h1>

				<div class="card">
					<div class="card-body p-0">
						<table class="table table-hover my-0">
							<thead>
								<tr>
									<th>#</th>
									<th>Designer</th>
									<th>Experience</th>
									<th>Rating</th>
									<th>Total Requests</th>
									<th>Accepted</th>
									<th>Live</th>
									<th>Solved</th>
								</tr>
							</thead>
							<tbody>

							<?php $i = 1; while ($row = mysqli_fetch_assoc($result)): ?>
								<tr>
									<td><?= $i++ ?></td>
									<td><?= htmlspecialchars($row['designer_name']) ?></td>

									<td>
										<?= $row['experience_years'] ?? 0 ?> yrs
									</td>

									<td>
										<span class="badge bg-success">
											⭐ <?= number_format($row['rating'], 1) ?>
										</span>
									</td>

									<td><?= $row['total_requests'] ?></td>

									<td>
										<span class="badge bg-primary">
											<?= $row['accepted_requests'] ?>
										</span>
									</td>

									<td>
										<span class="badge bg-warning">
											<?= $row['live_projects'] ?>
										</span>
									</td>

									<td>
										<span class="badge bg-success">
											<?= $row['solved_projects'] ?>
										</span>
									</td>
								</tr>
							<?php endwhile; ?>

							</tbody>
						</table>
					</div>
				</div>

			</div>
		</main>
	</div>
</div>

<script src="js/app.js"></script>
</body>
</html>
