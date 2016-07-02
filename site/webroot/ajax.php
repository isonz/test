<?php
$a = isset($_GET['a']) ? addslashes($_GET['a']) : '';
$id = isset($_GET['id']) ? (int)($_GET['id']) : 0;

if($id){
	Templates::Assign('id', $id);
	
	$destination = Destination::getAll('code, name', 'sort DESC, id ASC');
	Templates::Assign('destination', $destination);
	
	$projects = Setting::projects();
	Templates::Assign('projects', $projects);
	
	$tickets = Setting::tickets();
	Templates::Assign('tickets', $tickets);
	
	$hotel = Setting::hotel();
	Templates::Assign('hotels', $hotel);
	
	$car = Setting::car();
	Templates::Assign('cars', $car);
	
	$citys = Setting::citys();
	Templates::Assign('citys', $citys);
}

if($a){
	
	$maxpeoplenums = array();
	for ($i=1;$i<=Setting::MAX_PEOPLE_NUM; $i++) $maxpeoplenums[] = $i;
	Templates::Assign('maxpeoplenums', $maxpeoplenums);
	
	Templates::Display($a.'.html');
}



