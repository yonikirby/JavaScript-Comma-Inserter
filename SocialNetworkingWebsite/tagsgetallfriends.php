<?php session_start();
include("config.php");
$myID = 0;
if (isset($_SESSION['userid'])) {
    $myID = $_SESSION["userid"];
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









	$sql = "SELECT * FROM userinfo WHERE id in (" . implode(',', $IDsToQuery) . ")";
   

	if (!$result = mysqli_query($con,$sql)){
                            echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                          
    }

    $search_arr = array();

    while($fetch = mysqli_fetch_assoc($result)){
        $id = $fetch['id'];
        $firstname = stripslashes($fetch['firstname']);
        $lastname = stripslashes($fetch['lastname']);
        $name = $firstname . " " . $lastname;
        $profilephotopath = $fetch['profilephotopath'];
        $search_arr[] = array("id" => $id, "name" => $name, "profilephotopath" => $profilephotopath, "firstname" => $firstname, "lastname" => $lastname);
    }

    echo json_encode($search_arr);








?>
