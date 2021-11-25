<?php session_start();
include("config.php");

if ($_POST["NumLgtRqt"] == 1) {
    $_SESSION['userid'] = 0;
    
    session_destroy();
    
}
?>