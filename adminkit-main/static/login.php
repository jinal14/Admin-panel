<?php
session_start();
include 'config.php';

$error = "";

/* PROCESS LOGIN ONLY WHEN FORM IS SUBMITTED */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Username or password missing";
    } else {

        /* Fetch user */
        $stmt = mysqli_prepare($conn, "SELECT * FROM user WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) !== 1) {
            $error = "❌ User not found";
        } else {

            $user = mysqli_fetch_assoc($result);

            /* SIMPLE PASSWORD CHECK (PLAIN TEXT) */
            if ($password !== $user['password_hash']) {
                $error = "❌ Invalid password";
            }
            /* ADMIN CHECK */
            elseif ($user['role'] !== 'admin') {
                $error = "❌ Access denied (Admins only)";
            }
            else {
                /* LOGIN SUCCESS */
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_role'] = $user['role'];

                header("Location: index.php");
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Admin Login</title>
	<link href="css/app.css" rel="stylesheet">
</head>

<body>
<main class="d-flex w-100">
	<div class="container d-flex flex-column">
		<div class="row vh-100">
			<div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
				<div class="d-table-cell align-middle">

					<div class="text-center mt-4">
						<h1 class="h2">Admin Login</h1>
						<p class="lead">Login to access admin dashboard</p>
					</div>

					<div class="card">
						<div class="card-body">
							<div class="m-sm-3">

								<?php if (!empty($error)): ?>
									<div class="alert alert-danger">
										<?= htmlspecialchars($error) ?>
									</div>
								<?php endif; ?>

								<form method="POST">
									<div class="mb-3">
										<label class="form-label">Username</label>
										<input class="form-control form-control-lg" type="text" name="username" required>
									</div>

									<div class="mb-3">
										<label class="form-label">Password</label>
										<input class="form-control form-control-lg" type="password" name="password" required>
									</div>

									<div class="d-grid gap-2 mt-3">
										<button type="submit" class="btn btn-lg btn-primary">
											Log in
										</button>
									</div>
								</form>

							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</main>
</body>
</html>
