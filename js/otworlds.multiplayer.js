/**
 * Wrappers for TogetherJS
 */
Mapeditor.Multiplayer = {};

/**
 * Send an update to the universe
 *
 * @param {string} eventName
 * @param {object} jsonData
 */
Mapeditor.Multiplayer.emit = function(eventName, jsonData){
  var delivery = jQuery.extend({type: eventName}, jsonData);
  TogetherJS.send(delivery);
};

/**
 * React to when other players paint. Works much like onCreate()
 */
TogetherJS.hub.on("Mapeditor.paint", function(msg){
  console.log('Received paint event');
  var Tile = Mapeditor.Tiles.find(msg.Tile.x, msg.Tile.y, msg.Tile.z);
  if (!Tile) {
    console.log('Creating tile');
    Tile = Object.create(Mapeditor.Tile);
    Tile.x = msg.Tile.x;
    Tile.y = msg.Tile.y;
    Tile.z = msg.Tile.z;
    Tile.itemid = msg.Tile.itemid;
    
    Mapeditor.Tiles.add(Tile);
    //Because the tile hasnt already been created by infinitedrag, it doesnt have an $element
  } else {
    console.log('Tile existed');
    Mapeditor.Tiles.add(msg.Tile);
    //This will force it to be redrawn
    Tile.load();
  }
});
