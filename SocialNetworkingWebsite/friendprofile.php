<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("config.php");



$myID = $_GET['friendid'];

$_SESSION['friendid'] = $myID;

$targetPostID = $_GET['postid'];


if ($myID == $_SESSION['userid']){
    header('myprofile.php?postid='.$targetPostID);
}


if(isset($_POST['deleteImageID'])){
 
    $toDelete = $_POST['deleteImageID'];
    $toDeletePath = $_POST['deleteImageSRC'];
    
    
    $query = "DELETE FROM images WHERE id=".$toDelete.";";
    
   
    if (!mysqli_query($con,$query)){
        echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
        //mysqli_close($con);
    }
  
  
    unlink($toDeletePath);
    
   
}






if(isset($_POST['but_upload'])){
 
  $name = $_FILES['file']['name'];
  
  $dir = "upload/".$myID;
  if ( !file_exists( $dir ) && !is_dir( $dir ) ) {
    mkdir( $dir );
  }
  $target_dir = "upload/".$myID."/";
  $target_file = $target_dir . basename($_FILES["file"]["name"]);


  // Select file type
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  // Valid file extensions
  $extensions_arr = array("jpg","jpeg","png","gif");

  // Check extension
  if( in_array($imageFileType,$extensions_arr) ){
 
    
      $filepath = $target_dir.$name;
    
    

      if ( !file_exists( $target_file ) ) {
        
      
              // Insert record
              $query = "INSERT INTO images (imagepath, userid)
                    VALUES ('$filepath', '$myID')";
              
             
              if (!mysqli_query($con,$query)){
                  echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                  //mysqli_close($con);
              }
      }
  
     // Upload file
     move_uploaded_file($_FILES['file']['tmp_name'],$target_dir.$name);
    
  }
 
}





                       $sql = "select * from userinfo where id='$myID'";
                      
                      
                      
                      
                        
                        
                        if (!mysqli_query($con,$sql)){
                            echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                            //mysqli_close($con);
                        }
                      
                      
                      $result = mysqli_query($con,$sql);
   
                       $row = mysqli_fetch_array($result);
                      
                       
                       $cover_src = $row['coverphotopath'];
                       $_SESSION['friendprofilesrc'] = $profile_src = $row['profilephotopath'];
                       $_SESSION['friendfirstname'] = $firstname = stripslashes($row['firstname']);
                       $_SESSION['friendlastname'] = $lastname = stripslashes($row['lastname']);
                       $bio = $row['bio'];
                       $hobbies = $row['hobbies'];
                       
                       
                       
                      



?>
  

  <!doctype html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
  
    <title>YK Social Network</title>
    <meta name="description" content="My Profile">
    <meta name="author" content="YK Social Network">
  
    <link rel="stylesheet" href="css/friendprofilestyle.css">
    <link rel="stylesheet" href="css/friendscontainerstyle.css">
    <script src="js/jquery-3.6.0.js"></script>
  </head>
  
  <body>
      <script>var isFriends = false;</script>
      <div id = "MainContainer">
      
      
            <div id = "CoverPhotoContainer">
                  
                      <img src='<?php echo $cover_src; ?>' >
              
              
            </div>
      
      
            <div id = "ProfilePhotoContainer">
              
                      
                      <img src='<?php echo $profile_src; ?>' >
              
                      
            </div>
      
      
            <div id = "NameContainer">
                      
                      <?php echo $firstname; ?>&nbsp;<?php echo $lastname; ?>
              
             
              
              
            </div>
      
            <div id = "MenuBar">
              
				                <div class = "Tab" id = "PostsTab" onclick="showPosts()">
                          Posts
                        </div>
                        <div class = "Tab" id = "AboutTab" onclick="showAbout()">
                          About
                        </div>
                        <div class = "Tab" id = "FriendsTab" onclick="showFriends()">
                          Friends
                        </div>
                        <div class = "Tab" id = "PhotosTab" onclick="showPhotos()">
                          Photos
                        </div>
                        <div id = "AddPhotoTab" onclick="openUploadBox()()">
                          Add Photo
                        </div>
                        <script>
                            var currentFriendID = <?php echo $myID; ?>;
                           
                            

                            function addFriend(requesteeID){
                                        
                                      $.post("addfriend.php", { myRequesteeID:
                                                                    requesteeID
                                      },
                                          function(data){
                                              
                                              // callback function gets executed
                                              
                                      });
                                      addFriendDoCSS(requesteeID);
                                      // to prevent the default action
                                      return false;
                                      
                                    
                            }
                            
                            function addFriendDoCSS(id){
                                
                                $("#MyFriendsTabAddFriend").css("display","none");
                                $("#FriendRequestedTab").css("display","inline-block");
                                
                            }           

                            function cancelFriendRequest(requesteeID){
			   
			      
			      
                                      cancelFriendRequestDoCSS(requesteeID);
                                  
                                  
                                  
                                      
                                      $.post("cancelfriendrequest.php", { myCancelRequesteeID:
                                                                    requesteeID
                                      },
                                          function(data){
                                              
                                              
                                      });
                              
                                      // to prevent the default action
                                      return false;
                              }

                              function cancelFriendRequestDoCSS(requesteeID) {
                                      $("#FriendRequestedTab").css("display","none");
                                      $("#MyFriendsTabAddFriend").css("display","inline-block");
                                      
                              }


                              function unfriendUserDoCSS(IDToRemove){
                                
                                  $("#MyFriendsTab").css("display","none");
                                  $("#MyFriendsTabAddFriend").css("display","inline-block");
                                  
                              
                              }

                              function unfriendUser(id) {
                                  
                                                    unfriendUserDoCSS(id);
                                    
                                    
                                    
                                    
                                    
                                    $.post("deletefriend.php", { myDeleteFriendshipID:
                                                                id
                                    },
                                        function(data){
                                        
                                            
                                    });
                            
                                    // to prevent the default action
                                    return false;
                              }

                              function acceptFriendshipDoCSS(){
                                $("#AcceptFriendshipTab").css("display","none");
                                $("#MyFriendsTab").css("display","inline-block");
                                
                              }

                              function acceptFriendship(id) {
                                                    acceptFriendshipDoCSS();
                                    
                                    
                                    
                                    
                                    
                                    $.post("acceptfriend.php", { myAcceptFriendshipID:
                                                                id
                                    },
                                        function(data){
                                        
                                            
                                    });
                            
                                    // to prevent the default action
                                    return false;
                              }

                        </script>
                        <div id = "MyFriendsTabAddFriend" class = "HiddenTab" onclick = "addFriend('<?php echo $myID; ?>')"><img src = "images/addfriend.png" />Add Friend</div>
                        
                        <div id = "MyFriendsTab" class = "HiddenTab">Friends <img src = "images/check2.png" />
                              
                              <div id = "MyUnfriendTab" onclick = "unfriendUser('<?php echo $myID; ?>')">Unfriend <img src = "images/unfriend.png" /></div>
                              
                        </div>

                        <div id = "FriendRequestedTab" class = "HiddenTab" onclick = "cancelFriendRequest('<?php echo $myID; ?>')"><img src = "images/cancel.png" />Cancel Add </div>
                                 
                        <div id = "AcceptFriendshipTab" class = "HiddenTab" onclick = "acceptFriendship('<?php echo $myID; ?>')"><img src = "images/addfriend.png" />Accept</div>
                        
                        <script>$("#MyFriendsTab").hover(function(){
                                    $("#MyUnfriendTab").css("display", "block");
                                    }, function(){
                                    $("#MyUnfriendTab").css("display", "none");
                                    });
                        </script>
                        <?php
                        
                        $user1 = 0;
                        $user2 = 0;
                        
                        if ($myID > $_SESSION["userid"]){
                        	$user1 = $_SESSION["userid"];
                        	$user2 = $myID;
                        	
                        }
                        
                        else {
                        	$user2 = $_SESSION["userid"];
                        	$user1 = $myID;
                        	
                        }
                        
                        $sql = "select * from friends where user_one_id = '$user1' and user_two_id = '$user2'";
                      
                        if (!$result = mysqli_query($con,$sql)){
                            echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                            
                        }
                        
                        
                    
                        if (mysqli_num_rows($result) == 0){
                            
                            echo '<script>$("#MyFriendsTabAddFriend").css("display","inline-block");</script>';
                         
                        }
                        else {
                        
                          $row = mysqli_fetch_array($result);
                            if ($row['status']==1) { 
                                echo '<script>isFriends = true;</script>';
                               
                                echo '<script>$("#MyFriendsTab").css("display","inline-block");</script>';
                             
                            }
                            
                            
                            else if ($row['status']==0) {
                                
                              
                                    if (($row['user_one_id'] == $_SESSION["userid"] && $row['action_user_id'] == 1) ||
                                    ($row['user_two_id'] == $_SESSION["userid"] && $row['action_user_id'] == 2)){
                                        
                                        echo '<script>$("#FriendRequestedTab").css("display","inline-block");</script>';
                                    }
                                    else {
                                        echo '<script>$("#AcceptFriendshipTab").css("display","inline-block");</script>';
                                        
                                    }
                            }

                            else if ($row['status']==2) {
                                
                                if (($row['user_one_id'] == $_SESSION["userid"] && $row['action_user_id'] == 2) ||
                                ($row['user_two_id'] == $_SESSION["userid"] && $row['action_user_id'] == 1)){
                                    
                                    echo '<script>$("#FriendRequestedTab").css("display","inline-block");</script>';
                                }
                                else {
                                    
                                    echo '<script>$("#AcceptFriendshipTab").css("display","inline-block");</script>';
                                }
                        }
                          }
                        ?>
                        <div id = "MessageTab" class = "Tab" onclick="openFriendMessenger()">Message </div>
                        
                        <form action = "chat.php" method = "post" id="ChatForm">
                        <input type="hidden" value="<?php echo $myID ?>" id="chatid" name="chatid">
                        
                        
                        </form>
                        
						
                        
                        
                        <div id = "PhotoUpload">
  
                            <form method="post" action="" enctype='multipart/form-data'>
                              Select Image to Upload:
                              <input type='file' name='file' />
                              <input type='submit' value='Save' name='but_upload'>
                            </form>
                              <img src="images/cancel.png" onclick="closeUploadBox()"/>
                        </div>
                        <script>
                          function closeUploadBox() {
                            document.getElementById("PhotoUpload").style.zIndex = "-100";
                            document.getElementById("PhotoUpload").style.display = "none";
                          }
                          function openUploadBox() {
                            document.getElementById("PhotoUpload").style.zIndex = "100";
                            document.getElementById("PhotoUpload").style.display = "block";
                          }
                        </script>

              
            </div>
			<script type = "text/javascript">
			
  			function showPhotos() {
  			  <?php $_SESSION["LastTabActivated"] = "Photos"; ?>
          document.getElementById("PostsTab").style.backgroundColor = "transparent";
          document.getElementById("AboutTab").style.backgroundColor = "transparent";
          document.getElementById("FriendsTab").style.backgroundColor = "transparent";
          document.getElementById("PhotosTab").style.backgroundColor = "#70b3d4";
          
          
          document.getElementById("PhotosArea").style.display = "block";
          document.getElementById("AboutArea").style.display = "none";
          document.getElementById("FriendsArea").style.display = "none";
          document.getElementById("PostsArea").style.display = "none";
          
          $("#PhotosArea").load('friendprofilephotos.php?friendid=' + <?php echo $myID ?> );
          
          
        }
        
        
        function showPosts() {
          
          document.getElementById("PostsTab").style.backgroundColor = "#70b3d4";
          document.getElementById("AboutTab").style.backgroundColor = "transparent";
          document.getElementById("FriendsTab").style.backgroundColor = "transparent";
          document.getElementById("PhotosTab").style.backgroundColor = "transparent";
          document.getElementById("AddPhotoTab").style.display = "none";
          
          document.getElementById("AboutArea").style.display = "none";
          document.getElementById("PhotosArea").style.display = "none";
          document.getElementById("FriendsArea").style.display = "none";
          document.getElementById("PostsArea").style.display = "block";

          $("#PostsArea").load('friendposts.php?friendid=' + <?php echo $myID ?> + '&postid=' + '<?php echo $targetPostID ?>' );
        }
        
        function showAbout() {
          
          document.getElementById("PostsTab").style.backgroundColor = "transparent";
          document.getElementById("AboutTab").style.backgroundColor = "#70b3d4";
          document.getElementById("FriendsTab").style.backgroundColor = "transparent";
          document.getElementById("PhotosTab").style.backgroundColor = "transparent";
          document.getElementById("AddPhotoTab").style.display = "none";
          
          document.getElementById("AboutArea").style.display = "block";
          document.getElementById("PhotosArea").style.display = "none";
          document.getElementById("FriendsArea").style.display = "none";
          document.getElementById("PostsArea").style.display = "none";
        }
        
        function showFriends() {
          
          document.getElementById("PostsTab").style.backgroundColor = "transparent";
          document.getElementById("AboutTab").style.backgroundColor = "transparent";
          document.getElementById("FriendsTab").style.backgroundColor = "#70b3d4";
          document.getElementById("PhotosTab").style.backgroundColor = "transparent";
          document.getElementById("AddPhotoTab").style.display = "none";
          
          document.getElementById("AboutArea").style.display = "none";
          document.getElementById("PhotosArea").style.display = "none";
          document.getElementById("FriendsArea").style.display = "block";
          document.getElementById("PostsArea").style.display = "none";
          
          $("#FriendsArea").load('myprofileallfriends.php?friendid=' + <?php echo $myID ?> );
        }
        
        function openFriendMessenger(){
        	document.getElementById("ChatForm").submit();
        	
        	
        }

			
			
			  function deleteImage(imageID, imageSRC){

                $.post("myprofile.php", { deleteImageID: imageID, deleteImageSRC:
                                              imageSRC
                },
                    function(data){
                        // callback function gets executed
                       
                });
        
                // to prevent the default action
                return false;
			  }
			  

			  
			  function downloadImage(){
			    
			  }
			  
			
			  

              $( document ).ready(function() {
                    document.getElementById("HeaderContainer").style.width = "1300px";
                    showPosts();
              });

             

                
			</script>
			  
			  <div id = "TabsContainer">
        			  <div id = "AboutArea">
                    <div class = "TextHeader">Bio:</div>
                    <div class = "TextBody">
                    <?php

                            echo $bio;
                        
                        
                    
                    ?>
                    </div>
                    <br /><br />
                    <div class = "TextHeader">Hobbies:</div>
                    <div class = "TextBody">
                    <?php
                    

                            echo $hobbies;
                        
                        
                    
                    ?>
                    </div>
        		    </div>
        		
        		  <div id = "PhotosArea">

        		      
        		      
        		      
                    
        		    
        		  </div>
        		  
        		  <div id = "FriendsArea">
        		      <div id = "FriendsInnerContainer">
        		          <div id = "FriendsFirstRow">
            		          <div id = "FriendsHeaderText">
            		              Friends
            		          </div>
            		          
            		          
            		          <div id = "FriendsSearchBox">
            		              Search:
            		              <input type="text" id="txtSearchFriends" name="txtSearchFriends">
            		          </div>
            		          
            		          <div id="FriendRequestsLinkContainer">
            		              <a href = "myfriendrequests.php"><button type="button">Friend Requests</button></a>
            		            
            		          </div>
            		          <div id = "FindFriendsLinkContainer">
            		              <button type="button">Find Friends</button>
            		          </div>
            		          <div id = "BrowsePeopleLinkContainer">
            		              <a href = "browsepeople.php"><button type="button">Browse People</button></a>
            		          </div>
            		          
            		      </div>
            		      
            		      <div id = "FriendsSecondRow">
            		          <button type="button" style = "color:#1F76F2;
  border-bottom:3px solid #1F76F2;">All Friends</button>
  							  <button type="button">Mutual Friends</button>
            		          <button type="button">Recently Added</button> 
            		          <button type="button">Work</button>
            		          <button type="button">High School</button>
            		          <button type="button">Current City</button>
            		          <button type="button">Hometown</button>
            		          <button type="button">Following</button>

            		      </div>
            		      
            		      
            		      <div id = "FriendsInnerPHPContainer">
            		        
            		        
            		      </div>
            		      
            		      
 
            		      
            		      
            		      
        		      </div>
        		  </div>
        		  
        		  <div id = "PostsArea">
        		    
        		    
        		  </div>
		
		    </div>
		    
		    
		    
		    
		    
		    
		    
		    
		    <div>Icons made by <a href="https://www.freepik.com" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>
		
    </div>
    <?php
if (isset($_POST["adding"])){
    alert("got to post response!");
    echo '<script>$("#PostsList").append('.$_POST["adding"].');</script>';
}

?>




      </body>
  </html>