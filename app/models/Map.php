<?php

class Map extends Eloquent {
  public $timestamps = false;
  
  public function permissions()
  {
    return $this->hasMany('Permission', 'mapid', 'id');
  }
}
