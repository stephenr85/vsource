<?php
error_reporting(E_ALL ^ E_DEPRECATED);

define('VSOURCE_DIR', __DIR__);
define('VSOURCE_DB_HOST', 'localhost');
define('VSOURCE_DB_NAME', 'smoov22_vsource');
define('VSOURCE_DB_USER', 'smoov22_vsource');
define('VSOURCE_DB_PASSWORD', 'Diamondpony30$');
define('VSOURCE_SALT', 'fd4U2wFW5O6s2ZP727IKMa07lbl8K0qW');
define('VSOURCE_LOCALE', 'en-US');

if($_SERVER['HTTP_HOST'] == 'vsource.local'){
	define('VSOURCE_VIEW_ROOT', '/');
}else{
	define('VSOURCE_VIEW_ROOT', 'http://vesnaus.com/vsource/v1.6/');
}