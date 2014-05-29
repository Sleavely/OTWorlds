<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>OTWorlds Mapeditor</title>
	
	<link rel="shortcut icon" href="img/spellbook-16x16.png" type="image/x-icon" />
	<link rel="stylesheet" href="css/style.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
	
	<!-- page specific stuff: -->
	<link rel="stylesheet" href="css/landing.css" />
	<script>
	jQuery(document).ready(function(){
		
		moveBox();
		jQuery(window).resize(moveBox);
	});
	function moveBox(){
		var windowHeight = jQuery(window).height();
		var boxHeight = 0;
		jQuery(".row").each(function(){
			boxHeight += jQuery(this).height();
		});
		
		var availableSpace = windowHeight - boxHeight;
		if (availableSpace > 0) {
			jQuery("body > .container").css("paddingTop", (availableSpace/2)-40);
		}
	}
	</script>
</head>
<body id="page-login">
	<?php if(!isset($exception)){ ?>
		<div id="fb-root"></div>
		<script>
		
		// Additional JS functions here
		window.fbAsyncInit = function() {
			FB.init({
				appId      : '391055467680449', // App ID
				channelUrl : 'channel.html', // Channel File
				status     : true, // check login status
				cookie     : true, // enable cookies to allow the server to access the session
				xfbml      : true  // parse XFBML
			});
			
			// Here we subscribe to the auth.authResponseChange JavaScript event. This event is fired
			// for any authentication related change, such as login, logout or session refresh. This means that
			// whenever someone who was previously logged out tries to log in again, the correct case below 
			// will be handled. 
			FB.Event.subscribe('auth.authResponseChange', function(response) {
				// Here we specify what we do with the response anytime this event occurs. 
				if (response.status === 'connected') {
					// The response object is returned with a status field that lets the app know the current
					// login status of the person. In this case, we're handling the situation where they 
					// have logged in to the app.
					testAPI();
				}
			});
		};
		
		// Load the SDK asynchronously
		(function(d){
			var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
			if (d.getElementById(id)) {return;}
			js = d.createElement('script'); js.id = id; js.async = true;
			js.src = "//connect.facebook.net/en_US/all.js";
			ref.parentNode.insertBefore(js, ref);
		}(document));
		
		// Here we run a very simple test of the Graph API after login is successful. 
		// This testAPI() function is only called in those cases. 
		function testAPI() {
			FB.api('/me', function(response) {
				jQuery("#login .btn a").remove();
				jQuery("#login .btn").html('<a href="login/fb">Logged in as '+ response.name +'</a>');
				window.location = 'login/fb';
			});
		}
		/**/
		</script>
	<?php } ?>
	<div class="container">
		<div class="row" id="hero">
			<div class="twelve columns">
				<h1>OTWorlds Mapeditor</h1>
				<p>Experience mapping directly in your browser. <br />No installations, no .otb files to keep track of, and instant sharing.</p>
				<?php
				if(isset($exception)){
					echo '<p class="error">'.$exception.'</p>';
				}
				?>
			</div>
		</div>
		<div class="center-text row" id="links">
			<div class="twelve columns">
				<div class="pill-left medium info btn icon-left icon-quote">
					<a href="http://opentibia.net/topic/173113-mapeditor-otworlds/" target="_blank">Discussion forum</a>
				</div>
				<div class="pill-right medium danger btn icon-left icon-play">
					<a href="https://youtu.be/Q_KEVnJ1ugU" target="_blank">YouTube demo (1:08)</a>
				</div>
			</div>
		</div>
		<?php if(!isset($exception)){ ?>
		<div class="row" id="login">
			<div class="center-text twelve columns">
				<div class="large primary btn">
					<a href="login/fb">
						Log in with Facebook
						<p>Only your name and an ID to find your maps is used. We do not request your email or friends list.</p>
					</a>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
	<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-2840885-9']);
	_gaq.push(['_trackPageview', '/']);
	
	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
	</script>
</body>
</html>