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
      }).click(function(e){
        var pos_x = e.offsetX ? (e.offsetX) : e.pageX-this.offsetLeft;
        var pos_y = e.offsetY ? (e.offsetY) : e.pageY-this.offsetTop;
        jQuery(this).css({
          left: (100 - pos_x),
          top: (100 - pos_y)
        });
        Mapeditor.internals.infinitedrag.center(pos_x, pos_y);
      });
  Mapeditor.Minimap.loaded = true;
}
