<?php

$output = array();
try{
	require_once('../inc/db.php');
	require_once('../inc/user.php');
	require_once('../inc/helpers.php');
	
	if(!isset($_REQUEST['debug'])){
		
		//TODO: verify that user is logged in (and part of beta group?)
		if(isset($_REQUEST['action'])){
			if($_REQUEST['action'] == 'listMaps'){
				
				$output['maps'] = array();
				$maps_query = $db->query('SELECT * FROM maps');
				while($map = $maps_query->fetch_assoc()){
					$output['maps'][] = $map;
				}
				
			}elseif($_REQUEST['action'] == 'init' && isset($_REQUEST['map'])){
				
				$map_id = intval($_REQUEST['map']);
				
				//Find the map in DB
				$map_query = $db->query('SELECT * FROM maps WHERE id = '.$map_id);
				
				if($map_query->num_rows === 1){
					$map_result = $map_query->fetch_assoc();
					
					//Send metadata
					$output['id'] = $map_result['id'];
					$output['name'] = $map_result['name'];
					$output['description'] = $map_result['description'];
					$output['width'] = $map_result['width'];
					$output['height'] = $map_result['height'];
					$output['version'] = $map_result['version'];
				}else{
					throw new Exception('Invalid map ID.');
				}
				
			}elseif($_REQUEST['action'] == 'loadtiles' && isset($_REQUEST['map']) && isset($_REQUEST['tiles'])){
				
				$map_id = intval($_REQUEST['map']);
				//Find the map in DB
				$map_query = $db->query('SELECT * FROM maps WHERE id = '.$map_id);
				if($map_query->num_rows === 1){
					
					if(!is_array($_REQUEST['tiles'])) {
						throw new Exception('No tile array found.');
					}
					$tiles_to_load = array();
					foreach($_REQUEST['tiles'] as $tile_to_load) {
						$splittile = explode(',', $tile_to_load);
						$tiles_to_load[] = '(posz = '.intval($splittile[2]).' AND posx = '.intval($splittile[0]).' AND posy = '.intval($splittile[1]).')';
					}
					$tiles_or = implode(' OR ', $tiles_to_load);
					
					//TODO: this query should run once per 50 tiles, to avoid a humongous query crashing the db
					$tiles_query = $db->query('
						SELECT *
						FROM tiles
						WHERE
							mapid = '.$map_id.' AND
							('.$tiles_or.')
						ORDER BY posz, posx, posy
					');
					$output['tilesor'] = $tiles_or;
					
					//posz, posx, posy
					$output['tiles'] = array();
					while ($tile = $tiles_query->fetch_assoc()){
						addTileToArray($output['tiles'], $tile);
					}
					
				}else{
					throw new Exception('Invalid map ID.');
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