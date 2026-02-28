<?php
session_start();
include 'config.php';

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
WHERE u.role = 'designer'
";

$result = mysqli_query($conn, $sql);
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
                                    <h5 class="card-title mb-0">Designers List</h5>
                                </div>

                                <div class="card-body p-0">
                                    <table class="table table-hover my-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>More</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        <tbody>
                                            <?php $i = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td><?= htmlspecialchars($row['name']) ?></td>
                                                <td><?= htmlspecialchars($row['email']) ?></td>
                                                <td><?= htmlspecialchars($row['phone']) ?></td>
                                                <td>
                                                    <a href="designer_details.php?id=<?= $row['id'] ?>"
                                                        class="btn btn-sm btn-info">
                                                        More
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>

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