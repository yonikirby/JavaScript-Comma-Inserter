<?php session_start();
include("config.php");


$myID = $_SESSION["userid"];

if ($myID == 0) {
    header('Location: http://www.socialnetwork.a2hosted.com/');

}





                       $sql = "select * from userinfo where id='$myID'";
                      
                      
                      
                      
                        
                        
                        if (!$result = mysqli_query($con,$sql)){
                            echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                            //mysqli_close($con);
                        }
                      
                      
   
                       $row = mysqli_fetch_array($result);
                      
                       
                       $cover_src = $row['coverphotopath'];
                       $profile_src = $row['profilephotopath'];
                       $firstname = $row['firstname'];
                       $lastname = $row['lastname'];
                       $bio = $row['bio'];
                       $hobbies = $row['hobbies'];
                       
                       //////////////////////
                      
                       $sql = "select count(case was_viewed when '0' then 1 else null end) as total from recent_users_chatted_with
                       where (user_one_id = '$myID' AND action_user_id = '2') OR (user_two_id = '$myID' AND action_user_id = '1')";
                      
                      
                        
                        if (!$result = mysqli_query($con,$sql)){
                            echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                            //mysqli_close($con);
                        } 
                      
                        $data=mysqli_fetch_assoc($result);
                        $numNewMessages = $data['total'];

                       
						
						if(isset($_POST['CreatePostImageFile'])){
								echo '<script>alert("got in here!");</script>';
								/*
							  $name = $_FILES['file']['name'];
							  
							  $dir = "upload/".$myID."/";
							  if ( !file_exists( $dir ) && !is_dir( $dir ) ) {
								mkdir($dir, 0777, true);
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
								
								
								
								// Insert record
								$query = "INSERT INTO images (imagepath, userid)
									  VALUES ('$filepath', '$myID')";
								
							   
								if (!mysqli_query($con,$query)){
									echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
									//mysqli_close($con);
								}
							  
							  
								 // Upload file
								 move_uploaded_file($_FILES['file']['tmp_name'],$target_dir.$name);
									
									
								 echo '<script type="text/javascript">
											$("#CreateProfileInput").append("<img src = '.$target_dir.$name.'>");
												
									   </script>';
									
							  }
							 */
							}
						
						
?>
  
  <!doctype html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
  
    <title>YK Social Network</title>
    <meta name="description" content="My Profile">
    <meta name="author" content="YK Social Network">
  
    <link rel="stylesheet" href="css/headerstyle.css">
    <script src="js/jquery-3.6.0.js"></script>
  </head>
  
  <body>
			<iframe name="hidden-iframe" style="display: none;"></iframe>
  
            <div id = "HeaderContainer">
                <div id = "HeaderLogoBox">
                  <img src="images/facebook.png" />
                  
                </div>
                
                <div id = "HeaderSearchBox">
                  Search:
                  
                  
                  			<!--Make sure the form has the autocomplete function switched off:-->
							<!--<form autocomplete="off" action="/browsepeople.php">-->
  							<div id="autocomplete">
  								<input type="text" id="headerSearchFriends" name="headerSearchFriends">
  								
  								<div id = "SearchResultContainer">
  								
  											<ul id = "SearchResult">
  								
  											</ul>
  								</div>
  							
    						
  							</div>
  							
                  
                </div>
                
                <div id = "HeaderBrowsePeopleBox">
                  <a href = "browsepeople.php">Browse People</a>
                </div>
              
                <div id = "HeaderIconsBox">
                  
                  <a href = "myprofile.php">
                      <div id = "HeaderProfilePhotoBox">
                        
                        <img src='<?php echo $profile_src; ?>' >
                        
                        &nbsp;
                          
                        <?php echo $firstname; ?>
                      </div>
                  </a>
                      
                      <div class = "IconBox" onclick="showCreatePost()">
                          <img src = "images/plus2.png" />
                        
                      </div>
                  
                      <a href = "chat.php">
                      <div class = "IconBox" id = "MessageIcon">
                          <img src = "images/message2.png" />
                        
                          <div id = "NewMessagesNumber"><?php echo $numNewMessages; ?></div>
                      </div>
                      </a>
                      
                      <div class = "IconBox">
                          <img src = "images/notification.png" />
                        
                      </div>
                      
                     <div class = "IconBox">
                          <img src = "images/down-arrow.png" />
                        
                      </div>
                  
                  
                </div>
              




                        

                        <div id = "CreatePost">
								
						
						
                                <div id = "CreateTitleContainer">
                                            Create Post

                                            <div id = "CancelOption" onclick = "hideCreatePost()">
                                                    <img src = "images/cancel.png" />
                                            </div>
											
											
											<div id = "CreatePostUpload">

												  Select Image to Upload:
												  
												  
												  <input type='file' name='CreatePostImageFile' id = "CreatePostImageFile" />
												  
												  <input type='button' onclick = "uploadCreatePostPhoto()" value='Save' name='but_upload' id = "CreatePostImageSubmit" />
											
												
												
												  <img src="images/cancel.png" onclick="closeCreatePostUploadPhoto()"/>

                                                   

											</div>
											<script>
                                                function resetMyForm(){
                                                    return false;
                                                }


												function closeCreatePostUploadPhoto() {
												  document.getElementById("CreatePostUpload").style.display = "none";

                                                  
												}
												
												
												
												
												function uploadCreatePostPhoto() {
													
													    var fd = new FormData();
														var files = $('#CreatePostImageFile')[0].files[0];
														fd.append('file', files);
											   
														$.ajax({
															url: 'uploadMyCreatePostPhoto.php',
															type: 'post',
															data: fd,
															contentType: false,
															processData: false,
															success: function(response){
																$("#CreatePostUpload").append(response);
																closeCreatePostUploadPhoto();
															},
														});
												}
												
												
											</script>
											
											
                                </div>

                                <div id = "CreateProfileInfo">
                                            <img src = "<?php echo $profile_src; ?>"/>

                                            
                                            <?php echo $firstname . ' ' . $lastname; ?>

                                </div>
                              
                                <div contentEditable = "true" id="CreateProfileInput" name="CreateProfileInput">What's on your mind, <?php echo $firstname ?>?</div>
                                    
                                <div id = "AddToYourPost">
                                            &nbsp;&nbsp;&nbsp;&nbsp;Add to Your Post
                                            <div id = "CreateIconsContainer">
                                                    <div class = "OneIconContainer" title = "Add Photo" onclick = "addPhoto()">
                                                            <img src = "images/addphoto.png"  />
                                                    </div>
													<script>
														function addPhoto() {
														  document.getElementById("CreatePostUpload").style.display = "block";
														}
													</script>
													
                                                    <div class = "OneIconContainer" title = "Tag People" onclick = "openTagPeople()">
                                                            <img src = "images/tagperson.png"  />
                                                    </div>
                                            </div>
                                </div>

                                <button id = "CreatePostButton">Post</button>
                                <div style = "text-align:center;">Icons made by <a href="https://www.freepik.com" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>
                        </div>


                        <script>
                            function openTagPeople(){
                              $("#CreatePost").css("display","none");
                              $("#TagPeople").css("display","block");
                            }
                        
                        </script>

                        <div id = "TagPeople">
                            <div id = "TagPeopleHeader">
                                  Tag People

                                  <div id = "BackOption" onclick = "tagPeopleGoBack()">
                                                    <img src = "images/left-arrow.png" />
                                  </div>
                            </div>

                            <div id = "TagPeopleSearch">
                                    <div id = "MyInput">
                                            <input type="text" onfocus = "this.value=''" onfocusout = "this.value='Search for friends'" id="TagSearch" name="TagSearch" value = "Search for friends">
                                    
                                            <img src="images/magnifyingglass.png" />    
                                    </div>
                                    

                                    <a onclick = "tagPeopleGoBack()">Done</a>
                            </div>
                            <br />
                            <div id = "TagPeopleSuggestionsHeader">
                                    SUGGESTIONS
                            </div>
                            <?php
                            $sql = "SELECT * FROM friends WHERE status = '1' AND '$myID' = user_two_id OR '$myID' = user_one_id";
                      		
                      		
                      		if (!$result = mysqli_query($con,$sql)){
                          		echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                          		
                      		}
                      		
                      		$IDsToQuery = (array) null;
                      		
                      		while ($row = mysqli_fetch_array($result)) {
                       
                        		$UserOneID = $row['user_one_id'];
                        		$UserTwoID = $row['user_two_id'];
                        		
                        		if ($myID == $UserOneID){
                          			array_push($IDsToQuery,$UserTwoID);
                        		}
                        		else {
                          			array_push($IDsToQuery,$UserOneID);
                          		
                        		}
                    		}


                                $sql = "SELECT * FROM userinfo WHERE id in (" . implode(',', $IDsToQuery) . ")";
                                
                                if (!$result = mysqli_query($con,$sql)){
                                                            echo '<script type="text/javascript">alert("first query '.mysqli_error($con).'");</script>';
                                                            
                                                        }



                            while ($row = mysqli_fetch_array($result)) {
                                                                        $firstname = $row['firstname'];
                                                                        $lastname = $row['lastname'];
                                                                        $profile_src = $row['profilephotopath'];
                                                                        $id = $row['id'];
                            }

                            ?>
                        </div>
              
            </div>
    
    <div id = "OpaqueDiv">
            &nbsp;     
    </div>

<?php 
            if ($numNewMessages > 0) {
                echo '<script type="text/javascript">$("#NewMessagesNumber").css("display", "block");</script>';
            }
?>


</body>


<script>

function tagPeopleGoBack(){
    $("#TagPeople").css("display","none");
    $("#CreatePost").css("display","block");
    
}





var currentValue = "";

$(document).ready(function(){


    $("#headerSearchFriends").keyup(function(){
    
    	
            if (event.keyCode != 16){            
                        
                            var search = escape($(this).val());
                            
                            
                            $( "#SearchResult").empty();
                            
                            
                            
                            if(search != "" && search != currentValue){

                                $.ajax({
                                    url: 'getsearch.php',
                                    type: 'post',
                                    data: {search:search},
                                    dataType: 'json',
                                    success:function(response){
                                    
                                    
                                        currentValue = search;
                                    
                                        var len = response.length;
                                        
                                        for( var i = 0; i<len; i++){
                                        
                                            var id = response[i]['id'];
                                            var name = response[i]['name'];
                                            var profilephotopath = response[i]['profilephotopath'];

                                            $("#SearchResult").append("<li value='"+id+"'><img src = '"+profilephotopath+"' /><mytag>"+name+"</mytag></li>");

                                        }
                                    
                                        // binding click event to li
                                        $("#SearchResult li").bind("click",function(){
                                            selectUser(this);
                                        });

                                    }
                                });
                            }
                        }
    });


    var newinput = true;

    $("#CreateProfileInput").keypress(function(){
    
    	
                    if (newinput){

                        $( "#CreateProfileInput").empty();
                        
                        newinput = false;
                    }
                
    });

    



});


function focusTagSearch(){
    alert("." + document.getElementById("TagSearch").value + ".");
    $("#TagSearch").value = "";
    $( "#TagSearch").empty();
    if (document.getElementById("TagSearch").value == "Search for friends"){
            $("#TagSearch").css("color","#96999D");
            
            $("#TagSearch").value = "";
            alert("got here 2");
            
    }

}



// Set Text to search box and get details
function selectUser(element){

    var userid = $(element).val();
    
    window.location.replace("friendprofilefull.php?id=" + userid);

}


$('#headerSearchFriends').keydown(function (e) {
  var keyCode = e.keyCode || e.which;

  if (keyCode == 13) {
		window.location.replace("browsepeople.php?searchterm=" + escape($('#headerSearchFriends').val()));
    	
  }
  
});

function showCreatePost(){
    
 
    $("#OpaqueDiv").css("display", "block");
    $("#CreatePost").css("display", "block");
    

}

function hideCreatePost(){
    $("#OpaqueDiv").css("display", "none");
    $("#CreatePost").css("display", "none");
}

</script>

</html>
