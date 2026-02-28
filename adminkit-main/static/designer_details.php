<?php
session_start();
include 'config.php';

if (!isset($_GET['id'])) {
    die('Invalid request');
}

$id = mysqli_real_escape_string($conn, $_GET['id']); // ✅ STRING ID

$sql = "
SELECT 
    u.id,
    u.name,
    u.email,
    u.phone,
    u.profile_pic_url,
    u.created_at,

    d.experience_years,
    d.rating,

    q.qualification_name,
    q.institute_name,
    q.year_completed

FROM user u
LEFT JOIN designer d ON u.id = d.id
LEFT JOIN designer_qualification q ON d.id = q.designer_id
WHERE u.id = '$id'
";

$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    die('Designer not found');
}

$designer = mysqli_fetch_assoc($result);
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
                        <strong>Designer</strong> Details
                    </h1>

                    <div class="row">

                        <!-- DESIGNER INFO -->
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <?= htmlspecialchars($designer['name']) ?>
                                    </h5>
                                </div>

                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Experience</strong><br>
                                            <?= $designer['experience_years'] ?? 0 ?> Years
                                        </div>

                                        <div class="col-md-4">
                                            <strong>Rating</strong><br>
                                            <?= number_format($designer['rating'] ?? 0, 1) ?>
                                        </div>

                                        <div class="col-md-4">
                                            <strong>Joined On</strong><br>
                                            <?= date('d M Y', strtotime($designer['created_at'])) ?>
                                        </div>
                                    </div>

                                    <hr>

                                    <h5>Qualification Details</h5>

                                    <p>
                                        <strong>Qualification:</strong><br>
                                        <?= $designer['qualification_name'] ?? 'N/A' ?>
                                    </p>

                                    <p>
                                        <strong>Institute:</strong><br>
                                        <?= $designer['institute_name'] ?? 'N/A' ?>
                                    </p>

                                    <p>
                                        <strong>Year Completed:</strong><br>
                                        <?= $designer['year_completed'] ?? 'N/A' ?>
                                    </p>

                                    <hr>

                                    <div class="row mt-3">
                                        <div class="col-6">
                                            <a href="edit_designer.php?id=<?= $designer['id'] ?>"
                                                class="btn btn-primary w-100">
                                                Update
                                            </a>
                                        </div>

                                        <div class="col-6">
                                            <a href="delete_designer.php?id=<?= $designer['id'] ?>"
                                                onclick="return confirm('Delete this designer?')"
                                                class="btn btn-danger w-100">
                                                Delete
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- PROFILE CARD -->
                        <div class="col-lg-4 d-flex">
                            <div class="card h-80 w-100">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Designer Info</h5>
                                </div>

                                <div class="card-body text-center d-flex flex-column justify-content-center">

                                    <?php if (!empty($designer['profile_pic_url'])): ?>
                                    <img src="../<?= htmlspecialchars($designer['profile_pic_url']) ?>"
                                        style="width:110px;height:110px;border-radius:50%;object-fit:cover;"
                                        class="mx-auto">
                                    <?php else: ?>
                                    <img src="img/default-user.png" style="width:110px;height:110px;border-radius:50%;"
                                        class="mx-auto">
                                    <?php endif; ?>

                                    <h5 class="mt-3 mb-0">
                                        <?= htmlspecialchars($designer['name']) ?>
                                    </h5>

                                    <p class="text-muted mb-1">
                                        <?= htmlspecialchars($designer['email']) ?>
                                    </p>

                                    <p class="mb-0">
                                        <?= htmlspecialchars($designer['phone']) ?>
                                    </p>

                                </div>
                                <div class="row justify-content-center mb-3">
                                    <div class="col-10 d-flex justify-content-center">
                                        <a href="designer.php" class="btn btn-secondary w-100 mt-3">
                                            ← Back to Designers
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
    <script src="js/app.js"></script>
</body>

</html>