<?php
session_start();
include '../config.php';
include 'admin_only.php';

/* Fetch all users except admin (optional) */
$result = mysqli_query(
    $conn,
    "SELECT id, name, email, username, role 
     FROM User 
     ORDER BY created_at DESC"
);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Users</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<?php include __DIR__ . '/layout/sidebar.php'; ?>

<div class="main">
    <h1 class="page-title">All Users</h1>

    <div class="table-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th style="width:160px;">Actions</th>
                </tr>
            </thead>

            <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td>
                        <span class="role-badge role-<?= $row['role'] ?>">
                            <?= ucfirst($row['role']) ?>
                        </span>
                    </td>
                    <td class="actions">
                        <a href="edit-user.php?id=<?= $row['id'] ?>" class="btn btn-edit">Edit</a>

                        <?php if ($row['role'] !== 'admin') { ?>
                            <a href="delete-user.php?id=<?= $row['id'] ?>"
                               class="btn btn-delete"
                               onclick="return confirm('Are you sure you want to delete this user?')">
                               Delete
                            </a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
