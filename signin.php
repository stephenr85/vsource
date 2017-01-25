<?php 
	
require_once('_init.php');

$email = $_SAFE['email'];
$password = $_SAFE['password'];


if ((isset($_POST["signin"])) && ($_POST["signin"] == 1)) {

	$password = vsource_encrypt($password);

	$query = "SELECT userID, email FROM tbl_user where email = '$email' and password='$password' and status='active'";
    
	$result = mysql_query($query, $dbc) or die(mysql_error()); 
	$totalRows_Recordset1 = mysql_num_rows($result);
}	



if ($totalRows_Recordset1 == 0) {
	echo 0;

} else{
	//generate a key with user's password and vsource salt
	$row = mysql_fetch_assoc($result);
	$session_key = vsource_encrypt($row['userID'] . ';;;' . $row['email']);

	if (strpos($email, '@veolia.com') !== false) {
		echo '1;'.$session_key;//.';'.vsource_decrypt($session_key);
	}
	
	else {
	//guest
		echo '2;'.$session_key;//.';'.vsource_decrypt($session_key);
	}
}