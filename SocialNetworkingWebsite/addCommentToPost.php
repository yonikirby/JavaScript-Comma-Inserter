<?php session_start();
include("config.php");


	$myID = $_SESSION["userid"];
    //echo 'got inside php';

    
  



    if(isset($_POST['PostCommentedOnID'])){

      

        $postID = $_POST['PostCommentedOnID'];

        $postContent = addslashes($_POST['PostContent']);

        $ultimateparentid = $_POST['UltimateParentID'];

        $firstname = $_SESSION["firstname"];
        $lastname = $_SESSION["lastname"];

        $profilesrc = $_SESSION["profile_src"];

        $postlikes = "";

        $taggedpeople = "";

        $posteeid = -1;
        

        $subcomment_wall_id = $_POST['wall_id'];


        $PostAuthorID = $_POST['PostAuthorID'];

        
   


        $sql = "INSERT INTO posts (posterid, posteeid, htmlcontents, taggedpeople,
                posterprofilesrc, posterfirstname, posterlastname, postlikes, parentid, ultimateparentid, wallid)
        VALUES ('$myID', '$posteeid', '$postContent', '$taggedpeople','$profilesrc', '$firstname', '$lastname', '$postlikes', '$postID', '$ultimateparentid', '$subcomment_wall_id')";
        
        
        if (!$result = mysqli_query($con,$sql)){
            $message = '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
            $return_arr[] = array("info_was_inserted" => false,
            "message" => $message);
            // Encoding array in JSON format
            echo json_encode($return_arr);
        }


            
            
        if ($myID != $PostAuthorID){

                    

                    
                    $posteeID = -1;

                    $myName = $_SESSION["firstname"].' '.$_SESSION["lastname"];
                    $myProfileSrc = $_SESSION["profile_src"];

                    

                

                    $sql = "INSERT INTO notifications (posterid, posteeid, notificationrecipient, notification_message, postername, posterprofilesrc, postid, wallid)
                                                    VALUES ('$myID', '$posteeID', '$PostAuthorID', 'commented_on_post', '$myName', '$myProfileSrc', '$postID', '$subcomment_wall_id')";
                                                    
                                                    
                                                    // Perform a query, check for error
                                            
                                                   
                    if (!mysqli_query($con,$sql)){
                        $message = '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                        $return_arr[] = array("info_was_inserted" => false,
                        "message" => $message);
                        // Encoding array in JSON format
                        echo json_encode($return_arr);
                    }

                    

                    $sql = "SELECT LAST_INSERT_ID()";

                    if (!$result = mysqli_query($con,$sql)){
                        $message = '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                        $return_arr[] = array("info_was_inserted" => "false",
                        "message" => $message);
                        // Encoding array in JSON format
                        echo json_encode($return_arr);
                        
                    }
                    else {

                        $row = mysqli_fetch_array($result);
                        $return_arr[] = array("info_was_inserted" => "true",
                        "new_post_id" => $row[0]);
                        // Encoding array in JSON format
                        echo json_encode($return_arr);

                    }
                    
                    
        }        
        else {
           
                        
                $sql = "SELECT LAST_INSERT_ID()";
                if (!$result = mysqli_query($con,$sql)){
                    $message = '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                    $return_arr[] = array("info_was_inserted" => "false",
                    "message" => $message);
                    // Encoding array in JSON format
                    echo json_encode($return_arr);
                    
                }
                else {

                    $row = mysqli_fetch_array($result);
                    $return_arr[] = array("info_was_inserted" => "true",
                    "new_post_id" => $row[0]);
                    // Encoding array in JSON format
                    echo json_encode($return_arr);
                }

                



            

        }
        
       
    }


?>