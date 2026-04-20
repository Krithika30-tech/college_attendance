<?php
include 'db.php';

$date = date("Y-m-d");

foreach($_POST['attendance3'] as $student_id => $status) {
    $student_id = (int)$student_id;
    $allowed = ['Present', 'Absent', 'OD', 'Leave'];
    if (!in_array($status, $allowed)) continue;
    
    mysqli_query($conn, "INSERT INTO attendance3 (student_id, date, status)
                         VALUES ('$student_id', '$date', '$status')
                         ON DUPLICATE KEY UPDATE status='$status'");
}

header("Location: index.php?success=Attendance+saved");
exit;
?>