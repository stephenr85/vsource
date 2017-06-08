<?php require_once('_init.php'); 

vsource()->startView();

$locale = vsource()->getLocale();
$viewPath = trim($_SERVER['PATH_INFO'], '/');

$rootPath = vsource()->getRootDirectory();
$finalPath = NULL;

$testLocales = array($locale, VSOURCE_LOCALE);
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


require($finalPath);


vsource()->endView();

?>