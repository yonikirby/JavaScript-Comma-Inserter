<?php session_start();
include("config.php");


	$myID = $_SESSION["userid"];


    if(isset($_POST['id'])){

        

        $postID = $_POST['id'];

        $toFriend = $_POST['post_author_id'];

        $fullname = $_SESSION['firstname'] . ' ' . $_SESSION['lastname'];
        
       

        $toSet = $myID.','.$fullname.';';

        
        
        $query = "UPDATE posts SET postlikes = CONCAT(postlikes, '$toSet') where id = '$postID'";

        if (!$result = mysqli_query($con,$query)){
            echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
            
        }
        
        $myName = $_SESSION["firstname"].' '.$_SESSION["lastname"];
        $myProfileSrc = $_SESSION["profile_src"];

        $myWallID = $_POST["wall_id"];

        if ($_POST["myType"] == "post") {
            $notification_message = 'user_liked_post';
        }
        else {
            $notification_message = 'user_liked_comment';
        }

        $sql = "INSERT INTO notifications (postid, posterid, posteeid, notificationrecipient, notification_message, postername, posterprofilesrc, wallid)
                                        VALUES ('$postID', '$myID', '$toFriend', '$toFriend', '$notification_message', '$myName', '$myProfileSrc', '$myWallID')";
                                        
                                        
                                        // Perform a query, check for error
                                        
                                        
        if (!mysqli_query($con,$sql)){
            $response .= '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
            
        }
       
    }











?>