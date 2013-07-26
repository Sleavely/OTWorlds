Mapeditor.Materials = {};

Mapeditor.Materials.load = function(loadUrl, loadCallback, urlScope){
	if(loadUrl === undefined) return false;
	
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
				
				//TODO: parse "root" nodes
				
				if(this.tagName == "include"){
					Mapeditor.Materials.parseInclude(this, false, urlScope);
					return;
				}
				if(this.tagName == "tileset"){
					Mapeditor.Materials.parseTileset(this);
					return;
				}
				if(this.tagName == "brush"){
					Mapeditor.Materials.parseBrush(this);
					return;
				}
			});
			if(loadCallback !== undefined && loadCallback !== false) loadCallback();
		}
	});
}

Mapeditor.Materials.parseInclude = function(node, loadCallback, urlScope){
	var $this = jQuery(node);
	var fileToLoad = $this.attr("file"); //TODO: needs to be aware of relative URLs since we are already in XML folder. OR we need to always use XML folder.
	Mapeditor.Materials.load(fileToLoad, loadCallback, urlScope);
}

Mapeditor.Materials.parseBrush = function(node){
	var $brush = jQuery(node);
	if((($brush.attr("server_lookid") || "").length > 0) && (($brush.attr("name") || "").length > 0)){
		Mapeditor.Materials.Brushes = Mapeditor.Materials.Brushes || {};
		
		Mapeditor.Materials.Brushes[$brush.attr("name")] = {
			'name': $brush.attr("name"),
			'server_lookid': $brush.attr("server_lookid")
		};
	}
}

Mapeditor.Materials.parseTileset = function(node){
	var $tileset = jQuery(node);
	if ($tileset.attr('name') == 'Nature') {
		$tileset.children().each(function(){
			if (this.tagName == 'terrain') {
				//TODO: this is currently statically the Nature terrain-tileset
				var brushesToPrint = '';
				jQuery(this).children().each(function(){
					if (this.tagName == 'brush') {
						var brush = Mapeditor.Materials.Brushes[jQuery(this).attr('name')];
						brushesToPrint += '<li><a class="brush"><img src="'+ Mapeditor.config.urls.sprites.replace('%sprite%', brush.server_lookid) +'" /> '+ brush.name +'</a></li>'
					}
				});
				jQuery("#itemlist").append(brushesToPrint);
			}
		});
	}
}