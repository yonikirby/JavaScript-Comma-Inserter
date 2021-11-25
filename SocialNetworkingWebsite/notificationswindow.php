<?php session_start();
include("config.php");

$myID = $_SESSION["userid"];



?>

<div id = "NotificationsTitle">
    Notifications
</div>
<?php


$oldestNotificationTimestampSoFar = date("Y-m-d H:i:s");    


$sql = "select n.*, u.firstname, u.lastname FROM notifications n LEFT JOIN userinfo u ON n.posteeid = u.id where n.notificationrecipient = '$myID' order by time desc limit 12";
        
               
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
                        $postername = $row['postername'];
                        $notification_message = $row['notification_message'];
                        $time = $row['time'];
                        $was_viewed = $row['was_viewed'];    
                        $wallid = $row['wallid'];    
                        
                        $firstname = $row['firstname'];   
                        $lastname = $row['lastname'];  
                        
                 

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
                        else if ($notification_message == "user_liked_post") {
                            $messageLink = "friendprofilefull.php?id=".$wallid."&postid=".$postid;     
                        }
                        else if ($notification_message == "user_liked_comment") {
                            $messageLink = "friendprofilefull.php?id=".$wallid."&postid=".$postid;     
                        }
                        else if ($notification_message == "commented_on_post") {
                            $messageLink = "friendprofilefull.php?id=".$wallid."&postid=".$postid;
                        }

                        

                        echo '
                                <div class = "OneNotification" id = "OneNotification'.$id.'" 
                                        onclick = "redirectMe(\''.$messageLink.'\',\''.$id.'\')">
                                    <img src = "'.$posterprofilesrc.'" class = "OneNotificationProfilePic">
                                    <div class = "OneNotificationRightDiv">
                                        <div class = "OneNotificationInfo">
                                            
                                            <div class = "OneNotificationNameDiv">
                                                '.$postername.'
                                            </div>
                                            ';
                                            if ($notification_message == "friend_request"){
                                                echo ' sent you a friend request.';
                                            }
                                            else if ($notification_message == "friend_accepted") {
                                                echo ' accepted your friend request.';
                                            }
                                            else if ($notification_message == "post_on_wall" && $posterid != $myID) {
                                                if ($posteeid == $myID){
                                                    echo ' posted on your wall.';
                                                }
                                                else if ($posterid == $posteeid){
                                                    echo ' posted an update.';
                                                }
                                                else {
                                                    echo ' posted on '.$firstname.' '.$lastname.'\'s wall.';
                                                }
                                            }
                                            else if ($notification_message == "tagged_in_post") {
                                                echo ' tagged you in a post.';
                                            }
                                            else if ($notification_message == "user_liked_post") {
                                                echo ' liked your post.';
                                            }
                                            else if ($notification_message == "user_liked_comment") {
                                                echo ' liked your comment.';
                                            }
                                            else if ($notification_message == "commented_on_post") {
                                                echo ' commented on your post.';
                                            }

                        echo '
                                        </div>
                                        <div class = "OneNotificationTimeDiv">

                                                '.timeAgo($time).'
                                            
                                        </div>
                                    </div>';
                            if ($was_viewed == 0){
                                echo '<div class = "OneNotificationBlueDot" id = "OneNotificationBlueDot'.$id.'"></div>';
                            }
                    
                        echo '
                                </div>
                        ';
                        
                        $oldestNotificationTimestampSoFar = $time;

                        $_SESSION["oldestNotificationTimestampSoFar"] = $oldestNotificationTimestampSoFar;
        
    }       
    
    

                     
}
else {
    echo '<div id = "NoNewNotifications">No New Notifications</div>';
}






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

<script>


var oldestNotificationTimestampSoFar;


$('#NotificationsContainer').scroll(function() {
    if (!endOfNotificationsReached){
        if($('#NotificationsContainer').scrollTop() > $('#NotificationsContainer').height() - 100) {

       
                

                if (!endOfNotificationsReached){
                    $.ajax({
                        url: 'getMoreNotifications.php',
                        type: 'post',
                      
                        contentType: false,
                        processData: false,
                        success: function(response){
                            if (!endOfNotificationsReached){

                                    if (response == -1){
                                        
                                        $("#NotificationsContainer").append('<div id = "NoNewNotifications">No Additional Notifications</div>');
                                        endOfNotificationsReached = true;
                                    }
                                    else {
                                        
                                        $("#NotificationsContainer").append(response);
                                    }
                            }
                        },
                    });
                }
        }


    }
   
});

$("#NotificationsContainer").on('click', '.OneNotification', function(event){
            

            let myID = $( this ).attr("id");

            

        

            let myNumber = myID.substring(15);

            

            var myOnclick = document.getElementById("OneNotification" + myNumber).getAttribute("onclick");
         
            var myOnclickWithoutExtraComma = myOnclick.replace(',', '');
            
            eval(myOnclick);
});
</script>

