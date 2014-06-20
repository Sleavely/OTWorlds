
Mapeditor.Painter = {};

Mapeditor.Painter.lastPainted = {
  brush: {
    name: 'null',
    'server_lookid': 0
  },
  pos: {
    x: 0,
    y: 0,
    z: 0
  }
};

Mapeditor.Painter.paint = function(){
};

/**
 * @param {object} Tile
 * @param {object} Brush
 */
Mapeditor.Painter.paintTile = function(Tile, Brush){
  //Make sure we are not painting the same tile twice to avoid layout thrashing
  if (
    Mapeditor.map.currentFloor == Mapeditor.Painter.lastPainted.pos.z
    && Tile.x == Mapeditor.Painter.lastPainted.pos.x
    && Tile.y == Mapeditor.Painter.lastPainted.pos.y
    && Tile.z == Mapeditor.Painter.lastPainted.pos.z
    && Brush.name == Mapeditor.Painter.lastPainted.brush.name
    && Brush.server_lookid == Mapeditor.Painter.lastPainted.brush.server_lookid
  ) {
    return;
  }
  
  Tile.itemid = Brush.server_lookid;
  Mapeditor.Painter.lastPainted.brush.name = Brush.name;
  Mapeditor.Painter.lastPainted.brush.server_lookid = Brush.server_lookid;
  Mapeditor.Painter.lastPainted.pos.x = Tile.x;
  Mapeditor.Painter.lastPainted.pos.y = Tile.y;
  Mapeditor.Painter.lastPainted.pos.z = Tile.z;
  console.log('Painting '+Brush.name+' on '+Tile.x+', '+Tile.y+', '+Mapeditor.map.currentFloor);
  Tile.draw();
  
  Tile.save();
  
  Mapeditor.Multiplayer.emit('Mapeditor.paint', {
    Tile: {
      x: Tile.x,
      y: Tile.y,
      z: Tile.z,
      itemid: Tile.itemid
    }
  });
  }