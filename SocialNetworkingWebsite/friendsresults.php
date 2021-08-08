<?php session_start();
include("config.php");



if ($_GET["friendid"] == null){
	echo '<script type="text/javascript">alert("inside if");</script>';
	$myID = $_SESSION["userid"];
}
else {
	$myID = $_GET["friendid"];
}


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





if ($_GET["friendsquery"] == "AllFriends"){



	$sql = "SELECT * FROM userinfo WHERE id in (" . implode(',', $IDsToQuery) . ")";
	
	if (!$result = mysqli_query($con,$sql)){
                          		echo '<script type="text/javascript">alert("first query '.mysqli_error($con).'");</script>';
                          		
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
}

else if ($_GET["friendsquery"] == "Birthdays"){
echo '<script type="text/javascript">alert("got into Birthdays");</script>';

	$sql = "SELECT *
	
	
	
	
	FROM userinfo WHERE id in (" . implode(',', $IDsToQuery) . ")    

ORDER BY
  
	firstname
	
	
	
	";
	
	
	
	if (!$result = mysqli_query($con,$sql)){
                          		echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                          		
                      		}



while ($row = mysqli_fetch_array($result)) {
                    						$firstname = $row['firstname'];
                    						$lastname = $row['lastname'];
                    						$profile_src = $row['profilephotopath'];
                    						$id = $row['id'];
                    						$birthday = $row['birthday'];
                    						$monthstring = $birthday->format('%m');
                                            $daystring = $birthday->format('%d');
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
            		        								'.$monthstring.'/'.$daystring.'
            		        						</div>
            		        					';
            		        				}
            		        				
            		        				
            		        					
            		        				echo '</div>';	
            		        				
            		        				
            		        		}
}


							











?>
