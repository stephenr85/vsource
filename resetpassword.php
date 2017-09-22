<?php 
require_once('_init.php');

$email = $_SAFE['email'];
$temppassword = rand(); 


if ((isset($_POST["passwordreset"])) && ($_POST["passwordreset"] == 1)) {

$query = "SELECT * from tbl_user WHERE email = '$email'";

$result = mysql_query($query, $dbc) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($result);
$totalRows_Recordset1 = mysql_num_rows($result);

//if user is already registered send back 0 to app
if ($totalRows_Recordset1 > 0) {


$query2 = "UPDATE tbl_user set password = '" . vsource_encrypt($temppassword) . "' where email = '$email'";

$result2 = mysql_query($query2, $dbc) or die(mysql_error()); 

$to = strip_tags($_SAFE['email']);
if(isset($_SAFE['admin']) && $_SAFE['admin'] === '1') $to = 'stephenr85@gmail.com';
$subject = ' Your VSource temporary passcode';
$headers = "From: noreply@vnamailbox.com \r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
$message = '<html><body style="background-color:#eee;">';
$message .= '<table rules="all" width="650px" style="background-color:#fff; border-collapse: collapse;" align="center">';
$message .= '<tr><td colspan="2"><!--enter image here --></td></tr>';
$message .= '</table>';
$message .= '<table width="650px" style="background-color:#fff; border-collapse: collapse;" align="center" cellpadding="10">';
$message .= "<tr><td colspan=\"2\">Your VSource temporary passcode. </td></tr>";
$message .= "<tr><td width='30%'><strong>Your Email:</strong> </td><td width='70%'>" . strip_tags($_SAFE['email']) . "</td></tr>";
$message .= "<tr><td><strong>Temporary Code:</strong> </td><td>" . $temppassword . "</td></tr>";
$message .= "</table>";
$message .= "</body></html>";
mail($to, $subject, $message, $headers); 

	
	echo 1;

}	

	
	else {
	//guest
		echo 0;
	}
	
}	
?>
