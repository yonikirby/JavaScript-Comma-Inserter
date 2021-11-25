<?php
require_once('inc/chatinc.php');

$oSimpleChat = new SimpleChat();

// draw chat application

$sChatResult = $oSimpleChat->acceptMessages();
 
echo $sChatResult;
?>