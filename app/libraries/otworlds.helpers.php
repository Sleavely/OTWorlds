<?php

function addTileToArray(&$array, $tile) {
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
