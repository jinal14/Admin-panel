<?php
session_start();
include 'config.php';

/* ===============================
   VALIDATE REQUEST
================================ */
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Project ID missing');
}

$id = $_GET['id'];

/* ===============================
   CHECK PROJECT EXISTS
================================ */
$check = mysqli_prepare(
    $conn,
    "SELECT id FROM project WHERE id = ?"
);
mysqli_stmt_bind_param($check, "s", $id);
mysqli_stmt_execute($check);
$result = mysqli_stmt_get_result($check);

if (mysqli_num_rows($result) === 0) {
    die('Project not found');
}

/* ===============================
   DELETE CHILD RECORDS (IF ANY)
================================ */
/*
   Uncomment these if tables exist with FK constraints

// Delete rooms
$stmt = mysqli_prepare($conn, "DELETE FROM room WHERE project_id = ?");
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);

// Delete designer requests
$stmt = mysqli_prepare($conn, "DELETE FROM designer_request WHERE project_id = ?");
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);
*/

/* ===============================
   DELETE PROJECT
================================ */
$stmt = mysqli_prepare(
    $conn,
    "DELETE FROM project WHERE id = ?"
);
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);

/* ===============================
   REDIRECT
================================ */
header("Location: project.php?success=deleted");
exit;
