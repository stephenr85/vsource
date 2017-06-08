<?php require_once('_init.php'); 

$view = new Vsource\View();
$finalPath = $view->resolveViewPath($_SERVER['PATH_INFO']);

if($finalPath){
	echo $view->render($finalPath);
}else{
	http_response_code(404);
}


?>