<?php

namespace Vsource;

use \Wa72\HtmlPageDom\HtmlPageCrawler;
use Underscore\Types\Arrays;

class NewsFeed {
	
	public $isDebug = FALSE;
	protected $feedItems;

	public function __construct(){
		$this->feedItems = array();
	}

	public function getApp(){
		return \Vsource\App::getInstance();
	}

	public function loadRss($feedUrl, $feedName = NULL, $feedLink = NULL){

		$xml = $this->getApp()->getUrlContent($feedUrl);
		$xml = str_replace(array('<![CDATA[',']]>'), '', $xml);
		$xml = str_replace(array('link>'), 'a>', $xml); //crawler is behaving inconsistently with <link></link> tags for some reason
		//var_export($xml.'');
		$crawler = new HtmlPageCrawler($xml);

		if(!$feedLink){
			$feedLink = $feedUrl;
		}

		if(!$feedName){
			$feedName = $crawler->filter('channel > title')->text();
		}
		
		$items = $crawler->filter('item')->each(function(HtmlPageCrawler $node, $i) use ($feedName, $feedLink){
			$item = array(
				'feedName'=>$feedName,
				'feedUrl'=>$feedLink,
				'url' => $node->filter('a')->html(),
				'title'=> $node->filter('title')->text(),
				'date'=> strtotime($node->filter('pubDate')->text()),
				'datestr'=>$node->filter('pubDate')->text(),
				'datestr2'=>date('c', strtotime($node->filter('pubDate')->text())),
				'description'=>$node->filter('description')->html(),
				'image'=>NULL
			);
			//var_export($item);
			$img = $node->filter('description > img');
			if(count($img) > 0){
				$item['image'] = $img->attr('src');
				$img->remove();
				$item['description'] = $node->filter('description')->html();
			}else{
				$imgEnclosure = $node->filter('enclosure[type*="image"]');
				if(count($imgEnclosure) > 0){
					$item['image'] = $imgEnclosure->attr('url');
				}
			}
			return $item;
		});

		//print_r($items);
		$this->feedItems = array_merge($this->feedItems, $items);
		
		return $this;
	}

	public function loadLumSitesNews($params = array()){
		$adapter = $this->getApp()->getLumSitesAdapter();
		$adapter->cache(!$this->isDebug);
		$adapter->debug($this->isDebug);
		//$adapter->debug(true);

		

		$defaults = array(
			'action'=>"NEWS_READ",
			'customerId'=>$adapter->customerId,
			'instanceId'=>$adapter->instanceId,
			'lang'=>$this->getApp()->getLanguage(),
			'maxResults'=>20,
			'more'=>true,
			'sortOrder'=>"-publicationDate",
			'type'=>"news",
		);
		$params = array_merge($defaults, $params);

		$response = $adapter->request('POST', 'content/list', $params);
		if($adapter->isDebug) print_r((string)$response->getBody());
		$json = json_decode($response->getBody(), TRUE);
		$lang = $this->getApp()->getLanguage();
		$items = array();
		foreach($json['items'] as $jsonItem){			
			$flatItem = Arrays::flatten($jsonItem);
			//print_r($flatItem);

			$slug = $flatItem['slug.' . $lang] ? $flatItem['slug.'.$lang] : $flatItem['slug.en'];

			$item = array(
				'feedName' => 'One to One',
				'feedUrl' => 'https://oneintranet.veolia.com/nam-mgt-northamericaintranet//nam-mgt-northamericaintranet/news-list/',
				'exturl'=> 'https://oneintranet.veolia.com/nam-mgt-northamericaintranet/' . $slug,
				'url'=>$this->getApp()->getRootUrl().'view.php/news_item?slug='.$slug,
				'title' => $flatItem['title.' . $lang] ? $flatItem['title.'.$lang] : $flatItem['title.en'],
				'description' => isset($flatItem['template.components.0.cells.0.components.1.properties.content.'. $lang]) ? $flatItem['template.components.0.cells.0.components.1.properties.content.'. $lang] : $flatItem['template.components.0.cells.0.components.1.properties.content.en'],
				'date' => strtotime($flatItem['publicationDate']),
				'image' => $flatItem['thumbnail']
			);

			$items[] = $item;
		}

		$this->feedItems = array_merge(array_values($this->feedItems), array_values($items));

		return $this;
	}

	public function loadLumSitesNewsItem($slug){
		$adapter = $this->getApp()->getLumSitesAdapter();
		$adapter->cache(!$this->isDebug);
		$adapter->debug($this->isDebug);
		$params = array();
		$defaults = array(
			'action'=>"PAGE_READ",
			//'customerId'=>$adapter->customerId,
			'instance'=>$adapter->instanceId,
			'lang'=>$this->getApp()->getLanguage(),
			'slug'=>$slug,
			//'fields'=>'title'
		);
		$params = array_merge($defaults, $params);

		//$adapter->debug(true);
		$response = $adapter->request('GET', 'content/get', $params);

		$json = json_decode($response->getBody(), true);

		return $json;
	}

	public function debug($isDebug = TRUE){
		$this->isDebug = $isDebug;
		return $this;
	}

	public function load(){
		if($this->getApp()->getLanguage() == 'fr'){
			$this->loadRss('http://www.veolianorthamerica.com/fr/rss-articles', 'Communiqués de presse', 'http://www.veolianorthamerica.com/fr/communiques-de-presse');

			$this->loadLumSitesNews(array(
				'customContentTypeTags' => $this->getApp()->getLumSitesAdapter()->getCustomContentTypeTagIDs('News', 'FR')
			));
		}else{
			$this->loadRss('http://www.veolianorthamerica.com/en/rss-site-updates', 'VNA Newsroom', 'http://www.veolianorthamerica.com/en/media/newsroom');
        	$this->loadRss('http://planet.veolianorthamerica.com/feed/', 'Planet North America', 'http://planet.veolianorthamerica.com/');

        	$this->loadLumSitesNews();
		}
		
        
        $this->sortFeedItems();

        //print_r($this->feedItems);
        return $this;
	}

	public function sortFeedItems() {
	    //array_multisort(array_column($this->feedItems, 'date'), SORT_DESC,
         //       array_column($this->feedItems, 'title'), SORT_ASC,
         //       $this->feedItems);
		$this->feedItems = Arrays::sort($this->feedItems, function($item){
			return $item['date'] .' '.$item['title'];
		}, 'desc');
	    return $this;
	}

	public function getFeedItems(){
		return $this->feedItems;
	}
}


