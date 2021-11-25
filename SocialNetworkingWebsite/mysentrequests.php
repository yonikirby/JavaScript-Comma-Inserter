<?php session_start();

include('config.php');

$myID = $_SESSION["userid"];


if(isset($_POST['myCancelRequestID'])){

    $myCancelRequestID = $_POST['myCancelRequestID'];
    
    
    if ($myID > $myCancelRequestID){
      $userOne = $myCancelRequestID;
      $userTwo = $myID;
      $actionID = 2;
    }
    else {
      $userOne = $myID;
      $userTwo = $myCancelRequestID;
      $actionID = 1;
    }
    
    
    
    
    $query = "DELETE from friends WHERE user_one_id = '$userOne' AND user_two_id = '$userTwo'";


    if (!mysqli_query($con,$query)){
        echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
        
    }

}




?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">

  <link rel="stylesheet" href="css/sentrequestsstyle.css">

</head>

<body>
    
  
  <div id = "InnerContainer">
  
  
  <?php
      $sql = "select * from friends where user_one_id = '$myID' and action_user_id = '1' and status = '0'
                      
          union select * from friends where user_two_id = '$myID' and action_user_id = '2' and status = '0'";
    
  

      
      if (!$result = mysqli_query($con,$sql)){
          echo '<script type="text/javascript">alert("'.mysqli_error($con).' line 45");</script>';
          
      }
      
      
      $row_cnt = mysqli_num_rows($result);
      
      
      echo '
      
            <div id = "NumOfSentRequests">
              '.$row_cnt.' Sent Request';
              
              
              if ($row_cnt!=1){
                echo 's';
              }
              
              
              
              echo'
            </div>
      
      
      
      
      
      ';
  
            $IDsToQuery = (array) null;
  
            while ($row = mysqli_fetch_array($result)) {
                    $UserOneID = $row['user_one_id'];
                    $UserTwoID = $row['user_two_id'];
                    $Status = $row['status'];
                    $ActionUserID = $row['action_user_id'];


                    if ($ActionUserID==2){
                      array_push($IDsToQuery,$UserOneID);
                    }
                    else {
                      array_push($IDsToQuery,$UserTwoID);
                      
                    }
           
            }
            
            
            if ($IDsToQuery!=null){
                    
                                        $sql = "select * from userinfo where id in (" . implode(',', $IDsToQuery) . ")";
                                        
                                        
                                        
                                        if (!$result = mysqli_query($con,$sql)){
                                              echo '<script type="text/javascript">alert("'.mysqli_error($con).' line 83");</script>';
                                              
                                        }
                                        
                                        
                                            
                                        while ($row = mysqli_fetch_array($result)) {
                                           
                                              $firstname = stripslashes($row['firstname']);
                                              $lastname = stripslashes($row['lastname']);
                                              $profile_src = $row['profilephotopath'];
                                              $friendid = $row['id'];
                                        
                                          
                                          
                                          
                                              echo '
                                          
                                                  <div class = "OneSentFriendRequest" id = "OneSentFriendRequest'.$friendid.'">
                                                        <div class = "PhotoContainer">
                                                              <img src = "'.$profile_src.'" />
                                                        </div>
                                                    
                                                        <div class = "InfoContainer">
                                                              <div class = "SentNameContainer">
                                                                    <a href = "friendprofilefull.php?id='.$friendid.'">'.$firstname.' '.$lastname.'</a>
                                                              </div>

                                                          
                                                          
                                                        </div>
                                                    
                                                        <div class = "CancelRequestContainer">
                                                              <button class = "CancelButton" onclick="cancelRequest('.$friendid.')">Cancel Request</button>
                                                          
                                                        </div>
                                                    
                                                    
                                                  </div>
                                          
                                          
                                          
                                          
                                          
                                          
                                          
                                          
                                              ';
                                              
                                              
                                              
                                        }
                                        
                                        
                                        
                                        
                                        
            }
  
  ?>
  

      
    
      
    
    
    
    
 
    
  </div>
  
  <script>
  function cancelRequestDoCSS(userID){
           
                
                $( "#OneSentFriendRequest" + userID ).remove();
        }
      
      function cancelRequest(userID){
            
                cancelRequestDoCSS(userID);
                

                
                $.post("mysentrequests.php", { myCancelRequestID:
                                              userID
                },
                    function(data){
                     
                        
                });
        
                // to prevent the default action
                return false;
        }
  
  </script>
</body>
</html>
