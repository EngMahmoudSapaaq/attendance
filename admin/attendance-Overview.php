<?php
include 'config.php';
session_start();
$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:../External/index.php');
};

if (isset($_GET['logout'])) {
    unset($admin_id);
    session_destroy();
    header('location:../External/index.php');
}
$user_id = $_GET['user_id'] ?? null;
$users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = '$user_id'") or die('Query failed: ' . mysqli_error($conn));
$user = mysqli_fetch_assoc($users);

$shift_id = $user['shift_id'];
$shifts = mysqli_query($conn, "SELECT * FROM `shifts` WHERE shift_id = '$shift_id'") or die('Query failed: ' . mysqli_error($conn));
$shift = mysqli_fetch_assoc($shifts);


$date = date('Y-m-d');
$dayName = date('l', strtotime($date));
$attendances = mysqli_query($conn, "SELECT * FROM `attendance` WHERE user_id = '$user_id' AND attendance_date = '$date'") or die('Query failed: ' . mysqli_error($conn));
$attendance = mysqli_fetch_assoc($attendances);

//--------------------------------------------
$attendances_data = mysqli_query($conn, "SELECT * FROM `attendance` WHERE user_id = '$user_id' ") or die('Query failed: ' . mysqli_error($conn));

    $total_late_time = 0;
    $total_days_worked = 0;

    while ($attendance = mysqli_fetch_assoc($attendances_data)) {
        $check_in = $attendance['check_in_time'];
        $shift_id = $attendance['shift_id'];

        $shift_query = "SELECT Attendance FROM `shifts` WHERE shift_id = '$shift_id'";
        $shift_result = mysqli_query($conn, $shift_query) or die('Query failed: ' . mysqli_error($conn));
        $shift = mysqli_fetch_assoc($shift_result);
        $expected_check_in = $shift['Attendance'];

        $late_minutes = (strtotime($check_in) - strtotime($expected_check_in)) / 60;
        if ($late_minutes > 0) {
            $total_late_time += $late_minutes; 
        }

        $total_days_worked++; 
    }

    $average_monthly_delay = $total_days_worked > 0 ? ($total_late_time / $total_days_worked) / 60 : 0; 
$attendances_data_v = mysqli_query($conn, "SELECT * FROM `attendance` WHERE user_id = '$user_id' ") or die('Query failed: ' . mysqli_error($conn));

?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Facial Recognition Attendance System - Employee Guide">
        <meta name="author" content="">

        <title>Employee Guide - Facial Recognition Attendance System</title>

        <!-- Custom fonts and styles -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link href="../admin/css/sb-admin-2.min.css" rel="stylesheet">

        <!-- Custom styles for the circular progress bar -->
        <style>
            body {
                background-color: #f8f9fc;
            }

            .profile-box,
            .shift-box,
            .attendance-box,
            .statistics-box,
            .balance-box,
            .attendance-list-box {
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                margin-bottom: 20px;
                padding: 20px;
            }

            .profile-info,
            .shift-info,
            .attendance-info,
            .statistics-info,
            .balance-info {
                text-align: center;
            }

            .box-title {
                font-weight: bold;
                font-size: 18px;
                margin-bottom: 10px;
            }

            .statistics-info span,
            .balance-info span {
                font-weight: bold;
            }

            .attendance-list-table {
                width: 100%;
                text-align: center;
            }

            .attendance-list-table th,
            .attendance-list-table td {
                padding: 10px;
                border-bottom: 1px solid #e3e6f0;
            }

            .attendance-list-table th {
                background-color: #f8f9fc;
            }

            /* Circular progress chart for delay balance */
            .circle-progress {
                display: flex;
                justify-content: center;
                align-items: center;
                position: relative;
                width: 120px;
                height: 120px;
                margin: 20px auto;
            }

            .circle-progress .value {
                position: absolute;
                font-size: 16px;
                font-weight: bold;
            }

            .circle-progress .circle-bg {
                stroke: #e9ecef;
                stroke-width: 10;
                fill: none;
            }

            .circle-progress .circle {
                stroke-dasharray: 314;
                stroke-dashoffset: 314;
                transition: stroke-dashoffset 0.5s ease;
                stroke-width: 10px;
                fill: none;
                stroke: #4e73df;
                stroke-linecap: round;
                filter: drop-shadow(0px 4px 6px rgba(0, 0, 0, 0.2));
            }

            .balance-box {
                text-align: center;
            }

            .card-header h6 {
                color: #006079;
            }
        </style>
    </head>

    <body id="page-top">
        <!-- Page Wrapper -->
        <div id="wrapper">
            <ul class="navbar-nav  sidebar sidebar-dark accordion" id="accordionSidebar" style="background: #006079">
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="home.php" style="margin-top: 50px;margin-bottom: 50px">
                    <div class="sidebar-brand-icon ">
                        <img src="img/logo1.png" style="width: 160px;height: 130px;border-radius: 10px;">
                    </div>
                </a>



                <hr class="sidebar-divider">

                <div class="sidebar-heading">Interface</div>

                <li class="nav-item active">
                    <a class="nav-link" href="home.php">
                        <img src="img/team (2).png" style="width: 21px;height: 23px">
                        <span>Users</span>
                    </a>
                </li>

                <hr class="sidebar-divider">

                <li class="nav-item">
                    <a class="nav-link" href="common_questions.php">
                        <img src="img/people.png" style="width: 23px;height: 25px">
                        <span>FAQs</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="complaints.php">
                        <img src="img/risk.png" style="width: 23px;height: 25px">
                        <span>complaints</span>
                    </a>
                </li>


                <hr class="sidebar-divider d-none d-md-block">



            </ul>
            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">
                <div id="content">
                    <!-- Page Heading -->
                    <div class="container-fluid">
                        <h1 class="h3 mb-2 text-gray-800"><i class="fas fa-user-check"></i> Employee Guide</h1>

                        <div class="row">
                            <!-- Employee Profile -->
                            <div class="col-xl-4 col-md-6 mb-4 p">
                                <div class="card shadow h-100 py-2">
                                    <div class="profile-info">
                                        <img src="../admin/img/user.png" alt="User Image">
                                        <h2>User</h2>
                                        <p><?php echo $user['username'] ?? NULL; ?></p>
                                        <p>Email: <?php echo $user['email'] ?? NULL; ?></p>
                                        <p>Phone: +<?php echo $user['phone'] ?? NULL; ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Today's Shift Info -->
                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="card shadow h-100 py-2">
                                    <div class="shift-info">
                                        <h3 class="box-title"><i class="fas fa-clock"></i> Today's Shift</h3>
                                        <br><br>
                                        <p>Arrival Time: <?php echo $shift['Attendance'] ?? NULL; ?> AM</p>
                                        <br>
                                        <p>Departure Time: <?php echo $shift['Dismissal'] ?? NULL; ?> PM</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Monthly Delay Balance with Circular Progress -->
                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="card shadow h-100 py-2 balance-box">
                                    <h3 class="box-title"><i class="fas fa-balance-scale"></i> Monthly Delay Balance</h3>
                                    <div class="circle-progress">
                                        <svg viewBox="0 0 100 100">
                                        <circle class="circle-bg" cx="50" cy="50" r="45"></circle>
                                        <circle class="circle" cx="50" cy="50" r="45" style="stroke-dashoffset: calc(314 * (1 - <?php echo ((($average_monthly_delay)*60)/($user['allowed_Delay']))/100; ?>));"></circle>
                                        </svg>
                                        <div class="value"><?php echo ((($average_monthly_delay)*60)/($user['allowed_Delay']))*100; ?>%</div>
                                    </div>
                                    <p>Allowed Delay: <span><?php echo $user['allowed_Delay'] ?? NULL; ?> minutes</span></p>
                                    <p>Remaining: <span><?php echo ($user['allowed_Delay'])-($average_monthly_delay)*60;   ?> minutes</span></p>
                                </div>
                            </div>

                            <!-- Attendance Time -->
                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="card shadow h-100 py-2">
                                    <div class="attendance-info">
                                        <h3 class="box-title"><i class="fas fa-sign-in-alt"></i> Attendance Time</h3>
                                        <p>Checked-in: <?php echo $attendance['check_in_time'] ?? NULL; ?> AM</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Departure Time -->
                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="card shadow h-100 py-2">
                                    <div class="attendance-info">
                                        <h3 class="box-title"><i class="fas fa-sign-out-alt"></i> Departure Time</h3>
                                        <p>Checked-out: <?php echo $attendance['check_in_time'] ?? NULL; ?> PM</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Monthly Statistics -->
                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="card shadow h-100 py-2">
                                    <div class="statistics-info">
                                        <h3 class="box-title"><i class="fas fa-chart-pie"></i> Monthly Delay Statistics</h3>
                                        <p>Average Monthly Delay: <span><?php echo $average_monthly_delay;    ?> hours</span></p>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <!-- Attendance List -->
                        <div class="attendance-list-box">
                            <h3 class="box-title"><i class="fas fa-list"></i> Attendance List</h3>
                            <table class="attendance-list-table">
                                <thead>
                                    <tr>
                                        <th>Day</th>
                                        <th>Date</th>
                                        <th>Shift Name</th>
                                        <th>Actual Entry</th>
                                        <th>Actual Exit</th>
                                        <th>Notes</th>
                                        <th>Total Late Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($attendances_data_v && mysqli_num_rows($attendances_data_v) > 0) {
                                        while ($attendances_value = mysqli_fetch_assoc($attendances_data_v)) {
                                            $attendance_date = $attendances_value['attendance_date'];
                                            $day = date('l', strtotime($attendance_date));

                                            $shift_data_id = $attendances_value['shift_id'];
                                            $shift_data_query = mysqli_query($conn, "SELECT * FROM `shifts` WHERE shift_id = '$shift_data_id'") or die('Query failed: ' . mysqli_error($conn));
                                            $shift_data = mysqli_fetch_assoc($shift_data_query);

                                            $check_in = $attendances_value['check_in_time'];
                                            $expected_check_in = $shift_data['Attendance'];

                                            $late_minutes = (strtotime($check_in) - strtotime($expected_check_in)) / 60;

                                            echo '<tr>
                                                    <td>' . $day . '</td>
                                                    <td>' . $attendance_date . '</td>
                                                    <td>' . $shift_data['Name'] . ' Shift</td>
                                                    <td>' . $check_in . '</td>
                                                    <td>' . $attendances_value['check_out_time'] . '</td>';

                                            if ($late_minutes > 0) {
                                                echo '<td style="color: red">Late by ' . $late_minutes . ' minutes</td>';
                                            } else {
                                                echo '<td>On time</td>';
                                            }

                                            echo '<td>' . ($late_minutes > 0 ? $late_minutes : '0') . ' minutes</td>';
                                            echo '</tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="7">No attendance records found</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-primary" href="home.php?logout">Logout</a>
                    </div>
                </div>
            </div>
        </div>

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Facial Recognition Attendance System © 2024</span>
                        </div>
                    </div>
                </footer>
            </div>
            <!-- End of Content Wrapper -->
        </div>
        <!-- End of Page Wrapper -->

        <!-- Bootstrap core JavaScript -->
        <script src="../admin/js/jquery.min.js"></script>
        <script src="../admin/js/bootstrap.bundle.min.js"></script>
        <script src="../admin/js/jquery.easing.min.js"></script>
        <script src="../admin/js/sb-admin-2.min.js"></script>
    </body>

</html>
