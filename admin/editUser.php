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

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
    $Delay = mysqli_real_escape_string($conn, $_POST['Delay']);
    if (!empty($_POST['user_type'])) {
        foreach ($_POST['user_type'] as $check) {
            $type = $check;
        }
    }
    if (!empty($_POST['shift_type'])) {
        foreach ($_POST['shift_type'] as $check) {
            $shift = $check;
        }
    }

    $user_id = $_GET['user_id'] ?? null;
    $supervisor_id = $_GET['supervisor_id'] ?? null;

    $users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$password'") or die('query failed');
    $supervisors = mysqli_query($conn, "SELECT * FROM `supervisors` WHERE email = '$email' AND password = '$password'") or die('query failed');


    if ($type == 'employee') {
        if (mysqli_num_rows($users) > 0) {
            $message[] = 'user already exist';
        } elseif ($password != $cpassword) {
            $message[] = 'confirm password not matched!';
        } else {
           
            $update_query = "UPDATE `users` SET `username` = '$name' , `password` = '$password', `shift_id` = '$shift', `allowed_Delay` = '$Delay' ,`email` = '$email', `phone` = '$phone'"
                    . " WHERE `user_id` = '$user_id'";

            mysqli_query($conn, $update_query);
            $message1[] = ' Edit has been done successfully';
        }
    } else {


        //---------------------------------------

        if (mysqli_num_rows($supervisors) > 0) {
            $message[] = 'supervisor already exist';
        } elseif ($password != $cpassword) {
            $message[] = 'confirm password not matched!';
        } else {
            $update_query = "UPDATE `supervisors` SET `name` = '$name' , `email` = '$email', `password` = '$password', `phone` = '$phone'"
                    . " WHERE `supervisor_id` = '$supervisor_id'";

            mysqli_query($conn, $update_query);
            $message1[] = ' Edit has been done successfully';
        }
    }
//----------------------------------------------
   
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Facial Recognition Attendance System </title>

        <!-- Custom fonts -->

        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-OOqD/7ikfRMq9Z2AK4OzghkMz5KKRT5KlI6Tosca5McitPmzs5DlWg27DD5vr6TgVfU9xfAqnwgtE+7tD1Uqy2w==" crossorigin="anonymous" />

        <!-- Custom styles -->
        <link href="css/sb-admin-2.min.css" rel="stylesheet">
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
             .message{
                margin:10px 0;
                width: 100%;
                border-radius: 5px;
                padding:10px;
                text-align: center;
                background-color:red;
                color:white;
                font-size: 20px;
            }
            .message1{
                margin:10px 0;
                width: 100%;
                border-radius: 5px;
                padding:10px;
                text-align: center;
                background-color:green;
                color:white;
                font-size: 20px;
            }
        </style>

    </head>

    <body id="page-top">

        <!-- Page Wrapper -->
        <div id="wrapper">

            <!-- Sidebar -->
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



                        <!-- Topbar Navbar -->
                        <ul class="navbar-nav ml-auto">

                            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                            <li class="nav-item dropdown no-arrow d-sm-none">
                                <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-search fa-fw"></i>
                                </a>
                                <!-- Dropdown - Messages -->
                                <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                     aria-labelledby="searchDropdown">
                                    <form class="form-inline mr-auto w-100 navbar-search">
                                        <div class="input-group">
                                            <input type="text" class="form-control bg-light border-0 small"
                                                   placeholder="Search for..." aria-label="Search"
                                                   aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button">
                                                    <i class="fas fa-search fa-sm"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </li>


                            <div class="topbar-divider d-none d-sm-block"></div>

                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin</span>
                                    <img class="img-profile rounded-circle"
                                         src="img/user.png">
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                     aria-labelledby="userDropdown">

                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                        <img src="img/icons/login.png" style="width: 15px;height: 15px;">
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
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800"><img src="img/icons/edit.png" style="width: 23px;height: 25px"> Edit</h1>

                        </div>

                        <div class="card o-hidden border-0 shadow-lg my-5">
                            <div class="card-body p-0" style="width: 700px;margin-left: 300px">
                                <!-- Nested Row within Card Body -->

                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Edit User account!</h1>
                                        <?php
                                    if (isset($message)) {
                                            foreach ($message as $message) {
                                                echo '<div class="message">' . $message . '</div>';
                                            }
                                        } elseif (isset($message1)) {
                                            foreach ($message1 as $message1) {
                                                echo '<div class="message1">' . $message1 . '</div>';
                                            }
                                        }
                                        ?>
                                    </div>
                                    <form method="post" enctype="multipart/form-data">
                                         <div class="form-group">
                                            <input type="text" name="name" class="form-control form-control-user" id="exampleInputName" placeholder="Enter User Name...">
                                        </div>

                                        <!-- Email Input -->
                                        <div class="form-group">
                                            <input type="email" name="email" class="form-control form-control-user" id="exampleInputEmail" placeholder="Enter Email Address...">
                                        </div>

                                        <!-- Phone Number Input -->
                                        <div class="form-group">
                                            <input type="text" name="phone"  class="form-control form-control-user" id="exampleInputPhone" placeholder="Phone Number">
                                        </div>

                                        <!-- Password Inputs -->
                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <input type="password" name="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Old Password">
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="password" name="cpassword" class="form-control form-control-user" id="exampleNewPassword" placeholder="New Password">
                                            </div>
                                        </div>

                                        <!-- Dropdown List -->
                                        <div class="form-group" name="user_type[]">
                                            <select class="form-control " name="user_type[]" style="height: 50px;border-radius: 50px" id="exampleDropdown" name="userType" required>
                                                <option value="" disabled selected>Select User Type</option>
                                                <option value="supervisor">Supervisors</option>
                                                <option value="employee">Employees</option>
                                            </select>
                                        </div>
                                          <div class="form-group mb-3">
                                            <label for="attendanceTime" class="text-primary">allowed Delay</label>
                                            <input type="number" class="form-control" id="attendanceTime" name="Delay" required>

                                            <div class="invalid-feedback" data-sb-feedback="attendanceTime:required">Attendance time is required.</div>


                                        </div>

                                        <div class="form-group" name="shift_type[]">
                                            <select class="form-control " name="shift_type[]" style="height: 50px;border-radius: 50px" id="exampleDropdown" name="userType" required>
                                                <option value="" disabled selected>Select User Type</option>
                                                <?php
                                                $shifts = mysqli_query($conn, "SELECT * FROM `shifts` ") or die('Query failed: ' . mysqli_error($conn));

                                                if (isset($shifts) && mysqli_num_rows($shifts) > 0) {
                                                    while ($shift = mysqli_fetch_assoc($shifts)) {
                                                        echo '<option value="' . $shift['shift_id'] . '">' . $shift['Name'] . '</option>';
                                                    }
                                                } else {
                                                    echo '<option value="" disabled>There are no shifts available</option>';
                                                }
                                                ?>

                                            </select>
                                        </div>


                                        <!-- Add Button -->
                                        <button class="btn" style="background: #006079;color: white" name="submit" type="submit">
                                           <img src="img/icons/edit (1).png" style="width: 15px;height: 15px;">
                                            Edit
                                        </button>
                                        
                                        <hr>

                                    </form>
                                    <hr>

                                </div>

                            </div>
                        </div>

                        <!-- /.container-fluid -->

                    </div>
                    <!-- /.container-fluid -->
                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Facial Recognition Attendance System</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->



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
        <div class="dialog-container" id="deleteDialog">
            <span id="closeBtn"  onclick="closeDeleteDialog()">&times;</span>
            <h2>Delete </h2>
            <!-- Add your edit form fields here -->
            <div class="modal-body">Select "Delete" below if you are ready to Delete User's account.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeDeleteDialog()">Cancel</button>
                <button class="btn " style="background: #006079;color: white" onclick="deleteItem()" >Delete</button>
            </div>

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
                alert('User Blocked!');
                closeDeleteDialog();
            }
        </script>

        <!-- Bootstrap core JavaScript-->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>
        <script src="js/jquery.easing.min.js"></script>
        <script src="js/sb-admin-2.min.js"></script>
        <script src="js/Chart.min.js"></script>
        <script src="js/demo/chart-area-demo.js"></script>
        <script src="js/demo/chart-pie-demo.js"></script>

    </body>

</html>