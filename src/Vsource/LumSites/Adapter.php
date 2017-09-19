<?php

namespace Vsource\LumSites;

use Google_Client;
use Google_Service_LumSites;
use \Vsource\Cache;

use Guzzle\Cache\SymfonyCacheAdapter;
use Guzzle\Plugin\Cache\CachePlugin;
use Guzzle\Plugin\Cache\DefaultCacheStorage;

class Adapter {

	protected $client;
	protected $httpClient;
	protected $service;
	protected $accessToken;
	protected $cache;

	public $isDebug = FALSE;
	public $isCaching = TRUE;
	public $cacheSeconds = 3600;

	public $customerId = '5649391675244544';
	public $instanceId = '5183329204699136';

	
	public function __construct(){
		$this->client = new Google_Client();
		$this->client->setApplicationName('lumsites');
	}

	public function debug($isDebug = TRUE){
		$this->isDebug = $isDebug;
		return $this;
	}

	public function getApp(){
		return \Vsource\App::getInstance();
	}

	public function getClient(){
		return $this->client;
	}

	public function getHttpClient(){
		return $this->httpClient;
	}

	public function getAccessToken(){
		//TODO: Add caching
		$this->client->setAuthConfig(VSOURCE_LUMSITES_AUTH_CONFIG);
		$this->client->setScopes(VSOURCE_LUMSITES_AUTH_SCOPES);
		$this->client->setAccessType('offline');
		
		$this->client->setSubject(VSOURCE_LUMSITES_AUTH_SUBJECT);

		if($this->isCaching) $this->cache(TRUE);
		
		$token = $this->client->refreshToken(VSOURCE_LUMSITES_AUTH_REFRESH);

		return $token['access_token'];
	}

	public function authorize(){
		
		$this->accessToken = $this->getAccessToken();
		$this->client->setAccessToken($this->accessToken);

		$this->httpClient = $this->client->authorize();

		/*
		$cachePlugin = new CachePlugin(array(
			'storage' => new DefaultCacheStorage(
				new SymfonyCacheAdapter($this->getApp()->cache->cache)
			)
		));

		$this->httpClient->addSubscriber($cachePlugin);
		*/
		$this->service = new Google_Service_LumSites($this->client); //?
		$this->service->rootUrl = 'https://veolia-intranet.appspot.com';
		return $this;
	}

	public function getServiceUrl(){
		return implode('/', array(trim($this->service->rootUrl, '/'), trim($this->service->servicePath, '/')));
	}

	public function getEndpointUrl($endpoint){
		return implode('/', array($this->getServiceUrl(), trim($endpoint, '/')));
	}

	public function request($method, $endpoint, $params){
		$endpointUrl = $this->getEndpointUrl($endpoint);
		//$this->getApp()->cache->clear();
		//Setup params
		$defaults = array(
			'lang'=>$this->getApp()->getLanguage()
		);
		$params = array_merge($defaults, $params);
		/*
		//$cacheKey = $this->getApp()->cache->getKey(get_class($this), $endpointUrl, $params);

		//Check cache first
		if($this->isCaching && $this->getApp()->cache->has($cacheKey)){
			var_dump('CACHED!!!');
			$cacheItem = $this->getApp()->cache->getItem($cacheKey);
			$response = $cacheItem->get();
			return $response;
		}*/

		if($this->isDebug){
			echo "* Request Parameters\n";
			echo json_encode($params)."\n";
		}

		//Get data
		$config = array(
			'debug'=>$this->isDebug
		);
		if($method === 'GET'){
			$config['query'] = $params;
		}else{
			$config['json'] = $params;
		}
		$response = $this->httpClient->request($method, $endpointUrl, $config);

		if($this->isCaching){
		//	$response->getBody();
		//	$this->getApp()->cache->set($cacheKey, $response, $this->cacheSeconds);
		}

		return $response;
	}


	public function cache($isCaching = TRUE, $seconds = NULL){
		$this->isCaching = $isCaching;
		if(!is_null($seconds)){
			$this->cacheSeconds = $seconds;
		}
		if($isCaching){
			$cache = new \Vsource\Cache($this->cacheSeconds);
			$this->client->setCache($cache->adapter);
			$this->httpClient = $this->client->authorize();
		}else{
			$this->client->setCache(NULL);
			$this->httpClient = $this->client->authorize();
		}
		return $this;
	}


	public function getCustomContentType($name, $params = array()){
		$adapter = $this->getApp()->getLumSitesAdapter();
		$defaults = array(
			//'customerId'=>$adapter->customerId,
			'instance'=>$adapter->instanceId,
			//'lang'=>$this->getApp()->getLanguage(),
			//'maxResults'=>20,
			//'more'=>true,
			//'sortOrder'=>"-publicationDate",
			//'type'=>"news",
		);
		$params = array_merge($defaults, $params);
		$response = $this->request('GET', 'customcontenttype/list', $params);
		$json = json_decode($response->getBody(), TRUE);
		
		foreach($json['items'] as $ct){
			if(array_search($name, $ct['name']) !== FALSE){
				return $ct;
			}
		}

		return NULL;
	}


	public function getCustomContentTypeTagIDs($ctName, $tagNames){
		if(is_string($tagNames)) $tagNames = array($tagNames);

		$tagIDs = array();

		$ct = $this->getCustomContentType($ctName);
		foreach($ct['tags'] as $tagObj){
			foreach($tagNames as $tagName){
				if(array_search($tagName, $tagObj['name']) !== FALSE){
					$tagIDs[] = $tagObj['uuid'];
				}
			}
		}

		return $tagIDs;
	}


}