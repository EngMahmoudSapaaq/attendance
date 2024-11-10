<?php
include 'config.php';

$supervisors_total = 0;
$users_total = 0;
$attendance_total = 0;

$supervisors = mysqli_query($conn, "SELECT * FROM `supervisors` ") or die('Query failed: ' . mysqli_error($conn));
$users = mysqli_query($conn, "SELECT * FROM `users`") or die('Query failed: ' . mysqli_error($conn));
$attendance = mysqli_query($conn, "SELECT * FROM `attendance`") or die('Query failed: ' . mysqli_error($conn));

$supervisors_total = mysqli_num_rows($supervisors);
$users_total = mysqli_num_rows($users);
$attendance_total = mysqli_num_rows($attendance);


//------------------------------------
$supervisors_active = 0;
$users_active = 0;
$absent_total = 0;
$supervisors_active = mysqli_query($conn, "SELECT * FROM `supervisors` WHERE status = 'active'") or die('Query failed: ' . mysqli_error($conn));
$users_active = mysqli_query($conn, "SELECT * FROM `users` WHERE status = 'active'") or die('Query failed: ' . mysqli_error($conn));
$absent = mysqli_query($conn, "SELECT * FROM `absent`") or die('Query failed: ' . mysqli_error($conn));

$supervisors_active = mysqli_num_rows($supervisors_active);
$users_active = mysqli_num_rows($users_active);
$absent_total = mysqli_num_rows($absent);
//------------------------------------
$faqs = mysqli_query($conn, "SELECT * FROM `faqs` ") or die('Query failed: ' . mysqli_error($conn));


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
            .services-inner-icona img {
                transition: transform 0.6s ease;
                background: #61c3df;
                border-radius: 100px;
                padding-right: 5px;
                width: 90px;
                height: 100px;
            }

            .services-inner-icona:hover img {
                transform: rotate(360deg);
                background: #006079;
                border-radius: 100px;
                width: 100px;
                height: 100px;
            }

            .services-item {
                padding: 25px;
                background-color: #f9f9f9;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                transition: box-shadow 0.3s ease;
            }

            .services-item:hover {
                box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
                background: none
            }

            .btn-primary {
                background-color: #61c3df;
                border-color: #61c3df;
            }
            .border-primary{
                background:  #61c3df;
            }
            dropdown-item:hover{
                background: #61c3df;
                color: white
            }
            bg-dark{
                background: #61c3df;
            }
            .about-img {
                width: 100%;
                height: 100%;
                position: relative;
                padding: 30px 30px 30px 30px ;
                overflow: hidden;
            }

            .about-img::before {
                content: "";
                width: 100%;
                height: 40%;
                background: #61c3df;
                position: absolute;
                top: 0px;
                left: 0px;
                z-index: 1;
                border-radius: 10px 10px 10px 10px;
            }


            .about-img::after {
                content: "";
                width: 100%;
                height: 60%;
                background: #006079;
                position: absolute;
                bottom: 0px;
                left: 0px;
                z-index: 1;
                border-radius: 10px 10px 10px 10px;
            }
            .rotate-left {
                width: 150px;
                height: 100px;
                position: absolute;
                top: 28%;
                left: -10%;
                rotate: 25deg;
                z-index: 2;
            }

            .rotate-right {
                width: 150px;
                height: 100px;
                position: absolute;
                top: 28%;
                right: -10%;
                rotate: -25deg;
                z-index: 2;
            }
            /*** Project Start ***/
            .project-item {
                width: 100%;
                height: 100%;
                position: relative;
                padding: 30px 30px 30px 30px ;
                overflow: hidden;
            }

            .project-item::before {
                content: "";
                width: 100%;
                height: 40%;
                background: #61c3df;
                position: absolute;
                top: 0px;
                left: 0px;
                z-index: 1;
                border-radius: 10px 10px 10px 10px;
            }


            .project-item::after {
                content: "";
                width: 100%;
                height: 60%;
                background: #006079;
                position: absolute;
                bottom: 0px;
                left: 0px;
                z-index: 1;
                border-radius: 10px 10px 10px 10px;
            }

            .project-left {
                width: 180px;
                height: 70px;
                position: absolute;
                top: 22%;
                left: -18%;
                rotate: 30deg;
                z-index: 2;
            }

            .project-right {
                width: 180px;
                height: 70px;
                position: absolute;
                top: 22%;
                right: -18%;
                rotate: -30deg;
                z-index: 2;
            }

            .project-item img {
                position: relative;
                width: 100%;
                height: 100%;
                z-index: 2;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .project-item a {
                position: absolute;
                padding: 25px;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) scale(0) rotate(-360deg);
                border-radius: 10px;
                z-index: 3;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: .5s;
                opacity: 0;
                color: #ffffff;
            }

            .project-item:hover a {
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) scale(1) rotate(0deg);
                background: #006079;
                color: #ffffff;
                opacity: 1;

            }
            .call-to-action {
                background: linear-gradient(rgba(0, 0, 0, .7), rgba(0, 0, 0, .7)), url(img/ba.jpg) center center no-repeat;
                background-size: cover;
            }
            .team-item .team-content {
                box-shadow: inset 0 0 0 0 #006079;
                transition: 1s;

            }

            .team-item:hover .team-content {
                box-shadow: inset 550px 0 0 0 #61c3df;
                color: #ffffff !important;
            }
            .footer {
                background: linear-gradient(rgba(0, 0, 0, .7), rgba(0, 0, 0, .7)), url(img/4.jpg) center center no-repeat;
                background-size: cover;
                color: rgba(255, 255, 255, .7);
                margin-top: 6rem;
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
                            <a href="index.php" class="nav-item nav-link" style="color:#00303E;font-weight: bold;font-size: 18px">Home</a>
                            <a href="#about" class="nav-item nav-link" style="color:#ffffff">About</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" style="color:#ffffff">User Manual</a>
                                <div class="dropdown-menu m-0 bg-primary">
                                    <a href="#Userguide" class="dropdown-item bg-primary" style="color:#ffffff">User guide</a>
                                    <a href="#FAQs" class="dropdown-item bg-primary" style="color:#ffffff">FAQs</a>
                                </div>
                            </div>
                            <a href="#Contact" class="nav-item nav-link" style="color:#ffffff">Contact</a>
                            <a href="login.php" style="margin-left: 250px" class=""><button type="button" style="background: #61c3df;color: #ffffff" class="px-5 py-3 btn  border-2 rounded-pill animated slideInDown"><i class="fas fa-sign-in-alt "></i> LOgin</button></a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <!-- Navbar End -->


        <!-- Carousel Start -->
        <div class="container-fluid carousel px-0 mb-5 pb-5">
            <div id="carouselId" class="carousel slide" data-bs-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-bs-target="#carouselId" data-bs-slide-to="0" class="active" aria-current="true" aria-label="First slide"></li>
                    <li data-bs-target="#carouselId" data-bs-slide-to="1" aria-label="Second slide"></li>
                </ol>
                <div class="carousel-inner" role="listbox">
                    <div class="carousel-item active">
                        <img src="img/1.jpg" class="img-fluid w-100" alt="First slide">
                        <div class="carousel-caption">
                            <div class="container carousel-content">
                                <h4 class="text-white mb-4 animated slideInDown">No 1 Pest facial preparation</h4>
                                <h1 class="text-white display-1 mb-4 animated slideInDown">We make it easy for you to attend daily</h1>
                                <a href="#about" class="me-2"><button type="button" style="background: #61c3df;color: #ffffff"  class="px-5 py-3 btn  border-2 rounded-pill animated slideInDown">Read More</button></a>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="img/2.jpg" class="img-fluid w-100" alt="Second slide">
                        <div class="carousel-caption">
                            <div class="container carousel-content">
                                <h4 class="text-white mb-4 animated slideInDown">No 1 Pest facial preparation</h4>
                                <h1 class="text-white display-1 mb-4 animated slideInDown">Real-Time Attendance Monitoring and Reporting</h1>
                                <a href="#about" class="me-2"><button type="button" style="background: #61c3df;color: #ffffff" class="px-5 py-3 btn  border-2 rounded-pill animated slideInDown">Read More</button></a>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev btn  border border-2 border-start-0 border-primary" style="background: #61c3df;color: #ffffff" type="button" data-bs-target="#carouselId" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next  border border-2 border-end-0 border-primary" style="background: #61c3df;color: #ffffff" type="button" data-bs-target="#carouselId" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
        <!-- Carousel End -->





        <!-- About Start -->
        <div class="container-fluid py-5" id="about">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-6 col-md-12 wow fadeInUp" data-wow-delay=".3s">
                        <div class="about-img">
                            <div class="rotate-left " style="background: #61c3df;"></div>
                            <div class="rotate-right " style="background: #61c3df;"></div>
                            <img src="img/logo-removebg-preview.png" class="img-fluid h-100" alt="img">
                            <div class="bg-white experiences">
                                <h1 class="display-3">78</h1>
                                <h6 class="fw-bold">Number of users</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 wow fadeInUp" data-wow-delay=".6s">
                        <div class="about-item overflow-hidden">
                            <h5 class="mb-2 px-3 py-1  rounded-pill d-inline-block border border-2 border-primary" style="color: #ffffff">About System</h5>
                            <h1 class="display-5 mb-2">Revolutionizing Attendance with Facial Recognition Technology</h1>
                            <p class="fs-5" style="text-align: justify;">The Facial Recognition Attendance System is a modern, efficient solution designed to streamline attendance management within university boundaries. This system leverages facial recognition technology to ensure accurate and secure attendance tracking for staff and supervisors while offering comprehensive user and role management features for administrators.</p>
                            <div class="row">
                                <div class="col-3">
                                    <div class="text-center">
                                        <div class="p-4  rounded d-flex" style="background: #61c3df;align-items: center; justify-content: center;">
                                            <i class="fas fa-camera fa-4x " style="color: #ffffff"></i>
                                        </div>
                                        <div class="my-2">
                                            <h5>Open </h5>
                                            <h5>Camera</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center">
                                        <div class="p-4  rounded d-flex" style="background: #61c3df;align-items: center; justify-content: center;">
                                            <i class="fas fa-user-check fa-4x " style="color: #ffffff"></i>
                                        </div>
                                        <div class="my-2">
                                            <h5>Scan </h5>
                                            <h5>Face</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center">
                                        <div class="p-4  rounded d-flex" style="background: #61c3df;align-items: center; justify-content: center;">
                                            <i class="fas fa-id-card fa-4x " style="color: #ffffff"></i>
                                        </div>
                                        <div class="my-2">
                                            <h5>Verify </h5>
                                            <h5>Identity</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="text-center">
                                        <div class="p-4  rounded d-flex" style="background: #61c3df;align-items: center; justify-content: center;">
                                            <i class="fas fa-clipboard-check fa-4x " style="color: #ffffff"></i>
                                        </div>
                                        <div class="my-2">
                                            <h5>Record </h5>
                                            <h5> Attendance</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="#Services" class="me-2"><button type="button" style="background: #61c3df;color: #ffffff" class="btn  border-0 rounded-pill px-4 py-3 mt-5">Find Services</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->


        <!-- Services Start -->
        <div class="container-fluid services py-5" id="Services" style="min-height: 100vh; display: flex; align-items: center;">
            <div class="container text-center">
                <div class="text-center mb-4">
                    <h5 class="px-3 py-1 text-dark rounded-pill d-inline-block border border-2 border-primary">Our Services</h5>
                    <h1 class="display-6">Key Features</h1>
                </div>
                <div class="row g-4">
                    <!-- Service Item 1 -->
                    <div class="col-xxl-3 col-lg-6 col-md-6 col-sm-12">
                        <div class="bg-light rounded p-4 services-item">
                            <div class="mb-4 rounded-circle services-inner-icona">
                                <img src="../assets/1-removebg-preview.png"  />
                            </div>
                            <h4>Attendance</h4>
                            <p>Face scan check-in/out for quick and automatic attendance.</p>
                        </div>
                    </div>

                    <!-- Service Item 2 -->
                    <div class="col-xxl-3 col-lg-6 col-md-6 col-sm-12">
                        <div class="bg-light rounded p-4 services-item">
                            <div class="mb-4 rounded-circle services-inner-icona">
                                <img src="../assets/1-removebg-preview.png"  />
                            </div>
                            <h4>Reports</h4>
                            <p>Generate attendance reports and export data to Excel/PDF.</p>
                        </div>
                    </div>

                    <!-- Service Item 3 -->
                    <div class="col-xxl-3 col-lg-6 col-md-6 col-sm-12">
                        <div class="bg-light rounded p-4 services-item">
                            <div class="mb-4 rounded-circle services-inner-icona">
                                <img src="../assets/1-removebg-preview.png"  />
                            </div>
                            <h4>Feedback</h4>
                            <p>Submit suggestions or complaints and receive responses.</p>
                        </div>
                    </div>

                    <!-- Service Item 4 -->
                    <div class="col-xxl-3 col-lg-6 col-md-6 col-sm-12">
                        <div class="bg-light rounded p-4 services-item">
                            <div class="mb-4 rounded-circle services-inner-icona">
                                <img src="../assets/1-removebg-preview.png"  />
                            </div>
                            <h4>Support</h4>
                            <p>Access the user guide and FAQs for assistance.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Services End -->



        <!-- Project Start -->
        <div class="container-fluid py-5" id="Userguide">
            <div class="container py-5">
                <div class="text-center mb-5 wow fadeInUp" data-wow-delay=".3s">
                    <h5 class="mb-2 px-3 py-1 rounded-pill d-inline-block border border-2 border-primary" style="color: #ffffff">Our User Guide</h5>
                    <h1 class="display-5">Support and Assistance</h1>
                </div>
                <div class="row g-5">
                    <div class="col-xxl-4 col-lg-6 col-md-6 col-sm-12 wow fadeInUp" data-wow-delay=".3s">
                        <div class="project-item">
                            <div class="project-left " style="background: #61c3df;"></div>
                            <div class="project-right " style="background: #61c3df;"></div>
                            <img src="img/login.JPG" class="img-fluid h-100" alt="img">
                            <a class="fs-4 fw-bold text-center"><p style="color: #ffffff">Log in to the site</p></a>
                        </div>
                    </div>
                    <div class="col-xxl-4 col-lg-6 col-md-6 col-sm-12 wow fadeInUp" data-wow-delay=".5s">
                        <div class="project-item">
                            <div class="project-left " style="background: #61c3df;"></div>
                            <div class="project-right" style="background: #61c3df;"></div>
                            <img src="img/camera.JPG" class="img-fluid h-100" alt="img">
                            <a class="fs-4 fw-bold text-center" style="color: white"><p style="color: #ffffff">Open Camera and Scan Face</p></a>
                        </div>
                    </div>
                    <div class="col-xxl-4 col-lg-6 col-md-6 col-sm-12 wow fadeInUp" data-wow-delay=".7s">
                        <div class="project-item">
                            <div class="project-left " style="background: #61c3df;"></div>
                            <div class="project-right " style="background: #61c3df;"></div>
                            <img src="img/Identity.JPG" class="img-fluid h-100" alt="img">
                            <a  class="fs-4 fw-bold text-center"><p style="color: #ffffff">Verify Identity</p></a>
                        </div>
                    </div>
                    <div class="col-xxl-4 col-lg-6 col-md-6 col-sm-12 wow fadeInUp" data-wow-delay=".3s">
                        <div class="project-item">
                            <div class="project-left " style="background: #61c3df;"></div>
                            <div class="project-right " style="background: #61c3df;"></div>
                            <img src="img/atendence.JPG" class="img-fluid h-100" alt="img">
                            <a  class="fs-4 fw-bold text-center"><p style="color: #ffffff">Follow up on attendance and departure</p></a>
                        </div>
                    </div>
                    <div class="col-xxl-4 col-lg-6 col-md-6 col-sm-12 wow fadeInUp" data-wow-delay=".5s">
                        <div class="project-item">
                            <div class="project-left " style="background: #61c3df;"></div>
                            <div class="project-right " style="background: #61c3df;"></div>
                            <img src="img/complain.JPG" class="img-fluid h-100" alt="img">
                            <a  class="fs-4 fw-bold text-center"><p style="color: #ffffff">View other user's complaints</p></a>
                        </div>
                    </div>
                    <div class="col-xxl-4 col-lg-6 col-md-6 col-sm-12 wow fadeInUp" data-wow-delay=".7s">
                        <div class="project-item">
                            <div class="project-left " style="background: #61c3df;"></div>
                            <div class="project-right " style="background: #61c3df;"></div>
                            <img src="img/complain1.JPG" class="img-fluid h-100" alt="img">
                            <a  class="fs-4 fw-bold text-center" style="color: #ffffff"><p style="color: #ffffff">You can send your complaint</p></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Project End -->


        <!-- Blog Start -->

        <!-- Blog End -->


   <!-- Call To Action Start -->
<div class="container-fluid py-5 call-to-action wow fadeInUp" data-wow-delay=".3s" style="margin: 6rem 0;">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <div style="border-radius: 50%; overflow: hidden; background: white; width: 100%; height: 100%; max-width: 450px; max-height: 450px; margin: auto;">
                    <img src="img/abo-removebg-preview.png" style="width: 100%; height: 100%; object-fit: contain; transform: scale(0.75);" alt="Facial Recognition">
                </div>
            </div>
            <div class="col-lg-6 my-auto">
                <div class="text-start mt-4">
                    <h1 class="pb-4 text-white">Facial Recognition for Accurate Attendance Tracking: Ensure secure and error-free attendance with facial verification technology.</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Call To Action End -->




        <!-- pricing Start -->
        <div class="container-fluid py-5">
            <div class="container py-5">
                <div class="text-center mb-5 wow fadeInUp" data-wow-delay=".3s">
                    <h5 class="mb-2 px-3 py-1  rounded-pill d-inline-block border border-2 border-primary" style="color: #ffffff">System Statistics</h5>
                    <h1 class="display-5 w-50 mx-auto">Facial Recognition Attendance System Insights</h1>
                </div>
                <div class="row g-5">
                    <div class="col-lg-4 col-md-6 col-sm-12 wow fadeInUp" data-wow-delay=".3s">
                        <div class="rounded bg-light pricing-item">
                            <div class=" py-3 px-5 text-center rounded-top border-bottom border-dark" style="background: #006079">
                                <h2 class="m-0" style="color: #ffffff">Attendance</h2>
                            </div>
                            <div class="px-4 py-5 text-center  pricing-label mb-2" style="background: #006079">
                                <p class="mb-0 text-white"><i class="fa fa-check text-success me-2"></i>Present: <?php if(($attendance_total)>0){ echo (($attendance_total)*100)/(($attendance_total)+($absent_total));} else {echo 1;}  ?>%</p>
                                <p class="mb-0 text-white"><i class="fa fa-times text-danger me-2"></i>Absent: <?php if(($absent_total)>0){ echo (($absent_total)*100)/(($attendance_total)+($absent_total));} else {echo 1;}  ?>%</p>
                            </div>
                            <div class="px-4 text-center  statistic-label mb-2" style="height: 200px;width: 400px;margin-left: 80px">
                                <canvas id="attendanceChart" ></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 wow fadeInUp" data-wow-delay=".3s">
                        <div class="rounded bg-light pricing-item">
                            <div class=" py-3 px-5 text-center rounded-top border-bottom border-dark" style="background: #0090b8">
                                <h2 class="m-0" style="color: #ffffff">Supervisors</h2>
                            </div>
                            <div class="px-4  py-5 text-center  pricing-label mb-2" style="background: #0090b8">
                                <p class="mb-0 text-white"><i class="fa fa-user-shield text-success me-2"></i>Total Supervisors:  <?php if(($supervisors_total)>0){ echo $supervisors_total;} else {echo 1;} ?></p>
                                <p class="mb-0 text-white"><i class="fa fa-user-shield text-info me-2"></i>Active Supervisors: <?php if(( $supervisors_active)>0){ echo $supervisors_active;} else {echo 1;} ?></p>

                            </div>
                            <div class="px-4  text-center  statistic-label statistic-featured mb-2" style="height: 200px;width: 400px">
                                <canvas id="supervisorsChart" ></canvas><br><br>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 wow fadeInUp" data-wow-delay=".3s">
                        <div class="rounded bg-light pricing-item">
                            <div class=" py-3 px-5 text-center rounded-top border-bottom border-dark" style="background: #61c3df">
                                <h2 class="m-0" style="color: #ffffff">Users</h2>
                            </div>
                            <div class="px-4  py-5 text-center  pricing-label mb-2" style="background: #61c3df">
                                <p class="mb-0 text-white"><i class="fa fa-users text-success me-2"></i>Total Users: <?php if(($users_total)>0){ echo $users_total;} else {echo 1;} ?></p>
                                <p class="mb-0 text-white"><i class="fa fa-users-cog text-info me-2"></i>Active Users: <?php if(($users_active)>0){ echo $users_active;} else {echo 1;} ?></p>
                            </div>
                            <div class="px-4  text-center  statistic-label mb-2" style="height: 200px;width: 400px;margin-left: 80px">
                                <canvas id="usersChart" ></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pricing End -->






        <!-- Team Start -->
        <div class="container-fluid py-5">
            <div class="container py-5">
                <div class="text-center mb-5 wow fadeInUp" data-wow-delay=".3s">
                    <h5 class="mb-2 px-3 py-1 rounded-pill d-inline-block border border-2 border-primary" style="color: #ffffff">Our Team</h5>
                    <h1 class="display-5 w-50 mx-auto">Our Team Members</h1>
                </div>
                <div class="row g-4">
                    <!-- Team Member 1 -->
                    <div class="col-xxl-3 col-lg-6 col-md-6 col-sm-12 wow fadeInUp" data-wow-delay=".3s">
                        <div class="rounded team-item h-100 d-flex flex-column">
                            <img src="img/3_1.jpg" class="img-fluid rounded-top" style="height: 300px; object-fit: cover;" alt="Fahad Eid Hadid Almutairi">
                            <div class="team-content bg-primary text-dark text-center py-3 flex-grow-1">
                                <span class="fs-4 fw-bold" style="color: #ffffff">Fahad Eid Hadid </span>
                            </div>
                        </div>
                    </div>

                    <!-- Team Member 2 -->
                    <div class="col-xxl-3 col-lg-6 col-md-6 col-sm-12 wow fadeInUp" data-wow-delay=".5s">
                        <div class="rounded team-item h-100 d-flex flex-column">
                            <img src="img/Mishary.jpg" class="img-fluid rounded-top" style="height: 300px; object-fit: cover;" alt="Mishary Riyadh Mousa Almousa">
                            <div class="team-content bg-primary text-dark text-center py-3 flex-grow-1">
                                <span class="fs-4 fw-bold" style="color: #ffffff">Mishary Riyadh Mousa </span>
                            </div>
                        </div>
                    </div>

                    <!-- Team Member 3 -->
                    <div class="col-xxl-3 col-lg-6 col-md-6 col-sm-12 wow fadeInUp" data-wow-delay=".7s">
                        <div class="rounded team-item h-100 d-flex flex-column">
                            <img src="img/Nawaf.jpg" class="img-fluid rounded-top" style="height: 300px; object-fit: cover;" alt="‏Nawaf Khalid Alotaibi">
                            <div class="team-content bg-primary text-dark text-center py-3 flex-grow-1">
                                <span class="fs-4 fw-bold" style="color: #ffffff">‏Nawaf Khalid Alotaibi</span>
                            </div>
                        </div>
                    </div>

                    <!-- Team Member 4 -->
                    <div class="col-xxl-3 col-lg-6 col-md-6 col-sm-12 wow fadeInUp" data-wow-delay=".9s">
                        <div class="rounded team-item h-100 d-flex flex-column">
                            <img src="img/Turki-removebg-preview.png" class="img-fluid rounded-top" style="height: 300px; object-fit: cover;" alt="Turki Mohammed Alqahtani">
                            <div class="team-content bg-primary text-dark text-center py-3 flex-grow-1">
                                <span class="fs-4 fw-bold" style="color: #ffffff">Turki Mohammed Alqahtani</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Team End -->



        <!-- Testiminial Start -->
        <div class="container-fluid testimonial py-5" id="FAQs">
            <div class="container py-5">
                <div class="text-center mb-5 wow fadeInUp" data-wow-delay=".3s">
                    <h5 class="mb-2 px-3 py-1 rounded-pill d-inline-block border border-2 border-primary" style="color: #ffffff">Support and Assistance</h5>
                    <h1 class="display-5 w-50 mx-auto">FAQ section are available to help employees.</h1>
                </div>
                <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay=".5s">
                    
                    <?php
                    
                    if (isset($faqs) && mysqli_num_rows($faqs) > 0) {
                     while ($faq = mysqli_fetch_assoc($faqs)) {
                         
                         echo '<div class="testimonial-item">
                        <div class="testimonial-content rounded mb-4 p-4">
                            <p class="fs-5 m-0">'.$faq['Answer'].'<br><br></p>
                        </div>
                        <div class="d-flex align-items-center  mb-4" style="padding: 0 0 0 25px;">
                            <div class="position-relative">
                                <img src="img/ques.jpg" class="img-fluid rounded-circle py-2" style="width: 130px;height: 100px" alt="">
                                <div class="position-absolute" style="top: 33px; left: -25px;">
                                    <i class="fa fa-quote-left rounded-pill bg-primary text-white p-3"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-0">'.$faq['Question'].'?</h6>
                            </div>
                        </div>
                    </div>';
                         
                     }
                     } else {
                          echo '<div class="testimonial-item">
                        <div class="testimonial-content rounded mb-4 p-4">
                            <p class="fs-5 m-0">No answers available yet<br><br></p>
                        </div>
                        <div class="d-flex align-items-center  mb-4" style="padding: 0 0 0 25px;">
                            <div class="position-relative">
                                <img src="img/ques.jpg" class="img-fluid rounded-circle py-2" style="width: 130px;height: 100px" alt="">
                                <div class="position-absolute" style="top: 33px; left: -25px;">
                                    <i class="fa fa-quote-left rounded-pill bg-primary text-white p-3"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-0">No Questions available yet</h6>
                            </div>
                        </div>
                    </div>';
                     }
                    
                    
                    ?>
                    
                    
                    
                    
                    
                    
                    
                   <!-- <div class="testimonial-item">
                        <div class="testimonial-content rounded mb-4 p-4">
                            <p class="fs-5 m-0">The system captures a live image of the user’s face through a camera and compares it with pre-enrolled facial images stored in the database.It uses advanced algorithms to analyze facial features</p>
                        </div>
                        <div class="d-flex align-items-center  mb-4" style="padding: 0 0 0 25px;">
                            <div class="position-relative">
                                <img src="img/ques.jpg" class="img-fluid rounded-circle py-2" style="width: 95px;height: 100px" alt="">
                                <div class="position-absolute" style="top: 33px; left: -25px;">
                                    <i class="fa fa-quote-left rounded-pill bg-primary text-white p-3"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-0">How does the system recognize faces?</h6>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item">
                        <div class="testimonial-content rounded mb-4 p-4">
                            <p class="fs-5 m-0">Yes, facial recognition is a secure method for verifying identity. It minimizes the risk of fraudulent attendance (such as "buddy punching") since it requires the physical presence of the person being recognized.</p>
                        </div>
                        <div class="d-flex align-items-center  mb-4" style="padding: 0 0 0 25px;">
                            <div class="position-relative">
                                <img src="img/ques.jpg" class="img-fluid rounded-circle py-2" style="width: 80px;height: 100px" alt="">
                                <div class="position-absolute" style="top: 33px; left: -25px;">
                                    <i class="fa fa-quote-left rounded-pill bg-primary text-white p-3"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-0">Is facial recognition secure?</h6>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-item">
                        <div class="testimonial-content rounded mb-4 p-4">
                            <p class="fs-5 m-0">A Facial Recognition Attendance System is an automated system that uses facial recognition technology to verify the identity of individuals and record their attendance.<br><br></p>
                        </div>
                        <div class="d-flex align-items-center  mb-4" style="padding: 0 0 0 25px;">
                            <div class="position-relative">
                                <img src="img/ques.jpg" class="img-fluid rounded-circle py-2" style="width: 130px;height: 100px" alt="">
                                <div class="position-absolute" style="top: 33px; left: -25px;">
                                    <i class="fa fa-quote-left rounded-pill bg-primary text-white p-3"></i>
                                </div>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-0">What is a Facial Recognition Attendance System?</h6>
                            </div>
                        </div>
                    </div>-->
                </div>
            </div>
        </div>
        <!-- Testiminial End -->
        <!-- Contact Start -->
        <div class="container-fluid py-5" id="Contact">
            <div class="container py-5">
                <div class="text-center mb-5 wow fadeInUp" data-wow-delay=".3s">
                    <h5 class="mb-2 px-3 py-1  rounded-pill d-inline-block border border-2 border-primary" style="color: #ffffff">Get In Touch</h5>
                    <h1 class="display-5 w-50 mx-auto">Contact for any query</h1>
                </div>
                <div class="row g-5 mb-5">
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay=".3s">
                        <div class="h-100" >
                            <img src="img/contact-removebg-preview.png" style="width: 600px;height: 500px" alt="Logo">
                        </div>
                    </div>
                    <div class="col-lg-6 wow fadeInUp" data-wow-delay=".5s">
                        <p class="mb-4">Technical Support: Instant access to support for any technical issues that users may encounter.
                        </p>
                        <div class="rounded contact-form">
                            <div class="mb-4">
                                <input type="text" class="form-control p-3" placeholder="Your Name">
                            </div>
                            <div class="mb-4">
                                <input type="email" class="form-control p-3" placeholder="Your Email">
                            </div>

                            <div class="mb-4">
                                <textarea class="w-100 form-control p-3" rows="6" cols="10" placeholder="Message"></textarea>
                            </div>
                            <button onclick="confirm('You must log in first');" class="btn  border-0 py-3 px-4 rounded-pill" style="background: #006079;color: #ffffff" type="button">Send Message</button>
                        </div>
                    </div>
                </div>
                <div class="row g-4 wow fadeInUp" data-wow-delay=".3s">
                    <div class="col-xxl-3 col-lg-6 col-md-6 col-sm-12">
                        <div class="d-flex bg-light p-3 rounded contact-btn-link">
                            <div class="flex-shrink-0 d-flex align-items-center justify-content-center bg-primary rounded-circle p-3 ms-3" style="width: 64px; height: 64px;">
                                <i class="fa fa-phone text-white"></i>
                            </div>
                            <div class="ms-3 contact-link">
                                <h4 class="text-dark">phone</h4>
                                <a >
                                    <h5 class="text-dark d-inline fs-6">+96611-46-70-000</h5>
                                </a>

                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-lg-6 col-md-6 col-sm-12">
                        <div class="d-flex bg-light p-3 rounded contact-btn-link">
                            <div class="d-flex align-items-center justify-content-center bg-primary rounded-circle p-3 ms-3" style="width: 64px; height: 64px;">
                                <i class="fas fa-fax text-white"></i>
                            </div>
                            <div class="ms-3 contact-link">
                                <h4 class="text-dark">fax</h4>
                                <a >
                                    <h5 class="text-dark d-inline fs-6">00966114677580</h5>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-lg-6 col-md-6 col-sm-12">
                        <div class="d-flex bg-light p-3 rounded contact-btn-link">
                            <div class="d-flex align-items-center justify-content-center bg-primary rounded-circle p-3 ms-3" style="width: 64px; height: 64px;">
                                <i class="fa fa-phone text-white"></i>
                            </div>
                            <div class="ms-3 contact-link">
                                <h4 class="text-dark">Call Us</h4>
                                <a class="h5 text-dark fs-6">+96611-46-77-580</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-lg-6 col-md-6 col-sm-12">
                        <div class="d-flex bg-light p-3 rounded contact-btn-link">
                            <div class="d-flex align-items-center justify-content-center bg-primary rounded-circle p-3 ms-3" style="width: 64px; height: 64px;">
                                <i class="fa fa-envelope text-white"></i>
                            </div>
                            <div class="ms-3 contact-link">
                                <h4 class="text-dark">Email Us</h4>
                                <a class="h5 text-dark fs-6" >info@ksu.edu.sa</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Contact End -->


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
                                <a class="btn  ps-0" href="#about"><i class="fa fa-check me-2"></i>About Us</a>
                                <a class="btn  ps-0" href="#Contact"><i class="fa fa-check me-2"></i>Contact Us</a>
                                <a class="btn  ps-0" href="#FAQs"><i class="fa fa-check me-2"></i>FAQs</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            <h4 class="text-white fw-bold mb-4">Services Link</h4>
                            <div class="d-flex flex-column align-items-start">
                                <a class="btn  ps-0" href="#Services"><i class="fa fa-check me-2"></i>Attendance Management</a>
                                <a class="btn  ps-0" href="#Services"><i class="fa fa-check me-2"></i>Reports and Statistics</a>
                                <a class="btn ps-0" href="#Services"><i class="fa fa-check me-2"></i>Complaints and Suggestions</a>
                                <a class="btn  ps-0" href="#Services"><i class="fa fa-check me-2"></i>Support and Assistance</a>
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
        <!-- Include Chart.js for displaying charts -->

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
                                // Attendance Chart
                                const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
                                const attendanceChart = new Chart(attendanceCtx, {
                                    type: 'doughnut', // or 'bar', 'line', etc.
                                    data: {
                                        labels: ['Present', 'Absent'],
                                        datasets: [{
                                                data: [<?php if(($attendance_total)>0){echo (($attendance_total)*100)/(($attendance_total)+($absent_total));} else {
    
    echo '1';}  ?>, <?php if(($absent_total)>0){echo (($absent_total)*100)/(($attendance_total)+($absent_total));} else {
    
    echo 1;}  ?>], // Example data for attendance
                                                backgroundColor: ['#4CAF50', '#F44336'], // Colors for present and absent
                                                borderColor: '#ffffff', // White border for the chart
                                                borderWidth: 2
                                            }]
                                    },
                                    options: {
                                        plugins: {
                                            legend: {
                                                labels: {
                                                    color: 'black' // White color for the labels
                                                }
                                            }
                                        },
                                        scales: {
                                            y: {
                                                ticks: {
                                                    color: 'black' // White color for the numbers on Y-axis
                                                }
                                            },
                                            x: {
                                                ticks: {
                                                    color: 'black' // White color for the numbers on X-axis
                                                }
                                            }
                                        }
                                    }
                                });

                                // Supervisors Chart
                                const supervisorsCtx = document.getElementById('supervisorsChart').getContext('2d');
                                const supervisorsChart = new Chart(supervisorsCtx, {
                                    type: 'bar',
                                    data: {
                                        labels: ['Total Supervisors', 'Active Supervisors'],
                                        datasets: [{
                                                data: [<?php if (($supervisors_total)>0){echo $supervisors_total;}else{echo 1;}  ?>, <?php if (($supervisors_active)>0){echo $supervisors_active;}else{echo 1;}  ?>], // Example data for supervisors
                                                backgroundColor: ['#2196F3', '#FFC107'], // Colors for active and inactive supervisors
                                                borderColor: '#ffffff',
                                                borderWidth: 2
                                            }]
                                    },
                                    options: {
                                        plugins: {
                                            legend: {
                                                display: false // Hiding legend for bar chart
                                            }
                                        },
                                        scales: {
                                            y: {
                                                ticks: {
                                                    color: 'black'
                                                }
                                            },
                                            x: {
                                                ticks: {
                                                    color: 'black'
                                                }
                                            }
                                        }
                                    }
                                });

                                // Users Chart
                                const usersCtx = document.getElementById('usersChart').getContext('2d');
                                const usersChart = new Chart(usersCtx, {
                                    type: 'pie',
                                    data: {
                                        labels: ['Total Users', 'Active Users'],
                                        datasets: [{
                                                data: [<?php if (($users_total)>0){echo $users_total;}else{echo 1;}  ?>, <?php if (($users_active)>0){echo $users_active;}else{echo 1;}  ?>], // Example data for users
                                                backgroundColor: ['#9C27B0', '#3F51B5'], // Colors for total and active users
                                                borderColor: '#ffffff',
                                                borderWidth: 2
                                            }]
                                    },
                                    options: {
                                        plugins: {
                                            legend: {
                                                labels: {
                                                    color: 'black'// White color for the labels
                                                }
                                            }
                                        }
                                    }
                                });
        </script>

    </body>

</html>