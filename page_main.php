<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>OTWorlds Mapeditor</title>
	
	<link rel="shortcut icon" href="img/spellbook-16x16.png" type="image/x-icon" />
	<link rel="stylesheet" href="css/style.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	
	<script src="js/jquery.pep.js"></script>
	<script src="js/jquery.infinitedrag/jquery.infinitedrag.js"></script>
	
	<script src="js/vex/js/vex.combined.min.js"></script>
	<script>vex.defaultOptions.className = 'vex-theme-os';</script>
	<link rel="stylesheet" href="js/vex/css/vex.css" />
	<link rel="stylesheet" href="js/vex/css/vex-theme-os.css" />
	
	<?php
	$allowed = false;
	$dev = false;
	if(isset($facebook)){
		$profile = $facebook->api('/me');
		
		$logtext = '['.date('d-M-Y H:i:s').'] '.$profile['link'].PHP_EOL;
		file_put_contents('facebook.log', $logtext, FILE_APPEND);
		
		$allowed_usernames = array(
			'joakim.hedlund',
			'eliascarlsson',
			'joran.haagsma',
			'micke.hafner',
			'robbie.scott.79',
			'gunsnroses4201', //Kilaco
			'NathanMJacobs', //STiX
			'labrisanty', // VanessaX
			'adi.sliwinski1', // LLburn
			'anyza116', //kuzyn
			'mindrage', //mindrage
			'ranisalt', //Lordfire
		);
		if(in_array($profile['username'], $allowed_usernames)) $allowed = true;
		
	}elseif(in_array($_SERVER['HTTP_HOST'], array('localhost', 'cipc'))){
		$allowed = true;
		$dev = true;
	}
	if($allowed){
	?>
		<?php if($dev){ ?>
		<script src="js/otworlds.mapeditor.js"></script>
		<script src="js/otworlds.materials.js"></script>
		<script src="js/otworlds.tile.js"></script>
		<script src="js/otworlds.tiles.js"></script>
		<script src="js/otworlds.multiplayer.js"></script>
		<?php }else{ ?>
			<script>var Mapeditor={config:{urls:{backend:"ajax/",sprites:"http://cdn.otworlds.com/sprites/%sprite%.gif"}},internals:{createDebouncer:function(e,t,n){var r;return function(){var i=this,s=arguments;clearTimeout(r);r=setTimeout(function(){r=null;if(!n)e.apply(i,s)},t);if(n&&!r)e.apply(i,s)}},figureOutTile:function(e){var t=jQuery(e.currentTarget);var n,r;if(t.hasClass("item")){var i=t.parent();n=i.attr("col");r=i.attr("row");if(e.offsetY<=32){if(e.offsetX<=32){return Mapeditor.Tiles.find(n-1,r-1,Mapeditor.map.currentFloor)}else{return Mapeditor.Tiles.find(n,r-1,Mapeditor.map.currentFloor)}}else{if(e.offsetX<=32){return Mapeditor.Tiles.find(n-1,r,Mapeditor.map.currentFloor)}else{return Mapeditor.Tiles.find(n,r,Mapeditor.map.currentFloor);}}}else{n=t.attr("col");r=t.attr("row");return Mapeditor.Tiles.find(n,r,Mapeditor.map.currentFloor)}}},load:function(e){Mapeditor.reset();jQuery.ajax(Mapeditor.config.urls.backend,{dataType:"json",data:{action:"init",map:e},success:function(e){if(e.id){Mapeditor.map.meta.id=e.id;Mapeditor.map.meta.name=e.name;Mapeditor.map.meta.description=e.description;Mapeditor.map.meta.width=e.width;Mapeditor.map.meta.height=e.height;document.title=Mapeditor.map.meta.name+" - OTWorlds Mapeditor";jQuery("#menu .mapname > span").text(Mapeditor.map.meta.name);Mapeditor.Materials.load("xml/materials.xml",function(){Mapeditor.internals.infinitedrag=jQuery.infinitedrag("#canvas",{cursor:false},{width:32,height:32,range_col:[0,Mapeditor.map.meta.width],range_row:[0,Mapeditor.map.meta.height],start_col:Mapeditor.map.meta.width/2-Math.round(jQuery(window).width()/32/2),start_row:Mapeditor.map.meta.height/2-Math.round(jQuery(window).height()/32/2),class_name:"tile",oncreate:function(e,t,n){var r=Mapeditor.Tiles.find(t,n,Mapeditor.map.currentFloor);if(r){r.$element=e}else{r=Object.create(Mapeditor.Tile);r.x=t;r.y=n;r.z=Mapeditor.map.currentFloor;r.$element=e;Mapeditor.Tiles.add(r)}r.load()}})})}}})},map:{currentFloor:7,meta:{},_7:{_100:{_100:{itemid:4526,items:[{itemid:1337}]}}}},reset:function(){console.log("Resetting editor");Mapeditor.map.currentFloor=7;Mapeditor.map.meta={};for(var e=0;e<17;e++){if(Mapeditor.map["_"+e]!==undefined)Mapeditor.map["_"+e]=undefined}Mapeditor.internals.infinitedrag={};jQuery(".tile, #brushes .brush").remove();Mapeditor.Materials.Brushes={};Mapeditor.isEditing=false;$canvas.removeClass("editing");Mapeditor.lastPainted.pos={x:0,y:0,z:0}},isEditing:false,toggleEdit:function(){$canvas.toggleClass("editing");Mapeditor.isEditing=!Mapeditor.isEditing;Mapeditor.internals.infinitedrag.disabled(Mapeditor.isEditing);console.log("Toggling canvasmode. Now "+(Mapeditor.isEditing?"editing":"viewing"))},lastPainted:{brush:{name:"null",server_lookid:0},pos:{x:0,y:0,z:0}},paint:function(e,t){if(Mapeditor.map.currentFloor==Mapeditor.lastPainted.pos.z&&e.x==Mapeditor.lastPainted.pos.x&&e.y==Mapeditor.lastPainted.pos.y&&e.z==Mapeditor.lastPainted.pos.z&&t.name==Mapeditor.lastPainted.brush.name&&t.server_lookid==Mapeditor.lastPainted.brush.server_lookid){return}e.itemid=t.server_lookid;Mapeditor.lastPainted.brush.name=t.name;Mapeditor.lastPainted.brush.server_lookid=t.server_lookid;Mapeditor.lastPainted.pos.x=e.x;Mapeditor.lastPainted.pos.y=e.y;Mapeditor.lastPainted.pos.z=e.z;console.log("Painting "+t.name+" on "+e.x+", "+e.y+", "+Mapeditor.map.currentFloor);e.draw();e.save();Mapeditor.Multiplayer.emit("Mapeditor.paint",{Tile:{x:e.x,y:e.y,z:e.z,itemid:e.itemid}})}};Mapeditor.Materials={};Mapeditor.Materials.Brushes=Mapeditor.Materials.Brushes||{};Mapeditor.Materials.load=function(e,t,n){if(e===undefined)return false;if(!n){n=e.split("/");n.pop();n.join("/");n+="/"}else{e=n+e}jQuery.ajax(e,{cache:true,dataType:"xml",error:function(t,n){console.log("Mapeditor.Materials.load("+e+") failed:");console.log(n);console.log(t)},success:function(e){jQuery("materials",e).children().each(function(){if(this.tagName=="include"){Mapeditor.Materials.parseInclude(this,false,n);return}if(this.tagName=="tileset"){var e=this;setTimeout(function(e){Mapeditor.Materials.parseTileset(e)},3e3,this);return}if(this.tagName=="brush"){Mapeditor.Materials.parseBrush(this);return}});if(t!==undefined&&t!==false)t()}})};Mapeditor.Materials.parseInclude=function(e,t,n){var r=jQuery(e);var i=r.attr("file");Mapeditor.Materials.load(i,t,n)};Mapeditor.Materials.parseBrush=function(e){var t=jQuery(e);if((t.attr("server_lookid")||"").length>0&&(t.attr("name")||"").length>0){Mapeditor.Materials.Brushes=Mapeditor.Materials.Brushes||{};Mapeditor.Materials.Brushes[t.attr("name")]={name:t.attr("name"),server_lookid:t.attr("server_lookid"),type:t.attr("type")}}};Mapeditor.Materials.parseTileset=function(e){var t=jQuery(e);if(t.attr("name")=="Nature"){t.children().each(function(){if(this.tagName=="terrain"){var e="";jQuery(this).children().each(function(){if(this.tagName=="brush"){var t=Mapeditor.Materials.Brushes[jQuery(this).attr("name")];if(t.type=="ground"){e+='<li class="brush" data-name="'+t.name+'"><a><img src="'+Mapeditor.config.urls.sprites.replace("%sprite%",t.server_lookid)+'" /> '+t.name+"</a></li>"}}});jQuery("#itemlist").html(e);var t=jQuery("#itemlist .brush").eq(0).addClass("active").data("name");Mapeditor.Materials.Brushes.active=t}})}};Mapeditor.Tile=function(){};Mapeditor.Tile.load=function(){if(this.itemid&&this.$element){this.draw()}else{Mapeditor.Tiles.queue.download.push(this.x+","+this.y+","+this.z);Mapeditor.Tiles.download()}};Mapeditor.Tile.save=function(){var e={x:this.x,y:this.y,z:this.z,itemid:this.itemid};Mapeditor.Tiles.queue.upload.push(e);Mapeditor.Tiles.upload()};Mapeditor.Tile.draw=function(){this.$element.css("background-image","url("+Mapeditor.config.urls.sprites.replace("%sprite%",this.itemid)+")");if(this.items){jQuery.each(this.items,function(e,t){var n=jQuery("<div />");n.addClass("item");n.css("background-image","url("+Mapeditor.config.urls.sprites.replace("%sprite%",t.itemid)+")");n.appendTo(_this.$element)})}};Mapeditor.Tiles={};Mapeditor.Tiles.add=function(e){var t=e.x;var n=e.y;var r=e.z;if(Mapeditor.map["_"+r]===undefined)Mapeditor.map["_"+r]={};if(Mapeditor.map["_"+r]["_"+t]===undefined)Mapeditor.map["_"+r]["_"+t]={};if(Mapeditor.map["_"+r]["_"+t]["_"+n]===undefined){Mapeditor.map["_"+r]["_"+t]["_"+n]=e}else{jQuery.extend(Mapeditor.map["_"+r]["_"+t]["_"+n],e)}};Mapeditor.Tiles.find=function(e,t,n){if(Mapeditor.map["_"+n]===undefined)return false;if(Mapeditor.map["_"+n]["_"+e]===undefined)return false;if(Mapeditor.map["_"+n]["_"+e]["_"+t]===undefined)return false;return Mapeditor.map["_"+n]["_"+e]["_"+t]};Mapeditor.Tiles.download=Mapeditor.internals.createDebouncer(function(){console.log("Requesting "+Mapeditor.Tiles.queue.download.length+" tiles"+(Mapeditor.Tiles.queue.download.length>500?" in chunks of 500":"")+".");var e=[];while(Mapeditor.Tiles.queue.download.length){e=Mapeditor.Tiles.queue.download.splice(0,500);jQuery.ajax(Mapeditor.config.urls.backend,{dataType:"json",type:"POST",data:{action:"loadtiles",map:Mapeditor.map.meta.id,tiles:e},success:function(e){jQuery.each(e.tiles,function(e,t){jQuery.each(t,function(t,n){jQuery.each(n,function(n,r){var i=Mapeditor.Tiles.find(t,n,e);jQuery.extend(i,r);i.load()})})})}});e=[]}Mapeditor.Tiles.queue.download=[]},500);Mapeditor.Tiles.upload=Mapeditor.internals.createDebouncer(function(){console.log("Saving "+Mapeditor.Tiles.queue.upload.length+" tiles"+(Mapeditor.Tiles.queue.upload.length>100?" in chunks of 100":"")+".");var e=[];while(Mapeditor.Tiles.queue.upload.length){e=Mapeditor.Tiles.queue.upload.splice(0,100);jQuery.ajax(Mapeditor.config.urls.backend,{dataType:"json",type:"POST",data:{action:"savetiles",map:Mapeditor.map.meta.id,tiles:e},success:function(e){if(e.error){console.log("Something went wrong on the server while saving.")}},error:function(){console.log("Failed to save.")}});e=[]}Mapeditor.Tiles.queue.upload=[]},500);Mapeditor.Tiles.queue={};Mapeditor.Tiles.queue.download=[];Mapeditor.Tiles.queue.upload=[];Mapeditor.Multiplayer={};Mapeditor.Multiplayer.emit=function(e,t){var n=jQuery.extend({type:e},t);TogetherJS.send(n)};jQuery(document).ready(function(){TogetherJS.hub.on("Mapeditor.paint",function(e){console.log("Received paint event");var t=Mapeditor.Tiles.find(e.Tile.x,e.Tile.y,e.Tile.z);if(!t){}else{console.log("Tile existed");Mapeditor.Tiles.add(e.Tile);t.load()}})})</script>
		<?php } ?>
		
		<script>
		var TogetherJSConfig_siteName = 'OTWorlds';
		var TogetherJSConfig_suppressJoinConfirmation = true;
		//var TogetherJSConfig_suppressInvite = true;
		var TogetherJSConfig_includeHashInUrl = true;
		var TogetherJSConfig_dontShowClicks = true;
		<?php
		if(isset($facebook)){
			print 'var TogetherJSConfig_getUserName = "'.$profile['username'].'";';
			print 'var TogetherJSConfig_getUserAvatar = "http://graph.facebook.com/'.$profile['username'].'/picture?width=40&height=40";';
		}
		?>
		sessionStorage.removeItem("togetherjs-session.status");
		</script>
		<script src="https://togetherjs.com/togetherjs-min.js"></script>
		
		<script src="js/otworlds.init.js"></script>
	<?php
	}
	else
	{
		?>
		<script>
		jQuery(document).ready(function(){
			vex.open({
			content: jQuery("#welcome").html(),
			showCloseButton: false,
			escapeButtonCloses: false,
			overlayClosesOnClick: false
			});
		});
		</script>
		<?php
	}
	?>
</head>
<body id="page-main">
	<div class="toolbar" id="menu">
		<span class="mapname">
			<i class="icon-down-open"></i>
			<span><em>No map loaded</em></span>
			<ul>
				<li><a class="disabled">New map</a></li>
				<li><a href="#" onclick="showmaps(); return false;">Load map</a></li>
			</ul>
		</span>
		<a class="disabled">
			<i class="icon-download"></i>Download OTBM
		</a><a class="disabled">
			<i class="icon-share"></i>Share
		</a><a href="http://html2canvas.hertzen.com/examples.html" target="_blank">
			<i class="icon-camera"></i>Screenshot
		</a><a class="disabled">
			<i class="icon-help-circled"></i>Help
		</a><a class="disabled">
			<i class="icon-logout"></i>Logout
			<?php
			if(isset($facebook)){
				print htmlentities($profile['username'], ENT_COMPAT, 'UTF-8');
			}
			?>
		</a>
	</div>
	<div id="viewport">
		<div id="canvas"></div>
	</div>
	<div class="toolbar" id="brushes" style="width: 250px; left: 0px; top: 0px; bottom: 0px; overflow-y: scroll;">
		<ul id="itemlist">
		</ul>
	</div>
	<div id="welcome">
		<div>
			<div class="row" id="hero">
				<div class="twelve columns">
					<h1 class="center-text">Welcome<?php
					if(isset($facebook)){
						print ', ';
						print htmlentities($profile['first_name'], ENT_COMPAT, 'UTF-8');
					}
					?>!</h1>
				</div>
			</div>
			<div class="row">
				<div class="twelve columns">
					<p>Because this is a work in progress, and features are being removed as quickly as they were added, we only allow approved beta-testers to use the editor.</p>
					<p>If you want to get in on the action or just want to support the project, drop a greeting to <a href="http://twitter.com/Sleavely" target="_blank" title="@sleavely on Twitter">@Sleavely</a>.</p>
				</div>
			</div>
			<?php if($allowed){ ?>
			<div class="row">
				<div class="push_five seven columns center-text">
					<div class="large danger btn"><a href="#">Get started</a></div>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
	<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-2840885-9']);
	
	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
	</script>
</body>
</html>