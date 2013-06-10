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
	<div class="container">
		<div class="row" id="hero">
			<div class="twelve columns">
				<h1>OTWorlds Mapeditor</h1>
				<p>Congratulations! You are now logged in!</p>
			</div>
		</div>
		<div class="row">
			<div class="five columns">
				<p>Soon enough this page will contain the canvas on which you'll draw a whole new world!</p>
			</div>
			<div class="five columns">
				<p>To speed up the process you can put some pressure on <a href="http://twitter.com/Sleavely" target="_blank" title="@sleavely on Twitter">@Sleavely</a>.</p>
			</div>
		</div>
		<div class="row">
			<div class="push_one eleven columns">
				<p><strong>This is you:</strong></p>
				<pre>
				<?php
				
				try {
					// Proceed knowing you have a logged in user who's authenticated.
					$user_profile = $facebook->api('/me');
					
				} catch (FacebookApiException $e) {
					echo '<pre>'.htmlspecialchars(print_r($e, true)).'</pre>';
					$user = null;
				}
				
				echo '<pre style="font-family: monospace; font-size: 12px; line-height: 1.2em;">';
				var_export($user);
				echo '<br /><br />';
				var_export($user_profile);
				?>
				</pre>
			</div>
		</div>
	</div>
</body>
</html>