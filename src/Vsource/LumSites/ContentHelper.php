<?php
namespace Vsource\LumSites;

use \Wa72\HtmlPageDom\HtmlPageCrawler;

class ContentHelper {
	
	public $isDebug = false;

	public function getApp(){
		return \Vsource\App::getInstance();
	}
	
	public function getAdapter(){
		return $this->getApp()->getLumSitesAdapter();
	}

	public function getLanguage(){
		return $this->getApp()->getLanguage();
	}


	public function getPageData($params){
		$app = $this->getApp();
		$lang = $this->getLanguage();
		$adapter = $this->getAdapter();
		$adapter->cache(!$this->isDebug);
		$adapter->debug($this->isDebug);

		$defaults = array(
			'action'=>"PAGE_READ",
			//'customerId'=>$adapter->customerId,
			'instance'=>$adapter->instanceId,
			'lang'=>$app->getLanguage()
			//'fields'=>'title'
		);
		$params = array_merge($defaults, $params);

		//$adapter->debug(true);
		$response = $adapter->request('GET', 'content/get', $params);

		$json = json_decode($response->getBody(), true);

		return $json;
	}

	public function getPageDataBySlug($slug){
		$params = array(
			'slug'=>$slug,
		);

		return $this->getPageData($params);
	}

	public function getDataLanguageValue($data){
		$lang = $this->getLanguage();
		if(isset($data[$lang])){
			return $data[$lang];
		}else if(isset($data['en'])){
			return $data['en'];
		}else{
			return $data;
		}
	}


	//pass in page['template']['components'] recursively

	public function buildTemplateDom($template){
		$dom = new HtmlPageCrawler('<div></div>');

		foreach($template['components'] as $component){
			$dom->append($this->buildComponentDom($component));
		}

		return $dom;
	}


	public function buildComponentDom($component){
		$methodName = 'buildComponentDom_'.$component['type'];
		if(method_exists($this, $methodName)){
			return call_user_func(array($this, $methodName), $component);
		}else{
			return call_user_func(array($this, 'buildComponentDom__default'), $component);
		}
	}

	public function buildComponentDom__default($component){		
		return '<pre id="'. $component['uuid'] .'">[[[['.var_export($component, true).']]]]</pre>';
	}

	public function buildComponentDom_row($component){
		$dom = new HtmlPageCrawler('<div class="component-'.$component['type'].'" id="'. $component['uuid'] .'"></div>');
		if(isset($component['cells']))
		foreach($component['cells'] as $cell){
			$cellDom = new HtmlPageCrawler('<div class="component-'.$component['type'].'-cell" id="'. $component['uuid'] .'" data-width="'.$cell['width'].'"></div>');
			if(isset($cell['components']))
			foreach($cell['components'] as $component){				
				$cellDom->append($this->buildComponentDom($component));				
			}
			$dom->append($cellDom);	
		}
		return $dom;
	}

	public function buildComponentDom_widget($component){
		$methodName = 'buildWidgetDom_'.$component['widgetType'];
		if(method_exists($this, $methodName)){
			return call_user_func(array($this, $methodName), $component);
		}else{
			return call_user_func(array($this, 'buildWidgetDom__default'), $component);
		}
	}


	public function buildWidgetDom__default($widget){
		return '<pre id="'. $widget['uuid'] .'">[[[['.var_export($widget, true).']]]]</pre>';
	}

	public function buildWidgetDom_about($widget){
		if(!isset($widget['properties']) || !isset($widget['properties']['title'])) return;

		$dom = '<div class="widget-'.$widget['widgetType'].'" id="'.$widget['uuid'].'">';
		$dom .= '<h3>'. $this->getDataLanguageValue($widget['properties']['title']) .'</h3>';
		$dom .= '</div>';
		return $dom;
	}

	public function buildWidgetDom_html($widget){
		if(!isset($widget['properties']) || !isset($widget['properties']['content'])) return;

		$dom = '<div class="widget-'.$widget['widgetType'].'" id="'.$widget['uuid'].'">';
		$dom .= $this->getDataLanguageValue($widget['properties']['content']);
		$dom .= '</div>';
		return $dom;
	}

	public function buildWidgetDom_links($widget){
		if(!isset($widget['properties'])) return;

		$dom = '<div class="widget-'.$widget['widgetType'].'" id="'.$widget['uuid'].'">';
		$dom .= '<h4 class="widget-title">'.$this->getDataLanguageValue($widget['title']).'</h4>';
		$dom .= '<ul class="widget-links">';
		foreach($widget['properties']['items'] as $item){
			$dom .= '<li><a href="'.$this->getDataLanguageValue($item['link']).'" target="'.($item['targetBlank'] ? '_blank' : '').'">'.$this->getDataLanguageValue($item['title']).'</a></li>';
		}
		$dom .= '</ul>';
		$dom .= '</div>';
		return $dom;
	}

	public function buildWidgetDom_support($widget){
		$dom = '<div class="widget-'.$widget['widgetType'].'" id="'.$widget['uuid'].'">';
		
		$props = $widget['properties'];
		$title = $this->getDataLanguageValue($props['title']);
		$image = $this->getDataLanguageValue($props['url']);
		$name = $this->getDataLanguageValue($props['name']);
		$email = $this->getDataLanguageValue($props['email']);

		$dom .= '<img src="'.$image.'" alt="'.$title.'">';
		$dom .= '<h4 class="widget-title">'.$title.'</h4>';
		$dom .= '<p>';
		$dom .= 	'<strong class="widget-name">'.$name.'</strong>';
		$dom .= 	'<br><a href="mailto:'.$email.'">'.$email.'</a>';
		$dom .= '</p>';
		$dom .= '</div>';
		return $dom;
	}
	

}