<?php
session_start();
include '../config.php';
include 'admin_only.php';

$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$username = $_POST['username'];
$phone = $_POST['phone'];

$sql = "UPDATE User 
        SET name=?, email=?, username=?, phone=?
        WHERE id=? AND role='designer'";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sssss",
    $name, $email, $username, $phone, $id);

mysqli_stmt_execute($stmt);

header("Location: designers.php");
exit;
