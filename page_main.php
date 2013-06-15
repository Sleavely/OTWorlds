<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>OTWorlds Mapeditor</title>
	
	<link rel="shortcut icon" href="img/spellbook-16x16.png" type="image/x-icon" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1" />
	<link rel="stylesheet" href="css/style.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	
	<!-- page specific stuff: -->
	<script>
	jQuery(document).ready(function(){
		
	});
	</script>
</head>
<body id="page-main">
	<div class="toolbar" id="menu" style="width: 600px; position: fixed; top: 20px; left: 300px; background-color: rgba(255, 255, 255, 0.9); border: 1px solid rgba(0, 0, 0, 0.3); border-radius: 4px; padding: 1em;">
		Here go some Options
	</div>
	<div class="toolbar" id="meta" style="width: 200px; position: fixed; right: 20px; top: 200px; background-color: rgba(255, 255, 255, 0.9); border: 1px solid rgba(0, 0, 0, 0.3); border-radius: 4px; padding: 1em;">
		Toggle edit mode.
	</div>
	<div class="toolbar" id="brushes" style="width: 200px; position: fixed; left: 20px; top: 200px; background-color: rgba(255, 255, 255, 0.9); border: 1px solid rgba(0, 0, 0, 0.3); border-radius: 4px; padding: 1em;">
		Brushes be crazy!
	</div>
	<div style="position: fixed; width: 39%; left: 30%; top: 200px; background-color: rgba(255, 220, 255, 0.9); border: 1px solid rgba(0, 0, 0, 0.3); border-radius: 4px; padding: 1em;">
		<div class="row" id="hero">
			<div class="twelve columns">
				<h1 class="center-text">Welcome<?php
				if(isset($facebook)){
					print ', ';
					$profile = $facebook->api('/me');
					print htmlentities($profile['first_name'], ENT_COMPAT, 'UTF-8');
				}
				?>!</h1>
			</div>
		</div>
		<div class="row">
			<div class="six columns">
				<p>This page is a work in progress, and will gradually allow you to do cool new things.</p>
			</div>
			<div class="six columns">
				<p>To speed up the process you can put some pressure on <a href="http://twitter.com/Sleavely" target="_blank" title="@sleavely on Twitter">@Sleavely</a>.</p>
			</div>
		</div>
	</div>
	<div id="canvas" style="height: 100%; overflow: hidden; background-image: url(img/transparent.png);">
		&nbsp;
	</div>
</body>
</html>