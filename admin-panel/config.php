<?php
$conn = mysqli_connect("localhost", "root", "", "vibe_up");

if (!$conn) {
    die("❌ Database Connection Failed: " . mysqli_connect_error());
} else {
    // Optional (use only while testing)
     echo "✅ Database Connected Successfully";
}
?>
