<?php
session_start();
include '../config.php';
include 'admin_only.php';

$id = $_GET['id'];

$stmt = mysqli_prepare($conn, "SELECT * FROM User WHERE id=?");
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header("Location: users.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<?php include __DIR__ . '/layout/sidebar.php'; ?>

<div class="main">
    <h1 class="page-title">✏️ Edit User</h1>

    <div class="form-card">
        <form method="POST" action="update-user.php">

            <input type="hidden" name="id" value="<?= $user['id'] ?>">

            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role">
                    <option value="user" <?= $user['role']=='user'?'selected':'' ?>>User</option>
                    <option value="designer" <?= $user['role']=='designer'?'selected':'' ?>>Designer</option>
                </select>
            </div>

            <div class="form-actions">
                <button class="btn btn-update">Update</button>
                <a href="users.php" class="btn btn-cancel">Cancel</a>
            </div>

        </form>
    </div>
</div>

</body>
</html>
