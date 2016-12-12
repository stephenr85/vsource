<?php header("Access-Control-Allow-Origin: *"); ?>
<?php

	require_once("connections.php");
	$email = mysql_escape_string($_POST['emailcode']);
	$tempcode = mysql_escape_string($_POST['tempcode']);
	$password = mysql_escape_string($_POST['password']);
	
	if ((isset($_POST["newpswd"])) && ($_POST["newpswd"] == 1) && $tempcode) {
	
		$query = "UPDATE tbl_user SET password='$password' WHERE email = '$email' AND password = '$tempcode'";

	    mysql_select_db($database_dbc, $dbc);
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
