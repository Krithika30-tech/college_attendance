<?php
include 'db.php';

$name = mysqli_real_escape_string($conn, $_POST['name']);
$roll = mysqli_real_escape_string($conn, $_POST['roll']);
$year = (int)$_POST['year'];
$sem  = (int)$_POST['sem'];

$result = mysqli_query($conn, "INSERT INTO students3 (name, roll_no, year, semester) 
                               VALUES ('$name', '$roll', '$year', '$sem')");

if ($result) {
    header("Location: index.php?success=Student+added+successfully");
} else {
    header("Location: index.php?error=" . mysqli_error($conn));
}
exit;
?>