<?php session_start();
   include("config.php");
//password field needs to be type CHAR of length 60
   $myID = -1;
    if (isset($_SESSION['userid'])) {
        $myID = $_SESSION["userid"];
    }
   

   if (isset($_POST['forgotpasswordemail'])){
        echo '<h1>hi!!!!</h1><script type="text/javascript">alert("got inside post");</script>';
        echo '<h1>hi!!!!</h1><script type="text/javascript">console.log("got inside post")</script>';
    }

    if (isset($_GET['forgotpasswordemail'])){
        echo '<h1>hi!!!!</h1><script type="text/javascript">alert("got inside post");</script>';
        echo '<h1>hi!!!!</h1><script type="text/javascript">console.log("got inside post")</script>';
    }
   
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      

        if (isset($_POST['forgotpasswordemail'])){
            echo '<script>alert("got inside post 2");</script>';
            echo '<h1>hi!!!!</h1><script type="text/javascript">console.log("got inside post 2")</script>';
        }
                            
        else {
                            $myemail = mysqli_real_escape_string($con,$_POST['loginemail']);

                            

                            $mypassword = mysqli_real_escape_string($con,$_POST['loginpassword']);

                            
                            
                            $sql = "select password, id, salt from userinfo where email='$myemail'";
                            
                            
                            // Perform a query, check for error
                            
                            $result = mysqli_query($con,$sql);
                            
                            if (!$result){
                                echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                                mysqli_close($con);
                            }

                            else {
                                


                                $row = mysqli_fetch_array($result);
                                

                                $ProperPassword = $row['password'];
                                

                                if (!password_verify($mypassword, $ProperPassword)){ 
                                    echo '<script type="text/javascript">alert("Error: wrong password");</script>';
                                }
                                else {
                                    $_SESSION["userid"] = $row['id'];
                                    $_SESSION["email"] = addslashes($myemail);
                                    $_SESSION["password"] = addslashes($mypassword);
                                    header("Location: newsfeed.php");
                                    exit();
                                
                                }
                                

                                
                                

                            }

                            
        }
      
    
   }





?>
<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Yonatan Kirby's Social Network</title>
  <meta name="description" content="Yonatan Kirby Social Network">
  <meta name="author" content="Yonatan Kirby">

  <link rel="stylesheet" href="css/loginstyle.css">
  <script src="js/jquery-3.6.0.js"></script>
</head>

<body>
   

	<div id = "Explanation">
		<div class = "ExplanationHeader">Working Copy of Facebook Social Networking Site by Yonatan Kirby:</div>
		<br />
		
		<div id = "LeftList">
				<div class = "WorkingFeatures">
					Working Features:
				</div>
		
		
				<ul>
					<li>Login and Signup</li>
					<li>Make Friends and Post on Walls</li>
					<li>Upload and Display Photos for Each User</li>
					<li>Chat between Individual Users</li>
					<li>Ajax Search for Users</li>
                    <li>Source Code: <a href = "https://github.com/yonikirby/Yonatan-Kirby-s-Projects/tree/main/SocialNetworkingWebsite">here</a></li>
				</ul>
		</div>
	
		<div id = "RightList">
				<div class = "WorkingFeatures">
					Valid Logins (to test chat with):
				</div>
		
				<ul>
					<li>yonikirby@gmail.com / 1234 (use to see four chats available in one menu)</li>
					<li>ben@nouriel.com / 1234</li>
					<li>eitan@berkowitz.com / 1234</li>
					<li>roman@bobrakov.com / 1234</li>
					
				</ul>
				
				
				
		</div>
	
	</div>




  <div id="LoginContainer">
        <div id = "FormAndLabelContainer">
            <div id = "LabelContainer">
              
                  <div class = "EachLabel">Email:</div>
                  <div class = "EachLabel">Password:</div>
                      
              
            </div>
            <div id = "FormContainer">
              
                  <form action = "" method = "post">
              
                    <div class = EachTextBox><input type="text" id="loginemail" name="loginemail"></div>
                    <div class = EachTextBox><input type="password" id="loginpassword" name="loginpassword"></div>
                  
                  
            </div>
        </div>
        
        <div id = "LoginButton"><button type="submit" value="submit">Sign in</button></div>
        </form>
    
        <div id = "ForgotPassword"><a onclick = "forgotPassword()">Forgot Password?</a></div>
    
    
        <div id = "myHR"><hr /></div>
    
    
        <div id = CreateNewAccount>
          
            <form action="createaccount.php">
                <input type="submit" value="Create New Account" />
            </form>
          
        </div>
    
    
  </div>

<script>

//https://stackoverflow.com/questions/1102781/best-way-for-a-forgot-password-implementation
//https://www.texelate.co.uk/blog/create-a-guid-with-php
//https://stackoverflow.com/questions/46155/how-to-validate-an-email-address-in-javascript
function forgotPassword(){
    
    var emailValue = document.getElementById("loginemail").value;
   
    
    console.log('inside function');
    if (!validateEmail(emailValue)){
        alert("Please fill in a valid email.");
    }
    else {
        
        alert("A link containing information on how to reset your password has been sent to your email.");
        
        
        $.ajax({
        url: "createlostpasswordrequest.php",
        type: "POST",
        dataType:'JSON',
        data: {forgotpasswordemail: emailValue},
        success: function(response){
            
            if (response[0].info_was_inserted == "true"){
                
            }
            else {
                alert(response[0].message);
            }
            
        }
        });
        

    }
       
}

function validateEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}



</script>



</body>
</html>
