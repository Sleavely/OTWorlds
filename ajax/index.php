<?php

$output = array();
try{
	require_once('../inc/db.php');
	require_once('../inc/user.php');
	
	if(!isset($_REQUEST['debug'])){
		
		if(isset($_REQUEST['action'])){
			if($_REQUEST['action'] == 'listMaps'){
				$output['maps'] = $db->query('SELECT * FROM maps')->fetch_all(MYSQLI_ASSOC);
				
				
			}elseif($_REQUEST['action'] == 'init' && isset($_REQUEST['map'])){
				//Find the map in DB
				
				//Send metadata
				$output['name'] = 'gm-isle.otbm';
				$output['description'] = $map->getDescription();
				$output['width'] = $map->getWidth();
				$output['height'] = $map->getHeight();
				
				//Load a grid and send along
				
				
			}else{
				throw new Exception('Invalid action.');
			}
		}else{
			throw new Exception('No action.');
		}
		
		
		
		
	}else{
		//file_put_contents(dirname(__FILE__).'/maptiles.txt', $map->tiles);
		echo 'A'.PHP_EOL;
		echo '<pre>'.htmlentities(print_r($map->tiles, true), ENT_COMPAT, 'UTF-8').'</pre>';
		echo PHP_EOL.'B';
		die();
	}
	
}catch(Exception $e){
	$output['error'] = $e->getMessage();
	$output['trace'] = $e->getTrace();
}

if(isset($_GET['callback'])) echo $_GET['callback'].'(';
echo json_encode($output);
if(isset($_GET['callback'])) echo ')';

?>