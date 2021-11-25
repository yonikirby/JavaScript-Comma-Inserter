<?php session_start();
include("config.php");



if ($_GET["friendid"] == null){
	$myID = $_SESSION["userid"];
}
else {
	$myID = $_GET["friendid"];
}




if(isset($_POST['myDeleteFriendID'])){

    $myDeleteID = $_POST['myDeleteFriendID'];
    
    
    if ($myID > $myDeleteID){
      $userOne = $myDeleteID;
      $userTwo = $myID;
    }
    else {
      $userOne = $myID;
      $userTwo = $myDeleteID;
    }
    
    
    
    
    $query = "DELETE FROM friends WHERE user_one_id = '$userOne' AND user_two_id = '$userTwo'";


    if (!mysqli_query($con,$query)){
        echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
        
    }

}

echo '

<html>
<head>
<script>

$(document).ready(function(){
    $(\'#FriendsSearchBox input[type="text"]\').on("keyup input", function(){
        
        var inputVal = encodeURI($(this).val());

        var resultDropdown = $( "#FriendsInnerResultContainer" );//$(this).siblings(".result");
        //if(inputVal.length){
            $.ajax({
               //AJAX type is "Post".
               type: "POST",
               //Data will be sent to "ajax.php".
               url: "ajax-search.php",
               //Data, that will be sent to "ajax.php".
               data: {
                   //Assigning value of "name" into "search" variable.
                   search: inputVal,
                   userid: '.$myID.'
               },
               //If result found, this funtion will be called.
               success: function(html) {
                   //Assigning result to "display" div in "search.php" file.
                   $("#FriendsInnerResultContainer").html(html).show();
               }
           });
        //} else{
          //  resultDropdown.empty();
        //}
    });
    
    
});
</script>
</head>
';




echo '

<div id = "FriendsInnerContainer">
        		          <div id = "FriendsFirstRow">
            		          <div id = "FriendsHeaderText">

            		              Friends
            		          </div>
            		          
            		          <div id = "FriendsNumber">
            		          		
            		          </div>
            		          
            		          <div id = "FriendsSearchBox">
            		              Search:
            		              <input type="text" id="txtSearchFriends" name="txtSearchFriends">
            		          </div>
            		          ';
            		          
            		          if ($_GET["friendid"] == null){
            		          
            		          	echo '
            		          			<div id="FriendRequestsLinkContainer">
            		              			<a href = "myfriendrequests.php"><button type="button">Friend Requests</button></a>
            		            			
            		          			</div>
            		          			
            		          			<div id = "BrowsePeopleLinkContainer">
            		              			<a href = "browsepeople.php"><button type="button">Browse People</button></a>
            		          			</div>
            		          		';
            		          }
            		          	
            		         echo ' 	
            		      </div>
            		      
            		      <div id = "FriendsSecondRow">
            		          <button class = "FriendsButton" id = "AllFriendsButton" type="button" onclick="loadFriendsResults(\'AllFriends\')" style = "color:#1F76F2;
  border-bottom:3px solid #1F76F2;">All Friends</button>
            		          
            		          

            		      </div>
            		      
            		      
            		      <div id = "FriendsInnerResultContainer">';
            		      
            		     		$sql = "SELECT * FROM friends WHERE status = '1' AND (user_two_id = '$myID' OR user_one_id = '$myID')";
                      		
                      		
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







if (mysqli_num_rows($result) > 0){

	$sql = "SELECT * FROM userinfo WHERE id in (" . implode(',', $IDsToQuery) . ")";
	
	if (!$result = mysqli_query($con,$sql)){
                          		echo '<script type="text/javascript">alert("first query123 '.mysqli_error($con).'");</script>';
                          		
                      		}




while ($row = mysqli_fetch_array($result)) {
                    						$firstname = $row['firstname'];
                    						$lastname = $row['lastname'];
                    						$profile_src = $row['profilephotopath'];
                    						$id = $row['id'];
                    						echo '
            		        					<div class = "FriendsOneFriend" id = "FriendsOneFriend'.$id.'">
            		        						<img src = "'.$profile_src.'" />
            		        					
            		        					
            		        						<div class = "FriendsMiddleDiv">
            		        							<a href = "friendprofilefull.php?id='.$id.'">'.$firstname.' '.$lastname.'</a>
            		        						
            		        						</div>
            		        					';
            		        					
            		        				if ($_GET["friendid"] == null){
            		        					echo'
            		        						<div class = "FriendsRightDiv">
            		        								<div class = "CircleDiv" onmouseover="unfriendMenuAppear('.$id.')" onmouseout="unfriendMenuDisappear('.$id.')">
            		        									<img src = "images/ellipsis2.png" />
            		        									
            		        									<div class = "UnfriendMenu" id = "UnfriendMenu'.$id.'" onmouseout="unfriendMenuDisappear('.$id.')" onClick="friendsDeleteFriend('.$id.')">
            		        										Unfriend
            		        									</div>
            		        								</div>
            		        						</div>
            		        					';
            		        				}
            		        				
            		        				
            		        					
            		        				echo '</div>';	
            		      
            		      
            		      }
            		        	
						echo '
            		        
            		      </div>
            		      
            		      
 
            		      
            		      
            		      
        		      </div>


<script>

	function loadFriendsResults(name){
		$(".FriendsButton").each(function() {
    		$(this).css({"border": "none", "color": "grey"});
		});

	
		$("#"+name+"Button").css({"border-bottom": "3px solid #1F76F2", "color": "#1F76F2"});
		
	
		
	}

	function unfriendMenuAppear(id){
		$("#UnfriendMenu" + id).css("display", "block");
	}
	
	function unfriendMenuDisappear(id){
		$("#UnfriendMenu" + id).css("display", "none");
	}

	function friendsDeleteFriend(id){
			$( "#FriendsOneFriend" + id).remove();
	
			$.post("myprofileallfriends.php", { myDeleteFriendID: id
                                              
                },
                    function(data){
                        
                        // callback function gets executed
                        
                });
        
                // to prevent the default action
                return false;	
		
	
	}

	
	
	
	
	
	
	
	


	
	

</script>




';
}

?>
</html>
                        
