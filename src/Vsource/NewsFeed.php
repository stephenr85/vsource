<?php

namespace Vsource;

use \Wa72\HtmlPageDom\HtmlPageCrawler;
use Underscore\Types\Arrays;

class NewsFeed {
	
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
				'url' => $node->filter('link')->html(),
				'title'=> $node->filter('title')->text(),
				'date'=> strtotime($node->filter('pubDate')->text()),
				'description'=>$node->filter('description')->html(),
				'image'=>NULL
			);
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

		$this->feedItems += $items;

		return $this;
	}

	public function loadLumSitesNews($params = array()){
		$adapter = $this->getApp()->getLumSitesAdapter();
		$defaults = array(
			'action'=>"NEWS_READ",
			//'callId'=>"c1b67508-6984-4426-901b-0dbb75a3398b",
			//'callId'=>guidv4(),
			//'customerId'=>"5649391675244544",
			//'instanceId'=>"5183329204699136",
			//'lang'=>"en",
			'maxResults'=>20,
			'more'=>true,
			'sortOrder'=>"-publicationDate",
			'type'=>"news",
		);
		$params = array_merge($defaults, $params);

		$response = $adapter->request('POST', 'content/list', $params);

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
				'url'=> 'https://oneintranet.veolia.com/nam-mgt-northamericaintranet/' . $slug,
				'title' => $flatItem['title.' . $lang] ? $flatItem['title.'.$lang] : $flatItem['title.en'],
				'description' => $flatItem['template.components.0.cells.0.components.1.properties.content.'. $lang] ? $flatItem['template.components.0.cells.0.components.1.properties.content.'. $lang] : $flatItem['template.components.0.cells.0.components.1.properties.content.en'],
				'date' => strtotime($flatItem['publicationDate']),
				'image' => $flatItem['thumbnail']
			);

			$items[] = $item;
		}

		$this->feedItems += $items;

		return $this;
	}

	public function load(){
		$this->loadRss('http://www.veolianorthamerica.com/en/rss-site-updates', 'VNA Newsroom', 'http://www.veolianorthamerica.com/en/media/media/newsroom');
        $this->loadRss('http://planet.veolianorthamerica.com/feed/', 'Planet North America', 'http://planet.veolianorthamerica.com/');
        $this->loadLumSitesNews();
        $this->sortFeedItems();

        //print_r($this->feedItems);
        return $this;
	}

	public function sortFeedItems() {
	    array_multisort(array_column($this->feedItems, 'date'), SORT_DESC,
                array_column($this->feedItems, 'title'), SORT_ASC,
                $this->feedItems);
	    return $this;
	}

	public function getFeedItems(){
		return $this->feedItems;
	}
}


