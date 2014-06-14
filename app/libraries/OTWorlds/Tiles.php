<?php

namespace OTWorlds;

class Tiles {
  
  /**
   * Helper to format the JSON output like the editor expects
   */
  public static function addTileToArray(&$array, $tile) {
    //Convert to array because objects are references in eternity...
    $tile = (array) $tile;
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
  
  /**
   * Removes duplicates from posted tile arrays
   */
  public static function removeDuplicates($array)
  {
    //TODO: stuff
    return $array;
  }
}
