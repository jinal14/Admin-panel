<?php
session_start();
include '../config.php';
include 'admin_only.php';

$sql = "
SELECT 
    u.id, u.name, u.email, u.username, u.phone, u.profile_pic_url, u.created_at,
    d.experience_years, d.rating
FROM User u
LEFT JOIN Designer d ON u.id = d.id
WHERE u.role='designer'
";

$result = mysqli_query($conn, $sql);
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
                    <th>Experience</th>
                    <th>Rating</th>
                    <th>Joined</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1;
                while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $i++ ?></td>

                        <td>
                            <?php if ($row['profile_pic_url']): ?>
                                <img src="../<?= $row['profile_pic_url'] ?>" class="profile-avatar">
                            <?php else: ?>
                                <span class="no-img">No Image</span>
                            <?php endif; ?>
                        </td>

                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= $row['experience_years'] ?? 0 ?> yrs</td>
                        <td><?= $row['rating'] ?? '0.0' ?></td>
                        <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>

                        <td class="actions">
                            <a href="edit_designer.php?id=<?= $row['id'] ?>" class="btn btn-edit">Edit</a>
                            <a href="delete_designer.php?id=<?= $row['id'] ?>"
                                onclick="return confirm('Delete this designer?')"
                                class="btn btn-delete">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </div>

</body>

</html>