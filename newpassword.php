<?php
require_once("_init.php");

$email = $_SAFE['emailcode'];
$password = $_SAFE['newcode'];

if ((isset($_POST["newpasscode"])) && ($_POST["newpasscode"] == 1)) {

	$password = vsource_encrypt($password);
	$query = "SELECT userID, email FROM tbl_user where email = '$email' and password='$password'";

	$result = mysql_query($query, $dbc) or die(mysql_error()); 
	$totalRows_Recordset1 = mysql_num_rows($result);

}	

if ($totalRows_Recordset1 == 0) {
	echo 0;
}


else {
//guest
	echo 1;
}
?>
