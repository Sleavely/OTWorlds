/**
 * The Tile() object and functions for modifying a an individual tile.
 */
Mapeditor.Tile = function(){};


/**
 * Attach the object to the element and refresh the
 * element if theres anything in the cache/on the server.
 */
Mapeditor.Tile.load = function(){
	
  //Use itemid to see if the tile has actual data on it
  if (this.itemid && this.$element) {
    //Yep, alles good. Draw the tile.
    this.draw();
    
  } else {
    //No items :<
    Mapeditor.Tiles.queue.download.push(this.x+','+this.y+','+this.z);
    Mapeditor.Tiles.download();
  }
};

Mapeditor.Tile.save = function(){
	var savedata = {
		x: this.x,
		y: this.y,
		z: this.z,
		itemid: this.itemid
	};
	Mapeditor.Tiles.queue.upload.push(savedata);
	Mapeditor.Tiles.upload();
};

/**
 * Repaint the $element with current Tile() values
 */
Mapeditor.Tile.draw = function(){
	
  this.$element.css("background-image", "url("+ Mapeditor.config.urls.sprites.replace('%sprite%', this.itemid) +")");
	
	if(this.items){
		jQuery.each(this.items, function(itemIndex, itemValue){
			var $child = jQuery('<div />');
			$child.addClass('item');
			$child.css("background-image", "url("+ Mapeditor.config.urls.sprites.replace('%sprite%', itemValue.itemid) +")");
			$child.appendTo(_this.$element);
		});
	}
};
