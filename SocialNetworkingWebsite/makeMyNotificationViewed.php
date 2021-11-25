<?php session_start();
include("config.php");


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = "";


    
$myID = $_SESSION["userid"];


$notificationID = $_POST["id"];

$sql = "update notifications set was_viewed = '1' where id = '$notificationID'";
        
               
if (!$result = mysqli_query($con,$sql)){
    echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
    
}

echo 'Succeeded in setting was_viewed.'.$notificationID;


?>