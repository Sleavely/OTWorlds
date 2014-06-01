<?php

class MapController extends BaseController {

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getInit($mapid)
	{
		$map_id = intval($mapid);
    $map = Map::findOrFail($map_id)->toArray();
    return Response::json($map);
	}
}
