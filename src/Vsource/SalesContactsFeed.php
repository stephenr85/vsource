<?php

namespace Vsource;

use \Wa72\HtmlPageDom\HtmlPageCrawler;
use Underscore\Types\Arrays;

class SalesContactsFeed {

	public $isDebug = false;
	
	public function getApp(){
		return \Vsource\App::getInstance();
	}

	public function loadData(){
		$lang = $this->getApp()->getLanguage();

		$originalData = $this->loadOriginalData();
		//print_r($originalData);

		$data = $this->parseComponents($originalData['template']['components']);


		return $data;
	}

	public function loadOriginalData(){

		$adapter = $this->getApp()->getLumSitesAdapter();
		$adapter->cache(!$this->isDebug);
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

	public function parseContent($content){
		$cat = new SalesContactsFeedCategory();

		$lang = $this->getApp()->getLanguage();
		$dom = new HtmlPageCrawler($content);

		$parseContact = function($node, $contact = null){
			if(!$contact) $contact = new SalesContactsFeedContact();
			//$contact->node = $node;
			foreach($node->childNodes as $childNode){
				if($childNode->nodeType == 1 && in_array($childNode->tagName,  ['b', 'strong'])){
					$contact->name = $childNode->nodeValue;
					continue;
				}
				if(filter_var($childNode->nodeValue, FILTER_VALIDATE_EMAIL)){
					$contact->email = $childNode->nodeValue;
					continue;
				}
				if(preg_match('/\d{3}\.\d{3}\.\d{4}/', $childNode->nodeValue)){
					$contact->phone = $childNode->nodeValue;
					continue;
				}
				//if made it here, assume it's description
				if(trim($childNode->nodeValue)){
					$contact->description = $childNode->nodeValue;	
				}					
			}
			return $contact;
		};


		foreach($dom as $node){
			if(in_array($node->tagName, ['h1', 'h2', 'h3', 'h4', 'h5'])) break;

			if($node->tagName == 'p' && $node->firstChild && $node->firstChild->nodeType == 1 && in_array($node->firstChild->tagName,  ['b', 'strong'])){
				$cat->contacts[] = $parseContact($node);
			}else if($node->tagName == 'p'){
				$cat->description .= ($cat->description ? '<br><br>' : '') . (new HtmlPageCrawler($node))->html();
			}			
		}


		$cat->children = $dom->filter('h2,h3,h4,h5')->each(function($node, $i) use ($lang, &$parseContact){
			$cat = new SalesContactsFeedCategory();
			$cat->name = $node->text();
			$cat->color = 'rgb(128,65,128)';
			foreach($node->nextAll() as $next){
				$contact = false;
				//$next = new HtmlPageCrawler($next);
				if(in_array($next->tagName, ['h1', 'h2', 'h3', 'h4', 'h5'])) break;
				//$cat->nexts[] = $next->saveHTML();
				if($next->tagName == 'p' && $next->firstChild && $next->firstChild->nodeType == 1 && in_array($next->firstChild->tagName,  ['b', 'strong'])){
					$contact = $parseContact($next);
					$cat->contacts[] = $contact;
					$contact->category = $cat;

				}else if($next->tagName == 'p'){
					$cat->description .= ($cat->description ? '<br><br>' : '') . (new HtmlPageCrawler($next))->html();

				}else if($next->tagName == 'table'){
					foreach((new HtmlPageCrawler($next))->filter('td') as $td){
						//$cat->td[] = $td;
						$contact = $parseContact($td);
						$cat->contacts[] = $contact;
						$contact->category = $cat;
					}
				}

			}
			return $cat;
		});
		return $cat;
	}


	public function parseComponents($components){
		$data = [];
		$lang = $this->getApp()->getLanguage();

		foreach($components as $component){
	      	//print_r(Arrays::flatten($component));

	   		if($component['type'] == 'widget' && $component['widgetType'] == 'about'){
	   			continue;
	        	$cat = new SalesContactsFeedCategory();
	        	$cat->name = $component['properties']['title'];
	        	$cat->name = isset($cat->name[$lang]) ? $cat->name[$lang] : $cat->name['en'];

	        	if(is_array($component['properties']['content'])){
		        	$cat->description = $component['properties']['content'];
		        	$cat->description = isset($cat->description[$lang]) ? $cat->description[$lang] : $cat->description['en'];
		        }

	        	if(isset($component['properties']['veoliaWidgetColor']['value'])){
	        		$cat->color = $component['properties']['veoliaWidgetColor']['value'];
	        	}

	        	$data[] = $cat;

	          //echo '<h2>'.$component['properties']['title']['en'].'</h2>';
	          //echo '<p>'.$component['properties']['content']['en'].'</p>';
	        }else if($component['type'] == 'widget' && $component['widgetType'] == 'html'){
	          //echo '<div>'.$component['properties']['content'].'</div>';
	        	$content = $component['properties']['content'];
	        	$content = isset($content[$lang]) ? $content[$lang] : $content['en'];

	        	$cat = $this->parseContent($content);

	        	$cat->name = $component['title'];
	        	$cat->name = isset($cat->name[$lang]) ? $cat->name[$lang] : $cat->name['en'];

	        	$cat->color = 'rgb(233, 95, 71)';
	        	$data[] = $cat;
	        }

	        if(isset($component['cells'])){
	        	foreach($component['cells'] as $cell){
	        		if(isset($cell['components'])){
	        			$data = array_merge($data, $this->parseComponents($cell['components']));
	        		}		        		
	        	}
	        }
	        
	        
	    }

	    return $data;  
	}
}



class SalesContactsFeedCategory {	

	public $color = '#CCCCCC';
	public $name;
	public $description = '';

	public $parent;
	public $children;

	public $contacts;

	public function __construct(){
		$this->children = [];
		$this->contacts = [];
	}
}


class SalesContactsFeedContact {

	public $category;

	public $name;
	public $email;
	public $phone;
	public $description;

}