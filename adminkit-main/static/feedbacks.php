<?php
session_start();
include 'config.php';

/* Admin Protection */
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     die('Access denied');
// }

$query = "
    SELECT
        f.id AS feedback_id,
        f.rating,
        f.comment,
        f.created_at,
        f.status,

        cu.name AS client_name,
        du.name AS designer_name,
        p.name AS project_name

    FROM feedback f
    LEFT JOIN user cu ON f.from_user_id = cu.id
    LEFT JOIN user du ON f.to_user_id = du.id
    LEFT JOIN project p ON f.project_id = p.id
    ORDER BY f.created_at DESC
";

$result = mysqli_query($conn, $query);
if (!$result) {
    die('Query failed: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Feedback Management</title>
    <link href="css/app.css" rel="stylesheet">
</head>

<body>
<div class="wrapper">

<?php include 'includes/sidebar.php'; ?>

<div class="main">
<?php include 'includes/navbar.php'; ?>

<main class="content">
<div class="container-fluid p-0">

<h1 class="h3 mb-3"><strong>Client</strong> Feedbacks</h1>

<div class="card">
<div class="card-body p-0">

<table class="table table-hover my-0">
<thead>
<tr>
    <th>#</th>
    <th>Client</th>
    <th>Designer</th>
    <th>Project</th>
    <th>Rating</th>
    <th>Comment</th>
    <th>Status</th>
    <th>Action</th>
    <th>Date</th>
</tr>
</thead>

<tbody>
<?php if (mysqli_num_rows($result) > 0): ?>
<?php $i = 1; while ($row = mysqli_fetch_assoc($result)): ?>
<tr>

<td><?= $i++ ?></td>
<td><?= htmlspecialchars($row['client_name'] ?? '—') ?></td>
<td><?= htmlspecialchars($row['designer_name'] ?? '—') ?></td>
<td><?= htmlspecialchars($row['project_name'] ?? '—') ?></td>

<td>
<span class="badge bg-success">
⭐ <?= (int)$row['rating'] ?>/5
</span>
</td>

<td style="max-width:300px;">
<?= $row['comment']
? htmlspecialchars($row['comment'])
: '<span class="text-muted">No comment</span>' ?>
</td>

<td>
<?php if ($row['status'] === 'approved'): ?>
<span class="badge bg-success">Approved</span>
<?php else: ?>
<span class="badge bg-danger">Blocked</span>
<?php endif; ?>
</td>

<td>
<?php if ($row['status'] === 'approved'): ?>
<a href="feedback_action.php?id=<?= $row['feedback_id'] ?>&action=block"
   class="btn btn-sm btn-danger"
   onclick="return confirm('Block this feedback?')">
   Block
</a>
<?php else: ?>
<a href="feedback_action.php?id=<?= $row['feedback_id'] ?>&action=approve"
   class="btn btn-sm btn-success">
   Approve
</a>
<?php endif; ?>
</td>

<td><?= date('d M Y', strtotime($row['created_at'])) ?></td>

</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
<td colspan="9" class="text-center text-muted">
No feedback found
</td>
</tr>
<?php endif; ?>
</tbody>

</table>
</div>
</div>

</div>
</main>
</div>
</div>

<script src="js/app.js"></script>
</body>
</html>