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
  
  /**
   * Disable created_at and updated_at
   */
  public $timestamps = false;
  
  public function permissions()
  {
    return $this->hasMany('Permission', 'mapid', 'id');
  }
}
