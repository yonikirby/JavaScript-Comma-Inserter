<!--<meta http-equiv="refresh" content="5">-->
<html>
<head>
    <meta charset="utf-8">
  
    <title>helloyoni123</title>
    <meta name="description" content="My Profile">
    <meta name="author" content="YK Social Network">
  	<link rel="stylesheet" href="css/friendprofilefullstyle.css">
    <link rel="stylesheet" href="css/chatstyle.css">
    <script src="js/jquery-3.6.0.js"></script>
  </head>

<body>
testing123
<br /><br />hi<br /><br />hi<br /><br />hi<br /><br />hi<br /><br />hi<br /><br />hi<br /><br />hi<br /><br />hi<br /><br />hi<br /><br />hi<br /><br />hi<br /><br />hi<br /><br />hi
<?php
    $most_recent_message_id = 0;



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



   


        $sql = "SELECT * FROM chatmessages WHERE user_one_id = '$user1' AND user_two_id = '$user2' 
        ORDER BY timeofmessage ASC LIMIT 15";
                    
                    
                    // Perform a query, check for error
                   
                    
        if (!$result = mysqli_query($con,$sql)){
            echo '<script type="text/javascript">alert("mysql error:'.mysqli_error($con).'");</script>';
        }
        

        $numResults = mysql_num_rows($result);
        $counter = 0;
        
        while ($row = mysqli_fetch_array($result)) {
            
            $message_action_user_id = $row['action_user_id'];
            $message = stripslashes($row['message']);
            $timeofmessage = $row['timeofmessage'];
            
            if ($message_action_user_id == $action_user_id ){
                echo '<div class = "MySpeech">'.$message.'</div>';
            }
            
            else {
                echo '<div class = "HisSpeech">'.$message.'</div>';

            }
            
            if (++$counter == $numResults) {
                $most_recent_message_id = row['id'];
            } 

        }
        

setInterval(function(){
    $sql = "SELECT * FROM chatmessages WHERE user_one_id = '$user1' AND user_two_id = '$user2' AND id > '$most_recent_message_id'
        ORDER BY timeofmessage ASC";
                    
                    
                    // Perform a query, check for error
                   
                    
        if (!$result = mysqli_query($con,$sql)){
            echo '<script type="text/javascript">alert("mysql error:'.mysqli_error($con).'");</script>';
        }
        

        $numResults = mysql_num_rows($result);
        $counter = 0;
        
        while ($row = mysqli_fetch_array($result)) {
            
            $message_action_user_id = $row['action_user_id'];
            $message = $row['message'];
            $timeofmessage = $row['timeofmessage'];
            
            if ($message_action_user_id == $action_user_id ){
                echo '<div class = "MySpeech">'.$message.'</div>';
            }
            
            else {
                echo '<div class = "HisSpeech">'.$message.'</div>';

            }
            
            if (++$counter == $numResults) {
                $most_recent_message_id = row['id'];
            } 

        }
}, 5000);




?>








</body>
</html>
