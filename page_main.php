<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>OTWorlds Mapeditor</title>
	
	<link rel="shortcut icon" href="img/spellbook-16x16.png" type="image/x-icon" />
	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" href="css/style.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	<script src="//code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<script src="js/jquery.infinitedrag.js"></script>
	
	<?php
	$allowed = false;
	if(isset($facebook)){
		$profile = $facebook->api('/me');
		
		$logtext = '['.date('d-M-Y H:i:s').'] '.$profile['link'].PHP_EOL;
		file_put_contents('facebook.log', $logtext, FILE_APPEND);
		
		$allowed_usernames = array(
			'eliascarlsson',
			'joakim.hedlund',
			'joran.haagsma',
			'micke.hafner',
		);
		if(in_array($profile['username'], $allowed_usernames)) $allowed = true;
		
	}elseif($_SERVER['HTTP_HOST'] == 'localhost'){
		$allowed = true;
	}
	if($allowed){
	?>
	
		<script src="js/otworlds.mapeditor.js"></script>
		<script src="js/otworlds.mapeditor.xmlparser.js"></script>
		
		<script src="js/otworlds.init.js"></script>
	<?php
	}
	?>
</head>
<body id="page-main">
	<div id="viewport">
		<div id="canvas"></div>
	</div>
	<div class="toolbar" id="menu" style="width: 600px; top: 20px; left: 300px;">
		Map options
	</div>
	<div class="toolbar" id="meta" style="width: 200px; right: 20px; top: 200px;">
		Meta tools
	</div>
	<div class="toolbar" id="brushes" style="width: 200px; left: 20px; top: 200px;">
		Brushes
	</div>
	<div style="position: fixed; width: 39%; left: 30%; top: 200px; background-color: rgba(255, 220, 255, 0.9); border: 1px solid rgba(0, 0, 0, 0.3); border-radius: 4px; padding: 1em;">
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
			<div class="six columns">
				<p>Because this is a work in progress, and features are being removed as quickly as they were added, we only allow approved beta-testers to use the editor.</p>
			</div>
			<div class="six columns">
				<p>If you want to get in on the action or just want to support the project, drop a greeting to <a href="http://twitter.com/Sleavely" target="_blank" title="@sleavely on Twitter">@Sleavely</a>.</p>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-2840885-9']);
	_gaq.push(['_trackPageview', '/map']);
	
	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
	</script>
</body>
</html>