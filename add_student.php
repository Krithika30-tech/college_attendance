<?php
include 'db.php';

$name = trim($_POST['name']);
$roll = trim($_POST['roll']);
$year = (int)$_POST['year'];
$sem  = (int)$_POST['sem'];

$stmt = mysqli_prepare($conn, "INSERT INTO student3 (name, roll_no, year, semester) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssii", $name, $roll, $year, $sem);

if (mysqli_stmt_execute($stmt)) {
    header("Location: index.php?success=Student+added+successfully");
} else {
    header("Location: index.php?error=Roll+number+already+exists");
}

mysqli_stmt_close($stmt);
exit;
?>