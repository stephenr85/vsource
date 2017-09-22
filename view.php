<?php require_once('_init.php'); 

$app = vsource();
$view = new Vsource\View();
$finalPath = $view->resolveViewPath($_SERVER['PATH_INFO']);

if($finalPath){
	$cacheParams = $_GET + array();
	unset($cacheParams['auth']);
	$cacheKey = $app->cache->getKey(basename(__FILE__), $finalPath, http_build_query($cacheParams));
	echo "<!-- $cacheKey -->";
	if($app->cache->has($cacheKey) && VSOURCE_VIEW_CACHE_ENABLED){
		$output = $app->cache->get($cacheKey);
	}else{
		$output = $view->render($finalPath);
		$app->cache->set($cacheKey, $output);
	}
	
	echo $output;

}else{
	http_response_code(404);
}


?>