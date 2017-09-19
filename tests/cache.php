<?php

require_once('../_init.php');

header('Content: text/plain');

$cache = new \Vsource\Cache();

$cacheKey = 'testCacheItem';

if(!$cache->has($cacheKey)){
	$cache->set($cacheKey, 'HiHoHiHum '.date('c'));
	echo "$cacheKey set.";
}else{
	echo "$cacheKey ". $cache->get($cacheKey);

	$cacheItem = $cache->getItem($cacheKey);
	var_dump($cacheItem);
}