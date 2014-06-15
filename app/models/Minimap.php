<?php

class Minimap extends Eloquent {
  
  /**
   * Disable the auto-timestamp feature. We are flying manually from hereon.
   */
  public $timestamps = false;
  
  /**
   * Tell Eloquent we have an attribute that doesnt come from DB
   */
  protected $appends = array('path');
  
  /**
   * What would a minimap be without a map?
   */
  public function map()
  {
    return $this->belongsTo('Map', 'mapid', 'id');
  }
  
  /**
   * Get the path to the actual image.
   * External requests _should_ go through /api/map/{mapid}/minimap
   * but could be used like for publically sharing:
   * $minimap_url = asset($map->minimap->path(true))
   *
   * @param bool $external
   * @return string
   */
  public function getPathAttribute($external = false)
  {
    $encrypted_name = md5('SECRET_SAUCE-'.$this->map->id.'-'.$this->map->name);
    $encrypted_name = preg_replace('/[^a-z0-9]/i', '', $encrypted_name);
    $encrypted_name = substr($encrypted_name, 0, 32);/**/
    return ($external ? '' : public_path().'/') . 'img/maps/'.$encrypted_name.'.png';
  }
}
