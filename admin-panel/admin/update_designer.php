<?php
session_start();
include '../config.php';
include 'admin_only.php';

$id        = $_POST['id'];
$name      = $_POST['name'];
$email     = $_POST['email'];
$username  = $_POST['username'];
$phone     = $_POST['phone'];
$bio       = $_POST['bio'];
$experience= $_POST['experience_years'] ?: null;
$portfolio = json_encode(array_filter(
    array_map('trim', explode(',', $_POST['portfolio_urls']))
));

/* ===============================
   EMAIL UNIQUE CHECK
================================ */
$check = mysqli_prepare(
    $conn,
    "SELECT id FROM User WHERE email=? AND id!=?"
);
mysqli_stmt_bind_param($check, "ss", $email, $id);
mysqli_stmt_execute($check);
$res = mysqli_stmt_get_result($check);

if (mysqli_num_rows($res) > 0) {
    header("Location: edit_designer.php?id=$id&error=email_exists");
    exit;
}

/* ===============================
   UPDATE USER TABLE
================================ */
$u = mysqli_prepare(
    $conn,
    "UPDATE User
     SET name=?, email=?, username=?, phone=?
     WHERE id=? AND role='designer'"
);
mysqli_stmt_bind_param($u, "sssss",
    $name, $email, $username, $phone, $id
);
mysqli_stmt_execute($u);

/* ===============================
   CHECK DESIGNER ROW
================================ */
$exists = mysqli_prepare(
    $conn,
    "SELECT id FROM Designer WHERE id=?"
);
mysqli_stmt_bind_param($exists, "s", $id);
mysqli_stmt_execute($exists);
$designerExists = mysqli_stmt_get_result($exists);

/* ===============================
   INSERT OR UPDATE DESIGNER
================================ */
if (mysqli_num_rows($designerExists) > 0) {

    // UPDATE
    $d = mysqli_prepare(
        $conn,
        "UPDATE Designer
         SET bio=?, experience_years=?, portfolio_urls=?
         WHERE id=?"
    );
    mysqli_stmt_bind_param($d, "siss",
        $bio, $experience, $portfolio, $id
    );
    mysqli_stmt_execute($d);

} else {

    // INSERT
    $d = mysqli_prepare(
        $conn,
        "INSERT INTO Designer (id, bio, experience_years, portfolio_urls)
         VALUES (?, ?, ?, ?)"
    );
    mysqli_stmt_bind_param($d, "ssis",
        $id, $bio, $experience, $portfolio
    );
    mysqli_stmt_execute($d);
}

header("Location: designers.php?success=updated");
exit;
