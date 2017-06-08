<?php


function vsource(){
	return Vsource\App::getInstance();
}


function vsource_encrypt($text, $salt = VSOURCE_SALT){
	return vsource()->encrypt($text, $salt);
}

function vsource_decrypt($text, $salt = VSOURCE_SALT){
	return vsource()->decrypt($text, $salt);
}


function vsource_db(){
	return vsource()->db();
}

function vsource_get_user($id){
	return vsource()->getUser($id);
}


function vsource_escape_array($arr){
	return vsource()->escapeArray($arr);
}

function vsource_strip_array($arr){
	$result = vsource_escape_array($arr);
	foreach($result as $key => $value){
		if(is_array($value)){
			$result[$key] = vsource_safe_array($value);
		}else{
			$result[$key] = strip_tags($value);
		}		
	}
	return $result;
}

function vsource_safe_array($arr){
	return vsource_strip_array($arr);
}
