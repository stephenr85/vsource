<?php
require_once('_init.php');

$authCode = $_REQUEST['code'];

if($authCode){

	$app = vsource();
	$dbal = $app->dbal();
	$client = $app->getGoogleClient();
	$client->authenticate($authCode);

	$accessToken = $client->getAccessToken();
	$sessionid = session_id();

	$_SESSION['access_token'] = $accessToken;

	$client->setAccessToken($accessToken);
	$oauth2 = new \Google_Service_Oauth2($client);
	$userInfo = $oauth2->userinfo->get();

	//Check if email already exists in database
	$qbAuth = $dbal->createQueryBuilder()
		->select('id')
		->from('tbl_oauth2')
		//->where('session_id = :session_id')
		->where('email = :email')
		->andWhere('service = \'google\'')
		//->setParameter('session_id', $sessionid)
		->setParameter('email', $userInfo->email)
		->setMaxResults(1);

	$authRow = $qbAuth->execute()->fetch();
	/*var_export(array(
		$qbAuth->getSQL(),
		$userInfo->email,
		$authRow,
		$sessionid
	));
	*/
	if($authRow){
		//Update database
		$upResult = $dbal->createQueryBuilder()->update('tbl_oauth2')
			->set('date_created', $dbal->quote(date('Y-m-d H:i:s')))
			->set('access_token', $dbal->quote(json_encode($accessToken)))
			->set('session_id', $dbal->quote($sessionid))
			->where('id = :id')
			->setParameter('id', $authRow['id'])
			->execute();
	} else {
		$qbInsert = $dbal->createQueryBuilder()->insert('tbl_oauth2')
			->values(
				array(
					'email' => $dbal->quote($userInfo->email),
					'date_created' => $dbal->quote(date('Y-m-d H:i:s')),
					'service' => $dbal->quote('google'),
					'access_token' => $dbal->quote(json_encode($accessToken)),
					'session_id' => $dbal->quote($sessionid)
				)
			);
		$qbInsert->execute();
	}

	// REDIRECT TO APP PAGE...HOW?
	if(isset($_SESSION['client_url'])){
		header('Location: '.$_SESSION['client_url']);
		exit;
	}else{
		echo 'ERROR: UNKNOWN CLIENT URL FOR AUTH REDIRECT.';
		exit;
	}

}else{


}