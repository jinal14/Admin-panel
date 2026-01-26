<?php
session_start();
include '../config.php';
include 'admin_only.php';

$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$username = $_POST['username'];
$role = $_POST['role'];

/* Email uniqueness check */
$check = mysqli_prepare(
    $conn,
    "SELECT id FROM User WHERE email=? AND id!=?"
);
mysqli_stmt_bind_param($check, "ss", $email, $id);
mysqli_stmt_execute($check);
$res = mysqli_stmt_get_result($check);

if (mysqli_num_rows($res) > 0) {
    header("Location: edit-user.php?id=$id&error=email_exists");
    exit;
}

$stmt = mysqli_prepare(
    $conn,
    "UPDATE User SET name=?, email=?, username=?, role=? WHERE id=?"
);
mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $username, $role, $id);
mysqli_stmt_execute($stmt);

header("Location: users.php?success=updated");
exit;
