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
				$map_query = $db->query('SELECT * FROM maps WHERE id = '.intval($_REQUEST['map']));
				
				if($map_query->num_rows === 1){
					$map_result = $map_query->fetch_assoc();
					
					//Send metadata
					$output['name'] = $map_result['name'];
					$output['description'] = $map_result['description'];
					$output['width'] = $map_result['width'];
					$output['height'] = $map_result['height'];
					$output['version'] = $map_result['version'];
					
					//TODO: Load a grid of tiles and send along
					$output['tiles'] = array( //posz, posx, posy
						7 => array(
							1013 => array(
								1021 => array(
									'itemid' => 4664
								)
							),
							1024 => array(
								1024 => array(
									'itemid' => 4664
								)
							),
							1072 => array(
								1027 => array(
									'itemid' => 4664
								)
							)
						)
					);
					
				}else{
					throw new Exception('Invalid ID.');
				}
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