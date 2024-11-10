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

$html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #006079;
        }
        th, td {
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background-color: #006079;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        h2 {
            text-align: center;
            color: #006079;
        }
    </style>
</head>
<body>
    <h2>Attendance Report</h2>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Date</th>
                <th>Arrival Time</th>
                <th>Departure Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>';

if (mysqli_num_rows($attendances) > 0) {
    while ($attendance = mysqli_fetch_assoc($attendances)) {
        $name = htmlspecialchars($attendance['username']);
        $date = htmlspecialchars($attendance['attendance_date']);
        $arrival = htmlspecialchars($attendance['check_in_time']);
        $departure = htmlspecialchars($attendance['check_out_time']);
        $status = 'Present'; // Assuming status is always 'Present' based on original code

        $html .= "<tr>
                    <td>'.$name.'</td>
                    <td>'.$date.'</td>
                    <td>'.$arrival.'</td>
                    <td>'.$departure.'</td>
                    <td>'.$status.'</td>
                  </tr>";
    }
} else {
    $html .= '<tr>
                <td colspan="5" style="text-align:center;">There are no data</td>
              </tr>';
}

$html .= '
        </tbody>
    </table>
    <p>Generated on: ' . date('Y-m-d H:i:s') . '</p>
    <button onclick="window.print()">Print Report</button>
</body>
</html>';

echo $html;

exit;
?>
