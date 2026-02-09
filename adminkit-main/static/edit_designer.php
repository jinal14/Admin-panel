<?php
session_start();
include 'config.php';

/* ===============================
   HANDLE FORM SUBMIT (POST)
================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id         = $_POST['id'];
    $name       = $_POST['name'];
    $email      = $_POST['email'];
    $username   = $_POST['username'];
    $phone      = $_POST['phone'];
    $bio        = $_POST['bio'];
    $experience = $_POST['experience_years'] ?: null;

    $portfolio = json_encode(array_filter(
        array_map('trim', explode(',', $_POST['portfolio_urls']))
    ));

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
        header("Location: edit_designer.php?id=$id&error=email_exists");
        exit;
    }

    /* ===============================
       PROFILE PIC UPLOAD
    ================================ */
    $profilePicPath = null;

    if (!empty($_FILES['profile_pic']['name'])) {

        $uploadDir = "../uploads/profiles/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = strtolower(pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $allowed)) {
            die("Invalid image format");
        }

        $fileName = "designer_" . $id . "_" . time() . "." . $ext;
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetPath)) {
            $profilePicPath = "uploads/profiles/" . $fileName;
        }
    }

    /* ===============================
       UPDATE USER TABLE
    ================================ */
    if ($profilePicPath) {

        $u = mysqli_prepare(
            $conn,
            "UPDATE user
             SET name=?, email=?, username=?, phone=?, profile_pic_url=?
             WHERE id=? AND role='designer'"
        );
        mysqli_stmt_bind_param($u, "ssssss",
            $name, $email, $username, $phone, $profilePicPath, $id
        );

    } else {

        $u = mysqli_prepare(
            $conn,
            "UPDATE user
             SET name=?, email=?, username=?, phone=?
             WHERE id=? AND role='designer'"
        );
        mysqli_stmt_bind_param($u, "sssss",
            $name, $email, $username, $phone, $id
        );
    }

    mysqli_stmt_execute($u);

    /* ===============================
       UPSERT DESIGNER TABLE
    ================================ */
    $d = mysqli_prepare(
        $conn,
        "INSERT INTO designer (id, bio, experience_years, portfolio_urls)
         VALUES (?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE
            bio=VALUES(bio),
            experience_years=VALUES(experience_years),
            portfolio_urls=VALUES(portfolio_urls)"
    );
    mysqli_stmt_bind_param($d, "ssis",
        $id, $bio, $experience, $portfolio
    );
    mysqli_stmt_execute($d);

    header("Location: designer.php?success=updated");
    exit;
}

/* ===============================
   LOAD DESIGNER DATA (GET)
================================ */
if (!isset($_GET['id'])) {
    die("Designer ID missing");
}

$id = $_GET['id'];

$q = mysqli_prepare(
    $conn,
    "SELECT u.id, u.name, u.email, u.username, u.phone, u.profile_pic_url,
            d.bio, d.experience_years, d.portfolio_urls
     FROM user u
     LEFT JOIN designer d ON u.id = d.id
     WHERE u.id=? AND u.role='designer'"
);
mysqli_stmt_bind_param($q, "s", $id);
mysqli_stmt_execute($q);
$result = mysqli_stmt_get_result($q);

$designer = mysqli_fetch_assoc($result);
if (!$designer) {
    die('Designer not found');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Edit Designer</title>
	<link href="css/app.css" rel="stylesheet">
</head>

<body>
<main class="d-flex w-100">
	<div class="container d-flex flex-column">
		<div class="row vh-100">
			<div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
				<div class="d-table-cell align-middle">

					<div class="text-center mt-4">
						<h1 class="h2">Edit Designer</h1>
						<p class="lead">Update designer profile</p>
					</div>

					<div class="card">
						<div class="card-body">
							<div class="m-sm-3">

								<form method="POST" enctype="multipart/form-data">

									<input type="hidden" name="id" value="<?= $designer['id'] ?>">

									<div class="text-center mb-3">
										<?php if (!empty($designer['profile_pic_url'])): ?>
											<img src="../<?= htmlspecialchars($designer['profile_pic_url']) ?>"
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
											value="<?= htmlspecialchars($designer['name']) ?>" required>
									</div>

									<div class="mb-3">
										<label class="form-label">Username</label>
										<input class="form-control" type="text" name="username"
											value="<?= htmlspecialchars($designer['username']) ?>" required>
									</div>

									<div class="mb-3">
										<label class="form-label">Email</label>
										<input class="form-control" type="email" name="email"
											value="<?= htmlspecialchars($designer['email']) ?>" required>
									</div>

									<div class="mb-3">
										<label class="form-label">Phone</label>
										<input class="form-control" type="text" name="phone"
											value="<?= htmlspecialchars($designer['phone']) ?>" required>
									</div>

									<div class="mb-3">
										<label class="form-label">Bio</label>
										<textarea class="form-control" name="bio"
											rows="3"><?= htmlspecialchars($designer['bio']) ?></textarea>
									</div>

									<div class="mb-3">
										<label class="form-label">Experience (Years)</label>
										<input class="form-control" type="number" name="experience_years"
											value="<?= $designer['experience_years'] ?>">
									</div>

									<div class="mb-3">
										<label class="form-label">Portfolio URLs</label>
										<textarea class="form-control" name="portfolio_urls"><?= implode(',', json_decode($designer['portfolio_urls'] ?? '[]', true)) ?></textarea>
									</div>

									<div class="d-grid gap-2">
										<button class="btn btn-primary btn-lg">
											Update Designer
										</button>
									</div>

								</form>

							</div>
						</div>
					</div>

					<div class="text-center mt-3">
						<a href="designer.php">← Back to Designers</a>
					</div>

				</div>
			</div>
		</div>
	</div>
</main>

<script src="js/app.js"></script>
</body>
</html>
