/**
 * This is a collection of Tile-related utilities that don't directly modify a specific tile.
 */
Mapeditor.Tiles = {};

/**
 * Add a tile to the cache
 *
 */
Mapeditor.Tiles.add = function(Tile){
  var x = Tile.x;
  var y = Tile.y;
  var z = Tile.z;
  if(Mapeditor.map["_"+z] === undefined) Mapeditor.map["_"+z] = {};
  if(Mapeditor.map["_"+z]["_"+x] === undefined) Mapeditor.map["_"+z]["_"+x] = {};
  if(Mapeditor.map["_"+z]["_"+x]["_"+y] === undefined){
    Mapeditor.map["_"+z]["_"+x]["_"+y] = Tile;
  }else{
    //We are merging an existing tile, probably updated on server.
    //Properties on the new Tile overwrite existing ones.
    jQuery.extend(Mapeditor.map["_"+z]["_"+x]["_"+y], Tile);
  }
};

/**
 * @return Tile() or false if not loaded
 */
Mapeditor.Tiles.find = function(x, y, z){
  if(Mapeditor.map["_"+z] === undefined) return false;
  if(Mapeditor.map["_"+z]["_"+x] === undefined) return false;
  if(Mapeditor.map["_"+z]["_"+x]["_"+y] === undefined) return false;
  return Mapeditor.map["_"+z]["_"+x]["_"+y];
};

Mapeditor.Tiles.download = Mapeditor.internals.createDebouncer(function(){
  console.log('Requesting '+Mapeditor.Tiles.queue.download.length+' tiles'+(Mapeditor.Tiles.queue.download.length > 500 ? ' in chunks of 500' : '')+'.');
  
  //Split requests into chunks of 500 tiles at a time
  var limitedArray = [];
  while (Mapeditor.Tiles.queue.download.length) {
    limitedArray = Mapeditor.Tiles.queue.download.splice(0, 500);
    
    jQuery.ajax(Mapeditor.config.urls.backend, {
      dataType: "json",
      type: "POST",
      data: {
        'action' : 'loadtiles',
        'map' : Mapeditor.map.meta.id,
        'tiles' : limitedArray
      },
      success: function(data){
        //Parse through the tiles that existed on server
        jQuery.each(data.tiles, function(posz, zValue){
          jQuery.each(zValue, function(posx, xValue){
            jQuery.each(xValue, function(posy, tileValue){
              
              var Tile = Mapeditor.Tiles.find(posx, posy, posz);
              jQuery.extend(Tile, tileValue);
              
              //Tiles.download was called from Tile.load, so lets return there
              Tile.load();
            });
          });
        });
      }
    });
    limitedArray = [];
  }
  
  //Clear the queue as soon as the requests has been sent
  Mapeditor.Tiles.queue.download = [];
}, 500);


Mapeditor.Tiles.upload = Mapeditor.internals.createDebouncer(function(){
  console.log('Saving '+Mapeditor.Tiles.queue.upload.length+' tiles'+(Mapeditor.Tiles.queue.upload.length > 100 ? ' in chunks of 100' : '')+'.');
  
  //Split requests into chunks of 100 tiles at a time
  var limitedArray = [];
  while (Mapeditor.Tiles.queue.upload.length) {
    limitedArray = Mapeditor.Tiles.queue.upload.splice(0, 100);
    
    jQuery.ajax(Mapeditor.config.urls.backend, {
      dataType: "json",
      type: "POST",
      data: {
        'action' : 'savetiles',
        'map' : Mapeditor.map.meta.id,
        'tiles' : limitedArray
      },
      success: function(data){
        if (data.error) {
          console.log('Something went wrong on the server while saving.');
        }
      },
      error: function(){
        console.log('Failed to save.');
      }
    });
    limitedArray = [];
  }
  
  //Clear the queue as soon as the requests has been sent
  Mapeditor.Tiles.queue.upload = [];
}, 750);

// Storage for the down- and uploaders.
Mapeditor.Tiles.queue = {};
Mapeditor.Tiles.queue.download = [];
Mapeditor.Tiles.queue.upload = [];
