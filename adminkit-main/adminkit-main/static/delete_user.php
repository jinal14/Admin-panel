<?php
session_start();
include 'config.php';


$id = $_GET['id'];

/* Prevent deleting admin */
mysqli_query(
    $conn,
    "DELETE FROM User WHERE id='$id' AND role!='admin'"
);

header("Location: user.php?deleted=1");
exit;
