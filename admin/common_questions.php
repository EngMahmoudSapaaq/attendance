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
$faqs = mysqli_query($conn, "SELECT * FROM `faqs` WHERE admin_id = '$admin_id'") or die('Query failed: ' . mysqli_error($conn));

if (isset($_GET['faqs_id'] )) {
    
    $faqs_id= $_GET['faqs_id'] ?? null;
    mysqli_query($conn ,"DELETE FROM faqs WHERE faqs_id = '$faqs_id'");
     header('location:common_questions.php');
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

                <li class="nav-item ">
                    <a class="nav-link" href="home.php">
                        <img src="img/team (2).png" style="width: 21px;height: 23px">
                        <span>Users</span>
                    </a>
                </li>

                <hr class="sidebar-divider">


                <li class="nav-item active">
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
                        <h1 class="h3 mb-2 text-gray-800"><img src="img/people.png" style="width: 30px;height: 30px"> FAQs</h1>
                        <p class="mb-4">"User Management System" is more than just a script; it's a comprehensive solution for handling user-related operations, fostering a positive user experience, and maintaining the security and integrity of your application.</p>
                        <div class="add-course-container" style="width: 130px;margin-left: 1100px">
                            <a href="addQuestion.php" class="add-course-btn" style="color: white;text-decoration: none;" name="add">
                                <img src="img/add.png" alt="Add Icon">
                                Add 
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
                                                    <th>Image</th>
                                                    <th>Question</th>
                                                    <th>Answer</th>
                                                    <th>actions</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Image</th>
                                                    <th>Question</th>
                                                    <th>Answer</th>
                                                    <th>actions</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <?php
                                            if (isset($faqs) && mysqli_num_rows($faqs) > 0) {
                                                while ($faq = mysqli_fetch_assoc($faqs)) {
                                                    echo ' <tr>
                                                    <td > <img class="img-profile rounded-circle" style="width:90px;height: 70px;object-fit: cover;" src="img/ques.jpg"></td>
                                                    <td>'.$faq['Question'].'?</td>
                                                    <td>Answer : .... <div class="dropdown no-arrow">
                                                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class=""> see more</i>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                                                 aria-labelledby="dropdownMenuLink">
                                                                <div class="dropdown-header">Description :</div>
                                                                <p style="padding: 15px">'.$faq['Answer'].'</p>

                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="text-center">
                                                        <a class="btn" href="common_questions.php?faqs_id='.$faq['faqs_id'].'" style="background: red;color: white"><img src="img/icons/bin (1).png" style="width: 15px;height: 15px;margin-right: 5px"></a>
                                                        <a class="btn" href="editQuestion.php?faqs_id='.$faq['faqs_id'].'" style="background: green;color: white"><img src="img/icons/edit (1).png" style="width: 15px;height: 15px;margin-right: 5px"></a>
                                                       
                                                    </td>
                                                   

                                                </tr>';
                                                    
                                                }
                                                } else {
                                                     echo ' <td></td>
                                                    <td>there are no data</td>
                                                     <td></td>
                                                      <td></td>';
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
                alert('Done!');
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