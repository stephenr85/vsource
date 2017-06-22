<?php
namespace Vsource;

use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Loader\PhpFileLoader;

class App {
	//Singleton
    static private $_instance = null;

    public $cache;

	public static function & getInstance()
	{
		if (is_null(self::$_instance))
		{
		 	self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct(){
		$this->cache = new Cache();
	}

	public function getRootDirectory(){
		return VSOURCE_DIR;
	}

	public function getRootUrl(){
		return VSOURCE_VIEW_ROOT;
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
			//$token = $_REQUEST['auth'];
			$token = preg_replace('/[\s]/', '+', $_REQUEST['auth']);
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
			http_response_code(401);
		}

		return $is_authed;
	}

	public function getLocale(){
		return isset($_REQUEST['locale']) ? str_replace('-', '_', $_REQUEST['locale']) : VSOURCE_LOCALE;
	}

	public function getLanguage(){
		$locale = $this->getLocale();
		$lang = substr($locale, 0, 2);
		return $lang;
	}

	public function getTranslator($locale = NULL){
		if(!$locale) $locale = $this->getLocale();
		setlocale(LC_TIME, $locale);
		$translator = new Translator($locale, new MessageSelector());

		$translator->addLoader('php', new PhpFileLoader());
		$translator->addResource('php', './lang/en_US.php', 'en_US');
		$translator->addResource('php', './lang/fr_CA.php', 'fr_CA');

		return $translator;
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


	public function getUrlContent($url){
		$cacheKey = $this->cache->getKey($url);
		if($this->cache->has($cacheKey)){
			return $this->cache->get($cacheKey);
		}
		try{
			$client = new \GuzzleHttp\Client();
			$res = $client->request('GET', $url, array(
				//'debug'=>true,
				//'allow_redirects'=>false
			    //'auth' => ['user', 'pass']
			));

			$body = $res->getBody();

			$this->cache->set($cacheKey, (string)$body);

			return $body;

		}catch(RequestException $ex){

			return var_export($ex, TRUE);
		}
		return NULL;
		
	}

	public function encrypt($text, $salt = VSOURCE_SALT){
		return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
	}

	public function decrypt($text, $salt = VSOURCE_SALT){
	    return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $salt, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
	}

	public function escapeArray($arr){
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

	private $_lumSitesAdapter;
	public function getLumSitesAdapter(){
		if(!is_object($this->_lumSitesAdapter)){
			$this->_lumSitesAdapter = new \Vsource\LumSites\Adapter();
			$this->_lumSitesAdapter->authorize();
		}
		return $this->_lumSitesAdapter;
	}
}
