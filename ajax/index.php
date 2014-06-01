<?php

$output = array();
try{
	require_once('../inc/db.php');
	require_once('../inc/user.php');
	require_once('../inc/helpers.php');
	
	if(!isset($_REQUEST['debug'])){
		
		//TODO: verify that user is logged in (and part of beta group?)
		if(isset($_REQUEST['action'])){
			if($_REQUEST['action'] == 'savetiles' && isset($_REQUEST['map']) && isset($_REQUEST['tiles']))
			{
				//Lets validate the data
				$map_id = intval($_REQUEST['map']);
				$map_query = $db->query('SELECT * FROM maps WHERE id = '.$map_id);
				if($map_query->num_rows === 1){
					if(!is_array($_REQUEST['tiles'])) {
						throw new Exception('No tile array found.');
					}
					
					foreach($_REQUEST['tiles'] as $tile) {
						$ins_or_upd = 'INSERT INTO tiles (mapid, posx, posy, posz, itemid) 
							VALUES ('.$map_id.', '.intval($tile['x']).', '.intval($tile['y']).', '.intval($tile['z']).', '.intval($tile['itemid']).')
							ON DUPLICATE KEY UPDATE
							itemid='.intval($tile['itemid']);
						$db->query($ins_or_upd);
					}
					$output['status'] = 'Success!';
					
				}else{
					throw new Exception('Invalid map ID.');
				}
			}
			else
			{
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