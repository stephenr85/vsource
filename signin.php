<?php header("Access-Control-Allow-Origin: *"); ?>
<?php

	require_once("connections.php");
	$email = $_POST['email'];
	$password = $_POST['password'];
	
	if ((isset($_POST["signin"])) && ($_POST["signin"] == 1)) {
	
	$query = "SELECT userID, email FROM tbl_user where email = '$email' and password='$password' and status='active'";
    mysql_select_db($database_dbc, $dbc);
	$result = mysql_query($query, $dbc) or die(mysql_error()); 
	$totalRows_Recordset1 = mysql_num_rows($result);
}	

	if ($totalRows_Recordset1 == 0) {
		echo 0;
	}
	
	else if (strpos($email, '@veolia.com') !== false) {
	//veolia employee
		echo 1;
	}
	
	else {
	//guest
		echo 2;
	}
?>
