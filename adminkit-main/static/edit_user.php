<?php
session_start();
include 'config.php';

/* ===============================
   HANDLE FORM SUBMIT (POST)
================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id       = $_POST['id'];
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $username = $_POST['username'];
    $phone    = $_POST['phone'];
    $dob      = $_POST['dob'];

    /* ===============================
       EMAIL UNIQUE CHECK
    ================================ */
    $check = mysqli_prepare(
        $conn,
        "SELECT id FROM user WHERE email=? AND id!=?"
    );
    mysqli_stmt_bind_param($check, "ss", $email, $id);
    mysqli_stmt_execute($check);
    $res = mysqli_stmt_get_result($check);

    if (mysqli_num_rows($res) > 0) {
        header("Location: edit_user.php?id=$id&error=email_exists");
        exit;
    }

    /* ===============================
       PROFILE PIC UPLOAD
    ================================ */
    $profilePicPath = null;

    if (!empty($_FILES['profile_pic']['name'])) {

        $uploadDir = "../uploads/users/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = strtolower(pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $allowed)) {
            die("Invalid image format");
        }

        $fileName = "user_" . $id . "_" . time() . "." . $ext;
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetPath)) {
            $profilePicPath = "uploads/users/" . $fileName;
        }
    }

    /* ===============================
       UPDATE USER
    ================================ */
    if ($profilePicPath) {

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE user
             SET name=?, email=?, username=?, phone=?, dob=?, profile_pic_url=?
             WHERE id=? AND role='user'"
        );
        mysqli_stmt_bind_param($stmt, "sssssss",
            $name, $email, $username, $phone, $dob, $profilePicPath, $id
        );

    } else {

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE user
             SET name=?, email=?, username=?, phone=?, dob=?
             WHERE id=? AND role='user'"
        );
        mysqli_stmt_bind_param($stmt, "ssssss",
            $name, $email, $username, $phone, $dob, $id
        );
    }

    mysqli_stmt_execute($stmt);

    header("Location: user.php?success=updated");
    exit;
}

/* ===============================
   LOAD USER DATA (GET)
================================ */
if (!isset($_GET['id'])) {
    die("User ID missing");
}

$id = $_GET['id'];

$q = mysqli_prepare(
    $conn,
    "SELECT id, name, email, username, phone, dob, profile_pic_url
     FROM user
     WHERE id=? AND role='user'"
);
mysqli_stmt_bind_param($q, "s", $id);
mysqli_stmt_execute($q);
$result = mysqli_stmt_get_result($q);

$user = mysqli_fetch_assoc($result);
if (!$user) {
    die("User not found");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Edit User</title>
	<link href="css/app.css" rel="stylesheet">
</head>

<body>
<main class="d-flex w-100">
	<div class="container d-flex flex-column">
		<div class="row vh-100">
			<div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
				<div class="d-table-cell align-middle">

					<div class="text-center mt-4">
						<h1 class="h2">Edit User</h1>
						<p class="lead">Update user profile</p>
					</div>

					<div class="card">
						<div class="card-body">
							<div class="m-sm-3">

								<form method="POST" enctype="multipart/form-data">

									<input type="hidden" name="id" value="<?= $user['id'] ?>">

									<div class="text-center mb-3">
										<?php if (!empty($user['profile_pic_url'])): ?>
											<img src="../<?= htmlspecialchars($user['profile_pic_url']) ?>"
												style="width:90px;height:90px;border-radius:50%;object-fit:cover;">
										<?php else: ?>
											<img src="img/default-user.png"
												style="width:90px;height:90px;border-radius:50%;">
										<?php endif; ?>
									</div>

									<div class="mb-3">
										<label class="form-label">Profile Picture</label>
										<input class="form-control" type="file" name="profile_pic" accept="image/*">
									</div>

									<div class="mb-3">
										<label class="form-label">Name</label>
										<input class="form-control" type="text" name="name"
											value="<?= htmlspecialchars($user['name']) ?>" required>
									</div>

									<div class="mb-3">
										<label class="form-label">Username</label>
										<input class="form-control" type="text" name="username"
											value="<?= htmlspecialchars($user['username']) ?>" required>
									</div>

									<div class="mb-3">
										<label class="form-label">Email</label>
										<input class="form-control" type="email" name="email"
											value="<?= htmlspecialchars($user['email']) ?>" required>
									</div>

									<div class="mb-3">
										<label class="form-label">Phone</label>
										<input class="form-control" type="text" name="phone"
											value="<?= htmlspecialchars($user['phone']) ?>" required>
									</div>

									<div class="mb-3">
										<label class="form-label">Date of Birth</label>
										<input class="form-control" type="date" name="dob"
											value="<?= htmlspecialchars($user['dob']) ?>" >
									</div>

									<div class="d-grid gap-2">
										<button class="btn btn-primary btn-lg">
											Update User
										</button>
									</div>

								</form>

							</div>
						</div>
					</div>

					<div class="text-center mt-3">
						<a href="user.php">← Back to Users</a>
					</div>

				</div>
			</div>
		</div>
	</div>
</main>

<script src="js/app.js"></script>
</body>
</html>
