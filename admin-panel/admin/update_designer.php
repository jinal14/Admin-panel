<?php
session_start();
include '../config.php';
include 'admin_only.php';

$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];
$username = $_POST['username'];
$phone = $_POST['phone'];

$bio = $_POST['bio'];
$experience = $_POST['experience_years'];
$portfolio = json_encode(array_map('trim', explode(',', $_POST['portfolio_urls'])));

/* Email uniqueness check */
$check = mysqli_prepare(
    $conn,
    "SELECT id FROM User WHERE email=? AND id!=?"
);
mysqli_stmt_bind_param($check, "ss", $email, $id);
mysqli_stmt_execute($check);
$res = mysqli_stmt_get_result($check);

if(mysqli_num_rows($res) > 0){
    header("Location: edit_designer.php?id=$id&error=email_exists");
    exit;
}

/* Update User */
$u = mysqli_prepare(
    $conn,
    "UPDATE User SET name=?, email=?, username=?, phone=? WHERE id=?"
);
mysqli_stmt_bind_param($u, "sssss", $name, $email, $username, $phone, $id);
mysqli_stmt_execute($u);

/* Update Designer */
$d = mysqli_prepare(
    $conn,
    "UPDATE Designer 
     SET bio=?, experience_years=?, portfolio_urls=?
     WHERE id=?"
);
mysqli_stmt_bind_param($d, "siss", $bio, $experience, $portfolio, $id);
mysqli_stmt_execute($d);

header("Location: designers.php?success=updated");
exit;
