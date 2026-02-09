<?php
session_start();
include 'config.php';

/* Admin protection (optional but recommended) */
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     die('Access denied');
// }

/* Fetch designer requests */
$query = "
    SELECT
        dr.id AS request_id,
        dr.status,
        dr.created_at,

        u.name AS client_name,
        du.name AS designer_name,
        p.name AS project_name

    FROM designer_request dr
    JOIN user u ON dr.user_id = u.id
    JOIN designer d ON dr.designer_id = d.id
    JOIN user du ON du.id = d.id
    JOIN project p ON dr.project_id = p.id

    ORDER BY dr.created_at DESC
";


$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>VibeUp – Analytics Dashboard</title>

	<link href="css/app.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
        }
        .badge-pending { background: #ffc107; color: #000; }
.badge-accepted { background: #0d6efd; color: #fff; }
.badge-live { background: #20c997; color: #fff; }
.badge-solved { background: #198754; color: #fff; }
.badge-declined { background: #dc3545; color: #fff; }

        .btn {
            padding: 6px 10px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 13px;
        }
        .btn-approve { background: #28a745; color: #fff; }
        .btn-reject { background: #dc3545; color: #fff; }
    </style>
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
									<h5 class="card-title mb-0">Designers List</h5>
								</div>

								<div class="card-body p-0">
									<table class="table table-hover my-0">
										 <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Client</th>
                                                <th>Designer</th>
                                                <th>Project</th>
                                                <th>Status</th>
                                                <th>Requested On</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
										<tbody>

                                        <?php if (mysqli_num_rows($result) > 0): ?>
                                            <?php $i = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                                                <tr>
                                                    <td><?= $i++ ?></td>

                                                    <td><?= htmlspecialchars($row['client_name']) ?></td>

                                                    <td><?= htmlspecialchars($row['designer_name']) ?></td>

                                                    <td><?= htmlspecialchars($row['project_name']) ?></td>

                                                    <td>
                                                        <span class="badge badge-<?= $row['status'] ?>">
                                                            <?= ucfirst($row['status']) ?>
                                                        </span>
                                                    </td>

                                                    <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>

                                                    <td>
                                                        <?php if ($row['status'] === 'pending'): ?>
                                                            <a href="update_request_status.php?id=<?= $row['request_id'] ?>&status=accepted"
                                                            class="btn btn-sm btn-success"
                                                            onclick="return confirm('Approve this request?')">
                                                            Approve
                                                            </a>

                                                            <a href="update_request_status.php?id=<?= $row['request_id'] ?>&status=declined"
                                                            class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Reject this request?')">
                                                            Reject
                                                            </a>

                                                        <?php elseif ($row['status'] === 'accepted'): ?>
                                                            <span class="text-muted">Assigned</span>

                                                        <?php elseif ($row['status'] === 'live'): ?>
                                                            <span class="text-info">In Progress</span>

                                                        <?php elseif ($row['status'] === 'solved'): ?>
                                                            <span class="text-success">Completed</span>

                                                        <?php else: ?>
                                                            —
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>

                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="7">No designer requests found</td>
                                            </tr>
                                        <?php endif; ?>

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