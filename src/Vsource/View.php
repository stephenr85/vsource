<?php

namespace Vsource;

use \Wa72\HtmlPageDom\HtmlPageCrawler;

class View {

	public function getApp(){
		return App::getInstance();
	}

	public function startView(){
		ob_start();
	}

	public function endView(){
		$content = ob_get_clean();
		
		$hrefRegex = "/(?<=href=(\"|'))[^\"']+(?=(\"|'))/";
		$srcRegex = "/(?<=src=(\"|'))[^\"']+(?=(\"|'))/";

		$replaceUrl = function($input){

			if(strpos($input[0], '#') === 0 
				|| strpos($input[0], 'http') === 0 
				|| strpos($input[0], 'mailto') === 0
				|| strpos($input[0], 'tel') === 0){
				return $input[0];
			}else if(strpos($input[0], VSOURCE_VIEW_ROOT) !== 0) {
				return VSOURCE_VIEW_ROOT.$input[0];
			}else {
				return $input[0];
			}

		};
		
		$content = preg_replace_callback($hrefRegex, $replaceUrl, $content);
		$content = preg_replace_callback($srcRegex, $replaceUrl, $content);

		$content = str_replace(':url(/', ':url('.VSOURCE_VIEW_ROOT, $content);

		return $content;
	}

	public function resolveViewPath($path){
		$viewPath = trim($_SERVER['PATH_INFO'], '/');

		$rootPath = $this->getApp()->getRootDirectory();
		$finalPath = NULL;

		$testLocales = array($this->getApp()->getLocale(), VSOURCE_LOCALE);
		foreach($testLocales as $testLocale){
			$testPath = implode('/', array($rootPath, 'views', $testLocale, $viewPath));
			//echo $testPath;
			if(file_exists($testPath)){
				$finalPath = $testPath;
				break;
			}else if(file_exists($testPath.'.php')){
				$finalPath = $testPath.'.php';
				break;
			}
		}
		
		return $finalPath;
	}

	public function render($path){
		if(!file_exists($path)){
			$path = $this->resolveViewPath($path);
		}
		if(!file_exists($path)) return NULL;

		$this->startView();

		require($path);

		$content = $this->endView();
		$content .= '<!-- '.$this->getApp()->getTranslator()->getLocale().' : '.str_replace($this->getApp()->getRootDirectory(), '', $path).'-->';

		return $content;
	}
}