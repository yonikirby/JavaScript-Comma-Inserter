<?php session_start();
include("config.php");

$response = '';


if ($_SESSION['friendid']==0){
    echo '<script>alert("Error: you are not chatting with anybody");</script>';
}

if(isset($_POST['myinputmessage'])) {
    
      $sMessage = $_POST['myinputmessage'];


      if ($sMessage != '') {



        if ($_SESSION['friendid'] > $_SESSION['userid']){
            $user1 = $_SESSION['userid'];
            $user2 = $_SESSION['friendid'];
            $action_user_id = 1;
        }
        else {
            $user2 = $_SESSION['userid'];
            $user1 = $_SESSION['friendid'];
            $action_user_id = 2;
        }

          $sql = "INSERT INTO chatmessages (user_one_id, user_two_id, message, action_user_id)
          VALUES ('$user1', '$user2', '$sMessage', '$action_user_id')";
          
          
          // Perform a query, check for error
          
          
          if (!mysqli_query($con,$sql)){
              $response .= '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
              
          }


          $sql = "INSERT INTO recent_users_chatted_with
          (user_one_id, user_two_id, most_recent_message_contents, action_user_id, was_viewed)
      VALUES
          ('$user1', '$user2', '$sMessage', '$action_user_id', '0')
      ON DUPLICATE KEY UPDATE
      most_recent_message_contents = '$sMessage', action_user_id = '$action_user_id', was_viewed = '0';";
          
          
          // Perform a query, check for error
          
          
          if (!mysqli_query($con,$sql)){
              $response .= '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
              
          }


      }

      
      
  }
  
  echo $response;
  
  

?>