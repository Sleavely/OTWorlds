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
    //Do nothing because if the tile doesnt exist it will be downloaded when needed
  } else {
    console.log('Tile existed');
    Mapeditor.Tiles.add(msg.Tile);
    //This will force it to be redrawn
    Tile.load();
  }
});
