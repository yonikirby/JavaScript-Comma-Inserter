<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$id = $_GET['id'];

$targetPostID = "";

if (isset($_GET["postid"])){
    $targetPostID = $_GET["postid"];
}

if ($id == $_SESSION["userid"]){
    echo 'got here!';
	header('Location: myprofile.php?postid='.$targetPostID);
}

$_GET['friendid'] = $id;

include("config.php");
include("header.php");


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
  	<?php include("friendprofile.php");	?>
  </div>
  
  <script>

  $("#MainContainer").css("margin-left", "233px");
  
  </script>
  
  
  </body>
  
  </html>


