<?php
//header("Access-Control-Allow-Origin: *");
$headers = getallheaders();
if(isset($headers['Access-Control-Request-Headers'])){
	header("Access-Control-Allow-Headers: ".$headers['Access-Control-Request-Headers']);
}

require_once('vendor/autoload.php');
require_once('_config.php');
require_once('_functions.php');

if(isset($_REQUEST['sessionid']) && $_REQUEST['sessionid'] && strtolower($_REQUEST['sessionid']) != 'null'){
	session_id($_REQUEST['sessionid']);
}
session_start();

$dbc = vsource()->db();

$_SAFE = vsource_safe_array($_REQUEST);

if(isset($_SAFE['clearcache']) && $_SAFE['clearcache'] == 1){
	vsource()->cache->clear();
}