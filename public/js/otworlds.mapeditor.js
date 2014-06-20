var Mapeditor = {
	config: {
		urls: {
			backend: 'api/',
			sprites: 'http://cdn.otworlds.com/sprites/%sprite%.gif'
		}
	},
	internals: {
		
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
			var posx, posy;
			if($source.hasClass("item")){
				var $parent = $source.parent();
				posx = $parent.attr("col");
				posy = $parent.attr("row");
				if(e.offsetY <= 32){
					if(e.offsetX <= 32){
						return Mapeditor.Tiles.find(posx-1, posy-1, Mapeditor.map.currentFloor);
					}else{
						return Mapeditor.Tiles.find(posx, posy-1, Mapeditor.map.currentFloor);
					}
				}else{
					if(e.offsetX <= 32){
						return Mapeditor.Tiles.find(posx-1, posy, Mapeditor.map.currentFloor);
					}else{
						return Mapeditor.Tiles.find(posx, posy, Mapeditor.map.currentFloor);;
					}
				}
			}else{
				posx = $source.attr("col");
				posy = $source.attr("row");
				return Mapeditor.Tiles.find(posx, posy, Mapeditor.map.currentFloor);
			}
		}
	},
	load: function(id){
		//Clean out any maps that may be loaded already.
		Mapeditor.reset();
		
		//Load the map and fire up the grid
		jQuery.ajax(Mapeditor.config.urls.backend + 'map/' + id + '/init', {
			dataType: "json",
			success: function(data){
				if (data.id) {
					Mapeditor.map.meta.id = data.id;
					Mapeditor.map.meta.name = data.name;
					Mapeditor.map.meta.description = data.description;
					Mapeditor.map.meta.width = data.width;
					Mapeditor.map.meta.height = data.height;
					Mapeditor.canEdit = data.edit;
					
					document.title = Mapeditor.map.meta.name + ' - OTWorlds Mapeditor';
					jQuery('#menu .mapname > span').text(Mapeditor.map.meta.name);
					
					Mapeditor.Minimap.loadStatic();
					
					Mapeditor.Materials.load('xml/materials.xml', function(){
						
						jQuery('#brush-selectors select').prop('disabled', false);
						jQuery('#brush-selectors #palette-selector').change();
						
						Mapeditor.internals.infinitedrag = jQuery.infinitedrag("#canvas",
							{
								cursor: false,
								stop: function(eventObj, pepObj){
									if (Mapeditor.Minimap.loaded) {
										var $img = jQuery('#minimap img');
										var currX = parseInt($img.css('left'));
										var currY = parseInt($img.css('top'));
										var movedX = parseInt((eventObj.pageX - pepObj.startX) / 32);
										var movedY = parseInt((eventObj.pageY - pepObj.startY) / 32);
										$img.css({
											left: currX + movedX,
											top: currY + movedY
										});
									}
								}
							},
							{
								width: 32,
								height: 32,
								range_col: [0, Mapeditor.map.meta.width],
								range_row: [0, Mapeditor.map.meta.height],
								start_col: (Mapeditor.map.meta.width/2)-(Math.round(($viewport.width() / 32) / 2)),
								start_row: (Mapeditor.map.meta.height/2) - (Math.round(($viewport.height() / 32) / 2)),
								class_name: 'tile',
								oncreate: function($element, col, row) {
									//See if the tile is already stored from earlier.
									//This can happen if we've switched floors or use the garbage collector in Infinitedrag.
									var Tile = Mapeditor.Tiles.find(col, row, Mapeditor.map.currentFloor);
									if (Tile) {
										//The element is probably new.
										//(Otherwise why the fuck is the oncreate callback run?)
										Tile.$element = $element;
									} else {
										Tile = Object.create(Mapeditor.Tile);
										Tile.x = col;
										Tile.y = row;
										Tile.z = Mapeditor.map.currentFloor;
										Tile.$element = $element;
										Mapeditor.Tiles.add(Tile);
									}
									Tile.load();
								}
							}
						);
					});
				}
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
					//TODO: this should be a Tile() object
					itemid: 4526,
					items: [
						{
							itemid: 1337
						}
					]
				}
			}
		},
	},
	reset: function(){
		console.log('Resetting editor');
		//Remove map
		Mapeditor.map.currentFloor = 7;
		Mapeditor.map.meta = {};
		for (var i = 0; i < 17; i++) {
			if(Mapeditor.map["_"+i] !== undefined) Mapeditor.map["_"+i] = undefined;
		}
		Mapeditor.internals.infinitedrag = {};
		$canvas.html('');
		$viewport.css('zoom', 1);
		jQuery('#minimap img').remove();
		//Clear brushes
		jQuery("#brushes li").remove();
		Mapeditor.Materials.Brushes = {};
		Mapeditor.Materials.Tilesets = {};
		jQuery('#brush-selectors select#tileset-selector').prop('disabled', true);
		//Turn off painting
		Mapeditor.isEditing = false;
		$canvas.removeClass('editing');
		Mapeditor.Painter.lastPainted.pos = {x: 0, y: 0, z: 0};
	},
	canEdit: false,
	isEditing: false,
	toggleEdit: function(){
		if (Mapeditor.canEdit) {
			$canvas.toggleClass('editing');
			Mapeditor.isEditing = !Mapeditor.isEditing;
			Mapeditor.internals.infinitedrag.disabled(Mapeditor.isEditing);
			console.log('Toggling canvasmode. Now '+(Mapeditor.isEditing ? 'editing' : 'viewing'));
		}else{
			$canvas.removeClass('editing');
			Mapeditor.isEditing = false;
			console.log('Tried to toggle canvasmode but editing not allowed.');
		}
	}
};
