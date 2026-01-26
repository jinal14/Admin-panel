<?php
session_start();
include '../config.php';
include 'admin_only.php';

/* Fetch designers */
$result = mysqli_query($conn, "SELECT id, name, email, username, phone, profile_pic_url, created_at 
                               FROM User 
                               WHERE role = 'designer'");
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
        <h1>🎨 Designers</h1>

        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Profile</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Joined On</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $i = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td class="profile-col">

                            <?php if (!empty($row['profile_pic_url'])): ?>
                                <img
                                    src="../<?php echo $row['profile_pic_url']; ?>"
                                    class="profile-avatar"
                                    alt="Profile">
                            <?php else: ?>
                                <span class="no-img">No Image</span>
                            <?php endif; ?>
                        </td>


                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                        <td class="actions">
                            <a href="edit_designer.php?id=<?= $row['id'] ?>" class="btn btn-edit">Edit</a>

                            <a href="delete_designer.php?id=<?= $row['id'] ?>"
                                class="btn btn-delete"
                                onclick="return confirm('Are you sure you want to delete this designer?');">
                                Delete
                            </a>
                        </td>

                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

</body>

</html>