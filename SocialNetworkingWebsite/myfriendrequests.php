<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('header.php');

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


if(isset($_POST['myDeleteFriendshipRequestID'])){
  
    $myDeleteFriendshipID = $_POST['myDeleteFriendshipRequestID'];
    
    
    if ($myID > $myDeleteFriendshipID){
      $userOne = $myDeleteFriendshipID;
      $userTwo = $myID;
      $actionID = 2;
    }
    else {
      $userOne = $myID;
      $userTwo = $myDeleteFriendshipID;
      $actionID = 1;
    }
    
    
    
    
    $query = "UPDATE friends SET status = '2', action_user_id = '$actionID' WHERE user_one_id = '$userOne' AND user_two_id = '$userTwo'";


    if (!mysqli_query($con,$query)){
        echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
        
    }
  
  
}





?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">

  <title>YK Social Network Friend Requests</title>
  <meta name="description" content="YK Social Network Friend Requests">
  <meta name="author" content="Yonatan Kirby">

  <link rel="stylesheet" href="css/friendrequestsstyle.css">
  <link rel="stylesheet" href="css/sentrequestsstyle.css">

</head>
<!--Continuing MainContainer:
https://www.codedodle.com/social-network-friends-database/
-->
<body>
  
  <div id = "SentRequests">
      <div id = "SentHeaderDiv">
          Sent Requests
        
          <div id = "SentHeaderCancel">
              <img src = "images/cancel.png" onclick = "closeSentRequests();"/>
          </div>
      </div>
      
      <div id = "SentBodyDiv">
        
        
        
      </div>
    
    
  </div>
  
  <script>
      function closeSentRequests(){
          $("#SentRequests").css("display","none");
      }
  </script>
  
  
  
      <div id = "ComboContainer">
            <div id = "FriendRequestsContainer">
                <div id = "FRInnerContainer">
                    <div id = "FRTopRow">
                          Friends
                    </div>
                    
                
                
                
                
                <?php
                
                
                      $sql = "select * from friends where user_one_id = '$myID' and action_user_id = '2' and status = '0'
                      
                          union select * from friends where user_two_id = '$myID' and action_user_id = '1' and status = '0'";
                    
                  
 
                      
                      if (!$result = mysqli_query($con,$sql)){
                          echo '<script type="text/javascript">alert("'.mysqli_error($con).' line 45");</script>';
                          
                      }
                      
                      
                      $row_cnt = mysqli_num_rows($result);
                      
                      
                      echo '<div id = "FRSecondRow">'.$row_cnt.' Friend Request';
                        
                         if ($row_cnt != 1){
                           echo 's';
                         }
                    
                      
                      
                          echo '</div></div>';
                          
                          ?>
                          
                          
                          
                          <div id = "ViewSentRequests" onclick="showSentRequests()">View Sent Requests</div>
                          
                          
                          
                          
                          <?php
                      
                    
                    
                    $IDsToQuery = (array) null;
                    
                    $TimesOfRequest = (array) null;
                    
                    
                    
                    while ($row = mysqli_fetch_array($result)) {
                       
                        $UserOneID = $row['user_one_id'];
                        $UserTwoID = $row['user_two_id'];
                        $Status = $row['status'];
                        $ActionUserID = $row['action_user_id'];

                        array_push($TimesOfRequest,$row['time_of_request']);

                        if ($ActionUserID==2){
                          array_push($IDsToQuery,$UserTwoID);
                        }
                        else {
                          array_push($IDsToQuery,$UserOneID);
                          
                        }
                    }
                    
                    
                     
                    if ($IDsToQuery!=null){
                    
                                        $sql = "select * from userinfo where id in (" . implode(',', $IDsToQuery) . ")";
                                        
                                        
                                        
                                        if (!$result = mysqli_query($con,$sql)){
                                              echo '<script type="text/javascript">alert("'.mysqli_error($con).' line 83");</script>';
                                              
                                        }
                                        
                                        $i = 0;
                                        
                                        
                                            
                                        
                                            
                                        while ($row = mysqli_fetch_array($result)) {
                                           
                                              $firstname = $row['firstname'];
                                              $lastname = $row['lastname'];
                                              $profile_src = $row['profilephotopath'];
                                              $friendid = $row['id'];
                                          
                                      
                                              $datetime1 = new DateTime($TimesOfRequest[$i]);
                                              $datetime2 = new datetime(date("Y-m-d H:i:s"));
                                              $interval = $datetime1->diff($datetime2);
                                              
                                              $yearString = $interval->format('%Y');
                                              $monthString = $interval->format('%m');
                                              $dayString = $interval->format('%d');
                                              $hourString = $interval->format('%H');
                                              $minuteString = $interval->format('%i');
                                              $secondString = $interval->format('%s');
                                              
                                              $outputTime = "";
                                              
                                              if ($yearString!="00"){
                                                $outputTime = $yearString . "y";
                                                if (substr($outputTime, 0, 1)=="0"){
                                                        $outputTime = substr($outputTime, 1);
                                                }
                                              }
                                              else {
                                                if ($monthString!="0"){
                                                  $outputTime = $monthString . "m";
                                                }
                                                else {
                                                  if ($dayString!="0"){
                                                    $outputTime = $dayString . "d";
                                                  }
                                                  else {
                                                    if ($hourString!="00"){
                                                      $outputTime = $hourString . "h";
                                                      if (substr($outputTime, 0, 1)=="0"){
                                                        $outputTime = substr($outputTime, 1);
                                                      }
                                                    }
                                                    else{
                                                      if ($minuteString!="0"){
                                                        $outputTime = $minuteString . "m";
                                                      }
                                                      else {
                                                        $outputTime = $secondString . "s";
                                                      }
                                                    }
                                                  }
                                                }
                                                
                                              
                                              
                                            }
                                    
                                            
                                    
                                            echo '
                                                     <div class = "OneFriendRequest" id = "OneFriendBox'.$friendid.'">
                                                          <div class = "InnerBlueDiv" onclick="showFriendProfile('.$friendid.')">
                                                          
                                                                <div class = "PhotoColumn">
                                                                      <img src = "'.$profile_src.'" />
                                                                  
                                                                </div>
                                                            
                                                                <div class = "SecondColumn">
                                                                      <div class = "NameDiv">
                                      
                                                                            '.$firstname.' '.$lastname.'
                                                                            
                                                                            <div class = "TimeDiv">
                                                                                  '.$outputTime.'
                                                                            </div>
                                                                            
                                    
                                                                            
                                                                      </div>
                                                                      
                                                                      
                                                                      
                                                                      
                                                                      <div class = "ConfirmDeleteDiv">
                                                                            
                                                                            <button class = "ConfirmButton" id = "ConfirmButton'.$friendid.'"  onclick="acceptFriendship('.$friendid.')">
                                                                                  Confirm
                                                                            </button>
                                                                            
                                                                            <button class = "DeleteButton" onclick="deleteFriendshipRequest('.$friendid.')">
                                                                                  Delete
                                                                            </button>
                                                                        
                                                                        
                                                                      </div>
                                                                      
                                                                </div>
                                                          
                                                          </div>
                                                      
                                                    </div>
                                                        
                                                    ';
                                                    
                                                $i++;
                                    
                                            }
                    
                              
                
                
                    }
                
                






                ?>
                
                
                
                
                
                
                
                
                
                  
                
  
            </div>
            <div id = "FriendProfileDiv">
            </div>
            
            
      </div>
      
      
      <script>
      
      
$(document).ready(function () {
   
      $(".ConfirmButton").on("hover", function () {
        
        $(this).css({"cursor": "pointer","background-color":"#1771E6"});
        }, function(){
        $(this).css({"cursor": "auto","background-color":"#1877F2"});
      });
});












        function acceptFriendshipDoCSS(userID){
                
                
                
                $( "#OneFriendBox" + userID ).css("display","none");
        }
      
      function acceptFriendship(userID){
               
                acceptFriendshipDoCSS(userID);
                

                
                $.post("acceptfriend.php", { myAcceptFriendshipID:
                                              userID
                },
                    function(data){
                        
                        
                });
        
                // to prevent the default action
                return false;
        }
        
        
        function deleteFriendshipRequest(userID){
               
                acceptFriendshipDoCSS(userID);

                
                $.post("myfriendrequests.php", { myDeleteFriendshipRequestID:
                                              userID
                },
                    function(data){
                        
                        // callback function gets executed
                        
                });
        
                // to prevent the default action
                return false;
        }
        
        function showSentRequests(){
          $( "#SentRequests").css("display","block");
          
          $('#SentBodyDiv').load("mysentrequests.php");
          
        }
        
        function showFriendProfile(friendID){
            
        	$('#FriendProfileDiv').load("friendprofile.php?friendid="+friendID);
        }
        
      
      </script>
</body>
</html>
