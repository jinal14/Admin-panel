

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

$ratings = [0, 0, 0, 0, 0];

$ratingQuery = mysqli_query(
    $conn,
    "SELECT rating, COUNT(*) as total 
     FROM feedback 
     GROUP BY rating"
);

while ($row = mysqli_fetch_assoc($ratingQuery)) {
    $ratings[$row['rating'] - 1] = $row['total'];
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<?php include __DIR__ . '/layout/sidebar.php'; ?>

<div class="main">
    <h1>Welcome, <?php echo $_SESSION['username']; ?> 👋</h1>

  <div class="dashboard-cards">
    <div class="card">
        <h4>Total Clients</h4>
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

<div class="dashboard-charts">

    <!-- BAR CHART CARD -->
    <div class="chart-card">
        <h3>📊 System Overview</h3>
        <canvas id="overviewChart"></canvas>
    </div>

    <!-- PIE CHART CARD -->
    <div class="chart-card">
        <h3>⭐ Client Feedback Ratings</h3>
        <canvas id="ratingChart"></canvas>
    </div>

</div>




    <!-- <div class="intro">
        Interior Design Control Center  
        <br>Manage designers, projects & users from one placeeee.
    </div> -->
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const overviewCtx = document.getElementById('overviewChart');

new Chart(overviewCtx, {
    type: 'bar',
    data: {
        labels: ['Users', 'Designers', 'Pending', 'Projects'],
        datasets: [{
            data: [
                <?= $totalUsers ?>,
                <?= $totalDesigners ?>,
                <?= $pendingRequests ?>,
                <?= $totalProjects ?>
            ],
            backgroundColor: ['#00ffd5','#1abc9c','#f39c12','#3498db'],
            borderRadius: 6,
            barThickness: 35
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
<script>
const ratingCtx = document.getElementById('ratingChart');

new Chart(ratingCtx, {
    type: 'pie',
    data: {
        labels: ['1 ⭐','2 ⭐','3 ⭐','4 ⭐','5 ⭐'],
        datasets: [{
            data: <?= json_encode($ratings) ?>,
            backgroundColor: [
                '#e74c3c',
                '#e67e22',
                '#f1c40f',
                '#2ecc71',
                '#00ffd5'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});
</script>


</body>
</html>
