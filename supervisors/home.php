<?php
include 'config.php';
session_start();
$supervisor_id = $_SESSION['supervisor_id'];
if (!isset($supervisor_id)) {
    header('location:../External/login.php');
};
if (isset($_GET['logout'])) {
    unset($supervisor_id);
    session_destroy();
    header('location:../External/login.php');
}

$attendances_10 = 0;
$attendances_11 = 0;
$attendances_12 = 0;
$attendances_1 = 0;
$attendances_2 = 0;
$attendances_3 = 0;
$attendances_4 = 0;



$attendances10 = mysqli_query($conn, "SELECT * 
FROM `attendance`
WHERE MONTH(`attendance_date`) = 10 AND YEAR(`attendance_date`) = 2024;
") or die('Query failed: ' . mysqli_error($conn));

$attendances11 = mysqli_query($conn, "SELECT * 
FROM `attendance`
WHERE MONTH(`attendance_date`) = 11 AND YEAR(`attendance_date`) = 2024;
") or die('Query failed: ' . mysqli_error($conn));
$attendances12 = mysqli_query($conn, "SELECT * 
FROM `attendance`
WHERE MONTH(`attendance_date`) = 12 AND YEAR(`attendance_date`) = 2024;
") or die('Query failed: ' . mysqli_error($conn));
$attendances1 = mysqli_query($conn, "SELECT * 
FROM `attendance`
WHERE MONTH(`attendance_date`) = 1 AND YEAR(`attendance_date`) = 2024;
") or die('Query failed: ' . mysqli_error($conn));
$attendances2 = mysqli_query($conn, "SELECT * 
FROM `attendance`
WHERE MONTH(`attendance_date`) = 2 AND YEAR(`attendance_date`) = 2024;
") or die('Query failed: ' . mysqli_error($conn));
$attendances3 = mysqli_query($conn, "SELECT * 
FROM `attendance`
WHERE MONTH(`attendance_date`) = 3 AND YEAR(`attendance_date`) = 2024;
") or die('Query failed: ' . mysqli_error($conn));
$attendances4 = mysqli_query($conn, "SELECT * 
FROM `attendance`
WHERE MONTH(`attendance_date`) = 4 AND YEAR(`attendance_date`) = 2024;
") or die('Query failed: ' . mysqli_error($conn));

//-----------------------------------------------------------------------
$attendances_10 = mysqli_num_rows($attendances10);
$attendances_11 = mysqli_num_rows($attendances11);
$attendances_12 = mysqli_num_rows($attendances12);
$attendances_1 = mysqli_num_rows($attendances1);
$attendances_2 = mysqli_num_rows($attendances2);
$attendances_3 = mysqli_num_rows($attendances3);
$attendances_4 = mysqli_num_rows($attendances4);
//----------------------------------------------
$attendance_total = 0;
$absent_total = 0;
$attendances = mysqli_query($conn, "SELECT * FROM `attendance` ") or die('Query failed: ' . mysqli_error($conn));
$absents = mysqli_query($conn, "SELECT * FROM `absent` ") or die('Query failed: ' . mysqli_error($conn));
$attendance_total = mysqli_num_rows($attendances);
$absent_total = mysqli_num_rows($absents);
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Facial Recognition Attendance System - Supervisor Dashboard</title>

        <!-- Custom fonts and styles -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link href="../admin/css/sb-admin-2.min.css" rel="stylesheet">
        <style>
            .card {
                margin-bottom: 30px;
            }

            .chart-container {
                position: relative;
                height: 300px;
                width: 100%;
            }

            .card-header h6 {
                color: #006079;
            }

            /* Add custom styles for charts */
            .chartjs-render-monitor {
                animation: fadeIn 1s ease-in-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }

            .btn-custom {
                background-color: #006079;
                color: white;
                padding: 10px 20px;
                border-radius: 5px;
                border: none;
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
                <li class="nav-item active">
                    <a class="nav-link" href="home.php">
                        <i class="fas fa-chart-line"></i>
                        <span>Attendance Overview</span>
                    </a>
                </li>

                <li class="nav-item">
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
                        <h1 class="h3 mb-2 text-gray-800"><i class="fas fa-chart-line"></i> Supervisor Dashboard</h1>
                        <p class="mb-4">This dashboard provides insights into attendance metrics, reports, and system performance for supervisors.</p>

                        <!-- Dashboard Cards -->
                        <div class="row">
                            <!-- Attendance Rate -->
                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="card shadow h-100 py-2">
                                    <div class="card-header">
                                        <h6 class="m-0 font-weight-bold">Attendance</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="attendanceRateChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Punctuality Chart -->
                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="card shadow h-100 py-2">
                                    <div class="card-header">
                                        <h6 class="m-0 font-weight-bold">Punctuality</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="punctualityChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Absenteeism Chart -->
                            <div class="col-xl-4 col-md-6 mb-4">
                                <div class="card shadow h-100 py-2">
                                    <div class="card-header">
                                        <h6 class="m-0 font-weight-bold">Absenteeism</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="absenteeismChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reports -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold">Attendance Reports</h6>
                                    </div>
                                    <div class="card-body">
                                        <p>View detailed reports and export attendance data in various formats.</p>
                                        <a href="reports.php" class="btn btn-primary" style="background: #006079">Export to PDF/Excel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.container-fluid -->
                </div>
                <!-- End of Main Content -->

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
                                <a class="btn btn-primary"  href="home.php?logout">Logout</a>
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
        <script src="../admin/js/Chart.min.js"></script>

        <!-- Chart Initialization -->
        <script>
            // Attendance Rate Chart (Line Chart)
            var ctx1 = document.getElementById('attendanceRateChart').getContext('2d');
            var attendanceRateChart = new Chart(ctx1, {
                type: 'line',
                data: {
                    labels: ['October', 'November', 'December', 'January', 'February', 'March', 'April'],
                    datasets: [{
                            label: 'Attendance Numbers ',
                            data: [<?php echo $attendances_10 ?? 0 ; ?>, <?php echo $attendances_11 ?? 0 ; ?>, <?php echo $attendances_12 ?? 0 ; ?>, <?php echo $attendances_1 ?? 0 ; ?>, <?php echo $attendances_2 ?? 0 ; ?>, <?php echo $attendances_3 ?? 0 ; ?>, <?php echo $attendances_4 ?? 0 ; ?>],
                            backgroundColor: 'rgba(0, 123, 255, 0.2)',
                            borderColor: 'rgba(0, 123, 255, 1)',
                            borderWidth: 2,
                            pointBackgroundColor: '#007bff',
                        }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#006079',
                            }
                        },
                        x: {
                            ticks: {
                                color: '#006079',
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: '#006079'
                            }
                        }
                    }
                }
            });

            // Punctuality Chart (Bar Chart)
            var ctx2 = document.getElementById('punctualityChart').getContext('2d');
            var punctualityChart = new Chart(ctx2, {
                type: 'bar',
                data: {
                   labels: ['October', 'November', 'December', 'January', 'February', 'March', 'April'],
                    datasets: [{
                            label: 'Punctuality ',
                            data: [<?php echo $attendances_10 ?? 0 ; ?>, <?php echo $attendances_11 ?? 0 ; ?>, <?php echo $attendances_12 ?? 0 ; ?>, <?php echo $attendances_1 ?? 0 ; ?>, <?php echo $attendances_2 ?? 0 ; ?>, <?php echo $attendances_3 ?? 0 ; ?>, <?php echo $attendances_4 ?? 0 ; ?>],
                            backgroundColor: '#28a745',
                            borderColor: '#28a745',
                            borderWidth: 1,
                        }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#006079',
                            }
                        },
                        x: {
                            ticks: {
                                color: '#006079',
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: '#006079'
                            }
                        }
                    }
                }
            });

            // Absenteeism Chart (Doughnut Chart)
            var ctx3 = document.getElementById('absenteeismChart').getContext('2d');
            var absenteeismChart = new Chart(ctx3, {
                type: 'doughnut',
                data: {
                    labels: ['Present', 'Absent'],
                    datasets: [{
                            label: 'Absenteeism Rate',
                            data: [<?php echo ((($attendance_total)/($attendance_total+$absent_total))*100) ; ?>, <?php echo ((($absent_total)/($attendance_total+$absent_total))*100) ; ?>],
                            backgroundColor: ['#007bff', '#dc3545'],
                            borderColor: ['#007bff', '#dc3545'],
                            borderWidth: 1,
                        }]
                },
                options: {
                    plugins: {
                        legend: {
                            labels: {
                                color: '#006079'
                            }
                        }
                    }
                }
            });
        </script>
    </body>

</html>
