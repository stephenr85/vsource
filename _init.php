<?php
header("Access-Control-Allow-Origin: *");

require_once('_config.php');
require_once('_functions.php');

$dbc = vsource()->db();

$_SAFE = vsource_safe_array($_REQUEST);