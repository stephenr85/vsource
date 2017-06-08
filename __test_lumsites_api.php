<?php 

require_once 'vendor/autoload.php';

$client = new Google_Client();
$client->setApplicationName('lumsites');

$authMode = 1;

if($authMode == 0){
	$client->setAuthConfig('private/googleapikey.json');
	//$client->setAuthConfig('private/googleapitoken.json');
	$scopes = array('https://www.googleapis.com/auth/userinfo.email');
	$client->setScopes(implode(' ', $scopes));

	$client->useApplicationDefaultCredentials();
	$client->setSubject('esiteful.clients@gmail.com');

}else if($authMode == 1){
	// https://developers.google.com/oauthplayground/
	$scopes = array('https://www.googleapis.com/auth/userinfo.email');
	$client->setAuthConfig('private/client_secret_861547954802-5fso73p0o2mp18frr8keg1jjmarkoddc.apps.googleusercontent.com.json');
	$client->setScopes(implode(' ', $scopes));
	$client->setAccessType('offline');

	//$token = $client->fetchAccessTokenWithAuthCode('4/aCIdaz3q-1jECXzfKiYMlkhIidNJf3oxW-88q_7WVaA');
	$client->setSubject('us.vna.vsource.mailbox@veolia.com');
	$token = $client->refreshToken('1/N8q7bq3aINVYG9i_wP68GU7HBsdw_R79hDykAeEIQoY');
	$client->setAccessToken($token['access_token']);

	echo "\n\n======== AUTH TOKEN =========\n";
	echo print_r($token);
	echo "\n\n";
	
}else if($authMode == 2){

	//$client->setDeveloperKey();
}

$httpClient = $client->authorize();


$lumsites = new Google_Service_LumSites($client);

function guidv4()
{
    if (function_exists('com_create_guid') === true)
        return trim(com_create_guid(), '{}');

    $data = openssl_random_pseudo_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}


echo "\n\n======== GOOGLE ME REQUEST DEBUG =========\n";

$response = $httpClient->request('GET', 'https://www.googleapis.com/userinfo/v2/me', array(
		'debug'=>true,
		//'json'=> $params
	)
);

echo "\n\n======== RESPONSE =========\n";
echo $response->getBody();




echo "\n\n======== USER REQUEST DEBUG =========\n";

$params = array(
	'email'=>'us.vna.vsource.mailbox@veolia.com'
);
$response = $httpClient->request('GET', 'https://veolia-intranet.appspot.com/_ah/api/lumsites/v1/user/get', array(
		'debug'=>true,
		'json'=> $params
	)
);


echo "\n\n======== PAYLOAD =========\n";
echo json_encode($params);

echo "\n\n======== RESPONSE =========\n";
echo $response->getBody();

/*
$params = array(
	"maxResults"=>"6",
	"callId"=>"708009bc-2e89-42ca-911f-bf1519392494",
	"more"=>true,
	"lang"=>"en",
	"instanceId"=>"5183329204699136",
	"customerId"=>"5649391675244544",
	"type"=>"news",
	"sortOrder"=>"-publicationDate"
);*/

echo "\n\n======== NEWS REQUEST DEBUG =========\n";

$params = array(
	'action'=>"NEWS_READ",
	//'callId'=>"c1b67508-6984-4426-901b-0dbb75a3398b",
	//'callId'=>guidv4(),
	'customerId'=>"5649391675244544",
	'instanceId'=>"5183329204699136",
	'lang'=>"en",
	'maxResults'=>30,
	'more'=>true,
	'sortOrder'=>"-publicationDate",
	'type'=>"news",
);

$response = $httpClient->request('POST', 'https://veolia-intranet.appspot.com/_ah/api/lumsites/v1/content/list', array(
		'debug'=>true,
		'json'=> $params
	)
);



echo "\n\n======== PAYLOAD =========\n";
echo json_encode($params);

echo "\n\n======== RESPONSE =========\n";
echo $response->getBody();

//var_dump(json_decode($response->eof()));
//$response = $httpClient->request('POST', 'https://lumsites.appspot.com/_ah/api/lumsites/v1/directory/entry/list', array('debug'=>true));

//var_dump($response);