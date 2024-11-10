<?php
include 'config.php';
session_start();

if (isset($_POST['submit'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);

    $admin = mysqli_query($conn, "SELECT * FROM `admin` WHERE email = '$email' AND password = '$pass'") or die('query failed');
    $users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email' AND password = '$pass'") or die('query failed');
    $supervisors = mysqli_query($conn, "SELECT * FROM `supervisors` WHERE email = '$email' AND password = '$pass'") or die('query failed');
    if (mysqli_num_rows($admin) > 0) {
        $row = mysqli_fetch_assoc($admin);
        $_SESSION['admin_id'] = $row['admin_Id'];
        header('location: ../admin/home.php');
    }elseif (mysqli_num_rows($users) > 0) {
        $row = mysqli_fetch_assoc($users);
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['type'] = 'user';
        header('location: ../Employees/home.php');
    }elseif (mysqli_num_rows($supervisors) > 0) {
        $row = mysqli_fetch_assoc($supervisors);
        $_SESSION['supervisor_id'] = $row['supervisor_id'];
        header('location: ../supervisors/home.php');
    } else {
        $message[] = 'incorrect email or password!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Facial Recognition Attendance System</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@600;700;800&display=swap" rel="stylesheet"> 
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500&display=swap" rel="stylesheet">

        <!-- Icon Font Stylesheet -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="lib/animate/animate.min.css" rel="stylesheet">
        <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

        <!-- Customized Bootstrap Stylesheet -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="css/style.css" rel="stylesheet">
        <style>
            .page-header {
                background: linear-gradient(rgba(0, 0, 0, .7), rgba(0, 0, 0, .7)), url(img/4.jpg) center center no-repeat;
                background-size: cover;
                margin-bottom: 6rem;
            }
            .footer {
                background: linear-gradient(rgba(0, 0, 0, .7), rgba(0, 0, 0, .7)), url(img/4.jpg) center center no-repeat;
                background-size: cover;
                color: rgba(255, 255, 255, .7);
                margin-top: 6rem;
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
        </style>
    </head>

    <body>
        <!-- Spinner Start -->
        <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-grow text-primary" role="status"></div>
        </div>
        <!-- Spinner End -->

        <!-- Topbar Start -->
        <div class="container-fluid topbar-top " style="background: #61c3df;">
            <div class="container">
                <div class="d-flex justify-content-between topbar py-2">

                </div>
            </div>
        </div>
        <!-- Topbar End -->


        <!-- Navbar Start -->
        <div class="container-fluid " style="background: #006079">
            <div class="container">
                <nav class="navbar navbar-dark navbar-expand-lg py-lg-0">
                    <a href="index.php" class="navbar-brand">
                        <h1 class="text-primary mb-0 display-5"><img src="img/logo-removebg-preview.png" class="text-primary ms-2" style="width: 150px;height: 100px"/><img src="img/logo1-removebg-preview.png" class="text-primary ms-2" style="width: 250px;height: 60px"/></h1>
                    </a>
                    <button class="navbar-toggler bg-primary" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars text-dark"></span>
                    </button>
                    <div class="collapse navbar-collapse me-n3" id="navbarCollapse">
                        <div class="navbar-nav ms-auto">
                            <a href="index.php" class="nav-item nav-link" style="color:#ffffff;">Home</a>
                            <a href="index.php#about" class="nav-item nav-link" style="color:#ffffff">About</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" style="color:#ffffff">User Manual</a>
                                <div class="dropdown-menu m-0 bg-primary">
                                    <a href="index.php#Userguide" class="dropdown-item bg-primary" style="color:#ffffff">User guide</a>
                                    <a href="index.php#FAQs" class="dropdown-item bg-primary" style="color:#ffffff">FAQs</a>
                                </div>
                            </div>
                            <a href="index.php#Contact" class="nav-item nav-link" style="color:#ffffff">Contact</a>
                            <a href="login.php" style="margin-left: 250px" class=""><button type="button" style="background: #61c3df;color: #ffffff" class="px-5 py-3 btn  border-2 rounded-pill animated slideInDown"><i class="fas fa-sign-in-alt "></i> LOgin</button></a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <!-- Navbar End -->


        <!-- Page Header Start -->
        <div class="container-fluid page-header py-5">
            <div class="container text-center py-5">
                <h1 class="display-2 text-white mb-4 animated slideInDown">Login</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-0 animated slideInDown">
                        <li class="breadcrumb-item"><a href="#" style="color: #006079;">Home</a></li>
                        <li class="breadcrumb-item"><a href="#" style="color: #006079;">Pages</a></li>
                        <li class="breadcrumb-item text-white" aria-current="page">Login</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header End -->


        <!-- Login Start -->
<div class="container-fluid py-5" id="Login">
    <div class="container py-5">
        <div class="text-center mb-5 wow fadeInUp" data-wow-delay=".3s">
            <h5 class="mb-2 px-3 py-1 rounded-pill d-inline-block border border-2 border-primary" style="color: #ffffff; background: #61c3df;">Welcome Back</h5>
            <h1 class="display-5 w-50 mx-auto">Login to Your Account</h1>
        </div>
        <div class="row g-5 mb-5">
            <!-- Image Section with Creative Box -->
            <div class="col-lg-6 wow fadeInUp" data-wow-delay=".3s">
                <div class="image-box h-100" style="border: 2px solid #61c3df; border-radius: 15px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1); padding: 20px; background-color: #f9f9f9; transition: transform 0.3s ease;">
                    <img src="img/contact-removebg-preview.png" style="width: 100%; height:400px;" alt="Login Image">
                </div>
            </div>

            <!-- Login Form Section with Creative Box -->
            <div class="col-lg-6 wow fadeInUp" data-wow-delay=".5s" >
                <div class="login-box rounded" style="height:500px;border: 2px solid #61c3df; border-radius: 15px; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1); padding: 30px; background-color: #f9f9f9; transition: transform 0.3s ease;">
                    <!-- Username Field -->
                    <h2 style="margin-bottom: 50px">Login </h2>
                     <?php
                        if (isset($message)) {
                            foreach ($message as $message) {
                                echo '<div class="message">' . $message . '</div>';
                            }
                        }
                        ?>
                    <form method="post" enctype="multipart/form-data">
                    <div class="mb-4" >
                        <label for="email">Email *</label>
                        <input type="email" name="email"  class="form-control p-3" placeholder="Email">
                    </div>

                    <!-- Password Field -->
                    <div class="mb-4">
                        <label for="password">Password *</label>
                        <input type="password" name="password"  class="form-control p-3" placeholder="Password">
                    </div>

                    <!-- Login Button -->
                   <input  class="btn border-0 py-3 px-4 rounded-pill" style="background: #61c3df; color: #ffffff;" id="submitButton" value="Login" name="submit" type="submit">

                    <!-- Forgot Password Link -->
                    <div class="mt-3">
                        <a href="password-forget.php" style="color: #006079;">Forgot Password?</a>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Login End -->



        <!-- Footer Start -->
        <div class="container-fluid footer py-5 wow fadeIn" data-wow-delay=".3s">
            <div class="container py-5">
                <div class="row g-4 footer-inner">
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            <h4 class="text-white fw-bold mb-4">About Us.</h4>
                            <p>Assign Roles: Administrators can assign different roles, such as supervisors or staff, with unique permissions and access rights tailored to their responsibilities.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            <h4 class="text-white fw-bold mb-4">Usefull Link</h4>
                            <div class="d-flex flex-column align-items-start">

                                <a class="btn  ps-0" href="index.php"><i class="fa fa-check me-2"></i>Home</a>
                                <a class="btn  ps-0" href="index.php#about"><i class="fa fa-check me-2"></i>About Us</a>
                                <a class="btn  ps-0" href="index.php#Contact"><i class="fa fa-check me-2"></i>Contact Us</a>
                                <a class="btn  ps-0" href="index.php#FAQs"><i class="fa fa-check me-2"></i>FAQs</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            <h4 class="text-white fw-bold mb-4">Services Link</h4>
                            <div class="d-flex flex-column align-items-start">
                                <a class="btn  ps-0" href="index.php#Services"><i class="fa fa-check me-2"></i>Attendance Management</a>
                                <a class="btn  ps-0" href="index.php#Services"><i class="fa fa-check me-2"></i>Reports and Statistics</a>
                                <a class="btn ps-0" href="index.php#Services"><i class="fa fa-check me-2"></i>Complaints and Suggestions</a>
                                <a class="btn  ps-0" href="index.php#Services"><i class="fa fa-check me-2"></i>Support and Assistance</a>
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            <h4 class="text-white fw-bold mb-4">Contact Us</h4>
                            <a  class="btn  w-100 text-start ps-0 pb-3 border-bottom rounded-0"><i class="fa fa-print me-3"></i>  P.BOX 145111 Zip 4545</a>
                            <a  class="btn  w-100 text-start ps-0 py-3 border-bottom rounded-0"><i class="fa fa-phone-alt me-3"></i>+96611-46-77-580</a>
                            <a  class="btn  w-100 text-start ps-0 py-3 border-bottom rounded-0"><i class="fa fa-envelope me-3"></i>info@ksu.edu.sa</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->



        <!-- Copyright Start -->
        <div class="container-fluid copyright py-4" style="background: #006079;">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 text-center text-md-start mb-3 mb-md-0">
                        <a class="navbar-brand">
                            <h1 class="text-primary mb-0 display-5"><img src="img/logo-removebg-preview.png" class="text-primary ms-2" style="width: 150px;height: 100px"/><img src="img/logo1-removebg-preview.png" class="text-primary ms-2" style="width: 250px;height: 60px"/></h1>
                        </a>                   
                    </div>

                    <div class="col-md-4 my-auto text-center text-md-end text-white" style="margin-left: 200px;color: #ffffff">
                        <!--/*** This template is free as long as you keep the below author’s credit link/attribution link/backlink. ***/-->
                        <!--/*** If you'd like to use the template without the below author’s credit link/attribution link/backlink, ***/-->
                        <!--/*** you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". ***/-->
                        <div class="small text-white">All rights reserved &copy; 2024 - Facial Recognition Attendance System</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Copyright End -->


        <!-- Back to Top -->
        <a href="#" class="btn  rounded-circle border-3 back-to-top" style="background: #61c3df;color: #ffffff"><i class="fa fa-arrow-up"></i></a>

        <!-- JavaScript Libraries -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="lib/wow/wow.min.js"></script>
        <script src="lib/easing/easing.min.js"></script>
        <script src="lib/waypoints/waypoints.min.js"></script>
        <script src="lib/owlcarousel/owl.carousel.min.js"></script>

        <!-- Template Javascript -->
        <script src="js/main.js"></script>
    </body>

</html>