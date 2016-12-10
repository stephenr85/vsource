<?php header("Access-Control-Allow-Origin: *"); ?>
<?php

	require_once("connections.php");
	$email = $_POST['emailcode'];
	$password = $_POST['newcode'];

	if ((isset($_POST["newpasscode"])) && ($_POST["newpasscode"] == 1)) {
	
	$query = "SELECT userID, email FROM tbl_user where email = '$email' and password='$password'";
    mysql_select_db($database_dbc, $dbc);
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
