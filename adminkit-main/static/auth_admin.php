<?php
// session_start();

/* If admin not logged in */
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_role'])) {
    header("Location: login.php");
    exit;
}

/* If role is NOT admin */
if ($_SESSION['admin_role'] !== 'admin') {
    session_destroy();
    header("Location: login.php");
    exit;
}
