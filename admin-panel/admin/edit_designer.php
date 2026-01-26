<?php
session_start();
include '../config.php';
include 'admin_only.php';

$id = $_GET['id'];

$result = mysqli_query($conn,
    "SELECT * FROM User WHERE id='$id' AND role='designer'");

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
    <h2>Edit Designer</h2>

    <form method="POST" action="update_designer.php">
        <input type="hidden" name="id" value="<?= $designer['id'] ?>">

        <label>Name</label>
        <input type="text" name="name" value="<?= $designer['name'] ?>" required>

        <label>Email</label>
        <input type="email" name="email" value="<?= $designer['email'] ?>" required>

        <label>Username</label>
        <input type="text" name="username" value="<?= $designer['username'] ?>" required>

        <label>Phone</label>
        <input type="text" name="phone" value="<?= $designer['phone'] ?>">

        <button type="submit" class="btn btn-edit">Update</button>
    </form>
</div>

</body>
</html>
