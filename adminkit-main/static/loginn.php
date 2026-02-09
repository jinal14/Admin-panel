<?php
session_start();
include 'config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username === '' || $password === '') {
        $error = "Please fill all fields";
    } else {

        /* Secure prepared statement */
        $stmt = mysqli_prepare(
            $conn,
            "SELECT id, username, password_hash, role 
             FROM user 
             WHERE username = ? LIMIT 1"
        );

        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 1) {

            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password_hash'])) {

                if ($user['role'] === 'admin') {

                    // SESSION SET
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = 'admin';

                    header("Location: index.php");
                    exit;

                } else {
                    $error = "Access denied. Admin only.";
                }

            } else {
                $error = "Invalid password";
            }

        } else {
            $error = "User not found";
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

								<?php if ($error): ?>
									<div class="alert alert-danger">
										<?= htmlspecialchars($error) ?>
									</div>
								<?php endif; ?>

								<form method="POST" action="login.php">
							<div class="mb-3">
								<label class="form-label">Username</label>
								<input class="form-control form-control-lg" type="text" name="username" required />
							</div>

							<div class="mb-3">
								<label class="form-label">Password</label>
								<input class="form-control form-control-lg" type="password" name="password" required />
							</div>

							<div class="d-grid gap-2 mt-3">
								<button type="submit" class="btn btn-lg btn-primary">Log in</button>
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
