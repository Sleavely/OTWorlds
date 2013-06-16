var Mapeditor = {
	config: {
		urls: {
			backend: 'http://localhost/mapeditor-backend/',
			sprites: 'http://localhost/tibia-spriteExtractor/sprites/%sprite%.gif'
		}
	},
	internals: {
		currentFloor: 7,
		
		/**
		 * Ask the server for a tile area
		 */
		downloadTileArea: function(posx, posy, posz){
			if(posz === undefined) posz = Mapeditor.internals.currentFloor;
			
			//TODO: this doesnt work in backend
			//We will load an area by the size of quadrantSize*quadrantSize to cache
			var quadrantSize = 32;
		},
		figureOutTile: function(e){
			var $source = jQuery(e.srcElement);
			if($source.hasClass("item")){
				var $parent = $source.parent();
				var posx = $parent.attr("col");
				var posy = $parent.attr("row");
				if(e.offsetY <= 32){
					if(e.offsetX <= 32){
						return jQuery(".tile[col="+ (posx-1) +"][row="+ (posy-1) +"]");
					}else{
						return jQuery(".tile[col="+ posx +"][row="+ (posy-1) +"]");
					}
				}else{
					if(e.offsetX <= 32){
						return jQuery(".tile[col="+ (posx-1) +"][row="+ posy +"]");
					}else{
						return $parent;
					}
				}
			}else{
				return $source;
			}
		}
	},
	map: {
		meta: {
		},
		//Z axis, then X axis, then Y axis, just like OTBM by Remere's
		_7 : {
			_100 : {
				_100: {
					itemid: 4526,
					items: [
						{
							itemid: 1337
						}
					]
				}
			}
		},
		/**
		 * Save a tile to cache
		 */
		cacheTile: function(posx, posy, posz, tile){
			if(Mapeditor.map["_"+posz] === undefined) Mapeditor.map["_"+posz] = {};
			if(Mapeditor.map["_"+posz]["_"+posx] === undefined) Mapeditor.map["_"+posz]["_"+posx] = {};
			Mapeditor.map["_"+posz]["_"+posx]["_"+posy] = tile;
		},
		getTile: function(posx, posy, posz){
			//assume Tile is cached
			var fetchRemote = false;
			
			if(Mapeditor.map["_"+posz] === undefined){
				fetchRemote = true;
			}else if(Mapeditor.map["_"+posz]["_"+posx] === undefined){
				fetchRemote = true;
			}else{
				if(Mapeditor.map["_"+posz]["_"+posx]["_"+posy] === undefined) fetchRemote = true;
			}
			
			//now, do we need to ask the server or not?
			if(fetchRemote){
				Mapeditor.internals.downloadTileArea(posx, posy, posz);
			}else{
				//TODO: this should not be inside an 'else' statement, because downloadTileArea() should guarantee that the tile is set, unless something epic happens :(
				return Mapeditor.map["_"+posz]["_"+posx]["_"+posy];
			}
		}
	}
};

jQuery.fn.getTile = function(){
	var $this = this;
	return $this.data("tile");
}

Mapeditor.Tile = function(){};
Mapeditor.Tile.addItem = function(item){
	item.$element.appendTo(this.$element);
}
Mapeditor.Tile.load = function(posx, posy, posz, $element){
	this.$element = $element;
	
	var tileData = Mapeditor.map.getTile(posx, posy, posz);
	if(tileData){
		this.refresh(tileData);
	}
	
	this.$element.data("tile", this);
}
Mapeditor.Tile.refresh = function(tileData){
	var _this = this;
	if(tileData === undefined) tileData = _this;
	
	if(tileData.itemid) this.setItemid(tileData.itemid);
	if(tileData.items){
		jQuery.each(tileData.items, function(itemIndex, itemValue){
			var $child = jQuery('<div />');
			$child.addClass('item');
			$child.css("background-image", "url("+ Mapeditor.config.urls.sprites.replace('%sprite%', itemValue.itemid) +")");
			$child.appendTo(_this.$element);
		});
	}
}
Mapeditor.Tile.setItemid = function(newItemid){
	this.itemid = newItemid;
	this.$element.css("background-image", "url("+ Mapeditor.config.urls.sprites.replace('%sprite%', newItemid) +")");
}


Mapeditor.Item = function(){};
//TODO: add property handling for things such as ActionID and count