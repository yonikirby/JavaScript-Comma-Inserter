<!--<meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>
-->

<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("config.php");



$_POST["NumLgtRqt"] = "";

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

$_SESSION["userid"] = $myID;

if ($_POST["NumLgtRqt"] == 1) {
    $_SESSION['userid'] = 0;
    echo 'got into php';
    
 
}




                       $sql = "select * from userinfo where id='$myID'";
                      
                      
                      
                      
                        
                        
                        if (!$result = mysqli_query($con,$sql)){
                            echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                          
                        }
                      
                      
   
                       $row = mysqli_fetch_array($result);
                      
                       
                       $cover_src = $_SESSION["cover_src"] = $row['coverphotopath'];
                       $profile_src = $_SESSION["profile_src"] = $row['profilephotopath'];
                       $firstname = $_SESSION["firstname"] = $row['firstname'];
                       $lastname = $_SESSION["lastname"] = $row['lastname'];
                       $bio = $_SESSION["bio"] = $row['bio'];
                       $hobbies = $_SESSION["hobbies"] = $row['hobbies'];
                      
			 
			 
			 
			 
                       //////////////////////
                      
                       $sql = "select count(case was_viewed when '0' then 1 else null end) as total from recent_users_chatted_with
                       where (user_one_id = '$myID' AND action_user_id = '2') OR (user_two_id = '$myID' AND action_user_id = '1')";
                      
                      
                        
                        if (!$result = mysqli_query($con,$sql)){
                            echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                            
                        } 
                      
                        $data=mysqli_fetch_assoc($result);
                        $numNewMessages = $data['total'];

                        
						$sql = "select count(case was_viewed when '0' then 1 else null end) as total from notifications
                       where notificationrecipient = '$myID'";
                      
                      
                        
                        if (!$result = mysqli_query($con,$sql)){
                            echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                           
                        } 
                      
                        $data=mysqli_fetch_assoc($result);
                        $numNewNotifications = $data['total'];
						
						
				$posteeID = 0;
                $posteeFirstName = "";	
                
                if (isset($_POST["posteeID"])){
                   
                    $posteeID = $_POST["posteeID"];
                    $posteeFirstName = $_POST["posteeFirstName"];
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
    <link rel="stylesheet" href="css/profilepostsstyle.css">
    <script src="js/jquery-3.6.0.js"></script>
  </head>
  
  <body>

    <script>
            var endOfNotificationsReached = false;

            var posteeID = <?php echo $_SESSION["userid"]; ?>;
            var posteeFirstName = "<?php echo $_SESSION["firstname"]; ?>";
            

			var taggedPeople = [];
	


            function redirectMe(address, id){
               
                window.location.href = address;
                
                $.ajax({
                        url: 'makeMyNotificationViewed.php',
                        type: 'post',
                        data: {id:id},

                        success: function(response){
                            
                        },
				});

            }

            function showCreatePost(ID, firstname){
                taggedPeople = [];
                
                $("#OpaqueDiv").css("display", "block");
                $("#CreatePost").css("display", "block");
                
                posteeID = ID;
                posteeFirstName = firstname;

                if (posteeID == <?php echo $_SESSION["userid"]; ?>){
                    $("#CreateProfileInput").html("What's on your mind, " + posteeFirstName + "?");
                }
                else {
                    $("#CreateProfileInput").html("Write something to " + posteeFirstName  + "...");
                    
                }

                
                $.post("header.php", { posteeID:posteeID, posteeFirstName:posteeFirstName 
                },
                    function(data){
                        
                        
                });

            }

            function hideCreatePost(){
                $("#OpaqueDiv").css("display", "none");
                $("#CreatePost").css("display", "none");
            }

            function addMeToTaggedPeople(id, firstname, lastname){
                
                
                $("#TaggedPeople").css("display","block");

                

                $("#TaggedPeopleDisplay").append("<div class = 'OneTaggedPerson' id = 'OneTaggedPerson" + id + "' name = '" + firstname + " " + lastname + "'><span class = 'NameSpan'>&nbsp;" + firstname + " " + lastname + "&nbsp;&nbsp;<span><span class = 'CancelTag' data-id = '" + id + "' data-firstname = '" + firstname + "' data-lastname = '" + lastname + "'>&#10006;</span>&nbsp;&nbsp;</div>");
                   
                                          
                $("#OneTagPeopleResult" + id).css("display", "none");
				
				taggedPeople.push([id,firstname,lastname]);
				
                
            }


            function restoreMe (id,firstname,lastname) {
                $("#OneTaggedPerson" + id).remove();
                
                if($("#TaggedPeopleDisplay").html()==""){
                    $("#TaggedPeople").css("display","none");

                }

				$("#OneTagPeopleResult" + id).css("display", "block");

                var index = taggedPeople.indexOf([id,firstname,lastname]);
				if (index > -1) {
				  array.splice(index, 1);
				}
            }


            $(document).on('click', '.CancelTag', function(event){
    

                var id = $(this).data("id");
                var firstname = $(this).data("firstname");
                var lastname = $(this).data("lastname");
    

                $("#OneTaggedPerson" + id).remove();
                
                if($("#TaggedPeopleDisplay").html()==""){
                    $("#TaggedPeople").css("display","none");

                }

				$("#OneTagPeopleResult" + id).css("display", "block");

                var index = taggedPeople.indexOf([id,firstname,lastname]);
				if (index > -1) {
				  array.splice(index, 1);
				}
            });
    </script>


			<iframe name="hidden-iframe" style="display: none;"></iframe>
  
            <div id = "HeaderContainer">
                <div id = "HeaderLogoBox">
                    <a href = "newsfeed.php">
                      <img src="images/facebook.png" />
                    </a>
                </div>
                
                <div id = "HeaderSearchBox">
                  Search:
                  
                  
                  			
  							<div id="Autocomplete">
  								<input type="text" id="HeaderSearchFriends" name="HeaderSearchFriends">
  								
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
                      
                      <div class = "IconBox" onclick="showCreatePost(<?php echo $myID; ?>, '<?php echo $firstname ?>')">
                          <img src = "images/plus2.png" />
                        
                      </div>
                  
                      <a href = "chat.php">
                      <div class = "IconBox" id = "MessageIcon">
                          <img src = "images/message2.png" />
                        
                          <div id = "NewMessagesNumber"><?php echo $numNewMessages; ?></div>
                      </div>
                      </a>
                      
                      <div class = "IconBox" id = "NotificationsIconBox" onclick = "loadNotificationsWindow()">
                          <img src = "images/notification.png" />
                          <div id = "NewNotificationsNumber"><?php echo $numNewNotifications; ?></div>
                      </div>
                      
                     <div class = "IconBox" id = "LogoutIconBox">
                          <img src = "images/down-arrow.png" />
                          <div id = "ChangePasswordDiv">Change Password</div>
                          <div id = "LogoutDiv">Log Out</div>
                     </div>
                  
                    <div id = "NotificationsContainer">
                        
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
                                              

                                                function loadNotificationsWindow() {
                                                    $("#NotificationsContainer").css("display","block");
                                                    $("#NotificationsContainer").load('notificationswindow.php');
                                                    
                                                }


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
                                <!--https://stackoverflow.com/questions/3793090/html-is-there-any-way-to-show-images-in-a-textarea-->
                                <div contentEditable = "true" id="CreateProfileInput" name="CreateProfileInput">
                               
                                
                                        
                            
                            
                                </div>
                                    
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

                                <button id = "CreatePostButton" onclick = "uploadPostToDatabase('<?php echo $firstname; ?>')">Post</button>
                                <div style = "text-align:center;">Icons made by <a href="https://www.freepik.com" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>
                        </div>


                        <script>
						
							function uploadPostToDatabase(firstname){
								
                                var taggedVerbose = $("#CreateProfileInfo").html();
                              
							  
                                var startOfStringToBeSaved = (String(taggedVerbose)).indexOf(" is with ");
                                
                                if (startOfStringToBeSaved != -1){

                                    taggedVerbose = taggedVerbose.substring(startOfStringToBeSaved);
                            
                                            
                                }  




								var pmessage = encodeURI($("#CreateProfileInput").html());
								
								if (pmessage != "What's on your mind, " + firstname + "?"){
											
											let tp = taggedPeople.toString();
                                           
                                            
											$.ajax({
																		url: 'uploadPost.php',
																		type: 'post',
																		data: {pmessage:pmessage, taggedPeople:tp, taggedVerbose:taggedVerbose, posteeID:posteeID},
																		
																		success: function(myresponse){
																			
																				alert("Post succesful!");
																				
																				$("#CreateProfileInput").html("What's on your mind, " + firstname + "?");
																				
																				
																				$(".OneTaggedPerson").remove();
																				$("#TaggedPeople").css("display","none");
																				$(".OneTagPeopleResult").css("display","block");
																				
																				
																				var myString = $("#CreateProfileInfo").html();
                              
							  
																				  var startIndexOfStringToBeRemoved = (String(myString)).indexOf(" is with ");
																				  
																				  if (startIndexOfStringToBeRemoved != -1){

																							  var modifiedString = myString.substring(0,startIndexOfStringToBeRemoved);
																				
																							  $("#CreateProfileInfo").html(modifiedString);
																				  }  
																				
																				  
																				
																				
																				
																				
                                                                                hideCreatePost();
                                                                                
                                                                                posteeID = 0;
                                                                                posteeFirstName = "";
                                                                                
                                                                                if($('#PostsIntro').length){
                                                                                    $('#PostsList').prepend(myresponse);

                                                                                }
                                                                                


                                                                                
                                                                            }
                                                                                
											});
								}
								
							
                            else {
									alert("Please enter a post");
								}
                            }
						
						
                            function openTagPeople(){
                              
                                $("#ShareToFriendsWall").hide();


							  var myString = $("#CreateProfileInfo").html();
                              
							  
							  var startIndexOfStringToBeRemoved = (String(myString)).indexOf(" is with ");
							  
							  
							  
							  if (startIndexOfStringToBeRemoved != -1){
                                          
                                          
										  var modifiedString = myString.substring(0,startIndexOfStringToBeRemoved);
										  
										  
										  
										  $("#CreateProfileInfo").html(modifiedString);
							  }  
							  
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
                                            <input type="text" onfocus = "this.value=''" onfocusout = "this.value='Search for friends'" 
                                            id="TagSearch" name="TagSearch" value = "Search for friends">
                                    

                                            <img src="images/magnifyingglass.png" />    
                                    </div>
                                    

                                    <a onclick = "tagPeopleGoBack()">Done</a>
                            </div>
                            <br />


                            <div id = "TaggedPeople">
                                    TAGGED

                                    <div id="TaggedPeopleDisplay" name="TaggedPeopleDisplay"></div>

                            </div>

                            <div id = "TagPeopleSuggestionsHeader">
                                    SUGGESTIONS
                            </div>
                            <?php
                            $sql = "SELECT * FROM friends WHERE status = '1' AND ('$myID' = user_two_id OR '$myID' = user_one_id)";
                      		
                      		
                      		if (!$result = mysqli_query($con,$sql)){
                          		echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                          		
                      		}
                                            if (mysqli_num_rows($result) > 0){
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
                                                                            echo '<script type="text/javascript">alert("First Query: '.mysqli_error($con).'");</script>';
                                                                            
                                                                        }



                                            while ($row = mysqli_fetch_array($result)) {
                                                                                        $firstname = $row['firstname'];
                                                                                        $lastname = $row['lastname'];
                                                                                        $profile_src = $row['profilephotopath'];
                                                                                        $id = $row['id'];

                                                    echo '
                                                            <div class = "OneTagPeopleResult" id = "OneTagPeopleResult'.$id.'" 
                                                            onclick = "addMeToTaggedPeople(\''.$id.'\',\''.$firstname.'\',\''.$lastname.'\')">

                                                                    <img src = "'.$profile_src.'" />
                                                                    '.$firstname.' '.$lastname.'
                                                    
                                                    
                                                    
                                                            </div>
                                                    ';

                                            }
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
            if ($numNewNotifications > 0) {
                echo '<script type="text/javascript">$("#NewNotificationsNumber").css("display", "block");</script>';
            }
?>





<script>
//var taggedPeople;
function tagPeopleGoBack(){
    $("#TagPeople").css("display","none");
    $("#CreatePost").css("display","block");
    
	
	var taggedPeople = document.querySelectorAll(".OneTaggedPerson");
	
	var myLength = taggedPeople.length;
	
	if (myLength > 0){
		
		$("#CreateProfileInfo").append (" is with ");
		
		for (var i = 0; i < myLength; i++) {
			var myID = taggedPeople[i].id.substring(15);
			var myName = taggedPeople[i].getAttribute("name");
			$("#CreateProfileInfo").append("<a href = 'friendprofilefull.php?id=" + myID + "'>" + myName + "</a>");
   
			if (myLength > 1 && i < myLength - 1){
				if (i == myLength - 2){
					$("#CreateProfileInfo").append(" and ");
				}
				else {
					$("#CreateProfileInfo").append(", ");
				}
				
			}
  
		}
	}
	
    
	
}






$(document).ready(function(){

    $( "#LogoutIconBox" ).hover(
        function() {
            
            $("#LogoutDiv").css("display","block");
            $("#ChangePasswordDiv").css("display","block");
        }, function() {
            $("#LogoutDiv").css("display","none");
            $("#ChangePasswordDiv").css("display","none");
        }
    );

    $( "#LogoutDiv" ).hover(
        function() {
            
            $("#LogoutDiv").css("backgroundColor","#005ce6");
        }, function() {
            $("#LogoutDiv").css("backgroundColor","#cce0ff");
        }
    );

    $( "#ChangePasswordDiv" ).hover(
        function() {
           
            $("#ChangePasswordDiv").css("backgroundColor","#005ce6");
        }, function() {
            $("#ChangePasswordDiv").css("backgroundColor","#cce0ff");
        }
    );

    $("#LogoutDiv").click(function(){

        

        $.ajax({
                                        url: 'logout.php',
                                        type: 'POST',
                                        data: { NumLgtRqt: 1},
                                        
                                        success:function(response){
                                            
                                            
                                            window.location.replace("index.php");
                                        }

        });

        return false;
    });

    $("#ChangePasswordDiv").click(function(){
        var MY_DATA = 'sent_from_logged_in';
        var url = 'forgotpassword.php';
        var form = $('<form action="' + url + '" method="post">' +
        '<input type="text" name="MY_DATA" value="' + MY_DATA + '" />' +
        '</form>');
        $('body').append(form);
        form.submit();

    
    });

    var currentTagSearchValue = "";

    $("#TagSearch").keyup(function(){
    
    	
            if (event.keyCode != 16){            
                        
                            var search = encodeURI($(this).val());
                            
                            
                            
                            
                            if (search == ""){
                                
                                $.ajax({
                                    url: 'tagsgetallfriends.php',
                                    type: 'post',
                                    data: {search:search},
                                    dataType: 'json',
                                    success:function(response){
                                        $(".OneTagPeopleResult" ).remove();
                                        
                                    
                                        currentTagSearchValue = search;
                                    
                                        var len = response.length;
                                        
                                        for( var i = 0; i<len; i++){
                                        
                                           
                                            var id = response[i]['id'];
                                            
                                            var firstname = response[i]['firstname'];
                                            var lastname = response[i]['lastname'];
                                            var profilephotopath = response[i]['profilephotopath'];
                                            
                                            
                                            $("#TagPeople").append("<div class = 'OneTagPeopleResult' id = 'OneTagPeopleResult" + id + "'\
                                                    onclick = 'addMeToTaggedPeople(\"" + id + "\",\"" + firstname + "\",\"" + lastname + "\")'>\
                                                            <img src = '" + profilephotopath + "' />"
                                                             + firstname + " " + lastname
                                                 +"   </div> ");
                                                    
                                            
                                        }
                                    
                                        
                                    }
                                });
                            }
                            

                            
                            else if(search != "" && search != currentTagSearchValue){

                                $(".OneTagPeopleResult" ).remove();

                                $.ajax({
                                    url: 'getsearch.php',
                                    type: 'post',

                                    


                                    data: {search:search},
                                    dataType: 'json',
                                    success:function(response){
                                    
                                        
                                    
                                        currentTagSearchValue = search;
                                    
                                        var len = response.length;
                                        
                                        for( var i = 0; i<len; i++){
                                        
                                            
                                            var id = response[i]['id'];
                                            
                                            var firstname = response[i]['firstname'];
                                            var lastname = response[i]['lastname'];
                                            var profilephotopath = response[i]['profilephotopath'];
                                            
                                            
                                            $("#TagPeople").append("<div class = 'OneTagPeopleResult' id = 'OneTagPeopleResult" + id + "'\
                                                    onclick = 'addMeToTaggedPeople(\"" + id + "\",\"" + firstname + "\",\"" + lastname + "\")'>\
                                                            <img src = '" + profilephotopath + "' />"
                                                             + firstname + " " + lastname
                                                 + "   </div> ");
                                                    
                                            
                                        }
                                    
                                      
                                    }
                                });
                            }
                        }
    });

    var currentValue = "";


    $("#HeaderSearchFriends").keyup(function(){
    
    	
    if (event.keyCode != 16){            
                    $( "#SearchResultContainer").css("display","block");

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

                                    if (profilephotopath == null){
                                        profilephotopath = "";
                                    }

                                    $("#SearchResult").append("<li class = 'DynamicPerson' value='" + id + "'><img src = '" + profilephotopath + "' /><span class = 'MyText'>" + name + "</span></li>");

                                }
                                

                            }
                        });
                    }
                    
                }
});

$(document).on('click', '.DynamicPerson', function(event){
    

    var userid = $(this).val();

    window.location.replace("friendprofilefull.php?id=" + userid);
});



    $(document).mousedown(function(e) 
    {
        var container = $('#NotificationsContainer');

        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0) 
        {
            container.css("display","none");
        }
    });



$("#SearchResultContainer").focus(function() {
       
    }).blur(function() {
       
        $('#SearchResultContainer').css("display","none");
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
    
    $("#TagSearch").value = "";
    $( "#TagSearch").empty();
    if (document.getElementById("TagSearch").value == "Search for friends"){
            $("#TagSearch").css("color","#96999D");
           
            $("#TagSearch").value = "";
            
            
    }

}


// Set Text to search box and get details
function selectUser(element){

    alert("got in here");

    var userid = $(element).val();
    
    window.location.replace("friendprofilefull.php?id=" + userid);

}


$('#HeaderSearchFriends').keydown(function (e) {
  var keyCode = e.keyCode || e.which;

  if (keyCode == 13) {
		window.location.replace("browsepeople.php?searchterm=" + encodeURI($('#HeaderSearchFriends').val()));
    	
  }
  
});

function toggleLike(id){
   
    if ($("#LikeImage" + id).attr('src') == "images/like.png"){
        
    }


    
}

</script>
</body>
</html>
