<?php
include '../auth_check.php';

if ($_SESSION['role'] !== 'admin') {
    echo "Access Denied ❌";
    exit();
}
?>
