

<?php
session_start();
include '../config.php';

/* Total users (clients) */
$totalUsers = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM User WHERE role='user'")
)['total'];

/* Total designers */
$totalDesigners = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM User WHERE role='designer'")
)['total'];

/* Pending designer requests */
$pendingRequests = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM designer_request WHERE status='pending'")
)['total'];

/* Projects (optional) */
$totalProjects = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM project")
)['total'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>

<?php include __DIR__ . '/layout/sidebar.php'; ?>

<div class="main">
    <h1>Welcome, <?php echo $_SESSION['username']; ?> 👋</h1>

   <div class="dashboard-cards">

    <div class="card">
        <h4>Total Users</h4>
        <span><?= $totalUsers ?></span>
    </div>

    <div class="card">
        <h4>Total Designers</h4>
        <span><?= $totalDesigners ?></span>
    </div>

    <div class="card">
        <h4>Pending Requests</h4>
        <span><?= $pendingRequests ?></span>
    </div>

    <div class="card">
        <h4>Total Projects</h4>
        <span><?= $totalProjects ?></span>
    </div>

</div>


    <div class="intro">
        Interior Design Control Center  
        <br>Manage designers, projects & users from one placeeee.
    </div>
</div>
<script>
const ctx = document.getElementById('dashboardChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Users', 'Designers', 'Pending Requests', 'Projects'],
        datasets: [{
            label: 'System Overview',
            data: [
                <?= $totalUsers ?>,
                <?= $totalDesigners ?>,
                <?= $pendingRequests ?>,
                <?= $totalProjects ?>
            ],
            backgroundColor: [
                '#00ffd5',
                '#1abc9c',
                '#f39c12',
                '#3498db'
            ],
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

</body>
</html>
