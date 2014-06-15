<?php

class Map extends Eloquent {
  
  /**
   * Hide attributes from JSON and Array output
   */
  protected $hidden = array('deleted_at');
  
  /**
   * Allow map to be soft deleted to avoid army of mysql queries in hard deletes
   */
  protected $softDelete = true;
  
  public function permissions()
  {
    return $this->hasMany('Permission', 'mapid', 'id');
  }
  
  public function minimap()
  {
    return $this->hasOne('Minimap', 'mapid', 'id');
  }
}
