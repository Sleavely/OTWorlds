Mapeditor.Materials = {};
Mapeditor.Materials.Brushes = Mapeditor.Materials.Brushes || {};
Mapeditor.Materials.Tilesets = Mapeditor.Materials.Tilesets || {};

Mapeditor.Materials.waitingToLoad = 0;
Mapeditor.Materials.load = function(loadUrl, loadCallback, urlScope){
	if(loadUrl === undefined) return false;
	Mapeditor.Materials.waitingToLoad++;
	console.log('Loading', loadUrl);
	//figure out current URL scope
	if(!urlScope){
		//this is the first request in the chain
		urlScope = loadUrl.split("/");
		urlScope.pop();
		urlScope.join("/");
		urlScope += "/";
	}else{
		loadUrl = urlScope + loadUrl;
	}
	
	jQuery.ajax(loadUrl, {
		cache: true,
		dataType : 'xml',
		error: function(jqXHR, errorString){
			console.log('Mapeditor.Materials.load('+loadUrl+') failed:');
			console.log(errorString);
			console.log(jqXHR);
		},
		success: function(xmldoc){
			jQuery('materials', xmldoc).children().each(function(){
				
				if(this.tagName == "include"){
					Mapeditor.Materials.parseInclude(this, loadCallback, urlScope);
					return;
				}
				if(this.tagName == "tileset"){
						var tileset = this;
						Mapeditor.Materials.parseTileset(tileset);
					return;
				}
				if(this.tagName == "brush"){
					Mapeditor.Materials.parseBrush(this);
					return;
				}
			});
			
			Mapeditor.Materials.waitingToLoad--;
			console.log('Finished loading', loadUrl);
			if (Mapeditor.Materials.waitingToLoad === 0) {
				console.log('Finished loading all materials.');
				if(loadCallback !== undefined && loadCallback !== false) loadCallback();
			}
		}
	});
	//Activate the first brush
	//var startBrush = jQuery("#itemlist .brush").eq(0).addClass('active').data('name');
	//Mapeditor.Materials.Brushes.active = startBrush;
};

Mapeditor.Materials.parseInclude = function(node, loadCallback, urlScope){
	var $this = jQuery(node);
	var fileToLoad = $this.attr("file"); //TODO: needs to be aware of relative URLs since we are already in XML folder. OR we need to always use XML folder.
	Mapeditor.Materials.load(fileToLoad, loadCallback, urlScope);
};

Mapeditor.Materials.parseBrush = function(node){
	var $brush = jQuery(node);
	// Make sure this is a brush declaration and not an inclusion in a palette
	if (node.parentElement.nodeName == 'materials')
	{
		var brush = {};
		brush.name = $brush.attr('name');
		
		if (node.hasAttribute('server_lookid')) {
			brush.server_lookid = node.getAttribute('server_lookid');
		}else if (node.hasAttribute('lookid')) {
			brush.server_lookid = node.getAttribute('lookid');
		}
		
		// If we don't have a lookid by now, fuck it.
		if (brush.server_lookid) {
			
			// Lets go through the items and their chances
			brush.items = [];
			brush.totalchance = 0;
			$brush.children().each(function(){
				if (this.tagName == 'item')
				{
					var chanceAttr = parseInt(this.getAttribute('chance'));
					brush.items.push({
						type: 'item',
						id: this.getAttribute('id'),
						chance: chanceAttr
					});
					brush.totalchance += chanceAttr;
				}
				else if (this.tagName == 'composite')
				{
					// Composites are things that take up several tiles, like stairs
					var chanceAttr = parseInt(this.getAttribute('chance'));
					var composite = {
						type: 'composite',
						chance: chanceAttr,
						tiles : []
					};
					// Loop through the tiles involved
					jQuery(this).children().each(function(){
						if (this.tagName == 'tile') {
							composite.tiles.push({
								x: (this.hasAttribute('x') ? this.getAttribute('x') : 0),
								y: (this.hasAttribute('y') ? this.getAttribute('y') : 0),
								z: (this.hasAttribute('z') ? this.getAttribute('z') : 0),
								id: jQuery(this).children('item').eq(0).attr('id')
							});
						}
					});
					brush.items.push(composite);
					brush.totalchance += chanceAttr;
				}
			});
			// Save the parsed brush
			if (brush.items.length > 0) {
				Mapeditor.Materials.Brushes[brush.name] = brush;
			}
		}
	}
};

Mapeditor.Materials.parseTileset = function(node){
	var $tileset = jQuery(node);
	var tileset = {};
	
	$tileset.children().each(function(){
		if (this.tagName == 'terrain')
		{
			tileset.terrain = [];
			jQuery(this).children().each(function(){
				if (this.tagName == 'brush')
				{
					tileset.terrain.push({type: 'brush', name: this.getAttribute('name')});
				}
				else if (this.tagName == 'item')
				{
					if (this.hasAttribute('id'))
					{
						tileset.terrain.push({type: 'item', id: this.getAttribute('id')});
					}
					else if (this.hasAttribute('fromid') && this.hasAttribute('toid'))
					{
						var startNum = parseInt(this.getAttribute('fromid'));
						var endNum = parseInt(this.getAttribute('toid'));
						
						for(i = startNum; i <= endNum; i++)
						{
							tileset.terrain.push({type: 'item', id: i});
						}
					}
					else if (this.hasAttribute('fromid') && !this.hasAttribute('toid'))
					{
						tileset.terrain.push({type: 'item', id: this.getAttribute('fromid')});
					}
				}
			});
		}
		else if (this.tagName == 'doodad')
		{
			tileset.doodad = [];
			jQuery(this).children().each(function(){
				if (this.tagName == 'brush')
				{
					tileset.doodad.push({type: 'brush', name: this.getAttribute('name')});
				}
				else if (this.tagName == 'item')
				{
					if (this.hasAttribute('id'))
					{
						tileset.doodad.push({type: 'item', id: this.getAttribute('id')});
					}
					else if (this.hasAttribute('fromid') && this.hasAttribute('toid'))
					{
						var startNum = parseInt(this.getAttribute('fromid'));
						var endNum = parseInt(this.getAttribute('toid'));
						
						for(i = startNum; i <= endNum; i++)
						{
							tileset.doodad.push({type: 'item', id: i});
						}
					}
					else if (this.hasAttribute('fromid') && !this.hasAttribute('toid'))
					{
						tileset.doodad.push({type: 'item', id: this.getAttribute('fromid')});
					}
				}
			});
		}
		else if (this.tagName == 'items')
		{
			
		}
		else if (this.tagName == 'items_and_raw')
		{
			
		}
		else if (this.tagName == 'raw')
		{
			
		}
	});
	Mapeditor.Materials.Tilesets[$tileset.attr('name')] = tileset;
};

Mapeditor.Materials.switchPalette = function(newPalette) {
	jQuery('#brush-selectors select#tileset-selector').html('');
	// Parse through all tilesets to see which ones offer the new palette
	jQuery.each(Mapeditor.Materials.Tilesets, function(setName, setObj){
		if (setObj[newPalette]) {
			// Update the list of tilesets
			jQuery('#brush-selectors select#tileset-selector').append('<option>'+ setName +'</option>');
		}
	});
	// Set the first tileset as selected
	jQuery('#brush-selectors select#tileset-selector').prop('selectedIndex', 0);
	//Update the tilesets too
	Mapeditor.Materials.switchTileset( jQuery('#brush-selectors select#tileset-selector option:selected').text() );
};

Mapeditor.Materials.switchTileset = function(tileset) {
	var activePalette = jQuery('#brush-selectors select#palette-selector option:selected').data('palette');
	jQuery('#itemlist').html('');
	
	// Iterate the array of brushes and items for this palette/tileset combo
	jQuery.each(
		Mapeditor.Materials.Tilesets[tileset][activePalette],
		function(index, painterObj)
		{
			if (painterObj.type == 'brush') {
				var brush = Mapeditor.Materials.Brushes[painterObj.name];
				// Double-check that this references a brush we've actually parsed
				if (brush) {
					jQuery('#itemlist')
						.append('<li class="brush" data-name="'+ brush.name +'">\
											<a>\
												<img src="'+ Mapeditor.config.urls.sprites.replace('%sprite%', brush.server_lookid) +'" />\
												'+ brush.name +'\
											</a>\
										</li>');
				}
			}
			else if (painterObj.type == 'item')
			{
				jQuery('#itemlist')
					.append('<li class="item" data-id="'+ painterObj.id +'">\
										<a>\
											<img src="'+ Mapeditor.config.urls.sprites.replace('%sprite%', painterObj.id) +'" />\
											Item '+ painterObj.id +'\
										</a>\
									</li>');
			}
		}
	);
};
