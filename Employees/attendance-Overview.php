<?php
include 'config.php';
session_start();
$do = isset($_GET['do'])? $_GET['do'] : "Manage";
$user_id = $_SESSION['user_id'];
$type = $_SESSION['type'];
if (!isset($user_id)) {
    header('location:../External/login.php');
};
if ($type != 'user') {
    header('location:../External/login.php');
};
if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:../External/login.php');
}
$users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = '$user_id'") or die('Query failed: ' . mysqli_error($conn));
$user = mysqli_fetch_assoc($users);

$shift_id = $user['shift_id'];
$shifts_data = mysqli_query($conn, "SELECT * FROM `shifts` WHERE shift_id = '$shift_id'") or die('Query failed: ' . mysqli_error($conn));
$shift_data = mysqli_fetch_assoc($shifts_data);

$date = date('Y-m-d');
$dayName = date('l', strtotime($date));
$attendances_data = mysqli_query($conn, "SELECT * FROM `attendance` WHERE user_id = '$user_id' AND attendance_date = '$date'") or die('Query failed: ' . mysqli_error($conn));
$attendance = mysqli_fetch_assoc($attendances_data);
//--------------------------------------------
$attendances_data_value = mysqli_query($conn, "SELECT * FROM `attendance` WHERE user_id = '$user_id'") or die('Query failed: ' . mysqli_error($conn));

$total_late_time = 0; 
$total_days_worked = 0; 

while ($attendanced = mysqli_fetch_assoc($attendances_data_value)) {
    $check_ina = $attendanced['check_in_time'];
    $shift_ida = $attendanced['shift_id'];

    $shift_query = "SELECT Attendance FROM `shifts` WHERE shift_id = '$shift_ida'";
    $shift_result = mysqli_query($conn, $shift_query) or die('Query failed: ' . mysqli_error($conn));
    $shift = mysqli_fetch_assoc($shift_result);
    $expected_check_in = $shift['Attendance'];

    $late_minutes = (strtotime($check_ina) - strtotime($expected_check_in)) / 60;
    if ($late_minutes > 0) {
        $total_late_time += $late_minutes; 
    }

    $total_days_worked++; 
}

$average_monthly_delay = $total_days_worked > 0 ? round(($total_late_time / $total_days_worked) / 60, 2) : 0;
 // Average in hours
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

<style>
		.is_hidden {
		  display: none;
		}
		.t:hover{
			color: black;
		}
		h6{
        font-size: 17px;
        font-weight: 700;
      }
      .alert-d {
            padding: 20px;
            background-color: #dc3545!important;
            color: white;
            font-size: 17px;
            }

            .alert-a {
            padding: 20px;
            background-color: #28a745!important;
            color: white;
            font-size: 17px;
            }

            .alert-e {
            padding: 20px;
            background-color: #17a2b8!important;
            color: white;
            font-size: 17px;
            }

            .closebtn {
            margin-left: 15px;
            color: white;
            font-weight: bold;
            float: right;
            font-size: 25px;
            line-height: 20px;
            cursor: pointer;
            transition: 0.3s;
            }

            .closebtn:hover {
            color: black;
            }
	  </style>
    </head>

    <body id="page-top">
        <!-- Page Wrapper -->
        <div id="wrapper">
            <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background: #006079">
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="home.php" style="margin-top: 50px; margin-bottom: 50px">
                    <div class="sidebar-brand-icon">
                        <img src="../admin/img/logo1.png" style="width: 160px;height: 130px;border-radius: 10px;">
                    </div>
                </a>
                <hr class="sidebar-divider">
                <li class="nav-item ">
                    <a class="nav-link" href="home.php">
                        <i class="fas fa-user-check"></i>
                        <span>Check-In</span>
                    </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="attendance-Overview.php">
                        <i class="fas fa-chart-line"></i>
                        <span>Attendance Overview</span>
                    </a>
                </li>

                <li class="nav-item ">
                    <a class="nav-link" href="complaints.php">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Complaints</span>
                    </a>
                </li>
                <hr class="sidebar-divider">
            </ul>
            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">
                <div id="content">
                    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>

                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $user['username']; ?></span>
                                    <img class="img-profile rounded-circle" src="../admin/img/user.png">
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                        <img src="../admin/img/icons/login.png" style="width: 15px;height: 15px;">
                                        Logout
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </nav>
                    <!-- Page Heading -->
                    <div class="container-fluid">
                    <?php if($do == "Manage"){ ?>
                            
                        <?php }elseif($do == "done"){ ?>
                            <br>
                            <div class="alert-a">
                                <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
                                Attendance record has been saved successfully
                            </div>
                    <br>
                        <?php } ?>
                        <h1 class="h3 mb-2 text-gray-800"><i class="fas fa-user-check"></i> Employee Guide</h1>

                        <div class="row">
                            <!-- Employee Profile -->
                            <div class="col-xl-4 col-md-6 mb-4 p">
                                <div class="card shadow h-100 py-2">
                                    <div class="profile-info">
                                        <img src="../admin/img/user.png" alt="User Image">
                                        <h2><?php echo $user['username'] ?? NULL; ?></h2>
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
                                        <p>Arrival Time: <?php echo $shift_data['Attendance'] ?? NULL; ?> AM</p>
                                        <br>
                                        <p>Departure Time: <?php echo $shift_data['Dismissal'] ?? NULL; ?> PM</p>
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
                                        <circle class="circle" cx="50" cy="50" r="45" style="stroke-dashoffset: calc(314 * (1 - <?php echo ((($average_monthly_delay) * 60) / ($user['allowed_Delay'])) / 100; ?>));"></circle>
                                        </svg>
                                        <div class="value"><?php echo ((($average_monthly_delay) * 60) / ($user['allowed_Delay'])) * 100; ?>%</div>
                                    </div>
                                    <p>Allowed Delay: <span><?php echo $user['allowed_Delay'] ?? NULL; ?> minutes</span></p>
                                    <p>Remaining: <span><?php echo round(($user['allowed_Delay']) - ($average_monthly_delay * 60)); ?>
 minutes</span></p>
                                </div>
                            </div>

                            <!-- Attendance Time -->
                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="card shadow h-100 py-2">
                                    <div class="attendance-info">
                                        <h3 class="box-title"><i class="fas fa-sign-in-alt"></i> Attendance Time</h3>
                                        <p>Check-In Time: <?php echo isset($attendance['check_in_time']) ? date('h:i A', strtotime($attendance['check_in_time'])) : 'Not Yet'; ?></p>
                                    </div>
                                </div>
                            </div>
                                    

                            <!-- Departure Time -->
                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="card shadow h-100 py-2">
                                    <div class="attendance-info">
                                        <h3 class="box-title"><i class="fas fa-sign-out-alt"></i> Departure Time</h3>
                                       <p>Check-Out Time: <?php echo isset($attendance['check_out_time']) ? date('h:i A', strtotime($attendance['check_out_time'])) : 'Not Yet'; ?></p>
                                </div>
                                </div>
                            </div>

                            <!-- Monthly Statistics -->
                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="card shadow h-100 py-2">
                                    <div class="statistics-info">
                                        <h3 class="box-title"><i class="fas fa-chart-pie"></i> Monthly Delay Statistics</h3>
                                        <p>Average Monthly Delay: <span><?php echo $average_monthly_delay; ?> minutes</span></p>
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
                        <a class="btn btn-primary" href="attendance-Overview.php?logout">Logout</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bootstrap core JavaScript -->
        <script src="../admin/js/jquery.min.js"></script>
        <script src="../admin/js/bootstrap.bundle.min.js"></script>
        <script src="../admin/js/jquery.easing.min.js"></script>
        <script src="../admin/js/sb-admin-2.min.js"></script>
    </body>

</html>
