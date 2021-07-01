<?php session_start();

include("config.php");



$most_recent_message_id = 0;

$response = '';

//$response .= '<h1>HIIIIIII</h1><script>alert("got here and most recent message is is:</script>';

if (isset ($_POST['MostRecentID'])) {

    $most_recent_message_id = $_POST['MostRecentID'];

    //$response .= '<script>alert("got here and most recent message is from POST: "'.$most_recent_message_id.')</script>';

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

    
    //echo '<script type="text/javascript">alert("userone: '.$user1.' usertwo: '.$user2.' mostrecentmessageid: '.$most_recent_message_id
    //.' actionuserid: '.$action_user_id.'");</script>';
    


$sql = "SELECT * FROM chatmessages WHERE user_one_id = '$user1' AND user_two_id = '$user2' AND id > '$most_recent_message_id' 
AND action_user_id = '$action_user_id'
    ORDER BY timeofmessage ASC";
                
                
                // Perform a query, check for error
               
                
    if (!$result = mysqli_query($con,$sql)){
        echo '<script type="text/javascript">alert("mysql error:'.mysqli_error($con).'");</script>';
    }


    //$response .= '<script>alert("user1 is '.$user1.' and user2 is '.$user2.'");</script>';



    $sql = "UPDATE recent_users_chatted_with SET was_viewed = '1' WHERE user_one_id = '$user1' AND user_two_id = '$user2'
            AND action_user_id = '$action_user_id'";

    if (!mysqli_query($con,$sql)){
        echo '<script type="text/javascript">alert("mysql error:'.mysqli_error($con).'");</script>';
        
    }



    $numResults = mysqli_num_rows($result);
    $counter = 0;




    //$response .= '<script type="text/javascript">alert("num results is: "'.$numResults.');</script>';
    
    while ($row = mysqli_fetch_array($result)) {
        
        $message_action_user_id = $row['action_user_id'];
        $message = $row['message'];
        $timeofmessage = $row['timeofmessage'];
        

        $response .= '<div class = "HisSpeech"><span class = "Text">'.$message.'</span><img src = "'.$friendprofilesrc.'" class = "ChatFriendPhoto" /></div>';


        
        

    }
}






echo $response;


?>
