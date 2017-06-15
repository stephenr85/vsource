<?php
require_once('_init.php');

$locations = json_decode(file_get_contents('data/locations.json'), true);

foreach($locations as $l=>$location){

	$services = explode(',', $location['services']);

	foreach($services as $s=>$service){
		$services[$s] = t(trim($service));
	}
	
	$location['services'] = implode(', ', $services);
	$locations[$l] = $location;
}

echo json_encode($locations);