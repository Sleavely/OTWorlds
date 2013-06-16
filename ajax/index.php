<?php

try{
	
	require_once('../inc/POT/OTS.php');
	//OTS_OTBMFile.php has been modded to include tilereading, all mods are marked with a comment like so:
	//Sleavely: blah blah
	$map = new OTS_OTBMFile();
	
	
	
	/*
	echo '<pre>
width = '.$map->width.'
height = '.$map->height.'
description = '.htmlentities($map->description, ENT_COMPAT, 'UTF-8').'
towns = '.htmlentities(print_r($map->getTowns(), true), ENT_COMPAT, 'UTF-8').'

tiles: '.PHP_EOL.htmlentities(print_r($map->tiles, true), ENT_COMPAT, 'UTF-8').'
</pre>';
	*/
	$mapname = 'gm-isle.otbm';
	$map->loadFile('../maps/'.$mapname);
	
	if(!isset($_REQUEST['debug'])){
		
		$output = array();
		
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'init'){
			$output['name'] = 'gm-isle.otbm';
			$output['description'] = $map->getDescription();
			$output['width'] = $map->getWidth();
			$output['height'] = $map->getHeight();
		}
		$output['tiles'] = $map->tiles;
		
		if(isset($_GET['callback'])) echo $_GET['callback'].'(';
		echo json_encode($output);
		if(isset($_GET['callback'])) echo ')';
		
		
	}else{
		//file_put_contents(dirname(__FILE__).'/maptiles.txt', $map->tiles);
		echo 'A'.PHP_EOL;
		echo '<pre>'.htmlentities(print_r($map->tiles, true), ENT_COMPAT, 'UTF-8').'</pre>';
		echo PHP_EOL.'B';
	}
	
}catch(Exception $e){
	echo '<pre>'.htmlentities($e, ENT_COMPAT, 'UTF-8').'</pre>';
	echo '<pre>'.htmlentities(print_r($e->getTrace(), true), ENT_COMPAT, 'UTF-8').'</pre>';
}

?>