<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';

$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = gethostname();
$mail->SMTPAuth = true;
$mail->Username = 'socialn5@nl1-ss21.a2hosting.com';
$mail->Password = '6*f8Kb7Q0Yo@xX';
$mail->setFrom('socialn5@nl1-ss21.a2hosting.com');
$mail->addAddress('yonikirby@gmail.com');
$mail->Subject = 'Here is the subject';
$mail->Body    = 'This is the body.';
$mail->send();
?>
