<?php
include 'admin_only.php';
include '../config.php';

/* Dashboard stats */
$userCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM User"))['total'];
$designerCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM User WHERE role='designer'"))['total'];
$projectCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM Project"))['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<div class="sidebar">
    <h2>Interior Admin</h2>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="users.php">👤 Users</a>
    <a href="designers.php">🎨 Designers</a>
    <a href="projects.php">🏗 Projects</a>
    <a href="../logout.php">🚪 Logout</a>
</div>

<div class="main">
    <div class="header">
        Welcome, <?php echo $_SESSION['username'] ?? 'Admin'; ?> 👋
    </div>

    <div class="cards">
        <div class="card">
            <h3>Total Users</h3>
            <p><?php echo $userCount; ?></p>
        </div>

        <div class="card">
            <h3>Total Designers</h3>
            <p><?php echo $designerCount; ?></p>
        </div>

        <div class="card">
            <h3>Total Projects</h3>
            <p><?php echo $projectCount; ?></p>
        </div>
    </div>

    <div class="decor">
        <h3>✨ Interior Design Control Center</h3>
        <p>
            Manage designers, monitor projects, and control the entire
            home‑decor ecosystem from one place.
        </p>
    </div>
</div>

</body>
</html>
