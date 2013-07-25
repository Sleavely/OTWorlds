/**
 * Initializing of the main interface on Otworlds
 */

var $viewport;
var $canvas;
jQuery(document).ready(function(){
	$viewport = jQuery("#viewport");
	$canvas = jQuery("#canvas");
	
	jQuery('#welcome a[href="#listmaps"]').click(function(){
		jQuery.ajax(Mapeditor.config.urls.backend, {
			dataType: "json",
			data: {
				'action' : 'listMaps',
			},
			success: function(data){
				var $box = jQuery("#welcome");
				var list = '<h2>Available maps</h2>';
				list += '<ul>';
				jQuery.each(data.maps, function(key, val){
					list += '<li><a href="#" class="loadmap">'+ val.name +'</a></li>';
				});
				list += '</ul>';
				$box.html(list);
			}
		});
	});
	
	jQuery("#welcome").delegate('a.loadmap', 'click', function(){
		var $this = jQuery(this);
		Mapeditor.load($this.text());
		jQuery(".toolbar").animate({'opacity': 1}, 300, function(){
			jQuery(".toolbar").not("#brushes").draggable();
		});
		jQuery("#welcome").animate({'opacity': 0}, 300, function(){
			jQuery("#welcome").remove();
		});
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