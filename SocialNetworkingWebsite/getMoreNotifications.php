<?php session_start();
include("config.php");


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = "";


    
$myID = $_SESSION["userid"];




$oldestNotificationTimestampSoFar = $_SESSION["oldestNotificationTimestampSoFar"];

$sql = "select * from notifications where notificationrecipient = '$myID' and time < '$oldestNotificationTimestampSoFar' 
order by time desc limit 7";
        
               
if (!$result = mysqli_query($con,$sql)){
    echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
    
}



if ((mysqli_num_rows($result) > 0)){
    while ($row = mysqli_fetch_array($result)) {
        $id = $row['id'];             
        $posterid = $row['posterid'];
        $posteeid = $row['posteeid'];
        $postid = $row['postid'];
        $posterprofilesrc = $row['posterprofilesrc'];
        $postername = stripslashes($row['postername']);
        $notification_message = $row['notification_message'];
        $time = $row['time'];
        $was_viewed = $row['was_viewed'];           
        
        
        $messageLink = "";

        if ($notification_message == "friend_request"){
            $messageLink = "myfriendrequests.php";
        }
        else if ($notification_message == "friend_accepted") {
            $messageLink = "friendprofilefull.php?id=".$posterid;
        }
        else if ($notification_message == "post_on_wall") {
            $messageLink = "friendprofilefull.php?id=".$posteeid."&postid=".$postid;
        }
        else if ($notification_message == "tagged_in_post") {
            if ($posterid == $posteeid){
                $messageLink = "friendprofilefull.php?id=".$posteeid."&postid=".$postid;
            }
            else {
                $messageLink = "myprofile.php?postid=".$postid;
            }
        }



        

        $response .= '
                <div class = "OneNotification" id = "OneNotification'.$id.'" onclick = "redirectMe(\''.$messageLink.'\',,\''.$id.'\')">
                    <img src = "'.$posterprofilesrc.'" class = "OneNotificationProfilePic">
                    <div class = "OneNotificationRightDiv">
                        <div class = "OneNotificationInfo">
                            
                            <div class = "OneNotificationNameDiv">
                                '.$postername.'
                            </div>
                            ';
                            if ($notification_message == "friend_request"){
                                $response .= ' sent you a friend request.';
                            }
                            else if ($notification_message == "friend_accepted") {
                                $response .= ' accepted your friend request.';
                            }
                            else if ($notification_message == "post_on_wall") {
                                $response .= ' posted on your wall.';
                            }
                            else if ($notification_message == "tagged_in_post") {
                                $response .= ' tagged you in a post.';
                            }

        $response .= '
                        </div>
                        <div class = "OneNotificationTimeDiv">

                                '.timeAgo($time).'
                            
                        </div>
                    </div>';

        if ($was_viewed == 0){
            $response .= '<div class = "OneNotificationBlueDot" id = "OneNotificationBlueDot'.$id.'"></div>';
        }

        $response .='
                </div>
        
        
        ';

        $_SESSION["oldestNotificationTimestampSoFar"] = $time;

    }                
                     
}
else {
    $response = -1;
}

echo $response;





function timeAgo($timestamp){
    $datetime1=new DateTime("now");
    $datetime2=date_create($timestamp);
    $diff=date_diff($datetime1, $datetime2);
    $timemsg='';
    if($diff->y > 0){
        $timemsg = $diff->y .' year'. ($diff->y > 1?"s":'');

    }
    else if($diff->m > 0){
     $timemsg = $diff->m . ' month'. ($diff->m > 1?"s":'');
    }
    else if($diff->d > 0){
     $timemsg = $diff->d .' day'. ($diff->d > 1?"s":'');
    }
    else if($diff->h > 0){
     $timemsg = $diff->h .' hour'.($diff->h > 1 ? "s":'');
    }
    else if($diff->i > 0){
     $timemsg = $diff->i .' minute'. ($diff->i > 1?"s":'');
    }
    else if($diff->s > 0){
     $timemsg = $diff->s .' second'. ($diff->s > 1?"s":'');
    }

$timemsg = $timemsg.' ago';
return $timemsg;
}


?>

