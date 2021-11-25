<?php session_start();
include("config.php");


$myID = $_SESSION["userid"];






                     $sql = "select id, imagepath from images where userid='$myID'";
                    
                    
                    
                    
                      
                      
                      if (!mysqli_query($con,$sql)){
                          echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
                          
                      }
                    
                    
                    $result = mysqli_query($con,$sql);
 
    
                     
                     while ($row = mysqli_fetch_array($result)) {
                       
                        $image_src = $row['imagepath'];
                        $image_id = $row['id'];
                        echo '<div class = "OnePhoto" id = "'.'image'.$image_id.'"><img src="'.$image_src.'"
                                                            >';
                        
                        echo '<div class = "DeletePhotoIcon"
                                              onclick="deleteImage('.$image_id.',\''.$image_src.'\'); location.reload();">
                              <img src = "images/delete.svg" />
                              
                              </div>
                              
                              <a href="'.$image_src.'" download>
                              <div class = "DownloadPhotoIcon">
                              <img src = "images/download.svg" />
                              </div>
                              </a>
                        ';
                        
                        
                        
                        echo '</div>';
                        
 
                     }
                     
                     
                     
                     
                     




?>

                        
