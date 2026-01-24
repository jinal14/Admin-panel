<?php
session_start();
include 'config.php';

$username = $_POST['username'];
$password = $_POST['password'];

/* Fetch user by username */
$sql = "SELECT * FROM User WHERE username='$username'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {

    $user = mysqli_fetch_assoc($result);

    /* Verify password */
    if (password_verify($password, $user['password_hash'])) {

        // Store session data
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        /* Role-based access */
        if ($user['role'] === 'admin') {
            header("Location: admin/dashboard.php");
        } 
        elseif ($user['role'] === 'designer') {
            header("Location: designer/dashboard.php");
        } 
        else {
            header("Location: user/dashboard.php");
        }

    } else {
        echo "❌ Invalid password";
    }

} else {
    echo "❌ User not found";
}
?>
