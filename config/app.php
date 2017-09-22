<?php



define('VSOURCE_DIR', realpath(__DIR__.'/../'));
define('VSOURCE_LOCALE', 'en_US');


if(strpos($_SERVER['HTTP_HOST'], 'vesnaus.com') !== FALSE){
	define('VSOURCE_VIEW_ROOT', 'http://vesnaus.com/vsource/v1.6.1/');
}else{
	define('VSOURCE_VIEW_ROOT', 'http://' . $_SERVER['HTTP_HOST'].'/');
}




if(VSOURCE_ENV == 'DEV'){
	define('VSOURCE_VIEW_CACHE_ENABLED', 0);
}else{

	define('VSOURCE_VIEW_CACHE_ENABLED', 1);
}