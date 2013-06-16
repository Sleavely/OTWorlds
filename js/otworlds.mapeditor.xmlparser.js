Mapeditor.materials = {};

Mapeditor.materials.load = function(loadUrl, loadCallback, urlScope){
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
		cache: false,
		dataType : 'xml',
		error: function(jqXHR, errorString){
			console.log('Mapeditor.materials.load('+loadUrl+') failed:');
			console.log(errorString);
			console.log(jqXHR);
		},
		success: function(xmldoc){
			jQuery('materials', xmldoc).children().each(function(){
				
				//TODO: parse "root" nodes
				
				if(this.tagName == "include"){
					Mapeditor.materials.parseInclude(this, false, urlScope);
					return;
				}
				if(this.tagName == "brush"){
					Mapeditor.materials.parseBrush(this);
					return;
				}
			});
			if(loadCallback !== undefined && loadCallback !== false) loadCallback();
		}
	});
}

Mapeditor.materials.parseInclude = function(node, loadCallback, urlScope){
	var $this = jQuery(node);
	var fileToLoad = $this.attr("file"); //TODO: needs to be aware of relative URLs since we are already in XML folder. OR we need to always use XML folder.
	Mapeditor.materials.load(fileToLoad, loadCallback, urlScope);
}

Mapeditor.materials.parseBrush = function(node){
	var $brush = jQuery(node);
	if(($brush.attr("server_lookid") || "").length > 0){
		//dont fetch jQuery object for each of the thousand brushes
		if(!window.$itemlist) window.$itemlist = jQuery("#itemlist");
		var brushName = $brush.attr("name");
		var brushItemid = $brush.attr("server_lookid");
		var newBrush = '<li><a><img src="'+ Mapeditor.config.urls.sprites.replace('%sprite%', brushItemid) +'" /> '+ brushName +'</a></li>';
		window.$itemlist.append(newBrush);
	}
}