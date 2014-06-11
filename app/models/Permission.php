<?php

class Permission extends Eloquent {
  protected $fillable = array('mapid', 'userid');
  public $timestamps = false;
  public $primaryKey = 'pid';
  
  public function map()
  {
    return $this->belongsTo('Map', 'mapid', 'id');
  }
  public function user()
  {
    return $this->belongsTo('User', 'userid', 'id');
  }
}
