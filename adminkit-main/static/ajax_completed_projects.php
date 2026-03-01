<?php
include 'config.php';

$query = mysqli_query($conn, "
    SELECT name, description, created_at, status
    FROM project
    WHERE status = 'completed'
    ORDER BY created_at DESC
");

echo "<h4 class='mb-3'>Completed Projects</h4>";

if (mysqli_num_rows($query) === 0) {
    echo "<p class='text-muted'>No completed projects found.</p>";
    exit;
}

echo "<ul class='list-group list-group-flush'>";

while ($row = mysqli_fetch_assoc($query)) {
    echo "
    <li class='list-group-item'>
        <strong>{$row['name']}</strong><br>
        <small class='text-muted'>Completed on " . date('d M Y', strtotime($row['created_at'])) . "</small><br>
        <small>{$row['description']}</small>
        <span class='badge bg-success float-end'>Completed</span>
    </li>";
}

echo "</ul>";   