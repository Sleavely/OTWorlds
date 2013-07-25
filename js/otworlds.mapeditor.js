var Mapeditor = {
	config: {
		urls: {
			backend: 'ajax/',
			sprites: 'http://cdn.otworlds.com/sprites/%sprite%.gif'
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
	load: function(name){
		//Load the map and fire up the grid
		jQuery.ajax(Mapeditor.config.urls.backend, {
			dataType: "json",
			data: {
				'action' : 'init',
				'map' : name
			},
			success: function(data){
				
				Mapeditor.map.meta.name = data.name;
				Mapeditor.map.meta.description = data.description;
				Mapeditor.map.meta.width = data.width;
				Mapeditor.map.meta.height = data.height;
				
				document.title = Mapeditor.map.meta.name + ' - ' + document.title;
				
				//add tiles to cache
				jQuery.each(data.tiles, function(posz, zValue){
					jQuery.each(zValue, function(posx, xValue){
						jQuery.each(xValue, function(posy, tileValue){
							Mapeditor.map.cacheTile(posx, posy, posz, tileValue);
						});
					});
				});
				
				Mapeditor.Materials.load('xml/materials.xml', function(){
					Mapeditor.internals.infinitedrag = jQuery.infinitedrag("#canvas", {},
						{
							width: 32,
							height: 32,
							range_col: [0, Mapeditor.map.meta.width],
							range_row: [0, Mapeditor.map.meta.height],
							start_col: (Mapeditor.map.meta.width/2)-(Math.round((jQuery(window).width() / 32) / 2)),
							start_row: (Mapeditor.map.meta.height/2) - (Math.round((jQuery(window).height() / 32) / 2)),
							class_name: 'tile',
							oncreate: function($element, col, row) {
								var tileObject = Object.create(Mapeditor.Tile);
								tileObject.load(col, row, Mapeditor.internals.currentFloor, $element);
							}
						}
					);
				});
			}
		});
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