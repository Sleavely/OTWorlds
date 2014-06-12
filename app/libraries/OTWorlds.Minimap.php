<?php

class Minimap {
  
  public static $rgbs = array(
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //0
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //4
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //8
    array(0,102,0),  array(0,0,0),      array(0,0,0),      array(0,0,0),       //12
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //16
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //20
    array(0,204,0),  array(0,0,0),      array(0,0,0),      array(0,0,0),       //24
    array(0,0,0),    array(0,0,0),      array(0,255,0),    array(0,0,0),       //28
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //32
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //36
    array(51,0,204), array(0,0,0),      array(0,0,0),      array(0,0,0),       //40
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //44
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //48
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //52
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //56
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //60
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //64
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //68
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //72
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //76
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //80
    array(0,0,0),    array(0,0,0),      array(102,102,102),array(0,0,0),       //84
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //88
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //92
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //96
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //100
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //104
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //108
    array(0,0,0),    array(0,0,0),      array(163,51,0),   array(0,0,0),       //112
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //116
    array(0,0,0),    array(153,102,51), array(0,0,0),      array(0,0,0),       //120
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //124
    array(0,0,0),    array(153,153,153),array(0,0,0),      array(0,0,0),       //128
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //132
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //136
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //140
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //144
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //148
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //152
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //156
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //160
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //164
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //168
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //172
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(204,255,255), //176
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //180
    array(0,0,0),    array(0,0,0),      array(255,51,0),   array(0,0,0),       //184
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //188
    array(255,102,0),array(0,0,0),      array(0,0,0),      array(0,0,0),       //192
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //196
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //200
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(255,204,153), //204
    array(0,0,0),    array(0,0,0),      array(255,255,0),  array(0,0,0),       //208
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(255,255,255), //212
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //216
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //220
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //224
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //228
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //232
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //236
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //240
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //244
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0),       //248
    array(0,0,0),    array(0,0,0),      array(0,0,0),      array(0,0,0)        //252
  );
  public static $filename = null;
  private static $image = null;
  private static $items = array();
  
  /**
   * Create an empty PNG with custom dimensions
   */
  public static function create($width = 512, $height = 512)
  {
    self::$image = imagecreatetruecolor($width, $height);
    imagealphablending(self::$image, true);
    imagesavealpha(self::$image, true);
    $transparency = imagecolorallocatealpha(self::$image, 0, 0, 0, 127);
    imagefill(self::$image, 0, 0, $transparency);
  }
  
  public static function load()
  {
    if(!file_exists(self::$filename))
    {
      self::create();
    }
    else
    {
      self::$image = imagecreatefrompng(self::$filename);
    }
  }
  
  public static function save()
  {
    imagepng(self::$image, self::$filename);
    imagedestroy(self::$image);
  }
  
  /**
   * Remove the minimap
   */
  public static function remove()
  {
    if(file_exists(self::$filename)) unlink(self::$filename);
  }
  
  /**
   * Paint and item/tile to the minimap
   */
  public static function paint($x, $y, $itemid)
  {
    if(!self::$image) self::load();
    imagealphablending(self::$image, true);
    imagesavealpha(self::$image, true);
    
    $rgb = self::itemColor($itemid);
    if(!$rgb) return false;
    $color = imagecolorallocatealpha(self::$image , $rgb[0], $rgb[1], $rgb[2], 0);
    imagesetpixel(self::$image, $x, $y , $color);
    
    return true;
  }
  
  /**
   * Non-static method for the Laravel Queue handler
   */
  public function queuePaint($job, $data)
  {
    $map = Map::findOrFail($data['mapid']);
    self::$filename = $map->minimapPath();
    self::load();
    foreach($data['tiles'] as $tile)
    {
      if(intval($tile['z']) == 7)
      {
        self::paint(
          intval($tile['x']),
          intval($tile['y']),
          intval($tile['itemid'])
        );
      }
    }
    self::save();
    $job->delete();
  }
  
  /**
   * Fetch an item color from cache or OTB.
   *
   * @param $itemid
   * @return false|array RGB
   */
  public static function itemColor($itemid)
  {
    if(!isset(self::$items[$itemid]))
    {
      if(!POT::areItemsLoaded()) POT::loadItems( public_path().'/xml' );
      $list = POT::getItemsList();
      if($list->hasItemTypeId($itemid))
      {
        $item = $list->getItemType($itemid);
        if($item->hasAttribute('minimapColor'))
        {
          self::$items[$itemid] = self::$rgbs[$item->getAttribute('minimapColor')];
        }
      }
      if(!isset(self::$items[$itemid])) self::$items[$itemid] = false;
    }
    
    return self::$items[$itemid];
  }
}
