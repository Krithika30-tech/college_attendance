<?php
include 'db.php';

$date = date("Y-m-d");

if (!isset($_POST['attendance3']) || !is_array($_POST['attendances3'])) {
    header("Location: index.php?error=No+attendance+data");
    exit;
}

$stmt = mysqli_prepare($conn,
    "INSERT INTO attendances3 (student_id, date, status)
     VALUES (?, ?, ?)
     ON DUPLICATE KEY UPDATE status = VALUES(status)"
);

foreach ($_POST['attendance3'] as $student_id => $status) {
    $student_id = (int)$student_id;
    $allowed = ['Present', 'Absent', 'OD', 'Leave'];
    if (!in_array($status, $allowed)) continue;

    mysqli_stmt_bind_param($stmt, "iss", $student_id, $date, $status);
    mysqli_stmt_execute($stmt);
}

mysqli_stmt_close($stmt);
header("Location: index.php?success=Attendance+saved+for+$date");
exit;
?>