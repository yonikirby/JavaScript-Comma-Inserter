<?php session_start();
include("config.php");
include("header.php");


$id = $_GET['id'];

if ($id == $_SESSION["userid"]){
	header("Location: myprofile.php");
}

$_GET['friendid'] = $id;

?>

<!doctype html>
  <html lang="en">
  <head>
    <meta charset="utf-8">
  
    <title>YK Social Network</title>
    <meta name="description" content="Friend Profile">
    <meta name="author" content="YK Social Network">
  
    <link rel="stylesheet" href="css/friendprofilefullstyle.css">
    <script src="js/jquery-3.6.0.js"></script>
  </head>
  
  <body>
  <div id = "myDiv">
  	<?php include("friendprofile.php")	?>
  </div>
  
  <script>
  //$('#myDiv').load("friendprofile.php?friendid="+<?php echo $id ?>);
  $("#MainContainer").css("margin-left", "233px");
  
  </script>
  
  
  </body>
  
  </html>


