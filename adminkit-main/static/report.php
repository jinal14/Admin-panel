<?php
include 'config.php';

include 'config.php';

// $client   = $_GET['client']   ?? '';
// $designer = $_GET['designer'] ?? '';
// $date     = $_GET['date']     ?? '';

// $hasSearch = ($client !== '' || $designer !== '' || $date !== '');

// $result = null;

// if ($hasSearch) {

//     $where = [];
//     $params = [];

//     if ($client !== '') {
//         $where[] = "cu.name LIKE ?";
//         $params[] = "%$client%";
//     }

//     if ($designer !== '') {
//         $where[] = "du.name LIKE ?";
//         $params[] = "%$designer%";
//     }

//     if ($date !== '') {
//         $where[] = "DATE(f.created_at) = ?";
//         $params[] = $date;
//     }

//     $sql = "
//         SELECT
//             f.id,
//             cu.name AS client_name,
//             du.name AS designer_name,
//             p.name AS project_name,
//             f.rating,
//             f.status,
//             f.created_at
//         FROM feedback f
//         LEFT JOIN user cu ON f.from_user_id = cu.id
//         LEFT JOIN user du ON f.to_user_id = du.id
//         LEFT JOIN project p ON f.project_id = p.id
//     ";

//     if ($where) {
//         $sql .= " WHERE " . implode(" AND ", $where);
//     }

//     $sql .= " ORDER BY f.created_at DESC";

//     $stmt = $conn->prepare($sql);
//     if ($params) {
//         $stmt->bind_param(str_repeat('s', count($params)), ...$params);
//     }
//     $stmt->execute();
//     $result = $stmt->get_result();
// }\\\
//



$client   = $_GET['client']   ?? '';
$designer = $_GET['designer'] ?? '';
$date     = $_GET['date']     ?? '';

$searchType = null;

if ($client !== '') {
    $searchType = 'client';
} elseif ($designer !== '') {
    $searchType = 'designer';
} elseif ($date !== '') {
    $searchType = 'date';
}

if ($searchType === 'client') {

$sql = "
SELECT
    u.name AS client_name,
    u.phone,
    u.profile_pic_url,
    u.dob,

    du.name AS designer_name,

    p.status,
    p.total_estimated_cost,
    p.created_at AS project_date

FROM user u
LEFT JOIN project p ON p.user_id = u.id
LEFT JOIN user du ON p.designer_id = du.id
WHERE u.role='user' AND u.name LIKE ?
ORDER BY p.created_at DESC
";

$stmt = $conn->prepare($sql);
$like = "%$client%";
$stmt->bind_param("s", $like);
$stmt->execute();
$result = $stmt->get_result();
}

if ($searchType === 'designer') {

$sql = "
SELECT
    du.name AS designer_name,
    du.phone,
    du.profile_pic_url,
    du.dob,

    d.experience_years,
    d.rating,

    q.qualification_name,

    cu.name AS client_name,
    p.status,
    p.created_at

FROM user du
LEFT JOIN designer d ON du.id = d.user_id
LEFT JOIN designer_qualification q ON d.id = q.designer_id
LEFT JOIN project p ON p.designer_id = du.id
LEFT JOIN user cu ON p.user_id = cu.id
WHERE du.role='designer' AND du.name LIKE ?
ORDER BY p.created_at DESC
";

$stmt = $conn->prepare($sql);
$like = "%$designer%";
$stmt->bind_param("s", $like);
$stmt->execute();
$result = $stmt->get_result();
}


if ($searchType === 'date') {

$sql = "
SELECT
    cu.name AS client_name,
    du.name AS designer_name,
    p.name AS project_name,
    p.status,
    p.total_estimated_cost,
    p.created_at
FROM project p
LEFT JOIN user cu ON p.user_id = cu.id
LEFT JOIN user du ON p.designer_id = du.id
WHERE DATE(p.created_at) = ?
ORDER BY p.created_at DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Reports</title>
    <link href="css/app.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <?php include 'includes/sidebar.php'; ?>
        <div class="main">
            <?php include 'includes/navbar.php'; ?>

            <main class="content">
                <div class="container-fluid p-0">

                    <h1 class="h3 mb-3"><strong>Reports</strong></h1>

                    <!-- SEARCH CARD -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <form method="GET" class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" name="client" class="form-control"
                                        placeholder="Search Client Name">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="designer" class="form-control"
                                        placeholder="Search Designer Name">
                                </div>
                                <div class="col-md-3">
                                    <input type="date" name="date" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-primary w-100">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- RESULT TABLE -->
                    <div class="card">
                        <div class="card-body p-0">
                            <?php if ($searchType): ?>
                            <div class="d-flex justify-content-end mb-2 mt-2 me-2">
                                <form method="GET" action="report_pdf.php" target="_blank">
                                    <input type="hidden" name="client" value="<?= htmlspecialchars($client) ?>">
                                    <input type="hidden" name="designer" value="<?= htmlspecialchars($designer) ?>">
                                    <input type="hidden" name="date" value="<?= htmlspecialchars($date) ?>">

                                    <button class="btn btn-danger">
                                        📄 Download PDF
                                    </button>
                                </form>
                            </div>
                            <?php endif; ?>
                            <table class="table table-hover my-0">
                                <thead>
                                    <tr>
                                        <?php if ($searchType === 'client'): ?>
                                        <th>#</th>
                                        <th>Client</th>
                                        <th>Phone</th>
                                        <th>DOB</th>
                                        <th>Designer</th>
                                        <th>Estimated Cost</th>
                                        <th>Status</th>
                                        <th>Project Date</th>

                                        <?php elseif ($searchType === 'designer'): ?>
                                        <th>#</th>
                                        <th>Designer</th>
                                        <th>Phone</th>
                                        <th>Experience</th>
                                        <th>Rating</th>
                                        <th>Qualification</th>
                                        <th>Client</th>
                                        <th>Status</th>
                                        <th>Date</th>

                                        <?php elseif ($searchType === 'date'): ?>
                                        <th>#</th>
                                        <th>Client</th>
                                        <th>Designer</th>
                                        <th>Project</th>
                                        <th>Status</th>
                                        <th>Cost</th>
                                        <th>Date</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php if (!$searchType): ?>
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">
                                            Please search using filters above
                                        </td>
                                    </tr>

                                    <?php elseif ($result->num_rows == 0): ?>
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">
                                            No data found
                                        </td>
                                    </tr>

                                    <?php else: ?>
                                    <?php $i=1; while ($row = $result->fetch_assoc()): ?>
                                    <tr>

                                        <?php if ($searchType === 'client'): ?>
                                        <td><?= $i++ ?></td>
                                        <td><?= $row['client_name'] ?></td>
                                        <td><?= $row['phone'] ?></td>
                                        <td><?= date('d M Y', strtotime($row['dob'])) ?></td>
                                        <td><?= $row['designer_name'] ?? '—' ?></td>
                                        <td>₹<?= number_format($row['total_estimated_cost'],2) ?></td>
                                        <td><?= ucfirst($row['status']) ?></td>
                                        <td><?= date('d M Y', strtotime($row['project_date'])) ?></td>

                                        <?php elseif ($searchType === 'designer'): ?>
                                        <td><?= $i++ ?></td>
                                        <td><?= $row['designer_name'] ?></td>
                                        <td><?= $row['phone'] ?></td>
                                        <td><?= $row['experience_years'] ?> yrs</td>
                                        <td>⭐ <?= $row['rating'] ?></td>
                                        <td><?= $row['qualification_name'] ?></td>
                                        <td><?= $row['client_name'] ?? '—' ?></td>
                                        <td><?= ucfirst($row['status']) ?></td>
                                        <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>

                                        <?php elseif ($searchType === 'date'): ?>
                                        <td><?= $i++ ?></td>
                                        <td><?= $row['client_name'] ?></td>
                                        <td><?= $row['designer_name'] ?></td>
                                        <td><?= $row['project_name'] ?></td>
                                        <td><?= ucfirst($row['status']) ?></td>
                                        <td>₹<?= number_format($row['total_estimated_cost'],2) ?></td>
                                        <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>

                                        <?php endif; ?>

                                    </tr>
                                    <?php endwhile; ?>
                                    <?php endif; ?>
                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <script src="js/app.js"></script>
</body>

</html>