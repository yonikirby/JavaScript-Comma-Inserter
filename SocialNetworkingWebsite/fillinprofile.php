<?php session_start();
include("config.php");


$myID = $_SESSION["userid"];



if(isset($_POST['but_upload'])){
 
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
    $query = "update userinfo set profilephotopath = '$filepath' where id='$myID'";
    
   
    if (!mysqli_query($con,$query)){
        echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
        //mysqli_close($con);
    }
    
    // Insert record
    $query = "INSERT INTO images (imagepath, userid)
          VALUES ('$filepath', '$myID')";
    
   
    if (!mysqli_query($con,$query)){
        echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
        //mysqli_close($con);
    }
  
  
     // Upload file
     move_uploaded_file($_FILES['file']['tmp_name'],$target_dir.$name);

  }
 
}
    

if(isset($_POST['but_upload2'])){
 
  $name = $_FILES['file2']['name'];
  
  $dir = "upload/".$myID;
  if ( !file_exists( $dir ) && !is_dir( $dir ) ) {
    mkdir( $dir ,0777,true);
  }
  
  
  $target_dir = "upload/".$myID."/";
  $target_file = $target_dir . basename($_FILES["file2"]["name"]);

  // Select file type
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  // Valid file extensions
  $extensions_arr = array("jpg","jpeg","png","gif");

  // Check extension
  if( in_array($imageFileType,$extensions_arr) ){
 
    
    $filepath = $target_dir.$name;
    
    // Insert record
    $query = "update userinfo set coverphotopath = '$filepath' where id='$myID'";
    
   
    if (!mysqli_query($con,$query)){
        echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
        //mysqli_close($con);
    }
  
    $query = "INSERT INTO images (imagepath, userid)
          VALUES ('$filepath', '$myID')";
    
   
    if (!mysqli_query($con,$query)){
        echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
        //mysqli_close($con);
    }
  
     // Upload file
     move_uploaded_file($_FILES['file2']['tmp_name'],$target_dir.$name);

  }
 
}
  


if(isset($_POST['submitBio']))
{
    $myBio = addslashes($_POST['MyBio']);

    $update = mysqli_query($con,"update userinfo set bio = '$myBio' where id='$myID'");

    if(!$update)
    {
        echo mysqli_error($con);
    }
    else
    {
        echo "Records added successfully.";
    }
}


if(isset($_POST['submitHobbies']))
{
    $myHobbies = addslashes($_POST['MyHobbies']);

    $update = mysqli_query($con,"update userinfo set hobbies = '$myHobbies' where id='$myID'");

    if(!$update)
    {
        echo mysqli_error($con);
    }
    else
    {
        echo "Records added successfully.";
    }
}


?>







<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Fill In Your Profile</title>
  <meta name="description" content="Fill In Your Profile">
  <meta name="author" content="Yonatan Kirby">

  <link rel="stylesheet" href="css/fillinprofilestyle.css">

</head>
<!--search free x close icon image-->
<body>
  <div id = "MainContainer">
        
    
        <div id = "HeaderContainer">
          Edit Profile
          <a href = "myprofile.php"><img src="images/cancel.png" /></a>
        </div>
    
        <div id = "ProfilePhotoDiv">
              <div class = "ProfilePhotoTextDiv">
                    <div class = "HeaderText">
                      Profile Picture
                      
                    </div>
                    <div class = "EditText">
                      <a onclick="makeProfileBoxVisible()">Add</a>
                      
                    </div>
                
              </div>
          
              <div id = "ProfilePhotoImageDiv">
                    
                    <?php

                      
                     $myID = $_SESSION["userid"];


                     $sql = "select * from userinfo where id='$myID'";
                    
                    
                    
                    
                      
                      
                      if (!mysqli_query($con,$sql)){
                          echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                          
                      }
                    
                    
                    $result = mysqli_query($con,$sql);
 
                     $row = mysqli_fetch_array($result);
                    
                     
                     $image_src = $row['profilephotopath'];
                     
                     
                    ?>
                    <img src='<?php echo $image_src; ?>' >
                
                
              </div>
          
          
                <div id = "ProfilePhotoUpload">
  
                    <form method="post" action="" enctype='multipart/form-data'>
                      Select Image to Upload:
                      <input type='file' name='file' />
                      <input type='submit' value='Save' name='but_upload'>
                    </form>
                      <img src="images/cancel.png" onclick="myFunction()"/>
                </div>
                
                

          
        </div>
    
    
        <div id = "CoverPhotoDiv">
              <div id = "CoverPhotoTextDiv">
                    <div class = "HeaderText">
                      Cover Photo
                      
                    </div>
                    <div class = "EditText">
                      <a onclick="makeCoverBoxVisible()">Add</a>
                      
                    </div>
                
              </div>
          
          
              <div id = "CoverPhotoArea">
                        <?php

                      
                     $myID = $_SESSION["userid"];


        
                    
                     
                     $image_src = $row['coverphotopath'];
                     
                     
                    ?>
                    <img src='<?php echo $image_src; ?>' >
                
              </div>
              
              
                <div id = "CoverPhotoUpload">
  
                    <form method="post" action="" enctype='multipart/form-data'>
                      Select Image to Upload:
                      <input type='file' name='file2' />
                      <input type='submit' value='Save' name='but_upload2'>
                    </form>
                      <img src="images/cancel.png" onclick="myFunction2()"/>
                </div>
                

              
              
              
          
        </div>
        
        
        <div id = "BioDiv">
              <div class = "ProfilePhotoTextDiv">
                    <div class = "HeaderText">
                      Bio
                      
                    </div>
                    <div class = "EditText">
                      <form method="POST">
                      <input type="submit" name="submitBio" value="Save">
                      


                    </div>
                
              </div>
              
              <div class = "BioBodyDiv">
                
                
                
                      <?php
      
                            
                           $myID = $_SESSION["userid"];
      
      
                           
                          
                          
                            $bio_text = "Describe yourself...";
                           if ($row['bio'] != null){
                              if ($row['bio'] != ""){
                                $bio_text = $row['bio'];
                              }
                           }
                           
                           else {
                             $bio_text = "Describe yourself...";
                           }
                           
                           
                          ?>
                          
                
                          
                          <textarea name="MyBio" id="MyBio"><?php echo $bio_text; ?></textarea>
                </form>

                
              </div>
          
          
        </div>
        
        <div id = "HobbiesDiv">
              <div class = "ProfilePhotoTextDiv">
                    <div class = "HeaderText">
                      Hobbies
                      
                    </div>
                    <div class = "EditText">
                      <form method="POST">
                      <input type="submit" name="submitHobbies" value="Save">
                      
                    </div>
                
              </div>
              
              <div class = "BioBodyDiv">
                
                
                
                      <?php
      
                            
                           $myID = $_SESSION["userid"];
      
      
                           
                          
                          
                            $hobbies_text = "List hobbies here...";
                           if ($row['hobbies'] != null){
                              if ($row['hobbies'] != ""){
                                $hobbies_text = $row['hobbies'];
                              }
                           }
                           
                           else {
                             $hobbies_text = "List hobbies here...";
                           }
                           
                           
                          ?>
                          
                
                          
                          <textarea name="MyHobbies" id="MyHobbies"><?php echo $hobbies_text; ?></textarea>
                </form>

                
              </div>
          
        </div>
        
        <div id = "BackToMyProfile"><a href = "myprofile.php">Back to My Profile</a></div>
        
        
  </div>
  
  

  
  

  
  
  
  
<div>Icons made by <a href="https://www.flaticon.com/authors/roundicons" title="Roundicons">Roundicons</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a></div>



<script>
  
                  
                function myFunction() {
                  document.getElementById("ProfilePhotoUpload").style.zIndex = "-100";
                }
                function myFunction2() {
                  document.getElementById("CoverPhotoUpload").style.zIndex = "-103";
                }
             
                function makeProfileBoxVisible() {
                  document.getElementById("ProfilePhotoUpload").style.zIndex = "110";
                }
                function makeCoverBoxVisible() {
                  document.getElementById("CoverPhotoUpload").style.zIndex = "111";
                }
             
  
  
  
  document.querySelector('.close').addEventListener('click', function() {
  console.log('close');
});

</script>






</body>
</html>
