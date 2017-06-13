<?php

namespace Vsource;

use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class Cache {
	
	public $simple;
	public $adapter;
	private $cacheSeconds = 60;

	public function __construct(){
		$this->simple = new FilesystemCache('vsource');
		$this->adapter = new FilesystemAdapter('vsource');
	}

	public function clear(){
		$this->adapter->clear();
		return $this;
	}

	public function has($key){
		return $this->adapter->hasItem($key);
	}

	public function getKey($parts){
		$args = func_get_args();
		$regex = '/[^a-zA-Z0-9_]/';
		$segments = array();
		foreach($args as $akey=>$avalue){
			if(is_object($avalue)){
				$avalue = (array)$avalue; //convert to array
				ksort($avalue);
				$avalue = http_build_query($avalue);
			}else if(is_array($avalue)){
				$avalue = array() + $avalue; //copy array
				ksort($avalue);
				$avalue = http_build_query($avalue);
			}
			$segments[] = preg_replace($regex, '_', (string)$avalue);
		}
		$key = implode('.', $segments);
		return $key;
	}

	public function getItem($key){
		return $this->adapter->getItem($key);
	}

	public function get($key){
		return $this->getItem($key)->get();
	}

	public function set($key, $data = NULL, $seconds = NULL){
		if(is_null($data)){
			$this->adapter->deleteItem($key);
		}else{
			$cacheItem = $this->adapter->getItem($key);
			$cacheItem->set($data);

			if(is_null($seconds)){
				$seconds = $this->cacheSeconds;
			}
			$cacheItem->expiresAfter($seconds);
			$this->adapter->save($cacheItem);
		}
		
		return $this;
	}


}