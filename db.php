<?php
$host = 'sql111.byetcluster.com';
$user = 'if0_41702215';
$pass = 'qDLhJxjd8VRG6t';
$db   = 'if0_41702215_attendance';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');
?>