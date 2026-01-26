<?php
session_start();
include '../config.php';
include 'admin_only.php';

$id       = $_POST['id'];
$name     = $_POST['name'];
$email    = $_POST['email'];
$username = $_POST['username'];
$phone    = $_POST['phone'];

/* 🔎 Check if email belongs to ANOTHER user */
$check = mysqli_prepare(
    $conn,
    "SELECT id FROM User WHERE email = ? AND id != ?"
);
mysqli_stmt_bind_param($check, "ss", $email, $id);
mysqli_stmt_execute($check);
$result = mysqli_stmt_get_result($check);

if (mysqli_num_rows($result) > 0) {
    header("Location: edit_designer.php?id=$id&error=email_exists");
    exit;
}

/* ✅ Safe Update */
$update = mysqli_prepare(
    $conn,
    "UPDATE User
     SET name = ?, email = ?, username = ?, phone = ?
     WHERE id = ? AND role = 'designer'"
);

mysqli_stmt_bind_param(
    $update,
    "sssss",
    $name,
    $email,
    $username,
    $phone,
    $id
);

mysqli_stmt_execute($update);

header("Location: designers.php?success=updated");
exit;
