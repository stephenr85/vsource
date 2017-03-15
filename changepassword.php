<?php 

require_once("_init.php");

$email = $_SAFE['emailcode'];
$tempcode = $_SAFE['tempcode'];
$password = $_SAFE['password'];

if ((isset($_POST["newpswd"])) && ($_POST["newpswd"] == 1) && $tempcode) {

	$tempcode = vsource_encrypt($tempcode);
	$password = vsource_encrypt($password);

	$query = "UPDATE tbl_user SET password='$password', status='active'  WHERE email = '$email' AND password = '$tempcode'";

	$result = mysql_query($query, $dbc) or die(mysql_error()); 
	$totalRows_Recordset1 = mysql_affected_rows();
}

if ($totalRows_Recordset1 == 0) {
	echo 0;
}


else {
//guest
	echo 1;
}
?>
