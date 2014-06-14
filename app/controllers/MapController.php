<?php

class MapController extends BaseController {
  
  public static function create()
  {
    if (!Input::has('name')) App::abort(400);
    
    $user = Auth::user();
    
    $map = new Map;
    $map->width = 512;
    $map->height = 512;
    $map->name = Input::get('name');
    $map->description = (Input::has('description') ? Input::get('description') : '');
    $map->version = 960;
    $map->save();
    
    $ownership = new Permission;
    $ownership->mapid = $map->id;
    $ownership->userid = $user->id;
    $ownership->owner = true;
    $ownership->edit = true;
    $ownership->view = true;
    $ownership = $map->permissions()->save($ownership);
    
    $minimap = new Minimap;
    $minimap->mapid = $map->id;
    $minimap->updated_at = new \DateTime;
    $minimap = $map->minimap()->save($minimap);
    
    \OTWorlds\MinimapPainter::$filename = $minimap->path();
    \OTWorlds\MinimapPainter::create(512, 512);
    \OTWorlds\MinimapPainter::save();
    
    return $map->id;
  }
  
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
    
    //Enforce ACL
    if(!$this->userCanView($map)) App::abort(403);
    
    $arrayMap = $map->toArray();
    $arrayMap['edit'] = $this->userCanEdit($map);
    return Response::json($arrayMap);
	}
  
  public function postLoad($mapid)
  {
    $mapid = intval($mapid);
    $map = Map::findOrFail($mapid);
    
    //Enforce ACL
    if(!$this->userCanView($map)) App::abort(403);
    
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
  
  public function postSave($mapid)
  {
    
    $mapid = intval($mapid);
    $map = Map::findOrFail($mapid);
    
    //Enforce ACL
    if(!$this->userCanEdit($map)) App::abort(403);
    
    //Lets validate the data
    $tiles = Input::get('tiles');
    if(!is_array($tiles)) {
      throw new Exception('No tile array found.');
    }
    
    foreach($tiles as $tile) {
      $ins_or_upd = 'INSERT INTO tiles (mapid, posx, posy, posz, itemid) 
        VALUES ('.$mapid.', '.intval($tile['x']).', '.intval($tile['y']).', '.intval($tile['z']).', '.intval($tile['itemid']).')
        ON DUPLICATE KEY UPDATE
        itemid='.intval($tile['itemid']);
      DB::statement($ins_or_upd);
    }
    
    //Queue::push('Minimap@queuePaint', array(
    //  'mapid' => $map->id,
    //  'tiles' => $tiles,
    //));
    
    //No exceptions, no prisoners!
    $output = array();
    $output['status'] = 'Success!';
    return Response::json($output);
  }
  
  public function postShare($mapid)
  {
    $mapid = intval($mapid);
    $map = Map::findOrFail($mapid);
    
    //Enforce ACL
    if(!$this->userIsOwner($map)) App::abort(403);
    
    $user = User::where('email', '=', Input::get('username'))->firstOrFail();
    
    $ownership = Permission::firstOrNew(array('mapid' => $map->id, 'userid' => $user->id));
    
    $permissions = Input::get('permissions');
    $ownership->owner = ($permissions === 'owner');
    $ownership->edit = ($permissions === 'owner' || $permissions === 'edit');
    $ownership->view = true;
    
    $ownership = $map->permissions()->save($ownership);
    $output = array(
      'status' => 'probably ok'
    );
    return Response::json($output);
  }
  
  public function getMinimap($mapid)
  {
    $mapid = intval($mapid);
    $map = Map::findOrFail($mapid);
    
    //Enforce ACL
    if(!$this->userCanView($map)) App::abort(403);
    
    $png = file_get_contents( $map->minimap()->path() );
    return Response::make($png, 200, array('content-type' => 'image/png'));
  }
  
  private function userCanView(Map $map)
  {
    return (
      DB::table('permissions')
        ->where('mapid', $map->id)
        ->where('userid', Auth::user()->id)
        ->where('view', 1)
        ->count()
      >
      0
    );
  }
  private function userCanEdit(Map $map)
  {
    return (
      DB::table('permissions')
        ->where('mapid', $map->id)
        ->where('userid', Auth::user()->id)
        ->where('edit', 1)
        ->count()
      >
      0
    );
  }
  private function userIsOwner(Map $map)
  {
    return (
      DB::table('permissions')
        ->where('mapid', $map->id)
        ->where('userid', Auth::user()->id)
        ->where('owner', 1)
        ->count()
      >
      0
    );
  }
}
