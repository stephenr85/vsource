<?php

require_once('_init.php');


if(false){

	$query = "SELECT * FROM tbl_user";
	    
	$result = mysql_query($query, $dbc) or die(mysql_error()); 


	while($row = mysql_fetch_assoc($result)){
		var_dump($row);
	}

}


if(true){
	// token recieved from signin.php is the same as is being sent in Authorization header
	//received: d0Yeqk/9rr+NBQpFt/FZ6ADpnkLJuNS4Skuthh4WAco=
	//sent: d0Yeqk/9rr+NBQpFt/FZ6ADpnkLJuNS4Skuthh4WAco=
	//auth query string: d0Yeqk/9rr NBQpFt/FZ6ADpnkLJuNS4Skuthh4WAco=

	$token = 'd0Yeqk/9rr+NBQpFt/FZ6ADpnkLJuNS4Skuthh4WAco=';
}




