Mapeditor.Minimap = {};

Mapeditor.Minimap.loaded = false;

Mapeditor.Minimap.loadStatic = function(){
  jQuery('#minimap .content')
    .html('<img />');
  jQuery('#minimap img')
    .attr('src', Mapeditor.config.urls.backend + 'map/' + Mapeditor.map.meta.id + '/minimap?' + new Date().getTime())
    .css({
        'max-width': 'none',
        position: 'relative',
        left: (100)-(Mapeditor.map.meta.width/2),
        top: (100)-(Mapeditor.map.meta.height/2)
      });
  Mapeditor.Minimap.loaded = true;
}
