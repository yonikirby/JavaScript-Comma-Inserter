<?php session_start();
include("config.php");


	$myID = $_SESSION["userid"];


    if(isset($_POST['myRequesteeID'])){
   
  
  
 
        $toFriend = $_POST['myRequesteeID'];
        
        $timestamp = date("Y-m-d H:i:s");
        
        
        if ($myID < $toFriend){
        
            $query = "INSERT INTO friends (user_one_id, user_two_id, status, action_user_id, time_of_request)  VALUES ('$myID', '$toFriend', '0', '1', '$timestamp')";
        }
        else {
          $query = "INSERT INTO friends (user_one_id, user_two_id, status, action_user_id, time_of_request)  VALUES ('$toFriend', '$myID', '0', '2', '$timestamp')";
        }
        
    
       
        if (!mysqli_query($con,$query)){
            echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
            
        }


        $myName = $_SESSION["firstname"].' '.$_SESSION["lastname"];
        $myProfileSrc = $_SESSION["profile_src"];

        $sql = "INSERT INTO notifications (posterid, posteeid, notification_message, postername, posterprofilesrc)
                                        VALUES ('$myID', '$toFriend', 'friend_request', '$myName', '$myProfileSrc')";
                                        
                                        
                                        // Perform a query, check for error
                                        
                                        
        if (!mysqli_query($con,$sql)){
            $response .= '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
            
        }
      
       
    }











?>