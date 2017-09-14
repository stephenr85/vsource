<?php 
require_once('_init.php');

$user = vsource()->getCurrentUser();
if(!$user) {http_response_code(401); die('Forbidden');}


$idea = $_SAFE['idea'];
$problem = $_SAFE['problem'];
$solve = $_SAFE['solve'];
//$email = $_SAFE['shareemail'];
$email = $user['email'];


$to = 'braden.becker@veolia.com';
$subject = ' Mobile App Idea Submission';
$headers = "From: noreply@vnamailbox.com \r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
$message = '<html><body style="background-color:#eee;">';
$message .= '<table rules="all" width="650px" style="background-color:#fff; border-collapse: collapse;" align="center">';
$message .= '<tr><td colspan="2"><!--enter image here --></td></tr>';
$message .= '</table>';
$message .= '<table width="650px" style="background-color:#fff; border-collapse: collapse;" align="center" cellpadding="10">';
$message .= "<tr><td colspan=\"2\">The following idea was submitted with VSource. </td></tr>";
$message .= "<tr><td width='30%'><strong>Employee Email:</strong> </td><td width='70%'>" . $email . "</td></tr>";
$message .= "<tr><td><strong>Name of your idea:</strong> </td><td>" . $idea . "</td></tr>";
$message .= "<tr><td><strong>Problem it solves:</strong> </td><td>" . $problem . "</td></tr>";
$message .= "<tr><td><strong>Your idea to solve it:</strong> </td><td>" . $solve . "</td></tr>";

$message .= "</table>";
$message .= "</body></html>";
mail($to, $subject, $message, $headers);  
  

?>