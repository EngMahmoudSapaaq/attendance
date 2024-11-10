<?php
include 'config.php';
session_start();
$do = isset($_GET['do']) ? $_GET['do'] : "Manage";
$user_id = $_SESSION['user_id'];
$type = $_SESSION['type'];
if (!isset($user_id)) {
    header('location:../External/login.php');
}
if ($type != 'user') {
    header('location:../External/login.php');
}
if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:../External/login.php');
}
$users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = '$user_id'") or die('Query failed: ' . mysqli_error($conn));
$user = mysqli_fetch_assoc($users);

$user_id = $user['user_id'];
$shift_id = $user['shift_id'];

$shifts = mysqli_query($conn, "SELECT * FROM `shifts` WHERE shift_id = '$shift_id'") or die('Query failed: ' . mysqli_error($conn));
$shift = mysqli_fetch_assoc($shifts);

$attendances = mysqli_query($conn, "SELECT * FROM `attendance` WHERE user_id = '$user_id' AND DATE(attendance_date) = CURDATE();") or die('Query failed: ' . mysqli_error($conn));
$attendance = mysqli_fetch_assoc($attendances);
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Facial Recognition Attendance System - Employee Home Page">
        <meta name="author" content="">

        <title>Facial Recognition Attendance System - Employee Home Page</title>

        <!-- Custom fonts and styles -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link href="../admin/css/sb-admin-2.min.css" rel="stylesheet">

        <!-- Additional styles for dialog -->
        <style>
            .modal-content {
                text-align: center;
            }
            .checkmark {
                font-size: 60px;
                color: green;
            }
            .modal-header {
                border-bottom: none;
            }
        </style>
        <!-- Load TensorFlow.js -->
        <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@3.11.0"></script>

        <!-- Load BlazeFace model -->
        <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/blazeface"></script>
    </head>

    <body id="page-top">
        <div id="recognizedNameDisplay" class="not-recognized"></div> <!-- Display area for recognized name or error message -->

        <!-- Page Wrapper -->
        <div id="wrapper">
            <!-- Sidebar -->
            <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background: #006079">
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="home.php" style="margin-top: 50px;margin-bottom: 50px">
                    <div class="sidebar-brand-icon">
                        <img src="../admin/img/logo1.png" style="width: 160px;height: 130px;border-radius: 10px;">
                    </div>
                </a>
                <hr class="sidebar-divider">
                <li class="nav-item active">
                    <a class="nav-link" href="home.php">
                        <i class="fas fa-user-check"></i>
                        <span>Check-In</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="attendance-Overview.php">
                        <i class="fas fa-chart-line"></i>
                        <span>Attendance Overview</span>
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
                    <!-- End of Topbar -->

                    <!-- Begin Page Content -->
                    <div class="container-fluid">
                        <h1 class="h3 mb-2 text-gray-800"><i class="fas fa-user-check"></i> Register Attendance</h1>
                        <p class="mb-4">Use the camera below to register your attendance through facial recognition.</p>
                        <div class="card shadow mb-4">
                            <div class="card-body text-center">
                                <?php
                                if ($do == "wrong") {
                                    echo "
                                <h5 style='color:red;'>Login user is not the same user that has been detected by the model</h5>

                                <a href='home.php'><button class='btn btn-primary' style='background:#006079;'>Try Again</button></a>
                                ";
                                } else {
                                    $currentTime = date('H:i:s');
                                    $dbTime_in = date('H:i:s', strtotime($shift['Attendance'] ?? NULL . '-1 hour')); 
                                    $dbTime_late = date('H:i:s', strtotime($shift['Attendance'] ?? NULL)); 
                                    $dbTime_in_end = date('H:i:s', strtotime($shift['Attendance'] ?? NULL . '+1 hour')); 

                                    $dbTime_out = date('H:i:s', strtotime($shift['Dismissal'] ?? NULL . '-40 minutes')); 
                                    $dbTime_out_end = date('H:i:s', strtotime($shift['Dismissal'] ?? NULL . '+1 hour')); 

                                    if ($attendance == null) {
                                        if ($currentTime < $dbTime_in) {
                                            echo "<h5 style='color:red;'>The current time is earlier than the shift start time! You can check in at " . $shift['Attendance'] . "</h5>";
                                        } elseif ($currentTime > $dbTime_in_end) {
                                            echo "<h5 style='color:red;'>Check-in period has ended! You should have checked in by " . $dbTime_in_end . "</h5>";
                                        } elseif ($currentTime > $dbTime_late) {
                                            echo "<h5 style='color:orange;'>You are checking in late! Regular check-in time is before " . $dbTime_late . "</h5>";
                                        } else {
                                            echo '<h5 style="color:green;">Check In</h5>';
                                            echo '<video id="camera" autoplay playinline width="700" height="500"></video>';
                                            echo '<canvas id="snapshot" style="display:none;" width="640" height="480"></canvas>';
                                            echo '<div id="result"></div>';
                                        }
                                    } elseif ($attendance != null && $attendance['check_out_time'] == null) {
                                        if ($currentTime > $dbTime_out_end) {
                                            echo "<h5 style='color:red;'>You missed the checkout time window, which ended at " . $dbTime_out_end . "</h5>";
                                        } else {
                                            echo '<h5 style="color:green;">Check Out</h5>';
                                            echo '<video id="camera" autoplay playinline width="700" height="500"></video>';
                                            echo '<canvas id="snapshot" style="display:none;" width="640" height="480"></canvas>';
                                            echo '<div id="result"></div>';
                                        }
                                    } else {
                                        echo "<h5 style='color:green;'>You have already checked in and out for today. Date: " . $attendance['attendance_date'] . "</h5>";
                                    }
                                    $date = date('Y-m-d');
                                    //-------------------------------------
                                    if($currentTime>$dbTime_out_end){
                                        $attendances_end = mysqli_query($conn, "SELECT * FROM `attendance` WHERE user_id = '$user_id' AND DATE(attendance_date) = CURDATE();") or die('Query failed: ' . mysqli_error($conn));
                                         if (mysqli_num_rows($attendances_end) < 0) {
                                           mysqli_query($conn, "INSERT INTO `absent` (user_Id , date) VALUES ('$user_id', '$date')") or die('query failed');

                                         }
                                    }
                                    
                                    
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                    <!-- /.container-fluid -->
                </div>
                <!-- End of Main Content -->

                <!-- Attendance Confirmation Modal -->
                <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <i class="fas fa-check-circle checkmark"></i>
                                <h5 class="modal-title" id="confirmationModalLabel">Attendance Registered Successfully!</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Logout Modal-->
                <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                <!-- End of Footer -->
            </div>
            <!-- End of Content Wrapper -->
        </div>
        <!-- End of Page Wrapper -->

        <script src="../admin/js/jquery.min.js"></script>
        <script src="../admin/js/bootstrap.bundle.min.js"></script>
        <script src="../admin/js/jquery.easing.min.js"></script>
        <script src="../admin/js/sb-admin-2.min.js"></script>

        <script>
            const video = document.getElementById('camera');
            const canvas = document.getElementById('snapshot');
            let model;
            let detectionInterval;

            async function startCamera() {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({video: true});
                    video.srcObject = stream;
                    await loadModel();
                } catch (error) {
                    console.error("Error accessing camera:", error);
                }
            }

            async function loadModel() {
                model = await blazeface.load();
                detectFaces();
            }

            async function detectFaces() {
                const context = canvas.getContext('2d');

                detectionInterval = setInterval(async () => {
                    const predictions = await model.estimateFaces(video, false);

                    if (predictions.length > 0) {
                        console.log("Face detected");

                        // Capture the frame with the face
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        context.drawImage(video, 0, 0, canvas.width, canvas.height);

                        // Convert the frame to a data URL and send to PHP
                        const imageData = canvas.toDataURL('image/jpeg');
                        await saveImage(imageData);

                        // Stop the detection interval after a face is detected
                        clearInterval(detectionInterval);
                    }
                }, 1000); // Check every second
            }

            async function saveImage(dataURL) {
                try {
                    const response = await fetch('save_image.php', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({image: dataURL})
                    });

                    const data = await response.json();

                    if (data.status === 'success') {
                        // Redirect to result page with predicted_class and image_path as query parameters
                        window.location.href = `result.php?result=${encodeURIComponent(data.predicted_class)}&image_path=${encodeURIComponent(data.image_path)}`;
                    }


                } catch (error) {
                    console.error("Error saving image:", error);
                }
            }


            startCamera();

        </script>


    </body>

</html>
