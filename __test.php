<?php

require_once('_init.php');

if(true){
	$app = vsource();
	var_dump($app->dbal()->fetchAll('SELECT * FROM tbl_oauth2'));

	echo '<hr>';

	$qb = $app->dbal()->createQueryBuilder()->select('*')->from('tbl_oauth2')->where('sessionid = :sessionid')->setParameter('sessionid', session_id());
	
	//var_dump($qb->getQuery());
	var_dump($qb->execute()->fetchAll());
}


//google sso

if(false){
	$app = vsource();
	$client = $app->getGoogleClient();

	//$client->setRedirectUri(VSOURCE_VIEW_ROOT . '/oauth2callback.php');

	$authUrl = $client->createAuthUrl();

	if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
		
		echo 'Already authed.';

		$client->setAccessToken($_SESSION['access_token']);
		$oauth2 = new \Google_Service_Oauth2($client);
		print_r($oauth2->userinfo->get()->email);

	} else {
		echo '<a href="'.$authUrl.'">AUTH URL</a>';
	}

	echo '<Br><br>';
	var_dump($_SESSION);
}

//Output all users

if(false){

	$query = "SELECT * FROM tbl_user";
	    
	$result = mysql_query($query, $dbc) or die(mysql_error()); 


	while($row = mysql_fetch_assoc($result)){
		var_dump($row);
	}

}

// 1-X-2017 Debugging authentication for certain users

if(false){
	// token recieved from signin.php is the same as is being sent in Authorization header
	//received: d0Yeqk/9rr+NBQpFt/FZ6ADpnkLJuNS4Skuthh4WAco=
	//sent: d0Yeqk/9rr+NBQpFt/FZ6ADpnkLJuNS4Skuthh4WAco=
	//auth query string: d0Yeqk/9rr NBQpFt/FZ6ADpnkLJuNS4Skuthh4WAco=

	$token = 'd0Yeqk/9rr+NBQpFt/FZ6ADpnkLJuNS4Skuthh4WAco=';
}



// 2-22-2017 Debugging carol.rogers@veolia.com password reset
if(false){

	echo 'cook == ' . vsource_encrypt('cook') . ' == ' . vsource_decrypt(vsource_encrypt('cook'));

	$query = "SELECT * FROM tbl_user where email = 'carol.rogers@veolia.com'";

	$result = mysql_query($query, $dbc) or die(mysql_error()); 

	while($row = mysql_fetch_assoc($result)){
		var_dump($row);
	}
}