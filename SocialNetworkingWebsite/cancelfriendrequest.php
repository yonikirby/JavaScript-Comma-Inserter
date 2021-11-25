
<?php session_start();
include("config.php");


	$myID = $_SESSION["userid"];


if(isset($_POST['myCancelRequesteeID'])){

  
  
 
$toCancelFriendRequest = $_POST['myCancelRequesteeID'];


if ($myID>$toCancelFriendRequest){

  $query = "DELETE FROM friends WHERE user_one_id=".$toCancelFriendRequest." AND user_two_id=".$myID.";";
}

else {
  $query = "DELETE FROM friends WHERE user_one_id=".$myID." AND user_two_id=".$toCancelFriendRequest.";";
}


if (!mysqli_query($con,$query)){
    echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';

}


}



?>