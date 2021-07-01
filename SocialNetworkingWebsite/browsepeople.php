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

}

if(isset($_POST['myUnblockUserID'])){

  
    $myUnblockUserID = $_POST['myUnblockUserID'];
    
    
    if ($myID > $myUnblockUserID){
      $userOne = $myUnblockUserID;
      $userTwo = $myID;
      
    }
    else {
      $userOne = $myID;
      $userTwo = $myUnblockUserID;
      
    }
    
    
    
    
    $query = "DELETE FROM friends WHERE user_one_id = '$userOne' AND user_two_id = '$userTwo'";


    if (!mysqli_query($con,$query)){
        echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
        
    }

  
}

if(isset($_POST['myRequesteeID'])){
   
  
  
 
    $toFriend = $_POST['myRequesteeID'];
    
    $timestamp = date("Y-m-d H:i:s");
    
    
    if ($myID < $toFriend){
    
        $query = "INSERT INTO friends (user_one_id, user_two_id, status, action_user_id, time_of_request)  VALUES ('$myID', '$toFriend', '0', '1', '$timestamp')";
    }
    else {
      $query = "INSERT INTO friends (user_one_id, user_two_id, status, action_user_id, time_of_request)  VALUES ('$toFriend', '$myID', '0', '2', '$timestamp')";
    }
    

   
    if (!mysqli_query($con,$query)){
        echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
        
    }
  
   
}

if(isset($_POST['myCancelRequesteeID'])){

  
  
 
    $toCancelFriendRequest = $_POST['myCancelRequesteeID'];
    
    
    if ($myID>$toCancelFriendRequest){
  
      $query = "DELETE FROM friends WHERE user_one_id=".$toCancelFriendRequest." AND user_two_id=".$myID.";";
    }
    
    else {
      $query = "DELETE FROM friends WHERE user_one_id=".$myID." AND user_two_id=".$toCancelFriendRequest.";";
    }
    
   
    if (!mysqli_query($con,$query)){
        echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';

    }
  
   
}


include('header.php');


?>

<!--Continuing MainContainer:
https://www.codedodle.com/social-network-friends-database/
-->

<!doctype html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
  
    <title>YK Social Network</title>
  
    <link rel="stylesheet" href="css/myprofilestyle.css">
    <script src="js/jquery-3.6.0.js"></script>
  </head>
  
  <body>

<script>
  $(document).ready(function() {
      $( "#HeaderContainer" ).css( "float", "none" );
    
    
  });
</script>

  <div id = "FriendsListContainer">
    
    
   <?php
   
   		if(!isset($_GET['searchterm'])){
    		$sql = "select * from userinfo";
        }
        else {
        	$mySearchTerm = $_GET['searchterm'];
        	$sql = "select * from userinfo where firstname like '%$mySearchTerm%' or lastname like '%$mySearchTerm%'";
        }
 
                      
                      if (!$result = mysqli_query($con,$sql)){
                          echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                          
                      }
                    
                    
 
    
                     
                     while ($row = mysqli_fetch_array($result)) {
                       
                        $userid = $row['id'];
                        $firstname = $row['firstname'];
                        $lastname = $row['lastname'];
                        $profilephotopath = $row['profilephotopath'];
                        $bio = $row['bio'];
                        $hobbies = $row['hobbies'];
                        
                        
                        if ($userid != $myID){
                                      echo '
                                          <div class = "OneFriendContainer" id = "OneFriendBox'.$userid.'">
                                                <div class = "OneFriendProfilePhoto">
                                                    <img src = "'.$profilephotopath.'" />
                                                </div>
                                                
                                                <div class = "OneFriendMiddleDiv">
                                                    <div class = "OneFriendNameDiv">
                                                        <a href = "friendprofilefull.php?id='.$userid.'">
                                                        		'.$firstname.' '.$lastname.'
                                                        </a>
                                                       
                                                        
                                                    </div>
                                                  
                                                    <div class = "OneFriendInfoDiv">
                                                        Bio: '.$bio.'
                                                        
                                                      
                                                    </div>
                                                  
                                                  
                                                </div>
                                                
                                                <div class = "OneFriendIconDiv">
                                                
                                                      <div data-id = "'.$userid.'" class = "OneFriendIconContainer">
                                                            <img class = "imgAddFriend"  id = "imgAdd'.$userid.'" src = "images/addfriend.png"
                                                            onclick="addFriendRequest('.$userid.')"
                                                            
                                                            />
                                                            <img class = "imgCancelAddFriend"  id = "imgCancel'.$userid.'" src = "images/cancelfriend.png"
                                                            onclick="cancelFriendRequest('.$userid.')"
                                                            
                                                            />
                                                            
                                                      </div>
                                                      
                                                      <button class="OneFriendAcceptButton"           id="AcceptButton'.$userid.'" onclick="acceptFriendship('.$userid.')">Accept Friendship</button>
                                                      
                                                      <div class = "OneFriendCurrentlyFriendsButton" id = "CurrentlyFriendsButton'.$userid.'"><img src = "images/check.png" />Friends</div>
                                                      
                                                      <button class = "UnblockButton" id = "UnblockButton'.$userid.'" onclick = "unblockUser('.$userid.')">Unblock User</button>
                                                </div>
                                            
                                          </div>';
                        
                        }
                         
                     }
                        
                  
                  
                        
                
                        
        ?>
    
    
    
        
    
    
  </div>






<script>
  			  function addFriendRequest(requesteeID){
  			    addFriendDoCSS(requesteeID);
  			    
                $.post("browsepeople.php", { myRequesteeID:
                                              requesteeID
                },
                    function(data){
                        
                        // callback function gets executed
                        
                });
        
                // to prevent the default action
                return false;
                
                
			  }
			  
			  function addFriendDoCSS(requesteeID){
			      //alert("got to add function");
			    
			      $( "#imgAdd" + requesteeID ).css( "display", "none" );
  			    
  			    $( "#imgCancel" + requesteeID ).css( "display", "block" );
  			    
  			    
  			    $("div").find("[data-id='"+requesteeID+"']").css({"background-color": "#0571ED"});
			  }
			  
			  function cancelFriendDoCSS(requesteeID){
			      //alert("got to cancel function");
			      
			      $( "#imgCancel" + requesteeID ).css( "display", "none" );
  			    
  			    $( "#imgAdd" + requesteeID ).css( "display", "block" );
  			    
  			    
  			    $("div").find("[data-id='"+requesteeID+"']").css({"background-color": "#E4E6EB"});
			  }
			  
			  function enableAcceptButton(requesteeID){
			      //alert("got to enable accept button function for id " + requesteeID);
			  
			      $("div").find("[data-id='"+requesteeID+"']").css({"display": "none"});
			      
			    
			      $( "#AcceptButton" + requesteeID ).css( "display", "block" );
			  }
			  
			  function enableUnblockButton(requesteeID){
			      //alert("got to enable accept button function for id " + requesteeID);
			  
			      $("div").find("[data-id='"+requesteeID+"']").css({"display": "none"});
			      
			    
			      $( "#UnblockButton" + requesteeID ).css( "display", "block" );
			  }
			  
			  function enableCurrentlyFriendsButton(requesteeID){
			      $("div").find("[data-id='"+requesteeID+"']").css({"display": "none"});
			      $( "#CurrentlyFriendsButton" + requesteeID ).css( "display", "block" );
			      //$( "#BlockButton" + requesteeID ).css( "display", "block" );
			  }
			  
			   function cancelFriendRequest(requesteeID){
			   
			      
			      
			          cancelFriendDoCSS(requesteeID);
			     
  			    
  			    
        			  
                $.post("browsepeople.php", { myCancelRequesteeID:
                                              requesteeID
                },
                    function(data){
                        //cancelFriendDoCSS(requesteeID);
                        // callback function gets executed
                        
                });
        
                // to prevent the default action
                return false;
			  }
  
  
        function acceptFriendship(userID){
                //alert("got here for user: " + userID);
                acceptFriendshipDoCSS(userID);
                
                
                
                
                
                $.post("browsepeople.php", { myAcceptFriendshipID:
                                              userID
                },
                    function(data){
                        //cancelFriendDoCSS(requesteeID);
                        // callback function gets executed
                        
                });
        
                // to prevent the default action
                return false;
        }
        
        function unblockUser(userID){
                	  cancelFriendDoCSS(requesteeID);

                      $.post("browsepeople.php", { myUnblockUserID:
                                                    userID
                      },
                          function(data){
                              //alert("succesfully posted");
                              //cancelFriendDoCSS(requesteeID);
                              // callback function gets executed
                              
                      });
              
                      // to prevent the default action
                      return false;
                  
                
        }
        
        function acceptFriendshipDoCSS(userID){
                //alert("got here 2 for user: "+ userID);
                //$( ".OneFriendIconDiv").remove();
                
                
                //$( "#OneFriendBox" + userID ).remove();
                
                $( "#AcceptButton" + userID ).remove();
                
                enableCurrentlyFriendsButton(userID);
        }
  
  
</script>







<div>Icons made by <a href="https://www.freepik.com" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>

</div><!--End of MainContainer-->

</body>

</html>


<?php

  
                        $sql = "select * from friends";
                    
 
                      
                      if (!$result = mysqli_query($con,$sql)){
                          echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                          
                      }
                    
                    
 
    
                     
                     while ($row = mysqli_fetch_array($result)) {
                       
                        $UserOneID = $row['user_one_id'];
                        $UserTwoID = $row['user_two_id'];
                        $Status = $row['status'];
                        $ActionUserID = $row['action_user_id'];
                        
                        
                        
                    
                        
                     
                        
                        if ($UserOneID == $myID && $ActionUserID == 1 && $Status == 0){
                            
                            echo '<script type="text/javascript">addFriendDoCSS("'.$UserTwoID.'");</script>';
                        }
                      
                      
                        if ($UserTwoID == $myID && $ActionUserID == 2 && $Status == 0){
                            
                            echo '<script type="text/javascript">addFriendDoCSS("'.$UserOneID.'");</script>';
                        }
                        
                        
                        
                        
                        
                            
                        if ($UserOneID == $myID && $ActionUserID == '2' && $Status =='0'){
                          echo '<script type="text/javascript">enableAcceptButton("'.$UserTwoID.'");</script>';
                        }
                        if($UserTwoID == $myID && $ActionUserID == '1' && $Status =='0'){ //usertwoid = $myID
                          echo '<script type="text/javascript">enableAcceptButton("'.$UserOneID.'");</script>';
                        }
                        
                        
                        if ($UserOneID == $myID && $Status == '1'){
                          echo '<script type="text/javascript">enableCurrentlyFriendsButton("'.$UserTwoID.'");</script>';
                         
                        }
                        
                        if ($UserTwoID == $myID && $Status == '1'){
                          echo '<script type="text/javascript">enableCurrentlyFriendsButton("'.$UserOneID.'");</script>';
                         
                        }
                        
                        if ($UserOneID == $myID && $ActionUserID == '1' && $Status =='3'){
                          echo '<script type="text/javascript">enableUnblockButton("'.$UserTwoID.'");</script>';
                        }
                        if($UserTwoID == $myID && $ActionUserID == '2' && $Status =='3'){ 
                          echo '<script type="text/javascript">enableUnblockButton("'.$UserOneID.'");</script>';
                        }
                        
                        if ($UserOneID == $myID && $ActionUserID == '2' && $Status =='3'){
                          echo '<script type="text/javascript">deleteUserBox("'.$UserTwoID.'");</script>';
                        }
                        if($UserTwoID == $myID && $ActionUserID == '1' && $Status =='3'){ 
                          echo '<script type="text/javascript">deleteUserBox("'.$UserOneID.'");</script>';
                        }
                        
                        
                        
                        
                        if ($UserOneID == $myID && $ActionUserID == '2' && $Status =='2'){
                          echo '<script type="text/javascript">addFriendDoCSS("'.$UserTwoID.'");</script>';
                        }
                        if($UserTwoID == $myID && $ActionUserID == '1' && $Status =='2'){ 
                          echo '<script type="text/javascript">addFriendDoCSS("'.$UserOneID.'");</script>';
                        }
                        
                        
                        if ($UserOneID == $myID && $ActionUserID == '1' && $Status =='2'){
                          echo '<script type="text/javascript">enableAcceptButton("'.$UserTwoID.'");</script>';
                        }
                        if($UserTwoID == $myID && $ActionUserID == '2' && $Status =='2'){ 
                          echo '<script type="text/javascript">enableAcceptButton("'.$UserOneID.'");</script>';
                        }
                        
                        
                        
                        
                        
                        
                     }



                        
                        
                     


?>
