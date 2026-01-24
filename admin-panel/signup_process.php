<?php
include 'config.php';

$id = uniqid('u'); // auto user id
$name = $_POST['name'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$phone = $_POST['phone'];
$dob = $_POST['dob'];
$role = 'user'; // default role
$profile_pic_url = NULL;

// check if email already exists
$check = mysqli_query($conn, "SELECT id FROM User WHERE email='$email'");
if (mysqli_num_rows($check) > 0) {
    echo "Email already registered!";
    exit;
}

$sql = "INSERT INTO User 
(id, name, email, username, password_hash, phone, profile_pic_url, dob, role)
VALUES
('$id', '$name', '$email', '$username', '$password', '$phone', '$profile_pic_url', '$dob', '$role')";

if (mysqli_query($conn, $sql)) {
    header("Location: login.php");
} else {
    echo "Error: " . mysqli_error($conn);
}
?>
