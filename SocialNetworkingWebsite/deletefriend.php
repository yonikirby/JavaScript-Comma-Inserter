<?php session_start();
include("config.php");


	$myID = $_SESSION["userid"];


if(isset($_POST['myDeleteFriendshipID'])){

  
  
 
      $toDeleteFriend = $_POST['myDeleteFriendshipID'];


      if ($myID>$toDeleteFriend){

        $query = "DELETE FROM friends WHERE user_one_id=".$toDeleteFriend." AND user_two_id=".$myID.";";
      }

      else {
        $query = "DELETE FROM friends WHERE user_one_id=".$myID." AND user_two_id=".$toDeleteFriend.";";
      }


      if (!mysqli_query($con,$query)){
          echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';

      }


}



?>