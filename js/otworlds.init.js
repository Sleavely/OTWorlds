/**
 * Initializing of the main interface on Otworlds
 */

var $viewport;
var $canvas;
jQuery(document).ready(function(){
	$viewport = jQuery("#viewport");
	$canvas = jQuery("#canvas");
	
	function showmap(id) {
		jQuery(".toolbar").animate({'opacity': 1}, 300, function(){
			Mapeditor.load(id);
		});
	}
	
	if (window.location.hash.substr(1, 5) == 'mapid') {
		jQuery("#welcome").remove();
		showmap(window.location.hash.substr(7));
	}else{
		jQuery('#welcome a[href="#welcome-listmaps"]').click(function(){
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
						list += '<li><a href="#mapid-'+ val.id +'" class="loadmap" data-id="'+ val.id +'">'+ val.name +'</a></li>';
					});
					list += '</ul>';
					$box.html(list);
				}
			});
		});
		
		jQuery("#welcome").delegate('a.loadmap', 'click', function(){
			var $this = jQuery(this);
			showmap($this.attr('data-id'));
			jQuery("#welcome").animate({'opacity': 0}, 300, function(){
				jQuery("#welcome").remove();
			});
		});
	}
	
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
	jQuery(window).keyup(function(e) {
		//Spacebar
		if (e.which == 32) {
			$canvas.toggleClass('editing');
			Mapeditor.map.isEditing = !Mapeditor.map.isEditing;
			console.log('Toggling canvasmode. Now '+(Mapeditor.map.isEditing ? 'editing' : 'viewing'));
		}
	});
	
});