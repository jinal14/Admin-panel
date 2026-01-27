<?php
session_start();
include '../config.php';
include 'admin_only.php';

$result = mysqli_query(
    $conn,
    "SELECT 
        p.id,
        p.name,
        p.description,
        p.status,
        p.currency,
        p.total_estimated_cost,
        p.created_at,
        u.name AS user_name
     FROM project p
     LEFT JOIN User u ON p.user_id = u.id
     ORDER BY p.created_at DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Projects</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<?php include __DIR__ . '/layout/sidebar.php'; ?>

<div class="main">
    <h1 class="page-title">Projects</h1>

    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Project</th>
                    <th>User</th>
                    <th>Status</th>
                    <th>Cost</th>
                    <th>Created</th>
                    <!-- <th style="width:160px;">Actions</th> -->
                </tr>
            </thead>

            <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>

                    <td>
                        <strong><?= htmlspecialchars($row['name']) ?></strong><br>
                        <small class="muted">
                            <?= htmlspecialchars(substr($row['description'], 0, 60)) ?>…
                        </small>
                    </td>

                    <td><?= htmlspecialchars($row['user_name'] ?? '—') ?></td>

                    <td>
                        <span class="status-badge status-<?= $row['status'] ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>

                    <td>
                        <?= $row['currency'] ?>
                        <?= number_format($row['total_estimated_cost'], 2) ?>
                    </td>

                    <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>

               
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
