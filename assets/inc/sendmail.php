<?php

require_once('phpmailer/class.phpmailer.php');
require_once('phpmailer/class.smtp.php');

$mail = new PHPMailer();

// $mail->SMTPDebug = 3; // Enable verbose debug output
$mail->isSMTP(); // Set mailer to use SMTP
/*
$mail->Host = 'sandbox.smtp.mailtrap.io'; // Specify main and backup SMTP servers
$mail->SMTPAuth = true; // Enable SMTP authentication
$mail->Username = '068e4e686046e1';
$mail->Password = '92f1cdf578ce1e';
// $mail->SMTPSecure = true; // Enable TLS encryption, `ssl` also accepted
$mail->Port = 2525; // TCP port to connect to
// print_r($mail);
// exit();
*/
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'mediassureee@gmail.com';
$mail->Password = 'hkgvfvwvkwmbkeyw';
$mail->SMTPSecure = 'tls'; // Enable TLS encryption
// SMTP Port (Gmail SMTP port)
$mail->Port = 587; // TLS Port

$message = "";
$status = "false";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 $phoneno_count = strlen($_POST['form_phone']);
 if ($phoneno_count == 10) {
if ($_POST['form_name'] != '' and $_POST['form_email'] != '' and $_POST['form_subject'] != '' and $_POST['form_phone'] != '' and strlen($_POST['form_phone']) == 10) {
        $name = $_POST['form_name'];
        $email = $_POST['form_email'];
        $subject = $_POST['form_subject'];
        $phone = $_POST['form_phone'];
        $message = $_POST['form_message'];

        $subject = isset($subject) ? $subject : 'New Message | Contact Form';

        $botcheck = $_POST['form_botcheck'];

        $toemail = 'mediassureee@gmail.com'; // Your Email Address
        $toname = 'Train Ambulance Leads'; // Your Name

        if ($botcheck == '') {

            $mail->SetFrom($email, $name);
            $mail->AddReplyTo($email, $name);
            $mail->AddAddress($toemail, $toname);
            $mail->Subject = $subject;

            $name = isset($name) ? "Name: $name<br><br>" : '';
            $email = isset($email) ? "Email: $email<br><br>" : '';
            $phone = isset($phone) ? "Phone: $phone<br><br>" : '';
            $message = isset($message) ? "Message: $message<br><br>" : '';

            $referrer = $_SERVER['HTTP_REFERER'] ? '<br><br><br>This Form was submitted from: ' . $_SERVER['HTTP_REFERER'] : '';

            $body = "$name $email $phone $message $referrer";

            $mail->MsgHTML($body);
            $sendEmail = $mail->Send();

            if ($sendEmail == true) :
                $message = 'We have <strong>successfully</strong> received your Message and will get Back to you as soon as possible.';
                $status = "true";
            else :
                $message = 'Email <strong>could not</strong> be sent due to some Unexpected Error. Please Try Again later.<br /><br /><strong>Reason:</strong><br />' . $mail->ErrorInfo . '';
                $status = "false";
            endif;
        } else {
            $message = 'Bot <strong>Detected</strong>.! Clean yourself Botster.!';
            $status = "false";
        }
    } else {
        $message = 'Please <strong>Fill up</strong> all the Fields and Try Again.';
        $status = "false";
    }
   }else{
        $message = 'Please <strong>Enter</strong> Correct Phone No  and Try Again.';
        $status = "false";
    }
} else {
    $message = 'An <strong>unexpected error</strong> occured. Please Try Again later.';
    $status = "false";
}

$status_array = array('message' => $message, 'status' => $status);
echo json_encode($status_array);
