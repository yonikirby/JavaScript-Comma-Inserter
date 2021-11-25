<?php session_start();
include("config.php");


$myID = $_SESSION["userid"];

								$output = '';

								$output .= '<script>alert("got in here!");</script>';
							  
							 
							
							  
							 
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
								
								}
							  
							  
								 // Upload file
								 move_uploaded_file($_FILES['file']['tmp_name'],$target_dir.$name);
									
									
								 echo '<script type="text/javascript">
											$("#CreateProfileInput").append("<br /><br /><img class = \'CreatePostPhoto\' src = \''.$target_dir.$name.'\' />");
												
                                            
									   </script>';
									
							  }
							 
							




?>