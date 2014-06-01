<?php

class MapController extends BaseController {
  
  /**
   * Helper. Shaves off bytes by only storing each X once.
   * //TODO: should be moved to a helper class
   */
  private function addTileToArray(&$array, $tile) {
    //Convert to array because objects are references in eternity...
    $tile = (array) $tile;
    if(!isset($array[$tile['posz']])){
      $array[$tile['posz']] = array();
    }
    if(!isset($array[$tile['posz']][$tile['posx']])){
      $array[$tile['posz']][$tile['posx']] = array();
    }
    if(!isset($array[$tile['posz']][$tile['posx']][$tile['posy']])){
      $array[$tile['posz']][$tile['posx']][$tile['posy']] = $tile;
    }
    
    //Remove redundant data
    unset($array[$tile['posz']][$tile['posx']][$tile['posy']]['mapid']);
    unset($array[$tile['posz']][$tile['posx']][$tile['posy']]['posz']);
    unset($array[$tile['posz']][$tile['posx']][$tile['posy']]['posx']);
    unset($array[$tile['posz']][$tile['posx']][$tile['posy']]['posy']);
  }
  
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getInit($mapid)
	{
    //TODO: these two lines are used basically every time, use __construct?
		$mapid = intval($mapid);
    $map = Map::findOrFail($mapid);
    return Response::json($map->toArray());
	}
  
  public function postChunk($mapid)
  {
    $mapid = intval($mapid);
    $map = Map::findOrFail($mapid);
    //Because PHP scope doesn't work like JS
    $GLOBALS['tiles'] = Input::get('tiles');
    
    if(!is_array($GLOBALS['tiles'])) {
      // Bad request
      App::abort(400);
    }
    
    $tiles = DB::table('tiles')
      ->where('mapid', '=', $mapid)
      ->where(function($query)
      {
        foreach($GLOBALS['tiles'] as $tile_to_load)
        {
          $GLOBALS['splittile'] = explode(',', $tile_to_load);
          $query->orWhere(function($subquery)
          {
            $subquery
              ->where('posz', intval($GLOBALS['splittile'][2]))
              ->where('posx', intval($GLOBALS['splittile'][0]))
              ->where('posy', intval($GLOBALS['splittile'][1]));
          });
        }
      })
      ->get();
    
    $output = array();
    $output['tiles'] = array();
    foreach($tiles as $tile){
      $this->addTileToArray($output['tiles'], $tile);
    }
    
    return Response::json($output);
  }
}
