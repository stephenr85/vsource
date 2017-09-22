<?php

namespace Vsource;

use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class Cache {
	
	public $simple;
	public $adapter;
	private $cacheSeconds = 3600; //60 minutes

	public function __construct($cacheSeconds = NULL){
		if(!is_null($cacheSeconds)){
			$this->cacheSeconds = $cacheSeconds;
		}
		$this->simple = new FilesystemCache('vsource', $this->cacheSeconds);
		$this->adapter = new FilesystemAdapter('vsource', $this->cacheSeconds);
	}

	public function clear(){
		$this->adapter->clear();
		$this->simple->clear();
		return $this;
	}

	public function has($key){
		return $this->adapter->hasItem($key) && $this->adapter->getItem($key)->isHit();
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
		$item = $this->getItem($key);
		return $item->isHit() ? $item->get() : NULL;
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