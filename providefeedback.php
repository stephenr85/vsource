<?php 
require_once('_init.php');

$user = vsource()->getCurrentUser();
if(!$user) die('Forbidden');

$feedback = $_SAFE['feedbackform'];
//$feedbackemail = $_SAFE['feedbackemail'];
$feedbackemail = $user['email'];

$to = 'matt.demo@veolia.com';
$subject = 'VSOURCE Mobile App Feedback';
$headers = "From: noreply@vnamailbox.com \r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
$message = '<html><body style="background-color:#eee;">';
$message .= '<table rules="all" width="650px" style="background-color:#fff; border-collapse: collapse;" align="center">';
$message .= '<tr><td colspan="2"><!--enter image here --></a></td></tr>';
$message .= '</table>';
$message .= '<table width="650px" style="background-color:#fff; border-collapse: collapse;" align="center" cellpadding="10">';
$message .= "<tr><td colspan=\"2\">The following feedback was submitted with VSource. </td></tr>";
$message .= "<tr><td width='30%'><strong>Employee Email:</strong> </td><td width='70%'>" . $feedbackemail . "</td></tr>";
$message .= "<tr><td><strong>Feedback:</strong> </td><td>" . $feedback . "</td></tr>";

$message .= "</table>";
$message .= "</body></html>";
mail($to, $subject, $message, $headers);  
  

?>