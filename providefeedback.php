<?php header("Access-Control-Allow-Origin: *"); ?>

<?php 

$feedback = $_POST['feedbackform'];
$email = $_POST['feedbackemail'];


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
$message .= "<tr><td width='30%'><strong>Employee Email:</strong> </td><td width='70%'>" . strip_tags($_POST['feedbackemail']) . "</td></tr>";
$message .= "<tr><td><strong>Feedback:</strong> </td><td>" . strip_tags($_POST['feedbackform']) . "</td></tr>";

$message .= "</table>";
$message .= "</body></html>";
mail($to, $subject, $message, $headers);  
  

?>