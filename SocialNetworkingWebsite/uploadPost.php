<?php session_start();
include("config.php");


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$myID = 0;
if (isset($_SESSION['userid'])) {
    $myID = $_SESSION["userid"];
}
else {
    header("Location: index.php");
    
}

if ($myID == 0) {
    header('Location: index.php');

}



$posteeID = $_POST["posteeID"];



$response = '';




if(isset($_POST['pmessage'])) {
    
      $sMessage = $_POST['pmessage'];
	  $taggedPeople = $_POST['taggedPeople']; //explode(",", $_POST['taggedPeople']);
      $taggedVerbose = $_POST["taggedVerbose"];

      $firstname = $_SESSION["firstname"];
      $lastname = $_SESSION["lastname"];
      $profilesrc = $_SESSION["profile_src"];
	


      $myName = $_SESSION["firstname"].' '.$_SESSION["lastname"];
      $myProfileSrc = $_SESSION["profile_src"];

      $sMessage = addslashes($sMessage);
	
      if ($sMessage != '') {



        

          $sql = "INSERT INTO posts (posterID, posteeID, htmlcontents, taggedpeople, posterprofilesrc, posterfirstname, posterlastname, parentid)
          VALUES ('$myID', '$posteeID', '$sMessage', '$taggedPeople', '$profilesrc', '$firstname', '$lastname', -1)";
          
          
          // Perform a query, check for error
          
          
          if (!mysqli_query($con,$sql)){
              $response .= '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
              
          }
          
          $sql = "SELECT LAST_INSERT_ID()";

          if (!$result = mysqli_query($con,$sql)){
            $response .= '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
            
          }

          $row = mysqli_fetch_array($result);

          $postid = $row[0];
          
          
         

                        //get all my friends and postee's friends


                        $sql = "SELECT * FROM friends WHERE status = '1' AND (user_two_id = '$myID' OR user_one_id = '$myID' OR user_two_id = '$posteeID' OR user_one_id = '$posteeID')";

                        if (!$result1 = mysqli_query($con,$sql)){
                        echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                        
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


                        $IDsToQuery = array_unique($IDsToQuery);

                        $myCount = count($IDsToQuery);

                        

                        for ($i = 0; $i < $myCount; $i++){
                                        $currentID = $IDsToQuery[$i];
                                        
                                        if ($currentID != $myID){
                                                $sql = "INSERT INTO notifications (notificationrecipient, posterID, posteeID, postid, notification_message, postername, posterprofilesrc, wallid)
                                                                                VALUES ('$currentID', '$myID', '$posteeID', '$postid', 'post_on_wall', '$myName', '$myProfileSrc', '$posteeID')";
                                                                                
                                                                                
                                                                                // Perform a query, check for error
                                                                                
                                                                                
                                                if (!mysqli_query($con,$sql)){
                                                    $response .= '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                                                    
                                                }
                                        }

                        }
                        
        //}

          $response .=
                    '
                    <div class = "PostsOnePost">
                        <div class = "PostsOnePostInner">
                            <div class = "PostsOnePostTop" id = "PostsOnePostTop'.$postid.'">
                                <img class = "PostsOnePostProfileSrc" src = "'.$profilesrc.'" />
                                <div class = "PostsOnePostNameDiv">'.$firstname.'&nbsp;'.$lastname.'
                                ';
                                $taggedPeople = explode(",", $_POST['taggedPeople']);
                                if (count($taggedPeople) > 2){

                                    $counter = 0;
                                    
                                    while(count($taggedPeople) > 2){
                                       
                                        if ($counter == 0){
                                            $response .= 'is with ';
                                        }
                                        else if (count($taggedPeople) == 3){
                                            $response .= ', and ';
                                        }
                                        else {
                                            $response .= ', ';
                                        }

                                        $response .= '<a href = "http://www.socialnetwork.a2hosted.com/friendprofilefull.php?id='.$taggedPeople[0].'">'
                                        .$taggedPeople[1].'&nbsp;'.$taggedPeople[2].'</a>';


                                        $sql = "INSERT INTO notifications (notificationrecipient, posterID, posteeID, postid, notification_message
                                        , postername, posterprofilesrc)
                                        VALUES ('$taggedPeople[0]', '$myID', '$posteeID', '$postid', 'tagged_in_post', '$myName', '$myProfileSrc')";
                                        
                                        
                                        // Perform a query, check for error
                                        
                                        
                                        if (!mysqli_query($con,$sql)){
                                            $response .= '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                                            
                                        }
                              




                                        $taggedPeople = array_slice($taggedPeople,3);

                              

                                        $counter++;
                                    }
                                }
                                
                                $response .= '</div>
                                <div class = "PostsOnePostTimeDiv">'.date("F jS, Y", (time())).'</div>
                            </div>
                            <div id = "PostsPagePostHTMLContents'.$postid.'"><script>$("#PostsOnePostTop'.$postid.'").after(decodeURI("'.$sMessage.'"));</script></div>


                            <div class = "PostsPagePostLikes" id = "PostsPagePostLikes'.$postid.'">
                                <img src = "images/circlelike.png" />
                            </div>
                        
                            <div class = "LikeCommentShare" id = "LikeCommentShare'.$postid.'">
                                <div class = "LikeDiv ItemButton" data-wall_id = "'.$posteeID.'" data-post_author_id="'.$myID.'" id = "LikeDiv'.$postid.'" data-fullname = "'.$_SESSION['firstname'].' '.$_SESSION['lastname'].'">
                                    <div class = "LikeInnerDiv">
                                            <img src = "images/like.png" id = "LikeImage'.$postid.'" onclick = "toggleLike('.$postid.')"/>
                                            Like
                                    </div>
                                </div>
                                <div class = "CommentDiv ItemButton" id = "CommentDiv'.$postid.'">
                                    <div class = "CommentInnerDiv">
                                            Comment
                                            <img src = "images/comment3.png" />
                                    </div>
                                </div>
                                <div class = "ShareDiv ItemButton" id = "ShareDiv'.$postid.'">
                                    <div class = "ShareInnerDiv">
                                            Share
                                            <img src = "images/share.png" />
                                    </div>
                                    <div class = "ShareDivHidden" id = "ShareDivHidden'.$postid.'">
                                        <div class = "ShareDivHiddenSelf" data-id = "'.$postid.'">
                                            Share now (Friends)
                                        </div>

                                        <div class = "ShareDivHiddenFriend" data-id = "'.$postid.'">
                                            Share on a friend\'s profile
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class = "WriteCommentDiv" id = "WriteCommentDiv'.$postid.'">
                                <img src = "'.$_SESSION["profile_src"].'" />
                                <div id = "WriteComment'.$postid.'" contentEditable = "true" class = "PostsPageMakeComment" data-CommentToMakeStatus = "comment" data-CurrentCommentStatus = "comment" data-wall_id = "'.$posteeID.'"data-PostAuthorID = "'.$myID.'" name = "'.$myID.'">
                                    Write a comment...
                                </div>
                            </div>




                        </div>
                    </div>
                    
                    
                    
                    ';
          


      }

      
      
  }




  
  echo $response;
  
  

?>