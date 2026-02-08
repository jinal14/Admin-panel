<?php
session_start();
include 'config.php';

if (!isset($_GET['id'])) {
    header("Location: designer.php");
    exit;
}

$id = $_GET['id'];

/* Delete designer user (CASCADE will handle Designer table if FK is set) */
$sql = "DELETE FROM User WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);

header("Location: designer.php");
exit;
