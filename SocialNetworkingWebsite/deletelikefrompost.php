<?php session_start();
include("config.php");


	$myID = $_SESSION["userid"];


    if(isset($_POST['id'])){

        $postID = $_POST['id'];

        $fullname = $_SESSION['firstname'] . ' ' . $_SESSION['lastname'];
        
        
        $query = "SELECT postlikes from posts where id = '$postID'";

        if (!$result = mysqli_query($con,$query)){
            echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
            
        }

        $row = mysqli_fetch_array($result);

        $myString = $row['postlikes'];
        
        //echo $myString;

        $myString = str_replace($myID.",".$fullname.";","", $myString);


        $query = "UPDATE posts SET postlikes = '$myString' where id = '$postID'";

        if (!$result = mysqli_query($con,$query)){
            echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
            
        }
        
      
       
    }











?>