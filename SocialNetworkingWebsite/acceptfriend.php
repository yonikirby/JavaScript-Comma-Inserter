<?php session_start();
include("config.php");


	$myID = $_SESSION["userid"];



if(isset($_POST['myAcceptFriendshipID'])){

$myAcceptFriendshipID = $_POST['myAcceptFriendshipID'];


if ($myID > $myAcceptFriendshipID){
  $userOne = $myAcceptFriendshipID;
  $userTwo = $myID;
  $actionID = 2;
}
else {
  $userOne = $myID;
  $userTwo = $myAcceptFriendshipID;
  $actionID = 1;
}




$query = "UPDATE friends SET status = '1', action_user_id = '$actionID' WHERE user_one_id = '$userOne' AND user_two_id = '$userTwo'";


if (!mysqli_query($con,$query)){
    echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
    
}

$myName = $_SESSION["firstname"].' '.$_SESSION["lastname"];
$myProfileSrc = $_SESSION["profile_src"];

$sql = "INSERT INTO notifications (posterid, posteeid, notification_message, posterid, posterprofilesrc)
                                        VALUES ('$myID', '$myAcceptFriendshipID', 'friend_accepted', '$myName', '$myProfileSrc')";
                                        
                                        
                                        // Perform a query, check for error
                                        
                                        
        if (!mysqli_query($con,$sql)){
            $response .= '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
            
        }



}

?>