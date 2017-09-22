<?php

error_reporting(E_ALL ^ E_DEPRECATED);

define('VSOURCE_DIR', realpath(__DIR__.'/../'));
define('VSOURCE_LOCALE', 'en_US');


if(strpos($_SERVER['HTTP_HOST'], 'vesnaus.com') !== FALSE){
	define('VSOURCE_VIEW_ROOT', 'http://vesnaus.com/vsource/v1.6.1/');
}else{
	define('VSOURCE_VIEW_ROOT', 'http://' . $_SERVER['HTTP_HOST'].'/');
}