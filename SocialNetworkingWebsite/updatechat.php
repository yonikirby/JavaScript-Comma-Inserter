<?php session_start();

include("config.php");



$most_recent_message_id = 0;

$response = '';



if (isset ($_POST['MostRecentID'])) {

    $most_recent_message_id = $_POST['MostRecentID'];



    $myID = $_SESSION["userid"];
    $friendid = $_SESSION['friendid'];
    $friendprofilesrc = $_SESSION['friendprofilesrc'];


    $user1=0;
    $user2=0;
    $action_user_id=0;
    $ForQueryActionUserID = 0;

    if ($myID > $friendid) {
        $user1 = $friendid;
        $user2 = $myID;
        $action_user_id = 1;

    }
    else {
        $user2 = $friendid;
        $user1 = $myID;
        $action_user_id = 2;

    }

    



$sql = "SELECT * FROM chatmessages WHERE user_one_id = '$user1' AND user_two_id = '$user2' AND id > '$most_recent_message_id' 
AND action_user_id = '$action_user_id'
    ORDER BY timeofmessage ASC";
                
                
                // Perform a query, check for error
               
                
    if (!$result = mysqli_query($con,$sql)){
        echo '<script type="text/javascript">alert("mysql error:'.mysqli_error($con).'");</script>';
    }






    $sql = "UPDATE recent_users_chatted_with SET was_viewed = '1' WHERE user_one_id = '$user1' AND user_two_id = '$user2'
            AND action_user_id = '$action_user_id'";

    if (!mysqli_query($con,$sql)){
        echo '<script type="text/javascript">alert("mysql error:'.mysqli_error($con).'");</script>';
        
    }



    $numResults = mysqli_num_rows($result);
    $counter = 0;





    
    while ($row = mysqli_fetch_array($result)) {
        
        $message_action_user_id = $row['action_user_id'];
        $message = $row['message'];
        $timeofmessage = $row['timeofmessage'];
        

        $response .= '<div class = "HisSpeech"><span class = "Text">'.$message.'</span><img src = "'.$friendprofilesrc.'" class = "ChatFriendPhoto" /></div>';


        
        

    }
}






echo $response;


?>
