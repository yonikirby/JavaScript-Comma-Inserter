<?php session_start();
include("config.php");


	$myID = $_POST["userid"];
 
if(isset($_POST["search"])){
    // Prepare a select statement
    
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






	$myTerm = addslashes($_POST["search"]);

	if ($myTerm == ""){
		$sql = "SELECT * FROM userinfo WHERE id in (" . implode(',', $IDsToQuery) . ")";
	}
	else {
		$sql = "SELECT * FROM userinfo WHERE id in (" . implode(',', $IDsToQuery) . ") AND (firstname LIKE '%$myTerm%' OR lastname LIKE '%$myTerm%')";
	}

	
	
	if (!$result = mysqli_query($con,$sql)){
                          		echo '<script type="text/javascript">alert("first query '.mysqli_error($con).'");</script>';
                          		
                      		}
    
    
    

            
            // Check number of rows in the result set
            if(mysqli_num_rows($result) > 0){
                // Fetch result rows as an associative array
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                							//echo '<script type="text/javascript">alert("got one result");</script>';
                
                    						$firstname = stripslashes(row['firstname']);
                    						$lastname = stripslashes($row['lastname']);
                    						$profile_src = $row['profilephotopath'];
                    						$id = $row['id'];
                    						if ($id != $_SESSION["userid"]){
                    									echo '
            		        								<div class = "FriendsOneFriend" id = "FriendsOneFriend'.$id.'">
            		        									<img src = "'.$profile_src.'" />
            		        								
            		        								
            		        									<div class = "FriendsMiddleDiv">
            		        										<a href = "friendprofilefull.php?id='.$id.'">'.$firstname.' '.$lastname.'</a>
            		        									
            		        									</div>
            		        								';
            		        								
            		        							if ($myID == $_SESSION["userid"]){
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
                }
            }
             else{
                echo "<p>No matches found</p>";
            }
        
        
 } 
        
?>
