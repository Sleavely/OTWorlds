/**
 * Initializing of the main interface on Otworlds
 */

var $viewport;
var $canvas;
jQuery(document).ready(function(){
	$viewport = jQuery("#viewport");
	$canvas = jQuery("#canvas");
	
	//Calculate positions and dimensions on toolbars
	
	
	//Load the map and fire up the grid
	jQuery.ajax(Mapeditor.config.urls.backend, {
		dataType: "json",
		data: {
			'action' : 'init'
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
	
	//Keep track of whether we need to lookup ".tile.hovered" all the time
	var hoveredElements = 0;
	$viewport.on({
		mousemove: function(e) {
			if(hoveredElements > 0) jQuery(".tile.hovered").removeClass('hovered');
			var $target = Mapeditor.internals.figureOutTile(e);
			$target.addClass('hovered');
			hoveredElements++;
		} 
	}, '.tile');
});