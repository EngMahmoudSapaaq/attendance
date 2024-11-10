<?php
include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];
$type=$_SESSION['type'];
if (!isset($user_id)) {
    header('location:../External/login.php');
};
if ($type!='user') {
    header('location:../External/login.php');
};
if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:../External/login.php');
}
$complaints_suggestions = mysqli_query($conn, "SELECT * FROM `complaints_suggestions`") or die('Query failed: ' . mysqli_error($conn));

if (isset($_POST['submit'])) {

    $message = mysqli_real_escape_string($conn, $_POST['message']);
    
   $insert = mysqli_query($conn, "INSERT INTO `complaints_suggestions`(message_text,user_id,admin_Id)"
            . " VALUES('$message','$user_id','1')") or die('query failed');

    if ($insert) {
        header('location:complaints.php');
    } 
}
$users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = '$user_id'") or die('Query failed: ' . mysqli_error($conn));
$user = mysqli_fetch_assoc($users);

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
            .dialog-container {
                display: none;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                padding: 20px;
                background-color: #fff;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
                z-index: 1;
            }

            #overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 0;
            }

            .confirm-btn {
                background-color: #dc3545;
                color: #fff;
                padding: 10px 20px;
                border: none;
                cursor: pointer;
            }

            .cancel-btn {
                background-color: #6c757d;
                color: #fff;
                padding: 10px 20px;
                border: none;
                cursor: pointer;
                margin-left: 10px;
            }
            #closeBtn {
                cursor: pointer;
                float: right;
                font-size: 20px;
                font-weight: bold;
            }
            .add-course-container {
                display: flex;
                justify-content: flex-end;
                margin-bottom: 10px;
            }
            .add-course-btn {
                background: #006079;
                color: white;
                padding: 5px 10px;
                text-decoration: none;
                border-radius: 5px;
                display: flex;
                align-items: center;
            }

            /* Add margin to the icon */
            .add-course-btn img {
                margin-right: 5px;
                width: 15px;
                height: 15px;
            }
        </style>
    </head>

    <body id="page-top">
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
            <li class="nav-item ">
                <a class="nav-link" href="home.php">
                    <i class="fas fa-user-check"></i>
                    <span>Check-In</span>
                </a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="attendance-Overview.php">
                        <i class="fas fa-chart-line"></i>
                        <span>Attendance Overview</span>
                    </a>
                </li>
            

                <li class="nav-item active">
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
                        <!-- Sidebar Toggle (Topbar) -->
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>

                        <!-- User Information -->
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $user['username'];   ?></span>
                                    <img class="img-profile rounded-circle" src="../admin/img/user.png">
                                </a>
                                <!-- Dropdown - User Information -->
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
                        <h1 class="h3 mb-2 text-gray-800">
                            <i class="fas fa-exclamation-triangle"></i>  complaints
                        </h1>
                        <p class="mb-4">"User Management System" is more than just a script; it's a comprehensive solution for handling user-related operations, fostering a positive user experience, and maintaining the security and integrity of your application.</p>
                        <div class="add-course-container" style="width: 200px;margin-left: 1040px">
                               <a onclick="openDeleteDialog()" class="add-course-btn" style="color: white;text-decoration: none;" name="add">
                                   <img src="../admin/img/add.png" alt="Add Icon">
                                Send a complaint 
                            </a>
                        </div>
                        <!-- DataTales Example -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">DataTables </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>User Name</th>
                                                    <th>complaint</th>
                                                    <th>Response to the complaint</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>User Name</th>
                                                    <th>complaint</th>
                                                    <th>Response to the complaint</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                 <?php
                                            if (isset($complaints_suggestions) && mysqli_num_rows($complaints_suggestions) > 0) {
                                                while ($complaint = mysqli_fetch_assoc($complaints_suggestions)) {
                                                    $user_id=$complaint['user_id']??null;
                                                    $supervisor_id=$complaint['supervisor_id']??null;
                                                    
                                                    echo '<tr>';
                                                    if ($user_id>0){
                                                        $users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = '$user_id'") or die('Query failed: ' . mysqli_error($conn));
                                                    $user = mysqli_fetch_assoc($users);
                                                    echo '<td>'.$user['username'].'</td>';
                                                    }elseif ($supervisor_id>0) {
                                                    $supervisors = mysqli_query($conn, "SELECT * FROM `supervisors` WHERE supervisor_id = '$supervisor_id'") or die('Query failed: ' . mysqli_error($conn));
                                                    $supervisor = mysqli_fetch_assoc($supervisors);
                                                    echo '<td>'.$supervisor['name'].'</td>';
                                                }
                                                    
                                                    echo '<td>complaint : .... <div class="dropdown no-arrow">
                                                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class=""> see more</i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                                                 aria-labelledby="dropdownMenuLink">
                                                                <div class="dropdown-header">Description :</div>
                                                                <p style="padding: 15px">'.$complaint['message_text'].'</p>

                                                            </div>
                                                        </div>
                                                    </td>';
                                                    
                                                    if(($complaint['response_text'])>0){
                                                    echo '<td>Dear [Employee ],.... <div class="dropdown no-arrow">
                                                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class=""> see more</i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                                                 aria-labelledby="dropdownMenuLink">
                                                                <div class="dropdown-header">Description :</div>
                                                                <p style="padding: 15px">'.$complaint['response_text'].'</p>

                                                            </div>
                                                        </div>
                                                    </td>';
                                                    }else {
                                                    echo '<td>No response yet
                                                    </td>';
                                                    }
                                                    
                                                    
                                                    echo '</td>';
                                                    
                                                    
                                                }
                                                } else {
                                                    echo ' <td></td>
                                                    <td>there are no data</td>';
                                                    
                                                }
                                                
                                                
                                                ?>
                                               
                                            </tbody>
                                        </table>
                                    </form>
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
                                <a class="btn btn-primary" href="complaints.php?logout">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dialog-container" id="deleteDialog" style="width: 500px">
                    <span id="closeBtn"  onclick="closeDeleteDialog()">&times;</span>


                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Add complaint!</h1>
                    </div>
                     <form method="post" enctype="multipart/form-data">

                        <div class="form-floating mb-3">
                            <label for="fileInput" class="text-primary">complaint</label>
                            <textarea class="form-control" id="message"  name="message" type="text" placeholder="Enter your complaint here..." style="height: 10rem" data-sb-validations="required" required=""></textarea>

                            <div class="invalid-feedback" data-sb-feedback="question:required">A question is required.</div>
                        </div>
                        <hr>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" onclick="closeDeleteDialog()" style="margin-right: 250px">Cancel</button>
                            <button class="btn " style="background: #006079;color: white" name="submit" type="submit" ><img src="../admin/img/reply.png" style="width: 15px;height: 15px;">
                                Send</button>
                        </div>



                    </form>




                </div>

                <div id="overlay"></div>
                <script>
                    function openDeleteDialog() {
                        document.getElementById('deleteDialog').style.display = 'block';
                        document.getElementById('overlay').style.display = 'block';
                    }

                    function closeDeleteDialog() {
                        document.getElementById('deleteDialog').style.display = 'none';
                        document.getElementById('overlay').style.display = 'none';
                    }

                    function deleteItem() {
                        // Add your delete logic here
                        alert('Sent successfully!');
                        closeDeleteDialog();
                    }
                </script>
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

        <!-- Export to Excel and PDF scripts -->
        <script>
            function exportToExcel() {
                // Logic to export data to Excel
                alert('Data exported to Excel!');
            }

            function exportToPDF() {
                // Logic to export data to PDF
                alert('Data exported to PDF!');
            }
        </script>

        <!-- Bootstrap core JavaScript-->
        <script src="../admin/js/jquery.min.js"></script>
        <script src="../admin/js/bootstrap.bundle.min.js"></script>
        <script src="../admin/js/jquery.easing.min.js"></script>
        <script src="../admin/js/sb-admin-2.min.js"></script>

    </body>

</html>
