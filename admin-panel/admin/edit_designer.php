<?php
session_start();
include '../config.php';
include 'admin_only.php';

$id = $_GET['id'] ?? '';

$stmt = mysqli_prepare(
    $conn,
    "SELECT id, name, email, username, phone 
     FROM User 
     WHERE id = ? AND role = 'designer'"
);
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$designer = mysqli_fetch_assoc($result);

if (!$designer) {
    header("Location: designers.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Designer</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>

<?php include __DIR__ . '/layout/sidebar.php'; ?>

<div class="main">
    <h1 class="page-title">🎨 Edit Designer</h1>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'email_exists'): ?>
        <div class="alert error">
            ❌ Email already exists. Please use another email.
        </div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" action="update_designer.php" class="designer-form">

            <input type="hidden" name="id" value="<?= htmlspecialchars($designer['id']) ?>">

            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name"
                       value="<?= htmlspecialchars($designer['name']) ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email"
                       value="<?= htmlspecialchars($designer['email']) ?>" required>
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username"
                       value="<?= htmlspecialchars($designer['username']) ?>" required>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone"
                       value="<?= htmlspecialchars($designer['phone']) ?>" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-update">Update Designer</button>
                <a href="designers.php" class="btn btn-cancel">Cancel</a>
            </div>

        </form>
    </div>
</div>

</body>
</html>
