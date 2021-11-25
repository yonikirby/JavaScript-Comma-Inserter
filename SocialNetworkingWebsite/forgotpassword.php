<?php

//password field needs to be type CHAR of length 60

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("config.php");
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$wasSentFromEmail = false;

$token = "";

if (isset($_GET["ID"]) && isset($_GET["originemail"])){
    $wasSentFromEmail = true;

    $token = $_GET["ID"];
    $originemail = $_GET["originemail"];


            $sql = "SELECT id FROM userinfo WHERE email = '$originemail'";

            if (!$result = mysqli_query($con,$sql)){
                echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                
            }
            else {
                $row = mysqli_fetch_array($result);
                $_SESSION['userid'] = $row[0];
            }



    $sql = "SELECT id FROM password_change_requests WHERE useremail = '$originemail' order by time desc limit 1";

    if (!$result = mysqli_query($con,$sql)){
        echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
        
    }
}





?>


<!doctype html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
  
    <title>YK Social Network Reset Password</title>
  
    <link rel="stylesheet" href="css/forgotpasswordstyle.css">
    <script src="js/jquery-3.6.0.js"></script>
  </head>
  
  <body>


<?php

$hash = "";

$myhashresult = "";

if ($wasSentFromEmail){

    $row = mysqli_fetch_array($result);

    $myhashresult = $row['id'];



    echo '
        <script>
            //alert("hashed value is: '.$hash.' and token is '.$token.' num of results is '.mysqli_num_rows($result).' and my result is '.$myhashresult.'");

        </script>
    ';
}

if ((isset($_POST['MY_DATA']) && $_POST['MY_DATA'] == "sent_from_logged_in") || password_verify($token, $myhashresult)){
                        
    echo '
        
        <div id = "MainContainer">
            <div id = "LeftContainer">
                New Password: 
                <br />
                Confirm Password: 
            </div>
            <div id = "RightContainer">
                
                <input type = "password" id = "NewPasswordOne">
                <br />
                <input type = "password" id = "NewPasswordTwo">
            </div>
            <button id = "SubmitButton">Submit</button>
        </div>
    ';
}

else {
    echo '
    <div id = "MainContainer">
        Error: Invalid password reset link

    </div>
    ';
}


?>
    <script>
    $("#SubmitButton").on('click', function(event){
        var passwordone = $("#NewPasswordOne").val();
        var passwordtwo = $("#NewPasswordTwo").val();
        if (passwordone.localeCompare(passwordtwo) != 0){
            alert("Error: password fields need to match.");
            $("#NewPasswordOne").val("");
            $("#NewPasswordTwo").val("");
        }
        else {
            $.ajax({
                        url: 'setnewpassword.php',
                        type: 'post',
                        data: {password:passwordone},
                        dataType:"JSON",
                        success: function(response){
                            if (response[0].info_was_inserted == false){
                                alert(response[0].message);
                            }
                            else {
                                alert("Password has been reset!");
                                window.location.href = "newsfeed.php";
                            }
                        },
			});
        }

    });


    </script>




    </body>
</html>




