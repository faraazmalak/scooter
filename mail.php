

<?php

// Set the email address here
define('EMAIL_ADDR', 'g@floh.com');

define('UPLOAD_DIR', 'images/');


$file_name = UPLOAD_DIR . uniqid() . '.png';

$img = $_POST["image"];
$img = str_replace('data:image/png;base64,', '', $img);
$img = str_replace(' ', '+', $img);
$data = base64_decode($img);

$success = file_put_contents($file_name, $data);

if ($success) {
    require("mail_engine/class.phpmailer.php");
    $mail = new PHPMailer();
    
    /*Enter Email settings below*/
    $mail->IsSMTP(); // send via SMTP

    $mail->SMTPAuth = false; // turn on SMTP authentication

    $webmaster_email = "admin@flashscooter.com"; //Reply to this email ID
    $email = EMAIL_ADDR; // Recipients email ID
   
    $mail->From = $webmaster_email;
    $mail->FromName = "Webmaster";
    $mail->AddAddress($email, $_POST['name']);
  
    $mail->WordWrap = 50; // set word wrap

    $mail->AddAttachment($file_name, "drawing.png"); // attachment
    $mail->IsHTML(true); // send as HTML
    $mail->Subject = "Drawing by " . $_POST['name'];
    $mail->Body = "Artist's Name: " . $_POST['name'] . "<br>" . "Email: " . $_POST['email'] . "<br>" . "Drawing Title: " . $_POST['drawingTitle']; //HTML Body
    $mail->AltBody = "Artist's Name: " . $_POST['name'] . "Email: " . $_POST['email'] . "Drawing Title: " . $_POST['drawingTitle']; //Text Body
    if (!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "Message has been sent";
    }
}
?>