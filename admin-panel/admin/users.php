<?php
session_start();
include '../config.php';
include 'admin_only.php';

$result = mysqli_query($conn, "SELECT * FROM User");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Users</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<?php include 'layout/sidebar.php'; ?>

<div class="main">
    <h2>All Users</h2>

    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Username</th>
            <th>Role</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['name'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['username'] ?></td>
            <td><?= $row['role'] ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
