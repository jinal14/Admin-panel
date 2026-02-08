<?php
session_start();
include 'config.php';

// Optional admin protection
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     die('Access denied');
// }

if (!isset($_GET['id'], $_GET['status'])) {
    die('Invalid request');
}

$id = $_GET['id'];
$status = $_GET['status'];

/* Allowed statuses from DB enum */
$allowedStatuses = ['accepted', 'declined', 'live', 'solved'];

if (!in_array($status, $allowedStatuses)) {
    die('Invalid status');
}

/* Update request status */
$stmt = mysqli_prepare(
    $conn,
    "UPDATE designer_request SET status = ? WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "ss", $status, $id);
mysqli_stmt_execute($stmt);

header("Location: designer_requests.php");
exit;
