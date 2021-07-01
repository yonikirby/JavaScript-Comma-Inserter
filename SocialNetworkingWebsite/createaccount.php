<?php
   session_start();
   include("config.php");

   
   
   /*
   google making social network with php and mysql
   
   if ($_POST['password'] != $_POST['repeat-password']){
     echo '<script type="text/javascript">alert("Two password fields need to be identical.");</script>';
   }
   */
   
   
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form
      
      $myemail = mysqli_real_escape_string($con,$_POST['email']);
      $mypassword = mysqli_real_escape_string($con,$_POST['password']);
      $myfirstname = mysqli_real_escape_string($con,$_POST['firstname']);
      $mylastname = mysqli_real_escape_string($con,$_POST['lastname']);
      $mybirthday = mysqli_real_escape_string($con,$_POST['birthday']);
      
      
      $sql = "INSERT INTO userinfo (email, password, firstname, lastname, birthday)
      VALUES ('$myemail', '$mypassword', '$myfirstname', '$mylastname', '$mybirthday')";
      
      
      // Perform a query, check for error
      
      
      if (!mysqli_query($con,$sql)){
        echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
        mysqli_close($con);
      }

      else {
        
        

        // Set session variables
        $_SESSION["email"] = mysqli_real_escape_string($con,$_POST['email']);
        $_SESSION["password"] = mysqli_real_escape_string($con,$_POST['password']);
        $_SESSION["firstname"] = mysqli_real_escape_string($con,$_POST['firstname']);
        $_SESSION["lastname"] = mysqli_real_escape_string($con,$_POST['lastname']);
        $_SESSION["birthday"] = mysqli_real_escape_string($con,$_POST['birthday']);
        //echo "Session variables are set.";
        $myEmail = $_SESSION["email"];
        
        $sql = "select id from userinfo where email='$myEmail'";
                    
                    
                    
                    
                      
          
        if (!mysqli_query($con,$sql)){
            echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
            //mysqli_close($con);
        }
        
        
        $result = mysqli_query($con,$sql);

         $row = mysqli_fetch_array($result);
        

         $myID = $row['id'];
        
        $_SESSION["userid"] = $myID;
        
        //echo '<script type="text/javascript">alert("session userid is '.$_SESSION["userid"].'");</script>';
        
        header("Location: fillinprofile.php");
        exit();
      }

      
      
      
      
      //$result = mysqli_query($db,$sql);
      
      //echo '<script type="text/javascript">alert("'.$result.'");</script>';
   }
?>






<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>YK Social Network Sign Up</title>
  <meta name="description" content="YK Social Network Sign Up">
  <meta name="author" content="Yonatan Kirby">

  <link rel="stylesheet" href="css/createaccountstyle.css">

</head>

<body>
  
  <div id = "MainContainer">
    
    <div id = "HeadingContainer">
          
          <div id = "TitleContainer">
                <div id = "SignUp">
                  Sign Up
                </div>
                <div id = "QuickAndEasy">
                  It's quick and easy.
                  
                </div>
          </div>
          
          <div id = "BackToLoginButton">
            <form action="index.php">
                <input type="submit" value="Back To Login" />
            </form>
            
            
          </div>
          
          
          
    </div>
    
    <hr />
    
    <form action = "" method = "post">
    
    <div id = "ContentContainer">
        First Name:
        <input type="text" id="firstname" name="firstname">
        Last Name:
        <input type="text" id="lastname" name="lastname">
        
        <br /><br />
        
        
        <div id = "EmailPasswordContainer">
              <div id = "LabelContainer">
                
                Email
                <br /><br />
                Password
                
                
              </div>
              
      
      
              <div id = "FieldsContainer">
                <input type="text" id="email" name="email">
                <br /><br />
                <input type="text" id="password" name="password">
                <br /><br />
                
              </div>
        </div>
        
        <div id = "BirthdayContainer">
            <div id = "BirthdayLabelContainer">
              Birthday:
            </div>
              <div id = "BirthdayFieldContainer">
                  <div id = "DateContainer">
                      <input type="date" id="birthday" name="birthday">
                  </div>
                  
                  <div id = "SignUpContainer">
                      <input type="submit" value="Sign Up" name = "save"/>
                      
                  </div>
              </div>
        </div>
        
    </div>
    
    
    
  </div>
  
  </form>
  
</body>
</html>
