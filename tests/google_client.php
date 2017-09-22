<?php

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