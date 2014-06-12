<?php

class Item extends Eloquent {
  public $timestamps = false;
  public $primaryKey = 'propid';
  
  public function tile()
  {
    return $this->belongsTo('Tile', 'tileid', 'tileid');
  }
}
