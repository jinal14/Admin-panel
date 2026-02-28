<?php
session_start();
include 'config.php';

if (!isset($_GET['id'])) {
    die('Invalid request');
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

$sql = "
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
WHERE id = '$id'
";

$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    die('User not found');
}

$user = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link href="css/app.css" rel="stylesheet">
    <!-- <style>
        /* ================================
   User Details Page Font Scaling
================================ */

.user-details-page {
    font-size: 16px; /* base size */
}

.user-details-page h1 {
    font-size: 2.2rem;
}

.user-details-page h3 {
    font-size: 1.9rem;
}

.user-details-page h5 {
    font-size: 1.4rem;
}

.user-details-page p {
    font-size: 1.1rem;
    line-height: 1.7;
}

.user-details-page strong {
    font-size: 1.15rem;
}

.user-details-page .card-title {
    font-size: 1.5rem;
}

.user-details-page .btn {
    font-size: 1.05rem;
    padding: 10px 14px;
}

.user-details-page img {
    transform: scale(1.05);
}
    </style> -->
</head>

<body>
<div class="wrapper">

    <?php include 'includes/sidebar.php'; ?>

    <div class="main">

        <!-- ✅ NAVBAR WRAPPER (IMPORTANT) -->
        <nav class="navbar navbar-expand navbar-light navbar-bg">
            <?php include 'includes/navbar.php'; ?>
        </nav>

        <main class="content user-details-page">
            <div class="container-fluid p-0">

                <h1 class="h3 mb-3"><strong>User</strong> Details</h1>

                <div class="row align-items-stretch">

                    <!-- USER INFO -->
                    <div class="col-lg-8 d-flex">
                        <div class="card h-100 w-100">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <?= htmlspecialchars($user['name']) ?>
                                </h5>
                            </div>

                            <div class="card-body">
                                <p><strong>Username:</strong><br><?= htmlspecialchars($user['username']) ?></p>
                                <p><strong>Email:</strong><br><?= htmlspecialchars($user['email']) ?></p>
                                <p><strong>Phone:</strong><br><?= htmlspecialchars($user['phone']) ?></p>

                                <p><strong>Date of Birth:</strong><br>
                                    <?= $user['dob'] ? date('d M Y', strtotime($user['dob'])) : 'N/A' ?>
                                </p>

                                <p><strong>Joined On:</strong><br>
                                    <?= date('d M Y', strtotime($user['created_at'])) ?>
                                </p>

                                <hr>

                                <div class="row g-2">
                                    <div class="col-6">
                                        <a href="edit_user.php?id=<?= $user['id'] ?>"
                                           class="btn btn-primary w-100">Update</a>
                                    </div>
                                    <div class="col-6">
                                        <a href="delete_user.php?id=<?= $user['id'] ?>"
                                           onclick="return confirm('Delete this user?')"
                                           class="btn btn-danger w-100">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PROFILE CARD -->
                    <div class="col-lg-4 d-flex">
                        <div class="card h-100 w-100">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Profile</h5>
                            </div>

                            <div class="card-body text-center d-flex flex-column justify-content-center">
                                <img src="<?= !empty($user['profile_pic_url'])
                                    ? '../' . htmlspecialchars($user['profile_pic_url'])
                                    : 'img/default-user.png' ?>"
                                    style="width:110px;height:110px;border-radius:50%;object-fit:cover;"
                                    class="mx-auto">

                                <h5 class="mt-3 mb-0"><?= htmlspecialchars($user['name']) ?></h5>
                                <p class="text-muted mb-1"><?= htmlspecialchars($user['email']) ?></p>
                                <p><?= htmlspecialchars($user['phone']) ?></p>
                            </div>

                              <div class="row justify-content-center mt-5 mb-5">
                                    <div class="col-10">
                                        <a href="user.php" class="btn btn-secondary w-100 mt-3">
                                            ← Back to Users
                                        </a>
                                    </div>
                                </div>

                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
</div>

<!-- ✅ REQUIRED FOR SIDEBAR -->
<script src="js/app.js"></script>

</body>
</html>