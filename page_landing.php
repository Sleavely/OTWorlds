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
			jQuery("body > .container").css("paddingTop", availableSpace/2);
		}
	}
	</script>
</head>
<body id="page-login">
	<div id="fb-root"></div>
	<script>
	// Additional JS functions here
	window.fbAsyncInit = function() {
		FB.init({
			appId      : '391055467680449', // App ID
			channelUrl : '//<?php echo ($_SERVER['SERVER_NAME'] == 'localhost' ? 'localhost/otworlds' : 'otworlds.com'); ?>/channel.html', // Channel File
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
				console.log(response);
				testAPI();
			} else if (response.status === 'not_authorized') {
				// In this case, the person is logged into Facebook, but not into the app, so we call
				// FB.login() to prompt them to do so. 
				// In real-life usage, you wouldn't want to immediately prompt someone to login 
				// like this, for two reasons:
				// (1) JavaScript created popup windows are blocked by most browsers unless they 
				// result from direct interaction from people using the app (such as a mouse click)
				// (2) it is a bad experience to be continually prompted to login upon page load.
				FB.login();
			} else {
				// In this case, the person is not logged into Facebook, so we call the login() 
				// function to prompt them to do so. Note that at this stage there is no indication
				// of whether they are logged into the app. If they aren't then they'll see the Login
				// dialog right after they log in to Facebook. 
				// The same caveats as above apply to the FB.login() call here.
				FB.login();
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
			jQuery("#login .btn").html('<a href="<?php echo $facebook->getLogoutUrl(); ?>">Logged in as '+ response.name +'</a>');
			console.log(response);
			if(window.location.host != "localhost") window.location.reload();
		});
	}
	
	jQuery(document).ready(function(){
		jQuery("#login .btn a").click(function(e){
			e.preventDefault();
			console.log('Clicked login, initiating login.');
			FB.login(function(response){
				console.log(response);
			});
		});
	});
	</script>
	<div class="container">
		<div class="row" id="hero">
			<div class="twelve columns">
				<h1>OTWorlds Mapeditor</h1>
				<p>Experience mapping directly in your browser. <br />No installations, no .otb files to keep track of, and instant sharing.</p>
			</div>
		</div>
		<div class="row" id="login">
			<div class="center-text twelve columns">
				<div class="large primary btn"><a href="<?php echo $facebook->getLoginUrl(); ?>">Log in with Facebook</a></div>
			</div>
		</div>
	</div>
</body>
</html>