<?php

class Map extends Eloquent {
  protected $softDelete = true;
  public $timestamps = false;
  
  public function permissions()
  {
    return $this->hasMany('Permission', 'mapid', 'id');
  }
}
