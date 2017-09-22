<?php

error_reporting(E_ALL ^ E_DEPRECATED);

//define('VSOURCE_ENV', 'LIVE');
define('VSOURCE_ENV', 'DEV');

require_once('config/app.php');
require_once('config/database.php');
require_once('config/lumsites.php');