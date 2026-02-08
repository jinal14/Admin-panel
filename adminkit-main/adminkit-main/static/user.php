<?php
include 'config.php';
$query = "
    SELECT
        id,
        name,
        username,
        email,
        phone,
        profile_pic_url,
        dob,
        created_at
    FROM user
    WHERE role = 'user'
    ORDER BY created_at DESC
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
                                                <th>Photo</th>
                                                <th>Name</th>
                                                <th>Username</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>DOB</th>
                                                <th>Joined</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>

                                    <tbody>
                                    <?php
                                    $i = 1;
                                    while ($row = mysqli_fetch_assoc($result)):
                                    ?>
                                    <tr>
                                        <td><?= $i++ ?></td>

                                        <td>
                                            <?php if (!empty($row['profile_pic_url'])): ?>
                                                <img src="../<?= htmlspecialchars($row['profile_pic_url']) ?>"
                                                    style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                                            <?php else: ?>
                                                <img src="img/default-user.png"
                                                    style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                                            <?php endif; ?>
                                        </td>

                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td><?= htmlspecialchars($row['username']) ?></td>
                                        <td><?= htmlspecialchars($row['email']) ?></td>
                                        <td><?= htmlspecialchars($row['phone']) ?></td>

                                        <td>
                                            <?= $row['dob']
                                                ? date('d M Y', strtotime($row['dob']))
                                                : '<span class="text-muted">N/A</span>' ?>
                                        </td>

                                        <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>

                                        <td>
                                            <a href="edit_user.php?id=<?= $row['id'] ?>"
                                                class="btn btn-sm btn-primary">Edit</a>

                                            <a href="delete_user.php?id=<?= $row['id'] ?>"
                                                onclick="return confirm('Delete this user?')"
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