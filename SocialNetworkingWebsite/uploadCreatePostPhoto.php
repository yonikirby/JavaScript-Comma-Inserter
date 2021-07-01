<?php



								echo '<script>alert("got in here!");</script>';
								/*
							  $name = $_FILES['CreatePostImageFile']['name'];
							  
							  echo $name;
							  
							  $dir = "upload/".$myID."/";
							  if ( !file_exists( $dir ) && !is_dir( $dir ) ) {
								mkdir($dir, 0777, true);
							  }
							  $target_dir = "upload/".$myID."/";
							  $target_file = $target_dir . basename($_FILES["CreatePostImageFile"]["name"]);

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
									echo '<script type="text/javascript">alert("'.mysqli_error($con).'");</script>';
									//mysqli_close($con);
								}
							  
							  
								 // Upload file
								 move_uploaded_file($_FILES['CreatePostImageFile']['tmp_name'],$target_dir.$name);
									
									
								 echo '<script type="text/javascript">
											$("#CreateProfileInput").append("<img src = '.$target_dir.$name.'>");
												
									   </script>';
									
							  }
							 
							*/




?>