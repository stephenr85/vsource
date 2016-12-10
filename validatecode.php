<?php header("Access-Control-Allow-Origin: *"); ?>
<?php

	require_once("connections.php");
	$validationcode = $_POST['valcode'];
	
	
	if ((isset($_POST["valpost"])) && ($_POST["valpost"] == 1)) {
	
	$query = "SELECT * from tbl_user WHERE validation_code = '$validationcode'";
	mysql_select_db($database_dbc, $dbc);
	$result = mysql_query($query, $dbc) or die(mysql_error());
	$row_Recordset1 = mysql_fetch_assoc($result);
	$totalRows_Recordset1 = mysql_num_rows($result);
	
	//if user is already registered send back 0 to app
	if ($totalRows_Recordset1 > 0) {

	
	$query2 = "UPDATE tbl_user set status = 'active' where validation_code = '$validationcode'";
    mysql_select_db($database_dbc, $dbc);
	$result2 = mysql_query($query2, $dbc) or die(mysql_error()); 
	
	echo 1;

}	
	else {
	//guest
		echo 0;
	}
	
}	
?>
