<?php
include 'config.php';
session_start();

$supervisor_id = $_SESSION['supervisor_id'];
if (!isset($supervisor_id)) {
    header('Location: ../External/login.php');
    exit;
}

$attendances = mysqli_query($conn, "SELECT a.*, u.username 
    FROM `attendance` a 
    JOIN `users` u ON a.user_id = u.user_id") or die('Query failed: ' . mysqli_error($conn));

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="attendance_report_' . date('Y-m-d') . '.csv"');

$output = fopen('php://output', 'w');

fputcsv($output, ['Name', 'Date', 'Arrival Time', 'Departure Time', 'Status']);

if (mysqli_num_rows($attendances) > 0) {
    while ($attendance = mysqli_fetch_assoc($attendances)) {
        $name = $attendance['username'];
        $date = $attendance['attendance_date'];
        $arrival = $attendance['check_in_time'];
        $departure = $attendance['check_out_time'];
        $status = 'Present'; 
        fputcsv($output, [$name, $date, $arrival, $departure, $status]);
    }
} else {
    fputcsv($output, ['', 'There are no data', '', '', '']);
}

fclose($output);
exit;
?>
