<?php

namespace Vsource;

use \Wa72\HtmlPageDom\HtmlPageCrawler;
use Underscore\Types\Arrays;

class SalesContactsFeed {
	
	public function getApp(){
		return \Vsource\App::getInstance();
	}

	public function loadOriginalData(){

		$adapter = $this->getApp()->getLumSitesAdapter();
		$adapter->debug($this->isDebug);
		$params = array();
		$defaults = array(
			'action'=>"PAGE_READ",
			//'customerId'=>$adapter->customerId,
			'instance'=>$adapter->instanceId,
			'lang'=>$this->getApp()->getLanguage(),
			'slug'=>'sales-contacts',
			//'fields'=>'title'
		);
		$params = array_merge($defaults, $params);

		//$adapter->debug(true);
		$response = $adapter->request('GET', 'content/get', $params);

		$json = json_decode($response->getBody(), true);

		return $json;
	}
}