<?php
session_start();
include '../config.php';
include 'admin_only.php';

$result = mysqli_query($conn,
    "SELECT u.name, u.email, d.experience_years, d.rating
     FROM Designer d
     JOIN User u ON d.id = u.id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Designers</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<?php include __DIR__ . '/layout/sidebar.php'; ?>

<div class="main">
    <h2>Designers</h2>

    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Experience</th>
            <th>Rating</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= $row['name'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['experience_years'] ?> yrs</td>
            <td><?= $row['rating'] ?></td>
        </tr>
        <?php } ?>
    </table>
</div>

</body>
</html>
