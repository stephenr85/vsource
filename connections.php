<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_dbc = "localhost";
$database_dbc = "smoov22_vsource";
$username_dbc = "smoov22_vsource";
$password_dbc = "Diamondpony30$";
$dbc = mysql_pconnect($hostname_dbc, $username_dbc, $password_dbc) or trigger_error(mysql_error(),E_USER_ERROR); 
?>