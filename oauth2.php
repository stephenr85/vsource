<?php
require_once('_init.php');
header('Content-Type: application/json');

$app = vsource();
$client = $app->getGoogleClient();

$sessionid = session_id();

$accessToken = NULL;
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
	
	//get token from db
	$accessToken = $_SESSION['access_token'];

}else if(isset($_REQUEST['sessionid']) && $_REQUEST['sessionid']) {
	//look for session in database if session id was passed
	$qb = $app->dbal()->createQueryBuilder()
		->select('*')
		->from('tbl_oauth2')
		->where('session_id = :session_id')
		->setParameter('session_id', $sessionid)
		->setMaxResults(1);
	$authData = $qb->execute()->fetch();
	if($authData){		
		$accessToken = json_decode($authData['access_token'], true);
	}

}


if($accessToken){

	//try {
		$client->setAccessToken($accessToken);
		$oauth2 = new \Google_Service_Oauth2($client);

		if(isset($_REQUEST['revoke']) && $_REQUEST['revoke']){
			$_SESSION['access_token'] = null;
			$client->revokeToken();
			$app->dbal()->createQueryBuilder()
			->delete('tbl_oauth2')
			->where('session_id = :session_id')
			->setParameter('session_id', $sessionid)
			->execute();

			echo json_encode(array(
				'session_id' => $sessionid,
				'revoked' => 1
				));
			exit;
		}
		
		$data = $accessToken;
		$data['session_id'] = $sessionid;
		$data['userinfo'] = $oauth2->userinfo->get();

		$data['auth_group'] = strpos($data['userinfo']['email'], '@veolia.com') !== false ? '1' : '2';
		$data['auth_group'] = '1';
		echo json_encode($data);
		//$client->revokeToken();
		exit;
	//} catch (Google_Service_Exception $ex){
		//echo $ex;
		//couldn't authenticate properly
	//} catch (Exception $ex){
	//	echo $ex;
	//}

}
//If we've made it here...

if(isset($_REQUEST['redirect']) && $_REQUEST['redirect'] == '1'){

	$clientUrl = urldecode($_REQUEST['client_url']);
	$clientUrlParts = parse_url($clientUrl);

	//$clientUrlParts['query'] .= ($clientUrlParts['query'] ? '&' : '') . 'sessionid=' . urlencode($sessionid);

	$clientUrl = $clientUrlParts['scheme'] . '://' . $clientUrlParts['host'];
	$clientUrl .= ($clientUrlParts['port'] == '80' ? '' : ':' . $clientUrlParts['port']);
	$clientUrl .= $clientUrlParts['path'];
	$clientUrl .= '?' . $clientUrlParts['query'];
	if($clientUrlParts['fragment']){
		$clientUrl .= '#' . $clientUrlParts['fragment'];
	}
	
	$_SESSION['client_url'] = $clientUrl;

	header('Location: ' . $client->createAuthUrl());
	exit;
	
} else {

	$data = array(
		'session_id' => session_id(),
		//'auth_url' => $client->createAuthUrl()
		'auth_url' => (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '&redirect=1',
	);
	echo json_encode($data);
	exit;
}

	