<?php
include 'config.php';
session_start();
// Get predicted_class from URL parameters
$predicted_class = isset($_GET['result']) ? $_GET['result'] : 'Unknown';

$user_id = $_SESSION['user_id'];

$users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_id = '$user_id'") or die('Query failed: ' . mysqli_error($conn));
$user = mysqli_fetch_assoc($users);

$user_id= $user['user_id'];
$shift_id= $user['shift_id'];

$attendances = mysqli_query($conn, "SELECT * FROM `attendance` WHERE user_id = '$user_id' AND DATE(attendance_date) = CURDATE();") or die('Query failed: ' . mysqli_error($conn));
$attendance = mysqli_fetch_assoc($attendances);


$cdate = date('Y-m-d');
$currentTime = date('H:i:s');
// Display the result
echo "<h2>Detected Face: $predicted_class</h2>";

// Directory where images are saved
$directory = 'assets/img/';

// Delete all images in the directory
if (is_dir($directory)) {
    $files = glob($directory . '*'); // Get all files in the directory
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file); // Delete each file
        }
    }
    if($_SESSION['username'] == $predicted_class ){
        if($attendance == null){
            

            $insert = mysqli_query($conn, "INSERT INTO `attendance` (user_id, attendance_date, check_in_time, shift_id) VALUES ('$user_id', '$cdate', '$currentTime', '$shift_id')") or die('query failed');


            
            header("location: attendance-Overview.php?do=done");
            
        }
        elseif($attendance != null){
            $att_id = $attendance['attendance_id'];
            echo $att_id;
            
            $insert1 = mysqli_query($conn, "UPDATE `attendance` SET check_out_time = '$currentTime' WHERE attendance_id = '$att_id'") or die('query failed');


            
            header("location: attendance-Overview.php?do=done");
            
            
        }
        
    }
    
    else{
        
        header("location: home.php?do=wrong");
    }
    echo "<p>All temporary images deleted successfully.</p>";
} else {
    echo "<p>No images to delete.</p>";
}
?>
