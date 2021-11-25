<?php session_start();
include("config.php");
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

//password field needs to be type CHAR of length 60
$myPassword = $_POST['password'];




$myPassword = password_hash($myPassword, PASSWORD_BCRYPT);



$myID = $_SESSION['userid'];

$query = "UPDATE userinfo SET password = '$myPassword' WHERE id = '$myID'";


if (!mysqli_query($con,$query)){

    $return_arr[] = array("info_was_inserted" => "false", "message" => mysqli_error($con));
    // Encoding array in JSON format
    echo json_encode($return_arr);

}
else {
    $return_arr[] = array("info_was_inserted" => "true");
    // Encoding array in JSON format
    echo json_encode($return_arr);
}





?>