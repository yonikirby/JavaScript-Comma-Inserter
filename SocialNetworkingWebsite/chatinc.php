<?php session_start();
include("config.php");

// simple chat class
class SimpleChat {
    

    // constructor
    function SimpleChat() {
        session_start();
        include("config.php");
    }
    
    function getMessages() {
        session_start();
        include("config.php");


        $myID = $_SESSION["userid"];
        $friendid = $_SESSION['friendid'];
        $user1=0;
        $user2=0;
        $action_user_id=0;


        if ($myID > $friendid) {
            $user1 = $friendid;
            $user2 = $myID;
            $action_user_id = 2;
        }
        else {
            $user2 = $friendid;
            $user1 = $myID;
            $action_user_id = 1;
        }




        $sql = "SELECT * FROM chatmessages";
                    
                    
                    // Perform a query, check for error
                   
                    
        if (!$result = mysqli_query($con,$sql)){
            echo '<script type="text/javascript">alert("mysql error:'.mysqli_error($con).'");</script>';
        }
        
        $sMessages = '';
        
        while ($row = mysqli_fetch_array($result)) {
            
            $message_action_user_id = $row['action_user_id'];
            $message = stripslashes($row['message']);
            $timeofmessage = $row['timeofmessage'];
            
            if ($message_action_user_id == $action_user_id ){
                $sMessages .= '<div class = "MySpeech">'.$message.'</div>';
            }
            
            else {
                $sMessages .= '<div class = "HisSpeech">'.$message.'</div>';

            }
            
        }

        
        ob_start();
        //require_once('chat_begin.html');
        echo $sMessages;
        //require_once('chat_end.html');
        return ob_get_clean();
        
    }
}
?>