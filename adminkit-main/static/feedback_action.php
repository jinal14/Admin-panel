<?php
session_start();
include 'config.php';

/* Admin Protection */
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//     die('Access denied');
// }

if (!isset($_GET['id'], $_GET['action'])) {
    die('Invalid request');
}

$id = (int)$_GET['id'];
$action = $_GET['action'];

if ($action === 'block') {
    $status = 'blocked';
} elseif ($action === 'approve') {
    $status = 'approved';
} else {
    die('Invalid action');
}

$stmt = $conn->prepare("UPDATE feedback SET status=? WHERE id=?");
$stmt->bind_param("si", $status, $id);
$stmt->execute();

header("Location: feedbacks.php");
exit;