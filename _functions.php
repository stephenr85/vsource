<?php


class VSourceApp {
	//Singleton
    static private $_instance = null;

	public static function & getInstance()
	{
		if (is_null(self::$_instance))
		{
		 	self::$_instance = new self();
		}
		return self::$_instance;
	}

	private $_db = null;
	public function db(){
		if(!$this->_db){
			$this->_db = mysql_connect(VSOURCE_DB_HOST, VSOURCE_DB_USER, VSOURCE_DB_PASSWORD) or trigger_error(mysql_error(), E_USER_ERROR); 
			mysql_select_db(VSOURCE_DB_NAME, $this->_db);
		}
		return $this->_db;
	}

	private $_requestHeaders = null;
	public function getRequestHeaders(){
		if(!$this->_requestHeaders){
			$this->_requestHeaders = getallheaders();
		}
		return $this->_requestHeaders;
	}

	public function getRequestHeader($name){
		$headers = $this->getRequestHeaders();
		return isset($headers[$name]) ? $headers[$name] : null;
	}


	public function getRequestAuthToken(){
		$token = $this->getRequestHeader('Authorization');
		if($token){
			//parse
			$token = str_replace('Token token=', '', $token);
		}else if(isset($_REQUEST['auth'])){
			$token = $_REQUEST['auth'];
		}
		return $token;
	}

	public function checkAuthToken($token = true, $change_response_header = false){
		if($token === true) $token = $this->getRequestAuthToken();
		if(!$token) return false;

		$detoken = explode(';;;', vsource_decrypt($token));

		$user = intval($detoken[0]) > 0 ? $this->getUser($detoken[0]) : null;

		$is_authed = $user && $user['email'] == $detoken[1];

		if(!$is_authed && $change_response_header){
			header('HTTP/1.0 403 Forbidden');
		}

		return $is_authed;
	}

	
	public function getUser($id){
		$id = mysql_real_escape_string($id);
		$query = "SELECT * FROM tbl_user where userID = '$id'";
		$result = mysql_query($query, vsource()->db()) or die(mysql_error());
		$row = mysql_fetch_assoc($result);
		return $row;
	}

	public function getCurrentUser(){
		$token = $this->getRequestAuthToken();
		$detoken = explode(';;;', vsource_decrypt($token));
		$user = intval($detoken[0]) > 0 ? $this->getUser($detoken[0]) : null;
		$is_authed = $user && $user['email'] == $detoken[1];
		if($is_authed){
			return $user;
		}
		return null;
	}
}

function vsource(){
	return VSourceApp::getInstance();
}






function vsource_encrypt($text, $salt = VSOURCE_SALT){
	return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
}

function vsource_decrypt($text, $salt = VSOURCE_SALT){
    return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
}




function vsource_db(){
	return vsource()->db();
}

function vsource_get_user($id){
	return vsource()->getUser($id);
}


function vsource_escape_array($arr){
	$result = array();
	foreach($arr as $key => $value){
		if(is_array($value)){
			$result[$key] = vsource_safe_array($value);
		}else{
			$result[$key] = mysql_real_escape_string($value);
		}		
	}
	return $result;
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
