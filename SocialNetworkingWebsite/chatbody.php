<?php session_start();
include("config.php");

$myID = $_SESSION["userid"];
$friendid = $_SESSION['friendid'];

$friendprofilesrc = $_SESSION['friendprofilesrc'];
$friendfirstname = $_SESSION['friendfirstname'];
$friendlastname = $_SESSION['friendlastname'];



if (isset ($_GET['friendid'])){
    $friendid = $_GET['friendid'];
    $_SESSION['friendid'] = $friendid;
}




$sql = "SELECT firstname,lastname,profilephotopath FROM userinfo where id = '$friendid'";

if (!$result = mysqli_query($con,$sql)){
    echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
    
}

while ($row = mysqli_fetch_array($result)) {

    $friendfirstname = $row['firstname'];
    $friendlastname = $row['lastname'];
    $friendprofilesrc = $row['profilephotopath'];
    $_SESSION['friendprofilesrc'] = $friendprofilesrc;
}


if (isset ($_POST['NumMessagesToAdd'])){
    $most_recent_message_id .= $_POST['NumMessagesToAdd'];
  }

?>
    <!doctype html>
  <html lang="en">
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
        <div id = "FriendNameDiv">

          <img src = "<?php echo $friendprofilesrc ?>" />

          <?php echo $friendfirstname . ' ' . $friendlastname ?>
          
        </div>
        <div id = "InnerChatContainer">

        <?php
        
        $most_recent_message_id = 0;



           
            $user1=0;
            $user2=0;
            $action_user_id=0;


            if ($myID > $friendid) {
                $user1 = $friendid;
                $user2 = $myID;
                $action_user_id = 2;
                $for_update_action_id = 1;
            }
            else {
                $user2 = $friendid;
                $user1 = $myID;
                $action_user_id = 1;
                $for_update_action_id = 2;
            }



     


            



            $sql = "SELECT * FROM chatmessages WHERE user_one_id = '$user1' AND user_two_id = '$user2' 
            ORDER BY timeofmessage DESC LIMIT 15";
                        
                        
                        // Perform a query, check for error
                      
                        
            if (!$result = mysqli_query($con,$sql)){
                echo '<script type="text/javascript">alert("num1 mysql error:'.mysqli_error($con).'");</script>';
            }

            

            
            $sql = "UPDATE recent_users_chatted_with SET was_viewed = '1' WHERE user_one_id = '$user1' AND user_two_id = '$user2' 
            AND action_user_id = '$for_update_action_id'";
                        
                        
                        // Perform a query, check for error
                      
                        
            if (!mysqli_query($con,$sql)){
                echo '<script type="text/javascript">alert("num2 mysql error:'.mysqli_error($con).'");</script>';
            }
            
            

            $numResults = mysqli_num_rows($result);
            $counter = 0;
            
            
            while($row = mysqli_fetch_array($result))
            {
                $data[] = $row;
            }
            
            $data = array_reverse($data);
            foreach($data as $row){

                
                $message_action_user_id = $row['action_user_id'];
                $message = $row['message'];
                $timeofmessage = $row['timeofmessage'];
                
                if ($message_action_user_id == $action_user_id ){
                    echo '<div class = "MySpeech"><span class = "Text">'.htmlspecialchars(urldecode($message)).'</span></div>';
                }
                
                else {
                    echo '<div class = "HisSpeech"><span class = "Text">'.htmlspecialchars(urldecode($message)).'</span><img src = "'.$friendprofilesrc.'" class = "ChatFriendPhoto" /></div>';

                }
                
                
                if (++$counter == $numResults) {
                    $most_recent_message_id = $row['id'];
                    echo '<script>var MostRecID = '.$most_recent_message_id.'</script>';
                } 
                
            }
            ?>
            </div>

            <div id = "InputBoxContainer">

            <input type="text" id="ChatInputBox" name="s_message">

            </div>

            <script>    
                    var MostRecID = <?php echo $most_recent_message_id; ?>;

                    setInterval(function(){
                        
                    $.ajax({
                                    url: 'updatechat.php',
                                    type: 'POST',
                                    data: { MostRecentID: MostRecID },
                                    
                                    success:function(response){
                                        

                                    
                                        var count = (response.match(/HisSpeech/g)).length;
                                        
                                        MostRecID += count;



                                        $("#InnerChatContainer").append(unescape(response));

                                        var objDiv = document.getElementById("InnerChatContainer");
                                        objDiv.scrollTop = objDiv.scrollHeight;

                                    }

                                    
                            });
                    }, 5000);


                    

                    $('#ChatInputBox').keydown(function (e) {
                        var keyCode = e.keyCode || e.which;
                        var txt = $("#ChatInputBox").val();
                        
                        

                        if (keyCode == 13 && txt!="") {
                            
                            document.getElementById("ChatInputBox").value = "";
                            
                            $.ajax({
                                    url: 'inserttochat.php',
                                    type: 'POST',
                                    data: { myinputmessage: escape(txt) },
                                    
                                    success:function(response){
                                    
                                    $("#InnerChatContainer").append(response);

                                    $( "#InnerChatContainer" ).append( '<div class = "MySpeech"><span class = "Text">'+txt+'</span></div>' );

                                    var objDiv = document.getElementById("InnerChatContainer");
                                    objDiv.scrollTop = objDiv.scrollHeight;
                                    }

                            });

                            $.ajax({
                                        url: 'chatbody.php',
                                        type: 'POST',
                                        data: { NumMessagesToAdd: 1},
                                        
                                        success:function(response){
                                        MostRecID++;
                                        }

                            });
                            

                        }

                    });



                    $( document ).ready(function() {
                        var objDiv = document.getElementById("InnerChatContainer");
                        objDiv.scrollTop = objDiv.scrollHeight;


                    });
                    

            </script>





            



                </body>
                </html>


        