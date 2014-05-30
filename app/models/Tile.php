<?php

class Tile extends Eloquent {
  public $timestamps = false;
  public $primaryKey = 'tileid';
  
  public function map()
  {
    return $this->belongsTo('Map', 'mapid', 'id');
  }
  public function items()
  {
    return $this->hasMany('Item');
  }
}
