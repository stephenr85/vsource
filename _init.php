<?php
//header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

require_once('vendor/autoload.php');
require_once('_config.php');
require_once('_functions.php');

$dbc = vsource()->db();

$_SAFE = vsource_safe_array($_REQUEST);