/**
 * Initializing of the main interface on Otworlds
 */

var $viewport;
var $canvas;
jQuery(document).ready(function(){
	$viewport = jQuery("#viewport");
	$canvas = jQuery("#canvas");
	
	function showmap(id) {
		//If a map is already loaded then so is probably TogetherJS. Unload it
		if (TogetherJS.running) {
			TogetherJS();
			TogetherJS.require("storage").tab.clear("status");
		}
		_gaq.push(['_trackPageview', '/map/'+id]);
		history.pushState('', '', '#mapid-'+id);
		Mapeditor.load(id);
		TogetherJS.config('findRoom', 'otworlds_mapid-'+id);
		TogetherJS();
	}
	
	function readWindowState(){
		if (window.location.hash.substr(1, 5) == 'mapid') {
			jQuery("#welcome").remove();
			showmap(window.location.hash.substr(7));
		}else{
			_gaq.push(['_trackPageview', '/welcome']);
			
			//Move the welcome div into a dialog
			var $welcomeVex = vex.open({
				content: jQuery("#welcome").html(), //TODO: there shouldnt even be a #welcome
				showCloseButton: false,
				escapeButtonCloses: false,
				overlayClosesOnClick: false,
				afterOpen: function($vexContent) {
					jQuery("#welcome").remove();
					$vexContent.on({
						click: function(){
							vex.close($welcomeVex.data().vex.id);
							return false;
						}
					}, '.btn a');
				}
			});
		}
	}
	jQuery(window).on('popstate', function(e){
		//This will cause the (empty) popup to appear every time a # link is clicked
		readWindowState();
	});
	readWindowState();
	
	//Populate the map list modal
	window.showmaps = function(){
		jQuery.ajax(Mapeditor.config.urls.backend + 'maps', {
			dataType: "json",
			success: function(data){
				
				maplist = '';
				jQuery.each(data.maps, function(key, val){
					maplist += '<option value="'+ val.id +'">'+ val.name +'</option>';
				});
				
				vex.dialog.open({
					message: 'Select a map',
					input: '<select name="mapid">'+ maplist +'</select>',
					callback: function(selected){
						if(selected) showmap(selected.mapid);
					}
				});
				_gaq.push(['_trackPageview', '/maps']);
			}
		});
	};
	
	//Let user create a map
	window.createmap = function(){
		vex.dialog.open({
			message: 'Create new map',
			input: '<input name="name" type="text" placeholder="Map name" required />\n<input name="description" type="text" placeholder="Description" />',
			callback: function(inputdata) {
				if (inputdata !== false) {
					jQuery.ajax(
						Mapeditor.config.urls.backend + 'map/create',
						{
							dataType: "json",
							type: "POST",
							data: {
								'name' : inputdata.name,
								'description' : inputdata.description
							},
							success: function(responsedata){
								showmap(responsedata.id);
							}
						}
					);
				}
			}
		});
	};
	
	//Sharing dialog
	window.sharemap = function(){
		if (!Mapeditor.map.meta.id) {
			vex.dialog.alert('No map is loaded. You can\'t share what doesn\'t exist!');
			return;
		}
		vex.dialog.open({
			message: 'Enter an email to share with:',
			input: '<input name="username" type="text" placeholder="Email" required />\n\
							<label for="permissions-radio-view"><input type="radio" name="permissions" value="view" id="permissions-radio-view" checked /> View</label>\
							<label for="permissions-radio-edit"><input type="radio" name="permissions" value="edit" id="permissions-radio-edit" /> View + Edit</label>\
							<label for="permissions-radio-owner"><input type="radio" name="permissions" value="owner" id="permissions-radio-owner" /> Owner</label>',
			buttons: [
				$.extend({}, vex.dialog.buttons.YES, {
					text: 'Share'
				}), $.extend({}, vex.dialog.buttons.NO, {
					text: 'Back'
				})
			],
			callback: function(data) {
				if (data !== false) {
					jQuery.ajax(
						Mapeditor.config.urls.backend + 'map/' + Mapeditor.map.meta.id + '/share',
						{
							dataType: "json",
							type: "POST",
							data: {
								'username' : data.username,
								'permissions' : data.permissions
							},
							success: function(responsedata){
								vex.dialog.alert('The map has been shared');
							},
							error: function(){
								vex.dialog.alert('Something went wrong. Are you sure you entered the right username? If you are not the owner of the map you are not allowed to share it.');
							}
						}
					);
				}
			}
		});
	};
	
	//Keep track of whether we need to lookup ".tile.hovered" all the time
	var hoveredElements = 0;
	var mouseIsPressed = false;
	$viewport.on({
		mousemove: function(e) {
			if (Mapeditor.isEditing) {
				if(hoveredElements > 0) jQuery(".tile.hovered").removeClass('hovered');
				var Tile = Mapeditor.internals.figureOutTile(e);
				Tile.$element.addClass('hovered');
				if(!hoveredElements) hoveredElements++;
				
				if (Mapeditor.isEditing && mouseIsPressed) {
					var activeBrush = Mapeditor.Materials.Brushes[ Mapeditor.Materials.Brushes.active ];
					
					Mapeditor.Painter.paintTile(Tile, activeBrush);
				}
			}
		},
		//Painting when editing is active
		click: function(e) {
			if (Mapeditor.isEditing) {
				var activeBrush = Mapeditor.Materials.Brushes[ Mapeditor.Materials.Brushes.active ];
				var Tile = Mapeditor.internals.figureOutTile(e);
				
				Mapeditor.Painter.paintTile(Tile, activeBrush);
			}
		},
		mousedown: function(e) {
			if (e.which == 1) {
				mouseIsPressed = true;
			}
		},
		mouseup: function(e) {
			if (e.which == 1) {
				mouseIsPressed = false;
			}
		}
	}, '.tile');
	$viewport.on('mousewheel', function(e){
		if (!Mapeditor.map.meta.id) return;
		
		var viewport_zoom = parseFloat($viewport.css('zoom'));
		// Turn scrolling into small increments of zoom change
		var delta_change = parseFloat(e.originalEvent.wheelDeltaY / 1000);
		
		// Round the floats to 2 decimals
		viewport_zoom = Math.round((viewport_zoom + delta_change) * 100) / 100;
		
		// Min and max zoom level. Wont be exact since we deal with floats.
		if(viewport_zoom >= 0.5 && viewport_zoom <= 1.25)
		{
			$viewport.css('zoom', viewport_zoom);
			Mapeditor.internals.infinitedrag.update_containment();
		}
	});
	jQuery(window).keyup(function(e) {
		//Spacebar
		if (e.which == 32 && Mapeditor.map.meta.id) {
			Mapeditor.toggleEdit();
			e.preventDefault();
			e.stopPropagation();
		}
	});
	jQuery('#brush-selectors').on({
		change: function(e){
			var $this = jQuery(this);
			
			if ($this.attr('id') == 'palette-selector')
			{
				Mapeditor.Materials.switchPalette(jQuery('option:selected', this).data('palette'));
			}
			else if ($this.attr('id') == 'tileset-selector') {
				Mapeditor.Materials.switchTileset(jQuery('option:selected', this).text());
			}
		}
	}, 'select');
	jQuery('#brushes').on({
		click: function(e) {
			var $this = jQuery(this);
			if (!$this.hasClass('active')) {
				jQuery('.active').removeClass('active');
				$this.addClass('active');
				Mapeditor.Materials.Brushes.active = $this.data('name');
				
				if (!Mapeditor.isEditing) {
					Mapeditor.toggleEdit();
				}
			}
		}
	}, '.brush');
	
});