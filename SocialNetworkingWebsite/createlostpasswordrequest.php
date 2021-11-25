<?php session_start();
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';
include("config.php");

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

//password field needs to be type CHAR of length 60
	$myID = $_SESSION["userid"];
    //echo 'got inside php';

    //https://stackoverflow.com/questions/1102781/best-way-for-a-forgot-password-implementation
    //https://www.texelate.co.uk/blog/create-a-guid-with-php
    //https://stackoverflow.com/questions/46155/how-to-validate-an-email-address-in-javascript
  
    //https://stackoverflow.com/questions/37275492/ajax-how-to-return-an-error-message-from-a-php-file/37276289


    if(isset($_POST['forgotpasswordemail'])){



        $forgotpasswordemail = $_POST['forgotpasswordemail'];

        $token = createToken();

 
        
        
        $guid  = password_hash($token, PASSWORD_BCRYPT);



   
        

        $sql = "INSERT INTO password_change_requests (id, useremail, salt)
        VALUES ('$guid', '$forgotpasswordemail', '$randomString')";
        
        
        if (!$result = mysqli_query($con,$sql)){
            $message = '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
            $return_arr[] = array("info_was_inserted" => false,
            "message" => $message);
            // Encoding array in JSON format
            echo json_encode($return_arr);
        }

        else {

            // the message
            $msg = "Hello. Please click this link to reset your password: " . $token;

            // use wordwrap() if lines are longer than 70 characters
            $msg = wordwrap($msg,70);

            // send email
            //mail($forgotpasswordemail,"Yonatan Kirby Social Network: Password Reset",$msg);
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = gethostname();
            $mail->SMTPAuth = true;
            $mail->Username = 'socialn5@nl1-ss21.a2hosting.com';
            $mail->Password = '6*f8Kb7Q0Yo@xX';
            $mail->setFrom('socialn5@nl1-ss21.a2hosting.com');
            $mail->addAddress('yonikirby@gmail.com');
            $mail->Subject = 'Yonatan Kirby Social Network: Password Reset';
            $mail->Body    = 'Here is your password reset link: https://socialnetwork.a2hosted.com/forgotpassword.php?originemail=yonikirby@gmail.com&ID='.$token;
            $mail->send();


            $message = 'Info inserted';
            $return_arr[] = array("info_was_inserted" => "true");
            // Encoding array in JSON format
            echo json_encode($return_arr);
        }
        

    }     


    function createToken() {
        // Create a token
        $token      = $_SERVER['HTTP_HOST'];
        $token     .= $_SERVER['REQUEST_URI'];
        $token     .= uniqid(rand(), true);
        
        return $token;
    }


    //https://stackoverflow.com/questions/4795385/how-do-you-use-bcrypt-for-hashing-passwords-in-php

?>