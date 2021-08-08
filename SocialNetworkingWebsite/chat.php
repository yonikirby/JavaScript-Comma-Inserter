<?php session_start();
include("config.php");
include("header.php");



/*
if ($_SESSION['friendid'] == 0){
  header('Location: http://www.socialnetwork.a2hosted.com/myprofile.php');
}
*/



$myID = $_SESSION["userid"];

if ($_SESSION['friendid'] != null){ 
  $friendid = $_SESSION['friendid'];
}

if ($_GET['friendid'] != null){ 
  $friendid = $_GET['friendid'];
  $_SESSION['friendid'] = $friendid;
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
  <script>
  document.getElementById("HeaderContainer").style.width = "1300px";

  

  function loadChat(myid) {
    $( "#NewMessageSearchContainer" ).css("display", "none");
    $( "#ChatContainer" ).css("display", "block");

    $( ".OneFriend" ).css("backgroundColor", "white");
    $( "#OneFriend"+myid ).css("backgroundColor", "#EAF3FF");
    $( "#ChatContainer" ).load( "chatbody.php?friendid="+myid );
  }

  </script>
<div id = "BigContainer">
      <div id = "ChatFriendsContainer">
          <div id = "LeftNameDiv">
              <div id = "Text">Chats</div>
              <div id = "Image" onclick = "composeMessage()"><img src = "images/compose.png"></div>
              
          </div>

          <?php
            
              $sql = "SELECT * FROM recent_users_chatted_with WHERE user_one_id = '$myID' OR user_two_id = '$myID' 
              ORDER BY most_recent_message_time DESC LIMIT 15";

                          
              if (!$result1 = mysqli_query($con,$sql)){
                  echo '<script type="text/javascript">alert("num3 mysql error:'.mysqli_error($con).'");</script>';
              }

              $IDsToQuery = (array) null;

              
                      		
              while ($row = mysqli_fetch_array($result1)) {
            
                  $UserOneID = $row['user_one_id'];
                  $UserTwoID = $row['user_two_id'];
                  
                  if ($myID == $UserOneID){
                      array_push($IDsToQuery,$UserTwoID);
                  }
                  else {
                      array_push($IDsToQuery,$UserOneID);
                    
                  }

                  
              }


              $MostRecentChatee = $IDsToQuery[0];

              echo '<script>
              
              
              $( document ).ready(function() {

                $( "#ChatContainer" ).load( "chatbody.php?friendid=" + '.$MostRecentChatee.');
                $( "#OneFriend"+'.$MostRecentChatee.' ).css("backgroundColor", "#EAF3FF");
              });

              </script>';
              
            

              $ImplodedArray = implode(',', $IDsToQuery);



              if (count($IDsToQuery) > 0){     
                              $sql = "SELECT * FROM userinfo WHERE id in (" . $ImplodedArray . ") 
                              ORDER BY field(id, ".$ImplodedArray.")";
                  
                              if (!$result2 = mysqli_query($con,$sql)){
                                  echo '<script type="text/javascript">alert("num4 first query '.mysqli_error($con).'");</script>';
                                              
                              }

                              mysqli_data_seek($result1, 0) ;

                              while ($row2 = mysqli_fetch_array($result2)) {
                                

                                $firstname = $row2['firstname'];
                                $lastname = $row2['lastname'];
                                $profile_src = $row2['profilephotopath'];
                                $id = $row2['id'];
                                
                                
                                echo '
                                    <div id = "OneFriend'.$id.'" class = "OneFriend" onclick="loadChat('.$id.')">
                                        <img src = "'.$profile_src.'" />
                                        <div class = "TextDiv">
                                              <div class = "Name" id = "Name'.$id.'">'.$firstname.' '.$lastname.'</div>
                                              <div class = "MessageText" id = "MessageText'.$id.'"></div>
                                              
                                              
                                        </div>
                                        <div id = "BlueCircle'.$id.'" class = "BlueCircle"></div>
                                    </div>
                                ';

                              

                              }

                              

                              while ($row1 = mysqli_fetch_array($result1)) {

                                            $message_text = $row1['most_recent_message_contents'];

                                            $user1 = $row1['user_one_id'];
                                              $user2 = $row1['user_two_id'];
                                              $action_user_id = $row1['action_user_id'];

                                            $stringToInsert = '';

                                              if (($user1 == $myID && $action_user_id == 1) || $user2 == $myID && $action_user_id == 2){
                                                $stringToInsert .= 'You: ';
                                              }
                                              
                                              $stringToInsert .= htmlspecialchars(urldecode($message_text));

                                              $userToInsert = -1;

                                            if ($user1 == $myID){
                                                $userToInsert = $user2;
                                            }

                                            else {
                                                $userToInsert = $user1;
                                            }
                                            
                                            if (($myID == $user1 && $action_user_id == 2) || $myID == $user2 && $action_user_id == 1){
                                              if ($row1['was_viewed'] == 0){
                                                echo '<script> $("#MessageText"+'.$userToInsert.').css("color", "#267EF3"); 
                                                $("#Name"+'.$userToInsert.').css("font-weight", "bold");
                                                $("#BlueCircle"+'.$userToInsert.').css("display", "block");</script>';
                                                
                                                
                                              }
                                            }

                                            echo '<script>$( "#MessageText"+'.$userToInsert.' ).append( "'.$stringToInsert.'" );</script>';
                                
                                
                                            
                                }
                  }
          ?>

      
      </div>

      <div id = "ChatContainer">
        
        
      </div>

      <div id = "NewMessageSearchContainer">
                  <div id = "TextBoxContainer">
                      <div id = "SearchBoxContainer">
                          To: <input type="text" id="ComposeFriends" name="ComposeFriends">
                            <div id = "ComposeSearchResultContainer">
                                <ul id = "ComposeSearchResult">
                        
                                </ul>
                            </div>
                      </div>
                  </div>

      </div>
</div> 
	<!--https://www.script-tutorials.com/how-to-easily-make-a-php-chat-application/   -->
  


<script>
    function composeMessage(){
        $( "#ChatContainer" ).css("display", "none");
        $( "#NewMessageSearchContainer" ).css("display", "block");
    }

    var currentValue = "";

$(document).ready(function(){


    $("#ComposeFriends").keyup(function(){
    
    	
    
    
        var search = escape($(this).val());
		
		
		//var ul = document.getElementById("SearchResult");
		$( "#ComposeSearchResult").empty();
		
		//while(ul.firstChild) { ul.removeChild(ul.firstChild); }
		//alert("got here 1: " + search);
		
        if(search != "" && search != currentValue){

            $.ajax({
                url: 'getsearch.php',
                type: 'post',
                data: {search:search},
                dataType: 'json',
                success:function(response){
                
                	//alert ("got here 2: " + JSON.stringify(response));
                
                	currentValue = search;
                
                    var len = response.length;
                    
                    for( var i = 0; i<len; i++){
                    
                    	//alert ("got here 3");
                        var id = response[i]['id'];
                        var name = response[i]['name'];
                        var profilephotopath = response[i]['profilephotopath'];

                        $("#ComposeSearchResult").append("<li value='"+id+"'><div class = 'ComposeImageDiv'><img src = '"+profilephotopath
                        +"' /></div>"+name+"</li>");

                    }
				
                    // binding click event to li
                    $("#ComposeSearchResult li").bind("click",function(){
                        selectUser(this);
                    });

                }
            });
        }

    });

});

// Set Text to search box and get details
function selectUser(element){
    //alert("got into selectuser");

    var userid = $(element).val();
    

    $( "#NewMessageSearchContainer" ).css("display", "none");
    $( "#ChatContainer" ).css("display", "block");
       

    

    $( "#ChatContainer" ).load( "chatbody.php?friendid=" + userid );
    

}


</script>




  </body>
  
 </html>
