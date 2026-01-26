<?php
session_start();
include 'config.php';

$name     = $_POST['name'];
$email    = $_POST['email'];
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$phone    = $_POST['phone'];
$dob      = $_POST['dob'];
$role     = 'user';

/* ===== Profile Pic Upload ===== */
$profilePicPath = NULL;

if (!empty($_FILES['profile_pic']['name'])) {

    $uploadDir = "uploads/profiles/";
    $fileExt   = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
    $fileName  = uniqid($username . "_") . "." . $fileExt;

    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetPath)) {
        $profilePicPath = $targetPath;
    }
}

/* ===== Insert User ===== */
$sql = "INSERT INTO User 
(name, email, username, password_hash, phone, dob, profile_pic_url, role)
VALUES 
('$name','$email','$username','$password','$phone','$dob','$profilePicPath','$role')";

if (mysqli_query($conn, $sql)) {
    header("Location: login.php");
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
