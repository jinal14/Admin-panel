<?php
session_start();
include 'config.php';
include 'auth_admin.php';

/* ==========================
   New Projects (Last 7 Days)
========================== */

$query = mysqli_query($conn, "
    SELECT 
        id,
        name,
        description,
        status,
        created_at
    FROM project
    WHERE created_at >= NOW() - INTERVAL 7 DAY
    ORDER BY created_at DESC
");

if (!$query || mysqli_num_rows($query) === 0) {
    echo "<p class='text-muted'>No new projects in the last 7 days.</p>";
    exit;
}

echo "<ul class='list-group list-group-flush'>";

while ($row = mysqli_fetch_assoc($query)) {

    // Status color logic
    $statusClass = 'bg-secondary';

    if ($row['status'] === 'in_progress') {
        $statusClass = 'bg-warning text-dark';   // Orange
    } elseif ($row['status'] === 'draft') {
        $statusClass = 'bg-secondary';                // Custom brown
    } elseif ($row['status'] === 'completed') {
        $statusClass = 'bg-success';              // Green
    }

    echo "
    <li class='list-group-item'>
        <div class='d-flex justify-content-between align-items-start'>
            <div>
                <strong>" . htmlspecialchars($row['name']) . "</strong><br>
                <small class='text-muted'>
                    Created on " . date('d M Y', strtotime($row['created_at'])) . "
                </small><br>
                <small>" . htmlspecialchars($row['description']) . "</small>
            </div>
            <span class='badge {$statusClass} text-capitalize'>
                " . str_replace('_', ' ', $row['status']) . "
            </span>
        </div>
    </li>";
}

echo "</ul>";