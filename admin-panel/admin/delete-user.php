<?php
session_start();
include '../config.php';
include 'admin_only.php';

$id = $_GET['id'];

/* Prevent deleting admin */
mysqli_query(
    $conn,
    "DELETE FROM User WHERE id='$id' AND role!='admin'"
);

header("Location: users.php?deleted=1");
exit;
