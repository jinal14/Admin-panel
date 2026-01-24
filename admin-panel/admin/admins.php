<?php
include 'admin_only.php';
include '../config.php';

$query = "SELECT id, name, email, username, created_at 
          FROM User 
          WHERE role = 'admin'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admins</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main">
    <h2>🛡 Admin List</h2>

    <table class="data-table">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Username</th>
            <th>Created At</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['username'] ?></td>
            <td><?= $row['created_at'] ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
