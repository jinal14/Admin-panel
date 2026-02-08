<?php
session_start();
include 'config.php';

/* Admin protection (optional but recommended) */
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     die('Access denied');
// }

/* Fetch designer requests */
$query = "
    SELECT 
        dr.id AS request_id,
        dr.status,
        dr.created_at,

        u.name AS client_name,
        du.name AS designer_name,
        p.name AS project_name

    FROM designer_request dr

    JOIN user u ON dr.user_id = u.id
    JOIN designer d ON dr.designer_id = d.id
    JOIN user du ON d.user_id = du.id
    JOIN project p ON dr.project_id = p.id

    ORDER BY dr.created_at DESC
";


$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Designer Requests</title>
    <link rel="stylesheet" href="assets/css/admin.css"> <!-- if you have -->
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
        }
        .pending { background: #ffc107; color: #000; }
        .approved { background: #28a745; color: #fff; }
        .rejected { background: #dc3545; color: #fff; }
        .btn {
            padding: 6px 10px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 13px;
        }
        .btn-approve { background: #28a745; color: #fff; }
        .btn-reject { background: #dc3545; color: #fff; }
    </style>
</head>

<body>

<h2>Designer Requests</h2>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Client</th>
            <th>Designer</th>
            <th>Project</th>
            <th>Status</th>
            <th>Requested On</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php $i = 1; while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($row['client_name']) ?></td>
                <td><?= htmlspecialchars($row['designer_name']) ?></td>
                <td><?= htmlspecialchars($row['project_name']) ?></td>
                <td>
                    <span class="badge <?= $row['status'] ?>">
                        <?= ucfirst($row['status']) ?>
                    </span>
                </td>
                <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                <td>
                    <?php if ($row['status'] === 'pending'): ?>
                        <a href="update_request_status.php?id=<?= $row['request_id'] ?>&status=approved"
                           class="btn btn-approve"
                           onclick="return confirm('Approve this request?')">
                           Approve
                        </a>
                        <a href="update_request_status.php?id=<?= $row['request_id'] ?>&status=rejected"
                           class="btn btn-reject"
                           onclick="return confirm('Reject this request?')">
                           Reject
                        </a>
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="7">No designer requests found</td>
        </tr>
    <?php endif; ?>

    </tbody>
</table>

</body>
</html>
