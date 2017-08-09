<?php
error_reporting(E_ALL ^ E_DEPRECATED);

define('VSOURCE_DIR', __DIR__);
define('VSOURCE_DB_HOST', 'localhost');
define('VSOURCE_DB_NAME', 'smoov22_vsource');
define('VSOURCE_DB_USER', 'smoov22_vsource');
define('VSOURCE_DB_PASSWORD', 'Diamondpony30$');
define('VSOURCE_SALT', 'fd4U2wFW5O6s2ZP727IKMa07lbl8K0qW');
define('VSOURCE_LOCALE', 'en_US');

define('VSOURCE_LUMSITES_AUTH_CONFIG', 'private/client_secret_861547954802-5fso73p0o2mp18frr8keg1jjmarkoddc.apps.googleusercontent.com.json');
define('VSOURCE_LUMSITES_AUTH_SCOPES', 'https://www.googleapis.com/auth/userinfo.email');
define('VSOURCE_LUMSITES_AUTH_SUBJECT', 'us.vna.vsource.mailbox@veolia.com');
define('VSOURCE_LUMSITES_AUTH_REFRESH', '1/CujC6y9bekaXz-_yRQRsybnAueJBUMmBXLH0YuNfUCQ');

if(strpos($_SERVER['HTTP_HOST'], 'vesnaus.com') !== FALSE){
	define('VSOURCE_VIEW_ROOT', 'http://vesnaus.com/vsource/v1.6.1/');
}else{
	define('VSOURCE_VIEW_ROOT', 'http://' . $_SERVER['HTTP_HOST'].'/');
}