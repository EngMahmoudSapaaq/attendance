<?php
// reports.php

include 'config.php';
session_start();
$supervisor_id = $_SESSION['supervisor_id'];
if (!isset($supervisor_id)) {
    header('location:../External/login.php');
    exit;
};
if (isset($_GET['logout'])) {
    unset($supervisor_id);
    session_destroy();
    header('location:../External/login.php');
    exit;
}
$attendances = mysqli_query($conn, "SELECT a.*, u.username 
    FROM `attendance` a 
    JOIN `users` u ON a.user_id = u.user_id") or die('Query failed: ' . mysqli_error($conn));
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Supervisors can access reports and export attendance data in the Facial Recognition Attendance System.">
        <meta name="author" content="Attendance System">
        <title>Facial Recognition Attendance System - Supervisors</title>

        <!-- Custom fonts and styles -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link href="../admin/css/sb-admin-2.min.css" rel="stylesheet">

        <!-- Custom styles -->
        <style>
            .export-btns {
                display: flex;
                justify-content: flex-end;
                margin-bottom: 10px;
            }
            .export-btns a {
                background-color: #006079;
                color: white;
                padding: 10px 15px;
                text-decoration: none;
                border-radius: 5px;
                margin-left: 10px;
            }
            .export-btns a:hover {
                background-color: #004f60;
            }
            .table thead th {
                color: #006079;
                font-weight: bold;
            }
            .table tbody tr:hover {
                background-color: #f8f9fc;
            }
        </style>
    </head>

    <body id="page-top">
        <!-- Page Wrapper -->
        <div id="wrapper">

            <!-- Sidebar -->
            <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background: #006079">
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="home.php" style="margin-top: 50px;margin-bottom: 50px">
                    <div class="sidebar-brand-icon ">
                        <img src="../admin/img/logo1.png" style="width: 160px;height: 130px;border-radius: 10px;">
                    </div>
                </a>
                <hr class="sidebar-divider">

                <!-- Updated Sections -->
                <li class="nav-item ">
                    <a class="nav-link" href="home.php">
                        <i class="fas fa-chart-line"></i>
                        <span>Attendance Overview</span>
                    </a>
                </li>

                <li class="nav-item active">
                    <a class="nav-link" href="reports.php">
                        <i class="fas fa-file-alt"></i>
                        <span>Reports</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link" href="complaints.php">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Complaints</span>
                    </a>
                </li>

                <hr class="sidebar-divider">
            </ul>
            <!-- End of Sidebar -->

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">

                    <!-- Topbar -->
                    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">Supervisor</span>
                                    <img class="img-profile rounded-circle" src="../admin/img/user.png">
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                     aria-labelledby="userDropdown">

                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                        <img src="../admin/img/icons/login.png" style="width: 15px;height: 15px;">
                                        Logout
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </nav>
                    <!-- End of Topbar -->

                    <!-- Begin Page Content -->
                    <div class="container-fluid">

                        <!-- Page Heading -->
                        <h1 class="h3 mb-4 text-gray-800"><i class="fas fa-chart-line"></i> Attendance and Departure Reports</h1>
                        <p class="mb-4">View attendance and departure statistics. Export the data for further analysis in Excel or PDF format.</p>

                        <!-- Export Buttons -->
                        <div class="export-btns">
                            <a href="export_excel.php" target="_blank">Export to Excel <i class="fas fa-file-excel"></i></a>
                            <a href="export_pdf.php" target="_blank">Export to PDF <i class="fas fa-file-pdf"></i></a>
                        </div>

                        <!-- Report Table -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Attendance Statistics</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Date</th>
                                                <th>Arrival Time</th>
                                                <th>Departure Time</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (isset($attendances) && mysqli_num_rows($attendances) > 0) {
                                                while ($attendance = mysqli_fetch_assoc($attendances)) {
                                                    $user_id = $attendance['user_id'];
                                                    // Assuming 'username' is already fetched via JOIN
                                                    $username = htmlspecialchars($attendance['username']);
                                                    $attendance_date = htmlspecialchars($attendance['attendance_date']);
                                                    $check_in_time = htmlspecialchars($attendance['check_in_time']);
                                                    $check_out_time = htmlspecialchars($attendance['check_out_time']);
                                                    echo '<tr>
                                                        <td>' . $username . '</td>
                                                        <td>' . $attendance_date . '</td>
                                                        <td>' . $check_in_time . ' AM</td>
                                                        <td>' . $check_out_time . ' PM</td>
                                                        <td>Present</td>
                                                    </tr>';
                                                }
                                            } else {
                                                echo '<tr>
                                                    <td colspan="5" style="text-align:center;">There are no data</td>
                                                </tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->

                <!-- Logout Modal -->
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
                                <a class="btn btn-primary" href="reports.php?logout">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of Logout Modal -->

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Facial Recognition Attendance System &copy; 2024</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Bootstrap core JavaScript-->
        <script src="../admin/js/jquery.min.js"></script>
        <script src="../admin/js/bootstrap.bundle.min.js"></script>
        <script src="../admin/js/jquery.easing.min.js"></script>
        <script src="../admin/js/sb-admin-2.min.js"></script>

    </body>

</html>
