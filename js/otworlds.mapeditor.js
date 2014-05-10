﻿var Mapeditor = {
	config: {
		urls: {
			backend: 'ajax/',
			sprites: 'http://cdn.otworlds.com/sprites/%sprite%.gif'
		}
	},
	internals: {
		
		/**
		 * Queue a tile for batch download
		 */
		downloadTile: function(posx, posy, posz){
			if(posz === undefined) posz = Mapeditor.map.currentFloor;
			Mapeditor.internals.tileAreaQueue.push(posx+','+posy+','+posz);
			Mapeditor.internals.downloadTileArea();
		},
		tileAreaQueue: [],
		/**
		 * Helper to avoid flooding server with requests
		 */
		createDebouncer: function(func, wait, immediate) {
			var timeout;
			return function() {
				var context = this, args = arguments;
				clearTimeout(timeout);
				timeout = setTimeout(function() {
					timeout = null;
					if (!immediate) func.apply(context, args);
				}, wait);
				if (immediate && !timeout) func.apply(context, args);
			};
		},
		figureOutTile: function(e){
			var $source = jQuery(e.currentTarget);
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
	load: function(id){
		//Load the map and fire up the grid
		jQuery.ajax(Mapeditor.config.urls.backend, {
			dataType: "json",
			data: {
				'action' : 'init',
				'map' : id
			},
			success: function(data){
				
				Mapeditor.map.meta.id = data.id;
				Mapeditor.map.meta.name = data.name;
				Mapeditor.map.meta.description = data.description;
				Mapeditor.map.meta.width = data.width;
				Mapeditor.map.meta.height = data.height;
				
				document.title = Mapeditor.map.meta.name + ' - ' + document.title;
				
				Mapeditor.Materials.load('xml/materials.xml', function(){
					Mapeditor.internals.infinitedrag = jQuery.infinitedrag("#canvas", {cursor: false},
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
								tileObject.load(col, row, Mapeditor.map.currentFloor, $element);
							}
						}
					);
				});
			}
		});
	},
	map: {
		currentFloor: 7,
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
				Mapeditor.internals.downloadTile(posx, posy, posz);
			}else{
				//TODO: this should not be inside an 'else' statement, because downloadTileArea() should guarantee that the tile is set, unless something epic happens :(
				return Mapeditor.map["_"+posz]["_"+posx]["_"+posy];
			}
		}
	},
	isEditing: false,
	toggleEdit: function(){
		$canvas.toggleClass('editing');
		Mapeditor.isEditing = !Mapeditor.isEditing;
		Mapeditor.internals.infinitedrag.disabled(Mapeditor.isEditing);
		console.log('Toggling canvasmode. Now '+(Mapeditor.isEditing ? 'editing' : 'viewing'));
	},
	lastPainted: {
		brush: {
			name: 'null',
			'server_lookid': 0
		},
		pos: {
			x: 0,
			y: 0,
			z: 0
		}
	},
	/**
	 * @param {object} Tile
	 * @param {object} Brush
	 */
	paint: function(Tile, Brush){
		//Make sure we are not painting the same tile twice to avoid layout thrashing
		if (
			Mapeditor.map.currentFloor == Mapeditor.lastPainted.pos.z
			&& Tile.x == Mapeditor.lastPainted.pos.x
			&& Tile.y == Mapeditor.lastPainted.pos.y
			&& Tile.z == Mapeditor.lastPainted.pos.z
			&& Brush.name == Mapeditor.lastPainted.brush.name
			&& Brush.server_lookid == Mapeditor.lastPainted.brush.server_lookid
		) {
			return;
		}
		
		Tile.setItemid( Brush.server_lookid );
		Mapeditor.lastPainted.brush.name = Brush.name;
		Mapeditor.lastPainted.brush.server_lookid = Brush.server_lookid;
		Mapeditor.lastPainted.pos.x = Tile.x;
		Mapeditor.lastPainted.pos.y = Tile.y;
		Mapeditor.lastPainted.pos.z = Tile.z;
		
		console.log('Painting '+Brush.name+' on '+Tile.x+', '+Tile.y+', '+Mapeditor.map.currentFloor);
	}
};

/**
 * Ask the server for a tile area
 */
Mapeditor.internals.downloadTileArea = Mapeditor.internals.createDebouncer(
	function(){
		console.log('Requesting '+Mapeditor.internals.tileAreaQueue.length+' tiles'+(Mapeditor.internals.tileAreaQueue.length > 500 ? ' in chunks of 500' : '')+'.');
		
		//Split requests into chunks of 500 tiles at a time
		var limitedArray = [];
		while (Mapeditor.internals.tileAreaQueue.length) {
			limitedArray = Mapeditor.internals.tileAreaQueue.splice(0, 500);
			
			//TODO: need to implement some kind of caching of non-existing tiles before enabling this: the first request is 86~ KB HTTP-POST data
			jQuery.ajax(Mapeditor.config.urls.backend, {
				dataType: "json",
				type: "POST",
				data: {
					'action' : 'loadtiles',
					'map' : Mapeditor.map.meta.id,
					'tiles' : limitedArray
				},
				success: function(data){
					//add tiles to cache
					jQuery.each(data.tiles, function(posz, zValue){
						jQuery.each(zValue, function(posx, xValue){
							jQuery.each(xValue, function(posy, tileValue){
								Mapeditor.map.cacheTile(posx, posy, posz, tileValue);
								//TODO: need to refresh the tiles somehow
								var $tile = jQuery('.tile[col='+posx+'][row='+posy+']');
								Mapeditor.Tile.load(posx, posy, posz, $tile);
								//jQuery('.tile[col='+posx+'][row='+posy+']').getTile().setItemid(tileValue.itemid);
							});
						});
					});
				}
			});
			limitedArray = [];
		}
		
		//Clear the queue as soon as the requests has been sent
		Mapeditor.internals.tileAreaQueue = [];
	},
	500
);

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
	this.x = posx;
	this.y = posy;
	this.z = posz;
	
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