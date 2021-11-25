<?php session_start();
include("config.php");
include("header.php");


$myID = $_SESSION["userid"];

$_SESSION['friendid'] = $myID;

$friendsSelected = false; //if friends tab is selected

if(isset($_POST['friendsTabSelected'])){
		
			echo '<script type="text/javascript">alert("inside friendsTabSelected");</script>';

			$friendsSelected = true;
	
}


if(isset($_POST['deleteImageID'])){
 
    $toDelete = $_POST['deleteImageID'];
    $toDeletePath = $_POST['deleteImageSRC'];
    
    
    $query = "DELETE FROM images WHERE id=".$toDelete.";";
    
   
    if (!mysqli_query($con,$query)){
        echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
        
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



                       
                      



?>
  

  <!doctype html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
  
    <title>YK Social Network</title>
    <meta name="description" content="My Profile">
    <meta name="author" content="YK Social Network">
  
    <link rel="stylesheet" href="css/myprofilestyle.css">
    <link rel="stylesheet" href="css/friendscontainerstyle.css">
    <script src="js/jquery-3.6.0.js"></script>
  </head>
  
  <body>
      
      <div id = "MainContainer">
      
      
            <div id = "CoverPhotoContainer">
                  
                      <img src='<?php echo $cover_src; ?>' >
              
              
            </div>
      
      
            <div id = "ProfilePhotoContainer">
              
                      
                      <img src='<?php echo $_SESSION["profile_src"]; ?>' >
              
                      
            </div>
      
      
            <div id = "NameContainer">
                      
                      <?php echo $_SESSION["firstname"]; ?>&nbsp;<?php echo $_SESSION["lastname"]; ?>
              
                <div id = "EditProfile">
                    <a href="fillinprofile.php">Edit Profile</a>
                  
                </div>
              
              
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
          document.getElementById("AddPhotoTab").style.display = "inline-block";
          $("#PhotosArea").load('myprofilephotos.php');
          
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
		  
		  $("#PostsArea").load('myprofileposts.php?postid=' + '<?php echo $_GET["postid"] ?>');
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
          $("#FriendsArea").load('myprofileallfriends.php');
          
        
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
                  showPosts();
              });
              
			  











			</script>
			  
			  <div id = "TabsContainer">
        			  <div id = "AboutArea">
                    <div class = "TextHeader">Bio:</div>
                    <div class = "TextBody">
                    <?php

                            echo $_SESSION["bio"];
                        
                        
                    
                    ?>
                    </div>
                    <br /><br />
                    <div class = "TextHeader">Hobbies:</div>
                    <div class = "TextBody">
                    <?php
                    

                            echo $_SESSION["hobbies"];
                        
                        
                    
                    ?>
                    </div>
        		    </div>
        		
        		  <div id = "PhotosArea">

        		      
        		      
        		      
                    
        		    
        		  </div>
        		  
        		  
        		  
        		  
        		  
        		  
  
        		  <div id = "FriendsArea">
        		  
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
    
