<?php

$output = array();
try{
	require_once('../inc/db.php');
	require_once('../inc/user.php');
	
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
					$output['name'] = $map_result['name'];
					$output['description'] = $map_result['description'];
					$output['width'] = $map_result['width'];
					$output['height'] = $map_result['height'];
					$output['version'] = $map_result['version'];
					
					//Figure out where the center of the map is
					$chunk = array(
						'center' => array(
							'z' => 7,
							'x' => intval( $map_result['width'] / 2 ),
							'y' => intval( $map_result['height'] / 2 ),
						)
					);
					//Add a radius of tiles to load
					//TODO: this should be based on the viewport size, but with a maximum possible value
					$chunk['startx'] = $chunk['center']['x'] - 50;
					$chunk['starty'] = $chunk['center']['y'] - 50;
					$chunk['endx'] = $chunk['center']['x'] + 50;
					$chunk['endy'] = $chunk['center']['y'] + 50;
					
					//Load ground floor at the center of the map
					$tiles_query = $db->query('
						SELECT *
						FROM tiles
						WHERE
							mapid = '.$map_id.' AND
							posz = '.$chunk['center']['z'].' AND
							posx > '.$chunk['startx'].' AND
							posy > '.$chunk['starty'].' AND
							posx < '.$chunk['endx'].' AND
							posy < '.$chunk['endy'].'
						ORDER BY posz, posx, posy
					');
					
					//posz, posx, posy
					$output['tiles'] = array();
					while ($tile = $tiles_query->fetch_assoc()){
					
						if(!isset($output['tiles'][$tile['posz']])){
							$output['tiles'][$tile['posz']] = array();
						}
						if(!isset($output['tiles'][$tile['posz']][$tile['posx']])){
							$output['tiles'][$tile['posz']][$tile['posx']] = array();
						}
						if(!isset($output['tiles'][$tile['posz']][$tile['posx']][$tile['posy']])){
							$output['tiles'][$tile['posz']][$tile['posx']][$tile['posy']] = $tile;
						}
						
						//Remove redundant data
						unset($output['tiles'][$tile['posz']][$tile['posx']][$tile['posy']]['mapid']);
						unset($output['tiles'][$tile['posz']][$tile['posx']][$tile['posy']]['posz']);
						unset($output['tiles'][$tile['posz']][$tile['posx']][$tile['posy']]['posx']);
						unset($output['tiles'][$tile['posz']][$tile['posx']][$tile['posy']]['posy']);
					}
					
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